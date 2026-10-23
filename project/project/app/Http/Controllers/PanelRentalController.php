<?php
namespace App\Http\Controllers;
use App\Models\{RentalPanel, RentalRequest, User, Order, Transaction};
use App\Services\PanelRentals;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class PanelRentalController extends Controller {
    private function owned(int $id): RentalPanel { return RentalPanel::where('owner_id', auth()->id())->findOrFail($id); }
    public function index() { return view('rentals.manage', ['settings' => PanelRentals::settings(), 'panels' => RentalPanel::where('owner_id', auth()->id())->latest()->get()]); }
    public function store(Request $request) {
        $settings = PanelRentals::settings(); abort_unless($settings['enabled'], 403, 'Panel rentals are not open yet.');
        $data = $request->validate(['slug' => ['required', 'regex:/^[a-z][a-z0-9-]{2,39}$/', Rule::notIn(['www','admin','api','mail','smtp','ftp','panel','support','billing','app']), 'unique:rental_panels,slug'], 'name' => 'required|string|max:100', 'price' => 'required|numeric',
            'tagline' => 'nullable|string|max:160', 'color' => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/']]);
        if ((float)$data['price'] !== (float)$settings['price']) throw ValidationException::withMessages(['price' => 'The rental price changed. Refresh and review the new price.']);
        DB::transaction(function () use ($settings, $data) {
            $user = User::lockForUpdate()->findOrFail(auth()->id());
            PanelRentals::charge($user, $settings, 'Panel rental: '.$data['slug']);
            $panel = RentalPanel::create(['owner_id' => $user->id, 'name' => $data['name'], 'slug' => $data['slug'], 'subdomain' => PanelRentals::baseDomain($settings) ? $data['slug'].'.'.PanelRentals::baseDomain($settings) : null, 'domain_token' => Str::random(48), 'status' => 'active', 'features' => $settings['features'], 'expires_at' => now()->addDays($settings['days'])]);
            if ($panel->allows('branding')) {
                $panel->update(['tagline' => $data['tagline'] ?? null, 'color' => $data['color'] ?? '#2563eb']);
            }
            PanelRentals::audit($panel->id, 'user:'.$user->id, 'rented', ['price' => $settings['price'], 'days' => $settings['days']]);
        });
        return back()->with('success', 'Your panel is ready. Open Manage panel to customise it.');
    }
    public function show(int $id) {
        $panel = $this->owned($id);
        $filters = request()->validate([
            'customer_search' => 'nullable|string|max:100',
            'customer_id' => 'nullable|integer|min:1',
            'order_search' => 'nullable|string|max:100',
            'order_status' => 'nullable|in:pending,processing,in progress,completed,partial,canceled,refunded',
        ]);
        $customerOrders = Order::with('service')->where('rental_panel_id', $panel->id)
            ->when($filters['order_search'] ?? null, fn($q, $search) => $q->where(function ($match) use ($search) {
                $match->where('link', 'like', '%'.$search.'%');
                if (ctype_digit($search)) $match->orWhere('id', $search);
            }))
            ->when($filters['customer_id'] ?? null, fn($q, $customerId) => $q->where('user_id', $customerId))
            ->when($filters['order_status'] ?? null, fn($q, $status) => $q->where('status', $status));
        $orderStats = Order::where('rental_panel_id', $panel->id)
            ->selectRaw('status, COUNT(*) as total, COALESCE(SUM(price), 0) as revenue, COALESCE(SUM(rental_earning), 0) as earnings')
            ->groupBy('status')->orderByDesc('total')->get();
        $topServices = Order::with('service:id,service_title')->where('rental_panel_id', $panel->id)
            ->selectRaw('service_id, COUNT(*) as total, COALESCE(SUM(price), 0) as revenue, COALESCE(SUM(rental_earning), 0) as earnings')
            ->groupBy('service_id')->orderByDesc('total')->take(10)->get();
        $overrides = DB::table('rental_panel_prices')->where('panel_id', $panel->id)->get()->keyBy('service_id');
        $cur = PanelRentals::currency($panel);
        $services = \App\Models\Service::with('category')->where('service_status', 1)->orderBy('category_id')->get()
            ->each(function ($s) use ($panel, $overrides, $cur) {
                $s->cost_k = round(PanelRentals::costPerK($s, $panel) * (float) $cur->conversion_rate, 4);
                $s->own = optional($overrides->get($s->id))->price;
                $s->hidden_k = (bool) optional($overrides->get($s->id))->hidden || !PanelRentals::serviceImported($panel, (int) $s->id);
                $s->panel_price = PanelRentals::pricePerK($s, $panel, $overrides->get($s->id), $cur);
            });
        return view('rentals.panel', [
            'panel' => $panel, 'settings' => PanelRentals::settings(), 'cur' => $cur, 'services' => $services,
            'currencies' => \App\Models\Currency::where('status', 1)->orderBy('sort_by')->get(['code', 'name']),
            'mpesaReady' => (new \App\Services\MpesaService())->ready(),
            'earnings' => (float) auth()->user()->rental_earnings,
            'payouts' => \App\Models\RentalPayout::where('user_id', auth()->id())->latest()->take(10)->get(),
            'requests' => RentalRequest::with('providerOrder', 'service')->where('panel_id', $panel->id)->latest()->paginate(20),
            'customerOrders' => $customerOrders->latest()->paginate(20, ['*'], 'orders_page')->withQueryString(),
            // Customers with an account on this panel (they sign up on the panel's domain).
            'customers' => \App\Models\User::where('rental_panel_id', $panel->id)
                ->when($filters['customer_search'] ?? null, function ($query, $search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('username', 'like', '%'.$search.'%')->orWhere('email', 'like', '%'.$search.'%');
                    });
                })
                ->select('id', 'username', 'email', 'phone_code', 'phone', 'balance', 'status', 'rental_profile', 'created_at')
                ->selectSub(DB::table('orders')->whereColumn('user_id', 'users.id')->where('rental_panel_id', $panel->id)->selectRaw('count(*)'), 'orders_count')
                ->selectSub(DB::table('orders')->whereColumn('user_id', 'users.id')->where('rental_panel_id', $panel->id)->selectRaw('coalesce(sum(rental_earning), 0)'), 'earned')
                ->latest()->paginate(25, ['*'], 'customers_page')->withQueryString(),
            'customerTotal' => \App\Models\User::where('rental_panel_id', $panel->id)->count(),
            'orderTotal' => (clone $customerOrders)->count(),
            'serviceTotal' => $services->where('hidden_k', false)->count(),
            'orderStats' => $orderStats,
            'topServices' => $topServices,
        ]);
    }
    public function customerStatus(Request $request, int $id, int $customerId) {
        $panel = $this->owned($id);
        abort_unless($panel->allows('accounts'), 403);
        $data = $request->validate(['status' => 'required|boolean']);
        $customer = User::where('rental_panel_id', $panel->id)->findOrFail($customerId);
        DB::transaction(function () use ($panel, $customer, $data) {
            $customer->update(['status' => (bool) $data['status']]);
            PanelRentals::audit($panel->id, 'user:'.auth()->id(), 'customer_status_updated', [
                'customer' => $customer->id, 'status' => (bool) $data['status'],
            ]);
        });
        return back()->with('success', $data['status'] ? 'Customer access restored.' : 'Customer access suspended.');
    }

    public function update(Request $request, int $id) {
        $panel = $this->owned($id); abort_unless($panel->available(), 403, 'This panel is inactive.');
        $data = $request->validate([
            'name' => 'required|string|max:100', 'markup' => 'required|numeric|min:0|max:1000', 'custom_domain' => 'nullable|string|max:253',
            'tagline' => 'nullable|string|max:160', 'color' => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'], 'whatsapp' => ['nullable', 'regex:/^\+?[0-9 ]{7,20}$/'],
            'currency' => 'nullable|string|exists:currencies,code', 'payment_mode' => 'nullable|in:mpesa,manual',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048', 'remove_logo' => 'nullable|boolean',
            'workspace' => 'nullable|array:registration,registration_fields,show_spent,show_orders,announcement,seo_title,seo_description,seo_keywords,terms,privacy,price_format,fast_orders,auto_cancel_failed,show_avg_time,popular_badges,drip_feed,multi_orders,referral_percent,support_telegram,support_email,catalogue_mode,imported_service_ids',
            'workspace.registration_fields' => 'nullable|array:field_1,field_2,field_3,field_4,field_5',
            'workspace.registration_fields.*' => 'array:label,required',
            'workspace.registration_fields.*.label' => 'nullable|string|max:80',
            'workspace.registration_fields.*.required' => 'nullable|boolean',
            'workspace.registration' => 'required_with:workspace|boolean',
            'workspace.show_spent' => 'required_with:workspace|boolean',
            'workspace.show_orders' => 'required_with:workspace|boolean',
            'workspace.announcement' => 'nullable|string|max:1000',
            'workspace.seo_title' => 'nullable|string|max:100',
            'workspace.seo_description' => 'nullable|string|max:300',
            'workspace.seo_keywords' => 'nullable|string|max:300',
            'workspace.terms' => 'nullable|string|max:20000',
            'workspace.privacy' => 'nullable|string|max:20000',
            'workspace.price_format' => 'nullable|in:per_k,per_hundred,per_one',
            'workspace.fast_orders' => 'nullable|boolean',
            'workspace.auto_cancel_failed' => 'nullable|boolean',
            'workspace.show_avg_time' => 'nullable|boolean',
            'workspace.popular_badges' => 'nullable|boolean',
            'workspace.drip_feed' => 'nullable|boolean',
            'workspace.multi_orders' => 'nullable|boolean',
            'workspace.referral_percent' => 'nullable|numeric|min:0|max:100',
            'workspace.support_telegram' => 'nullable|string|max:100',
            'workspace.support_email' => 'nullable|email|max:255',
            'active_tab' => 'nullable|string|max:40',
        ]);
        if (isset($data['workspace'])) {
            $workspace = $data['workspace'];
            if (!$panel->allows('accounts')) unset($workspace['registration_fields']);
            foreach (['registration', 'show_spent', 'show_orders', 'fast_orders', 'auto_cancel_failed', 'show_avg_time', 'popular_badges', 'drip_feed', 'multi_orders'] as $key) {
                if (array_key_exists($key, $workspace)) $workspace[$key] = (bool) $workspace[$key];
            }
            foreach (['announcement', 'seo_title', 'seo_description', 'seo_keywords', 'terms', 'privacy', 'price_format', 'support_telegram', 'support_email'] as $key) {
                if (array_key_exists($key, $workspace)) $workspace[$key] = $workspace[$key] ?? '';
            }
            if (array_key_exists('referral_percent', $workspace)) {
                $workspace['referral_percent'] = round((float) $workspace['referral_percent'], 2);
            }
            if (!$panel->allows('branding')) {
                foreach (['announcement', 'seo_title', 'seo_description', 'seo_keywords', 'terms', 'privacy'] as $key) unset($workspace[$key]);
            }
            $panel->workspace_settings = array_replace($panel->workspace(), $workspace);
        }
        if ($panel->allows('branding')) {
            $panel->name = $data['name'];
            $panel->tagline = $data['tagline'] ?? null;
            $panel->color = $data['color'] ?? '#2563eb';
            $panel->whatsapp = !empty($data['whatsapp']) ? preg_replace('/[^0-9]/', '', $data['whatsapp']) : null;
            if ($request->hasFile('logo')) {
                $dir = base_path('assets/upload/rentals');
                if (!is_dir($dir)) mkdir($dir, 0755, true);
                $file = 'panel-' . $panel->id . '-' . Str::random(8) . '.' . $request->file('logo')->extension();
                $request->file('logo')->move($dir, $file);
                if ($panel->logo) @unlink($dir . '/' . $panel->logo);
                $panel->logo = $file;
            } elseif ($request->boolean('remove_logo') && $panel->logo) {
                @unlink(base_path('assets/upload/rentals/' . $panel->logo));
                $panel->logo = null;
            }
        }
        $panel->currency = $data['currency'] ?? $panel->currency;
        $panel->payment_mode = $data['payment_mode'] ?? $panel->payment_mode;
        if ($panel->allows('markup')) $panel->markup = $data['markup'];
        $domain = PanelRentals::host(array_key_exists('custom_domain', $data) ? ($data['custom_domain'] ?? '') : ($panel->custom_domain ?? ''));
        if ($domain !== ($panel->custom_domain ?? '')) {
            abort_unless($panel->allows('custom_domain'), 403);
            $base = PanelRentals::settings()['base_domain'];
            $main = parse_url(config('app.url'), PHP_URL_HOST);
            if ($domain && (!PanelRentals::validHost($domain) || $domain === $main || ($base && ($domain === $base || str_ends_with($domain, '.'.$base))) || RentalPanel::where('id', '!=', $panel->id)->where(fn($q) => $q->where('custom_domain', $domain)->orWhere('subdomain', $domain))->exists())) {
                throw ValidationException::withMessages(['custom_domain' => 'Use a valid, unassigned domain you own.']);
            }
            $panel->custom_domain = $domain ?: null; $panel->domain_verified_at = null; $panel->domain_token = Str::random(48);
        }
        $panel->save(); PanelRentals::audit($panel->id, 'user:'.auth()->id(), 'settings_updated');
        return back()->with('success', 'Panel settings saved.');
    }
    public function verify(int $id) {
        $panel = $this->owned($id);
        if (!PanelRentals::verifyDomain($panel)) return back()->withErrors(['domain' => 'Verification record not found yet. Check the DNS TXT record and allow time for DNS updates.']);
        $verified = RentalPanel::whereKey($panel->id)->where('custom_domain', $panel->custom_domain)->where('domain_token', $panel->domain_token)->update(['domain_verified_at' => now()]);
        if (!$verified) return back()->withErrors(['domain' => 'The domain changed while verifying. Please try again.']);
        PanelRentals::audit($panel->id, 'user:'.auth()->id(), 'domain_verified');
        return back()->with('success', 'Domain ownership verified. DNS routing and HTTPS must also be configured on your server.');
    }
    public function renew(Request $request, int $id) {
        $settings = PanelRentals::settings();
        $request->validate(['price' => 'required|numeric', 'renewal_token' => 'required|string']);
        if (!hash_equals((string)session('rental_renew_'.$id, ''), $request->renewal_token)) return back()->withErrors(['renewal' => 'This renewal was already submitted or expired. Refresh before trying again.']);
        abort_unless($settings['enabled'], 403);
        if ((float)$request->price !== (float)$settings['price']) return back()->withErrors(['price' => 'The rental price changed. Refresh and review it.']);
        DB::transaction(function () use ($id, $settings) {
            $panel = RentalPanel::where('owner_id', auth()->id())->lockForUpdate()->findOrFail($id);
            abort_unless($panel->status === 'active', 403, 'Only the super admin can restore this panel.');
            $user = User::lockForUpdate()->findOrFail(auth()->id());
            PanelRentals::charge($user, $settings, 'Panel renewal: '.$panel->slug);
            $panel->update(['expires_at' => ($panel->expires_at->isFuture() ? $panel->expires_at : now())->addDays($settings['days'])]);
            PanelRentals::audit($panel->id, 'user:'.$user->id, 'renewed', ['price' => $settings['price']]);
        });
        session()->forget('rental_renew_'.$id);
        return back()->with('success', 'Panel renewed.');
    }
    public function linkOrder(Request $request, int $id, int $requestId) {
        $panel = $this->owned($id); abort_unless($panel->allows('orders'), 403);
        $request->validate(['provider_order_id' => 'required|integer']);
        DB::transaction(function () use ($panel, $requestId, $request) {
            $item = RentalRequest::where('panel_id', $panel->id)->lockForUpdate()->findOrFail($requestId);
            abort_if($item->provider_order_id, 409, 'This request already has a provider order.');
            $order = Order::where('user_id', auth()->id())->where('service_id', $item->service_id)->where('quantity', $item->quantity)->where('link', $item->link)->find($request->provider_order_id);
            if (!$order || RentalRequest::where('provider_order_id', $order->id)->exists()) throw ValidationException::withMessages(['provider_order_id' => 'Choose your matching service, link and quantity order that has not already been linked.']);
            $item->update(['provider_order_id' => $order->id, 'status' => 'submitted']);
            PanelRentals::audit($panel->id, 'user:'.auth()->id(), 'provider_order_linked', ['request' => $item->id, 'order' => $order->id]);
        });
        return back()->with('success', 'Provider order linked. The tracking page now follows its status.');
    }
    /** Save the reseller's own prices (per 1,000, panel currency) and hidden services. */
    public function prices(Request $request, int $id) {
        $panel = $this->owned($id); abort_unless($panel->available() && $panel->allows('markup'), 403);
        $request->validate(['price' => 'nullable|array', 'price.*' => 'nullable|numeric|min:0|max:100000000', 'hidden' => 'nullable|array']);
        $cur = PanelRentals::currency($panel);
        $belowCost = 0;
        DB::transaction(function () use ($request, $panel, $cur, &$belowCost) {
            $ids = collect(array_keys((array) $request->input('price', [])))->merge(array_keys((array) $request->input('hidden', [])))->map(fn($v) => (int) $v)->unique();
            foreach (\App\Models\Service::whereIn('id', $ids)->get() as $service) {
                $raw = $request->input('price.' . $service->id);
                $price = ($raw === null || $raw === '') ? null : (float) $raw;
                $cost = PanelRentals::costPerK($service, $panel) * (float) $cur->conversion_rate;
                if ($price !== null && $price < $cost) { $price = round($cost, 4); $belowCost++; }
                $hidden = (bool) $request->input('hidden.' . $service->id);
                if ($price === null && !$hidden) {
                    DB::table('rental_panel_prices')->where('panel_id', $panel->id)->where('service_id', $service->id)->delete();
                    continue;
                }
                DB::table('rental_panel_prices')->updateOrInsert(['panel_id' => $panel->id, 'service_id' => $service->id],
                    ['price' => $price, 'hidden' => $hidden, 'updated_at' => now(), 'created_at' => now()]);
            }
        });
        PanelRentals::audit($panel->id, 'user:'.auth()->id(), 'prices_updated');
        return back()->with('success', 'Prices saved.' . ($belowCost ? " {$belowCost} price(s) were below your cost and were raised to the cost." : ''));
    }

    /** Live preview of the storefront with unsaved settings (logged-in reseller, before or after renting). */
    public function preview(Request $request) {
        $request->validate(['name' => 'nullable|string|max:100', 'tagline' => 'nullable|string|max:160', 'color' => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'markup' => 'nullable|numeric|min:0|max:1000', 'currency' => 'nullable|string|max:8', 'panel' => 'nullable|integer']);
        $panel = $request->filled('panel') ? $this->owned((int) $request->panel)->replicate() : new RentalPanel();
        if (!$panel->exists && !$request->filled('panel')) {
            $panel->forceFill(['id' => 0, 'slug' => 'preview', 'markup' => 20, 'status' => 'active', 'features' => array_keys(PanelRentals::FEATURES),
                'expires_at' => now()->addDay(), 'payment_mode' => 'mpesa', 'owner_id' => auth()->id()]);
        }
        if ($request->filled('panel')) $panel->id = (int) $request->panel;
        $panel->expires_at = now()->addDay(); $panel->status = 'active';
        foreach (['name', 'tagline', 'color', 'currency'] as $f) if ($request->filled($f)) $panel->{$f} = $request->input($f);
        if ($request->filled('markup')) $panel->markup = (float) $request->markup;
        $panel->name = $panel->name ?: 'Mass Panel Shop';
        $panel->setRelation('owner', auth()->user());
        return app(RentalStorefrontController::class)->render($panel, true);
    }

    /** Public visual preview of the signed-in customer workspace. */
    public function ownerPreview(Request $request) {
        $data = $request->validate(['screen' => 'nullable|in:control,content,promotions,support,landing',
            'order_status' => 'nullable|in:pending,processing,in progress,completed,partial,canceled,refunded']);
        $panel = new RentalPanel(['name' => 'Mass Panel Shop', 'slug' => 'preview', 'status' => 'active',
            'expires_at' => now()->addDays(30), 'features' => array_keys(PanelRentals::FEATURES), 'markup' => 20]);
        $panel->id = 0;
        $empty = fn() => new \Illuminate\Pagination\LengthAwarePaginator([], 0, 25);
        $common = ['panel' => $panel, 'ownerPreview' => true];
        if (($data['screen'] ?? '') === 'landing') return response()->view('rentals.landing-editor', $common + ['draft' => \App\Services\RentalLanding::starter()]);
        if (($data['screen'] ?? '') === 'support') return response()->view('rentals.support', $common + ['owner' => true, 'tickets' => $empty(), 'selected' => null, 'messages' => collect()]);
        if (($data['screen'] ?? '') === 'content') return response()->view('rentals.content-manager', $common + ['selected' => null, 'entries' => $empty()]);
        if (($data['screen'] ?? '') === 'promotions') return response()->view('rentals.promotions', $common + ['promotions' => $empty()]);
        // Synthetic, unsaved records: the public preview never exposes customer orders.
        $sampleOrders = collect(['in progress', 'completed', 'pending'])->map(function ($status, $index) {
            $order = new Order(['service_id' => 101 + $index, 'quantity' => 100 * ($index + 1),
                'price' => 2 + $index, 'status' => $status, 'link' => 'https://example.com/demo']);
            $order->forceFill(['id' => 1001 + $index, 'created_at' => now()->subDays($index), 'rental_earning' => 0.4]);
            $order->setRelation('service', new \App\Models\Service(['service_title' => ['Instagram likes · sample order', 'Facebook followers · sample order', 'YouTube views · sample order'][$index]]));
            return $order;
        })->filter(fn($order) => empty($data['order_status']) || $order->status === $data['order_status'])->values();
        return response()->view('rentals.panel', $common + [
            'settings' => array_replace(PanelRentals::settings(), ['enabled' => false]),
            'cur' => (object)['code' => basicControl()->base_currency],
            'currencies' => \App\Models\Currency::where('status', 1)->get(['code','name']),
            'services' => collect(), 'mpesaReady' => false, 'earnings' => 0, 'payouts' => collect(),
            'requests' => $empty(), 'customerOrders' => new \Illuminate\Pagination\LengthAwarePaginator($sampleOrders, $sampleOrders->count(), 25), 'customers' => $empty(),
            'customerTotal' => 0, 'orderTotal' => 0, 'serviceTotal' => 0,
            'orderStats' => collect(), 'topServices' => collect(),
        ]);
    }

    /** Public visual preview of the signed-in customer workspace. */
    public function customerPreview(Request $request) {
        $data = $request->validate(['screen' => 'nullable|in:dashboard,orders,services,funds,support']);
        $panel = new RentalPanel(['name' => 'Mass Panel Shop', 'status' => 'active', 'expires_at' => now()->addDay(), 'features' => ['branding', 'accounts', 'orders', 'catalogue']]);
        return response()->view('rentals.customer-workspace', [
            'panel' => $panel, 'preview' => true, 'screen' => $data['screen'] ?? 'dashboard',
            'walletBalance' => 0, 'orders' => collect(['total' => 0, 'active' => 0, 'spent' => 0]),
            'currency' => null, 'recentOrders' => collect(),
        ]);
    }

    public function savePayoutDestination(Request $request) {
        abort_unless(RentalPanel::where('owner_id', auth()->id())->exists(), 403);
        $data = \App\Services\PayoutDestination::validate($request);
        DB::table('rental_payout_destinations')->updateOrInsert(['user_id' => auth()->id()], $data + ['created_at' => now(), 'updated_at' => now()]);
        PanelRentals::audit(null, 'user:'.auth()->id(), 'payout_destination_updated', ['country' => $data['country'], 'method' => $data['method']]);
        return back()->with('success', 'Payout destination saved. Existing payout requests keep their original destination.');
    }

    /** Reserve earnings and snapshot the confirmed destination and conversion rate. */
    public function withdraw(Request $request) {
        abort_unless(RentalPanel::where('owner_id', auth()->id())->exists(), 403);
        $request->validate(['amount' => 'required|numeric|decimal:0,2|min:0.01', 'payout_token' => 'required|string']);
        $token = (string) session('rental_payout_token', '');
        if (!$token || !hash_equals($token, $request->payout_token)) throw ValidationException::withMessages(['amount' => 'This request was already submitted. Refresh to request another payout.']);
        $settings = PanelRentals::settings();
        $destination = DB::table('rental_payout_destinations')->where('user_id', auth()->id())->first();
        if (!$destination) throw ValidationException::withMessages(['account' => 'Save your payout destination first.']);
        $data = ['country' => $destination->country, 'method' => $destination->method, 'account' => $destination->account, 'account_ref' => $destination->account_ref];
        $amount = (float) $request->amount;
        if ($amount < (float) $settings['min_payout']) throw ValidationException::withMessages(['amount' => 'The minimum payout is '.$settings['min_payout'].' '.basicControl()->base_currency.'.']);
        $currency = $data['country'] === 'TZ' ? 'TZS' : 'KES';
        $rate = strtoupper(basicControl()->base_currency) === $currency ? 1 : (float) \App\Models\Currency::where('code', $currency)->where('status', 1)->value('conversion_rate');
        if ($rate <= 0) throw ValidationException::withMessages(['amount' => 'An enabled '.$currency.' exchange rate is required. Contact the platform owner.']);
        $local = floor($amount * $rate);
        if ($local < 1) throw ValidationException::withMessages(['amount' => 'The payout must be at least 1 '.$currency.'.']);
        DB::transaction(function () use ($amount, $data, $currency, $local) {
            $user = User::lockForUpdate()->findOrFail(auth()->id());
            if ((float) $user->rental_earnings < $amount) throw ValidationException::withMessages(['amount' => 'That is more than your available earnings.']);
            $user->decrement('rental_earnings', $amount);
            $payout = \App\Models\RentalPayout::create($data + ['user_id' => $user->id, 'amount' => $amount, 'amount_kes' => $currency === 'KES' ? $local : 0,
                'payout_currency' => $currency, 'payout_amount' => $local, 'status' => 'pending']);
            PanelRentals::audit(null, 'user:'.$user->id, 'payout_requested', ['payout' => $payout->id, 'amount' => $amount, 'currency' => $currency]);
        });
        session()->forget('rental_payout_token');
        return back()->with('success', 'Payout requested. Your earnings are reserved pending admin approval.');
    }

    /** Move earnings to the main wallet (to place orders). */
    public function earningsToWallet(Request $request) {
        $data = $request->validate(['amount' => 'required|numeric|min:0.01']);
        DB::transaction(function () use ($data) {
            $user = User::lockForUpdate()->findOrFail(auth()->id());
            if ((float) $user->rental_earnings < (float) $data['amount']) throw ValidationException::withMessages(['amount' => 'That is more than your available earnings.']);
            $user->rental_earnings = (float) $user->rental_earnings - (float) $data['amount'];
            $user->balance = (float) $user->balance + (float) $data['amount'];
            $user->save();
            Transaction::create(['user_id' => $user->id, 'amount' => $data['amount'], 'trx_type' => '+', 'charge' => 0, 'balance' => $user->balance, 'remarks' => 'Panel earnings moved to wallet']);
        });
        return back()->with('success', 'Earnings moved to your main wallet.');
    }

    // ---- admin: payouts
    public function adminPayouts() {
        abort_unless(PanelRentals::isSuperAdmin(), 403);
        return view('admin.rentals.payouts', ['payouts' => \App\Models\RentalPayout::with('user:id,username')->latest()->paginate(30),
            'settings' => PanelRentals::settings(), 'auto' => (new \App\Services\MpesaService())->payoutsReady(PanelRentals::settings())]);
    }

    public function adminPayoutAction(Request $request, int $id) {
        abort_unless(PanelRentals::isSuperAdmin(), 403);
        $request->validate(['action' => 'required|in:send,paid,reject', 'mpesa_ref' => 'required_if:action,paid|nullable|string|max:60', 'note' => 'nullable|string|max:200']);
        $token = Str::random(64);
        $payout = DB::transaction(function () use ($request, $id, $token) {
            $payout = \App\Models\RentalPayout::lockForUpdate()->findOrFail($id);
            // Legacy failed payouts may already have been refunded; never resend them.
            abort_unless(in_array($payout->status, ['pending', 'retryable']), 409, 'Already handled or requires reconciliation. Do not resend.');
            if ($request->action === 'reject') {
                $user = User::lockForUpdate()->findOrFail($payout->user_id);
                $user->increment('rental_earnings', $payout->amount);
                $payout->update(['status' => 'rejected', 'note' => $request->note ?: 'Rejected; earnings returned']);
            } elseif ($request->action === 'paid') {
                $payout->update(['status' => 'paid', 'mpesa_ref' => $request->mpesa_ref, 'note' => $request->note ?: 'Payment confirmed manually']);
            } else {
                abort_unless($payout->country === 'KE', 422, 'Tanzania automatic payouts are not connected. Pay manually and record the receipt.');
                abort_unless((new \App\Services\MpesaService())->payoutsReady(PanelRentals::settings()), 422, 'Configure M-Pesa payouts first.');
                $payout->update(['status' => 'processing', 'callback_token_hash' => hash('sha256', $token), 'conversation_id' => null, 'note' => 'Submitting to M-Pesa; do not resend']);
            }
            PanelRentals::audit(null, 'admin:'.auth('admin')->id(), 'payout_'.$request->action, ['payout' => $id]);
            return $payout;
        });
        if ($request->action !== 'send') return back()->with('success', 'Payout updated.');
        $res = (new \App\Services\MpesaService())->payout(PanelRentals::settings(), $payout->method, $payout->account, $payout->account_ref,
            (int) ($payout->payout_amount ?? $payout->amount_kes), 'Payout #'.$payout->id, $payout->id, $token);
        // A callback may have completed the payout while the HTTP request was in flight.
        \App\Models\RentalPayout::whereKey($id)->where('status', 'processing')->update([
            'status' => $res['ok'] ? 'processing' : (!empty($res['uncertain']) ? 'review' : 'retryable'),
            'conversation_id' => $res['conversation_id'], 'note' => Str::limit($res['message'], 250)]);
        return $res['ok'] ? back()->with('success', 'Sent to M-Pesa; waiting for confirmation.')
            : back()->withErrors(['payout' => $res['message']]);
    }

    /** Per-attempt secret callback; duplicate results cannot credit earnings twice. */
    public function payoutResult(Request $request) {
        $id = $request->query('payout');
        $token = (string) $request->query('token', '');
        $r = $request->json('Result') ?? [];
        DB::transaction(function () use ($id, $token, $r, $request) {
            $payout = \App\Models\RentalPayout::lockForUpdate()->find($id);
            abort_unless($payout && $token && $payout->callback_token_hash && hash_equals($payout->callback_token_hash, hash('sha256', $token)), 403);
            if (!in_array($payout->status, ['processing', 'review'])) return;
            if ($payout->conversation_id && !in_array($payout->conversation_id, [$r['ConversationID'] ?? null, $r['OriginatorConversationID'] ?? null], true)) abort(403);
            if ($request->query('timeout') || !isset($r['ResultCode'])) {
                $payout->update(['status' => 'review', 'note' => 'Uncertain outcome. Reconcile with M-Pesa before any further payment.']);
                return;
            }
            if ((string)$r['ResultCode'] === '0') {
                $parameters = collect($r['ResultParameters']['ResultParameter'] ?? [])->pluck('Value', 'Key');
                if (empty($r['TransactionID']) || !is_numeric($parameters->get('TransactionAmount')) || (float)$parameters->get('TransactionAmount') !== (float)($payout->payout_amount ?? $payout->amount_kes)) {
                    $payout->update(['status' => 'review', 'note' => 'Payment receipt or amount mismatch. Reconciliation required.']);
                    return;
                }
                $payout->update(['status' => 'paid', 'mpesa_ref' => $r['TransactionID'], 'note' => 'Confirmed by M-Pesa']);
            } else {
                $user = User::lockForUpdate()->findOrFail($payout->user_id);
                $user->increment('rental_earnings', $payout->amount);
                $payout->update(['status' => 'rejected', 'note' => Str::limit('M-Pesa declined; earnings returned. '.($r['ResultDesc'] ?? ''), 250)]);
            }
            PanelRentals::audit(null, 'provider:mpesa', 'payout_result', ['payout' => $payout->id, 'status' => $payout->status]);
        });
        return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
    }

    public function admin() {
        abort_unless(PanelRentals::isSuperAdmin(), 403);
        return view('admin.rentals.index', ['settings' => PanelRentals::settings(), 'panels' => RentalPanel::with('owner:id,username')->latest()->paginate(30),
            'audits' => DB::table('rental_audit')->latest('created_at')->limit(30)->get()]);
    }
    public function adminSettings(Request $request) {
        abort_unless(PanelRentals::isSuperAdmin(), 403);
        $data = $request->validate(['price' => 'required|numeric|min:0|max:100000', 'days' => 'required|integer|min:1|max:366', 'base_domain' => 'nullable|string|max:253', 'features' => 'nullable|array', 'features.*' => Rule::in(array_keys(PanelRentals::FEATURES)),
            'platform_fee' => 'nullable|numeric|min:0|max:100', 'min_payout' => 'nullable|numeric|min:0', 'b2c_initiator' => 'nullable|string|max:60',
            'b2c_credential' => 'nullable|string|max:2000', 'b2c_shortcode' => 'nullable|digits_between:5,7']);
        $old = PanelRentals::settings();
        $data['platform_fee'] = (float) ($data['platform_fee'] ?? 0);
        $data['min_payout'] = (float) ($data['min_payout'] ?? 1);
        $data['b2c_initiator'] = $data['b2c_initiator'] ?? '';
        $data['b2c_shortcode'] = $data['b2c_shortcode'] ?? '';
        $data['b2c_credential'] = !empty($data['b2c_credential']) ? $data['b2c_credential'] : $old['b2c_credential'];   // blank = keep saved
        $data['base_domain'] = PanelRentals::host($data['base_domain'] ?? '');
        if ($data['base_domain'] && !PanelRentals::validHost($data['base_domain'])) return back()->withErrors(['base_domain' => 'Enter a domain such as panels.example.com, without https or a path.']);
        $data['enabled'] = $request->boolean('enabled'); $data['features'] = $data['features'] ?? [];
        DB::table('rental_settings')->updateOrInsert(['id' => 1], ['settings' => json_encode($data), 'created_at' => now(), 'updated_at' => now()]);
        PanelRentals::audit(null, 'admin:'.auth('admin')->id(), 'rental_settings_updated', array_diff_key($data, ['b2c_credential' => 1]));
        $filled = PanelRentals::backfillSubdomains();
        return back()->with('success', 'Rental settings saved. Defaults apply to new panels; existing panel controls are below.' . ($filled ? " {$filled} panel(s) received their subdomain." : ''));
    }
    public function adminPanel(Request $request, int $id) {
        abort_unless(PanelRentals::isSuperAdmin(), 403);
        $data = $request->validate(['status' => 'required|in:active,suspended,terminated', 'features' => 'nullable|array', 'features.*' => Rule::in(array_keys(PanelRentals::FEATURES))]);
        $data['features'] = $data['features'] ?? [];
        $panel = RentalPanel::findOrFail($id); $panel->update($data);
        PanelRentals::audit($panel->id, 'admin:'.auth('admin')->id(), 'panel_controls_updated', $data);
        return back()->with('success', 'Panel access and features updated.');
    }
}
