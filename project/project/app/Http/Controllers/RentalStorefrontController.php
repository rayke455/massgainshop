<?php
namespace App\Http\Controllers;
use App\Models\{RentalPanel, RentalRequest, Service};
use App\Services\{MpesaService, PanelRentals};
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RentalStorefrontController extends Controller {
    public function panel(string $slug): RentalPanel {
        $panel = RentalPanel::with('owner')->where('slug', $slug)->firstOrFail();
        abort_unless($panel->available(), 403, 'This panel is currently unavailable.'); return $panel;
    }

    public function show(string $slug) {
        $panel = $this->panel($slug);
        return $this->render($panel, false);
    }

    /** Storefront with the given panel's data; $preview disables ordering and shows a ribbon. */
    public function render(RentalPanel $panel, bool $preview) {
        $shopping = request()->boolean('shop') || (session()->has('errors'));
        if ($shopping) request()->validate(['network' => 'nullable|string|max:100', 'category' => 'nullable|integer|min:1', 'service' => 'nullable|integer|min:1', 'q' => 'nullable|string|max:100']);
        $services = $shopping && $panel->allows('catalogue') ? PanelRentals::catalogue($panel) : collect();
        if ($shopping && request()->filled('service')) abort_unless($services->contains('id', (int) request('service')), 404);
        return view($shopping ? 'rentals.guest-order' : 'rentals.storefront', [
            'panel' => $panel, 'preview' => $preview, 'shopping' => $shopping, 'cur' => PanelRentals::currency($panel),
            'groups' => $services->groupBy(fn($s) => optional(optional($s->category)->socialMedia)->name ?: 'Other'),
            'mpesa' => $panel->payment_mode === 'mpesa' && (new MpesaService())->ready(),
            'base' => $this->base($panel),
        ]);
    }

    public function order(Request $request, string $slug) {
        $panel = $this->panel($slug); abort_unless($panel->allows('orders') && $panel->allows('catalogue'), 403);
        $useMpesa = $panel->payment_mode === 'mpesa' && (new MpesaService())->ready();
        if ($panel->payment_mode === 'mpesa' && !$useMpesa) {
            return back()->withInput()->withErrors(['payment' => 'M-Pesa payments are temporarily unavailable. Please contact panel support.']);
        }
        $data = $request->validate([
            'name' => 'required|string|max:100', 'email' => 'required|email|max:255', 'service_id' => 'required|integer',
            'quantity' => 'required|integer|min:1|max:2147483647', 'link' => 'required|url:http,https|max:2048',
            'phone' => $useMpesa ? ['required', 'string', 'max:20'] : 'nullable|string|max:20',
        ]);
        $service = PanelRentals::catalogue($panel)->firstWhere('id', (int) $data['service_id']);
        if (!$service) return back()->withInput()->withErrors(['service_id' => 'Choose a service from the list.']);
        if ($data['quantity'] < $service->min_amount || $data['quantity'] > $service->max_amount) {
            return back()->withInput()->withErrors(['quantity' => "Quantity must be between {$service->min_amount} and {$service->max_amount}."]);
        }
        $cur = PanelRentals::currency($panel);
        $quote = PanelRentals::quantityBased($service)
            ? round($service->panel_price * $data['quantity'] / 1000, 2)
            : round($service->panel_price, 2);

        $phone = null;
        if ($useMpesa) {
            $phone = MpesaService::normalizePhone($data['phone']);
            if (!$phone) return back()->withInput()->withErrors(['phone' => 'Enter your M-Pesa number, e.g. 0712 345 678.']);
        }
        $item = RentalRequest::create([
            'panel_id' => $panel->id, 'token' => (string) Str::uuid(), 'name' => $data['name'], 'email' => $data['email'],
            'service_id' => $service->id, 'quantity' => $data['quantity'], 'link' => $data['link'],
            'quote' => $quote, 'currency' => $cur->code, 'phone' => $phone,
            'status' => $useMpesa ? 'awaiting_payment' : 'requested',
        ]);

        if ($useMpesa) {
            $kes = PanelRentals::quoteToKes($quote, $cur);
            $push = (new MpesaService())->stkPush($phone, $kes, 'P' . $panel->id . 'R' . $item->id, 'Order');
            if (!$push['ok']) {
                $item->update(['status' => 'payment_failed', 'note' => $push['message']]);
                return redirect($this->base($panel) . '/track/' . $item->token)->withErrors(['payment' => 'M-Pesa could not start the payment: ' . $push['message']]);
            }
            // paid_amount holds the KES amount requested until the payment is confirmed.
            $item->update(['payment_ref' => $push['checkout_id'], 'paid_amount' => $kes, 'note' => "Waiting for M-Pesa payment of KES {$kes}"]);
        }
        return redirect($this->base($panel) . '/track/' . $item->token);
    }

    /** Polled by the tracking page while the customer enters their PIN. */
    public function payStatus(string $slug, string $token) {
        $panel = $this->panel($slug);
        $item = RentalRequest::where('panel_id', $panel->id)->where('token', $token)->firstOrFail();
        if ($item->status === 'awaiting_payment' && $item->payment_ref) {
            $res = (new MpesaService())->stkQuery($item->payment_ref);
            if ($res && $res['code'] === '0') {
                PanelRentals::fulfil($item, (float) $item->paid_amount);
            } elseif ($res) {
                $item->update(['status' => 'payment_failed', 'note' => $res['desc'] ?: 'Payment was not completed']);
            }
            $item->refresh();
        }
        return response()->json(['status' => $item->status, 'note' => $item->note]);
    }

    public function track(string $slug, string $token) {
        $panel = $this->panel($slug); abort_unless($panel->allows('orders'), 403);
        $item = RentalRequest::with('providerOrder', 'service')->where('panel_id', $panel->id)->where('token', $token)->firstOrFail();
        return response()->view('rentals.track', ['panel' => $panel, 'item' => $item, 'base' => $this->base($panel)])
            ->header('Cache-Control', 'private, no-store')->header('Referrer-Policy', 'no-referrer')->header('X-Robots-Tag', 'noindex');
    }

    public function base(RentalPanel $panel): string { return request()->attributes->get('rental_host') ? '' : '/panels/'.$panel->slug; }
}
