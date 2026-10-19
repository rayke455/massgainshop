<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ApiProvider;
use App\Models\Order;
use App\Models\Review;
use App\Models\Service;
use App\Models\Transaction;
use App\Models\User;
use App\Services\ApiOrderService;
use App\Services\Growth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

/** Customer side of the growth features: favourites, cancel, reviews, free trial. */
class GrowthController extends Controller
{
    public function toggleFavorite(Service $service)
    {
        $uid = Auth::id();
        $exists = DB::table('favorite_services')->where('user_id', $uid)->where('service_id', $service->id);
        if ($exists->exists()) {
            $exists->delete();
            return response()->json(['favorite' => false, 'message' => __('Removed from favourites')]);
        }
        DB::table('favorite_services')->insert(['user_id' => $uid, 'service_id' => $service->id, 'created_at' => now(), 'updated_at' => now()]);
        return response()->json(['favorite' => true, 'message' => __('Saved to favourites')]);
    }

    /** Cancel an order that hasn't started yet. */
    public function cancelOrder(Order $order)
    {
        abort_unless($order->user_id === Auth::id(), 404);
        if (!in_array($order->status, ['pending', 'awaiting', 'processing'], true)) {
            return back()->with('error', __('This order has already started, so it can no longer be cancelled.'));
        }

        // Not sent to a provider (manual service or the provider rejected it): cancel and refund now.
        if (!$order->api_order_id) {
            DB::transaction(function () use ($order) {
                $o = Order::lockForUpdate()->find($order->id);
                if (!in_array($o->status, ['pending', 'awaiting', 'processing'], true)) {
                    return;
                }
                $o->status = 'canceled';
                $o->status_description = 'Cancelled by customer';
                $o->save();
                \App\Services\PanelRentals::clawBack($o, (float) $o->price);
                $user = User::lockForUpdate()->find($o->user_id);
                $user->balance += (float) $o->price;
                $user->save();
                Transaction::create(['user_id' => $user->id, 'trx_type' => '+', 'amount' => $o->price, 'charge' => 0,
                    'balance' => $user->balance, 'remarks' => "Cancelled order #{$o->id}: refunded"]);
            });
            return back()->with('success', __('Order cancelled and refunded to your balance.'));
        }

        // Sent to a provider: ask them to cancel. The status check refunds automatically once they confirm.
        $provider = ApiProvider::find($order->api_provider_id ?? optional($order->service)->api_provider_id);
        if (!$provider) {
            return back()->with('error', __('This order cannot be cancelled online. Please contact support.'));
        }
        try {
            $res = Http::asForm()->timeout(25)->post($provider->url, ['key' => $provider->api_key, 'action' => 'cancel', 'orders' => $order->api_order_id])->json();
        } catch (\Throwable $e) {
            return back()->with('error', __('Could not reach the supplier. Please try again in a minute.'));
        }
        $row = is_array($res) && array_is_list($res) ? ($res[0] ?? []) : (array) $res;
        $error = $row['cancel']['error'] ?? $row['error'] ?? null;
        if ($error || empty($row)) {
            return back()->with('error', __('The supplier could not cancel this order: ') . ($error ?: __('not supported')));
        }
        $order->update(['status_description' => 'Cancel requested by customer']);
        return back()->with('success', __('Cancel requested. If the supplier confirms, the amount is refunded automatically within a few minutes.'));
    }

    public function storeReview(Request $request, Order $order)
    {
        abort_unless($order->user_id === Auth::id(), 404);
        if (!Growth::get('reviews.enabled', true)) {
            return back()->with('error', __('Reviews are not open right now.'));
        }
        $data = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:600',
            'display_name' => 'nullable|string|max:60',
            'country' => 'nullable|string|max:60',
        ]);
        if ($order->status !== 'completed') {
            return back()->with('error', __('You can review an order once it is completed.'));
        }
        if (Review::where('order_id', $order->id)->exists()) {
            return back()->with('error', __('You have already reviewed this order.'));
        }
        $user = Auth::user();
        Review::create([
            'user_id' => $user->id,
            'order_id' => $order->id,
            'rating' => (int) $data['rating'],
            'comment' => strip_tags($data['comment']),
            'display_name' => strip_tags($data['display_name'] ?: ucfirst($user->username)),
            'country' => strip_tags((string) ($data['country'] ?? '')) ?: null,
            'status' => Review::PENDING,
        ]);
        return back()->with('success', __('Thank you! Your review will appear after a quick check.'));
    }

    /** One free trial order per account, phone number and internet connection. */
    public function claimTrial(Request $request)
    {
        $request->validate(['link' => 'required|url|max:255']);
        $user = Auth::user();
        if (!Growth::get('trial.enabled')) {
            return back()->with('error', __('The free trial is not available right now.'));
        }
        $service = Service::where('service_status', 1)->find(Growth::get('trial.service_id'));
        if (!$service) {
            return back()->with('error', __('The free trial is not available right now.'));
        }
        $phone = trim(($user->phone_code ?? '') . ($user->phone ?? ''));
        $ip = $request->ip();
        $used = DB::table('free_trials')->where('user_id', $user->id)
            ->orWhere(fn($q) => $q->where('ip', $ip)->whereNotNull('ip'))
            ->orWhere(fn($q) => $phone !== '' ? $q->where('phone', $phone) : $q->whereRaw('1=0'))
            ->exists();
        if ($used) {
            return back()->with('error', __('The free trial has already been used on this account, phone number or connection.'));
        }

        $quantity = max((int) $service->min_amount, (int) Growth::get('trial.quantity', 100));
        $order = DB::transaction(function () use ($user, $service, $quantity, $request, $phone, $ip) {
            $order = Order::create([
                'user_id' => $user->id, 'category_id' => $service->category_id, 'service_id' => $service->id,
                'link' => $request->link, 'quantity' => $quantity, 'price' => 0, 'status' => 'pending', 'reason' => 'Free trial',
            ]);
            DB::table('free_trials')->insert(['user_id' => $user->id, 'phone' => $phone ?: null, 'ip' => $ip, 'order_id' => $order->id,
                'created_at' => now(), 'updated_at' => now()]);
            return $order;
        });
        ApiOrderService::placeOrder($service, $order, ['link' => $request->link, 'quantity' => $quantity]);
        return redirect()->route('user.order.index')->with('success', __('Your free trial order #:id is on its way!', ['id' => $order->id]));
    }
}
