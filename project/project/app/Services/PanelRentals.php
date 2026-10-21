<?php
namespace App\Services;
use App\Models\{Currency, Order, RentalPanel, RentalRequest, Service, User, Transaction};
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PanelRentals {
    public const FEATURES = ['catalogue' => 'Service catalogue', 'orders' => 'Order requests', 'branding' => 'Custom panel name', 'markup' => 'Set resale markup', 'custom_domain' => 'Custom domain', 'accounts' => 'Customer accounts (sign up, wallet, dashboard)'];
    public static function settings(): array {
        $row = DB::table('rental_settings')->where('id', 1)->first();
        return array_replace([
            'enabled' => false, 'price' => 0, 'days' => 30, 'base_domain' => '', 'features' => array_keys(self::FEATURES),
            // M-Pesa checkout & payouts
            'platform_fee' => 0,          // % of each sale's profit kept by the platform
            'min_payout' => 1,            // base currency
            'b2c_initiator' => '', 'b2c_credential' => '', 'b2c_shortcode' => '',
        ], $row ? json_decode($row->settings, true) : []);
    }
    public static function audit(?int $panel, string $actor, string $action, array $details = []): void {
        DB::table('rental_audit')->insert(['panel_id' => $panel, 'actor' => $actor, 'action' => $action, 'details' => json_encode($details), 'created_at' => now()]);
    }
    public static function isSuperAdmin(): bool { return auth('admin')->check() && (int) auth('admin')->id() === config('rentals.super_admin_id'); }
    public static function host(string $value): string { return strtolower(rtrim(trim($value), '.')); }
    public static function validHost(string $host): bool {
        return strlen($host) <= 253 && !filter_var($host, FILTER_VALIDATE_IP) && (bool) preg_match('/^(?:[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?\.)+[a-z]{2,63}$/D', $host);
    }

    /** Base for automatic subdomains: the admin setting, else the main site's domain (never an IP / localhost). */
    public static function baseDomain(?array $settings = null): string {
        $settings ??= self::settings();
        if (!empty($settings['base_domain'])) return $settings['base_domain'];
        $main = self::host((string) parse_url(config('app.url'), PHP_URL_HOST));
        $main = preg_replace('/^www\./', '', $main);
        return self::validHost($main) ? $main : '';
    }

    /** Give every panel without one its automatic subdomain (e.g. after the base domain is set). */
    public static function backfillSubdomains(): int {
        $base = self::baseDomain();
        if ($base === '') return 0;
        $n = 0;
        foreach (RentalPanel::whereNull('subdomain')->get() as $panel) {
            $sub = $panel->slug . '.' . $base;
            if (!RentalPanel::where('subdomain', $sub)->orWhere('custom_domain', $sub)->exists()) {
                $panel->update(['subdomain' => $sub]);
                self::audit($panel->id, 'system', 'subdomain_assigned', ['subdomain' => $sub]);
                $n++;
            }
        }
        return $n;
    }

    public static function charge(User $user, array $settings, string $description): void {
        $price = round((float) $settings['price'], 4);
        if ((float) $user->balance < $price) throw ValidationException::withMessages(['balance' => 'Add funds to your account before renting or renewing a panel.']);
        if ($price > 0) {
            $user->decrement('balance', $price);
            Transaction::create(['user_id' => $user->id, 'amount' => $price, 'trx_type' => '-', 'charge' => 0, 'remarks' => $description, 'balance' => $user->fresh()->balance]);
        }
    }
    public static function verifyDomain(RentalPanel $panel): bool {
        if (!$panel->allows('custom_domain') || !$panel->custom_domain) return false;
        $records = @dns_get_record('_zirka-panel.'.$panel->custom_domain, DNS_TXT) ?: [];
        foreach ($records as $record) {
            if (hash_equals('zirka-panel='.$panel->domain_token, $record['txt'] ?? '')) return true;
        }
        return false;
    }

    // ------------------------------------------------------------------ pricing

    /** Currency a panel shows and charges in: its own choice, else KES (M-Pesa), else the base currency. */
    public static function currency(RentalPanel $panel): object {
        $code = $panel->currency ?: 'KES';
        $c = Currency::where('code', $code)->where('status', 1)->first();
        if ($c) return $c;
        return (object) ['code' => basicControl()->base_currency, 'conversion_rate' => 1, 'symbol' => basicControl()->currency_symbol];
    }

    /** Platform cost per 1,000 for this panel owner (their VIP discount included), base currency. */
    public static function costPerK(Service $service, RentalPanel $panel): float {
        return \App\Services\Growth::applyVip((float) $service->price, $panel->owner);
    }

    public static function quantityBased(Service $service): bool {
        return in_array($service->service_type ?: 'Default', Order::QUANTITY_BASED_TYPES, true);
    }

    /** Customer price per 1,000 in the panel currency. Own price if set (never below cost), else markup. */
    public static function pricePerK(Service $service, RentalPanel $panel, ?object $override = null, ?object $cur = null): float {
        $cur ??= self::currency($panel);
        $rate = (float) $cur->conversion_rate ?: 1;
        $cost = self::costPerK($service, $panel) * $rate;
        $own = $override && $override->price !== null ? (float) $override->price : null;
        $price = $own !== null && $panel->allows('markup') ? $own : $cost * (1 + (float) $panel->markup / 100);
        return round(max($price, $cost), 4);
    }

    /** Visible services with this panel's prices. */
    public static function catalogue(RentalPanel $panel) {
        $overrides = DB::table('rental_panel_prices')->where('panel_id', $panel->id)->get()->keyBy('service_id');
        $cur = self::currency($panel);
        return Service::with('category.socialMedia')->where('service_status', 1)
            ->whereHas('category', fn($q) => $q->where('status', 1))->orderBy('category_id')->get()
            ->reject(fn($s) => optional($overrides->get($s->id))->hidden)
            ->each(function ($s) use ($panel, $overrides, $cur) {
                $s->panel_price = self::pricePerK($s, $panel, $overrides->get($s->id), $cur);
            })->values();
    }

    public static function logoUrl(RentalPanel $panel): ?string {
        return $panel->logo ? asset('assets/upload/rentals/' . $panel->logo) : null;
    }

    // ------------------------------------------------------------------ checkout fulfilment

    /** KES amount for a quote in the panel currency (M-Pesa only takes whole KES). */
    public static function quoteToKes(float $quote, object $cur): int {
        $kes = Currency::where('code', 'KES')->first();
        $kesRate = $kes ? (float) $kes->conversion_rate : 1;
        $base = $quote / ((float) $cur->conversion_rate ?: 1);
        return (int) ceil($base * $kesRate);
    }

    /**
     * Called once a customer's M-Pesa payment is confirmed. Places the real order on the owner's account,
     * credits the owner's profit to their earnings wallet. Safe to call more than once.
     */
    public static function fulfil(RentalRequest $request, float $paidKes): void {
        DB::transaction(function () use ($request, $paidKes) {
            $item = RentalRequest::lockForUpdate()->find($request->id);
            if (!$item || $item->status !== 'awaiting_payment') return;      // already handled
            $panel = RentalPanel::with('owner')->find($item->panel_id);
            $service = Service::find($item->service_id);
            $owner = User::lockForUpdate()->find($panel->owner_id);
            $kes = Currency::where('code', 'KES')->first();
            $paidBase = round($paidKes / ($kes ? (float) $kes->conversion_rate : 1), 6);

            $item->paid_amount = $paidKes;
            $item->paid_at = now();
            $cost = self::quantityBased($service)
                ? round(self::costPerK($service, $panel) * $item->quantity / 1000, 6)
                : round(self::costPerK($service, $panel), 6);
            $item->cost = $cost;

            $order = Order::create([
                'user_id' => $owner->id, 'category_id' => $service->category_id, 'service_id' => $service->id,
                'link' => $item->link, 'quantity' => $item->quantity, 'price' => $cost, 'status' => 'pending',
                'reason' => 'Panel ' . $panel->slug . ' customer order #' . $item->id,
            ]);
            $sent = \App\Services\ApiOrderService::placeOrder($service, $order, ['link' => $item->link, 'quantity' => $item->quantity]);

            $fee = (float) self::settings()['platform_fee'];
            if ($sent === false) {
                // No supplier accepted it: keep the order out of the queue, give the owner the full payment to refund/redo.
                $order->update(['status' => 'canceled']);
                $earning = $paidBase;
                $item->status = 'failed';
                $item->note = 'Supplier could not accept the order. The full payment was added to your earnings so you can refund the customer or place it again.';
            } else {
                $profit = max(0, $paidBase - $cost);
                $earning = round($profit * (1 - $fee / 100), 6);
                $item->status = 'submitted';
                $item->provider_order_id = $order->id;
            }
            $item->earning = $earning;
            $item->save();

            $owner->rental_earnings = (float) $owner->rental_earnings + $earning;
            $owner->save();
            self::audit($panel->id, 'system', 'customer_paid', ['request' => $item->id, 'paid_kes' => $paidKes, 'cost' => $cost, 'earning' => $earning, 'order' => $order->id, 'sent' => $sent]);
        });
    }

    // ------------------------------------------------------------------ customer accounts on a panel

    /** The panel this request is served on (set by ResolveRentalHost), or null on the main site. */
    public static function current(): ?RentalPanel {
        return app()->bound('rental.panel') ? app('rental.panel') : null;
    }

    public static function currentId(): ?int {
        return optional(self::current())->id;
    }

    private static array $panelMemo = [];

    public static function panelFor(?User $user): ?RentalPanel {
        if (!$user || !$user->rental_panel_id) return null;
        return self::$panelMemo[$user->rental_panel_id] ??= RentalPanel::with('owner')->find($user->rental_panel_id);
    }

    /** Price per 1,000 a panel customer pays, in the BASE currency (their panel's price, converted). */
    public static function customerRate(Service $service, User $user): float {
        $panel = self::panelFor($user);
        if (!$panel) return (float) $service->price;
        $cur = self::currency($panel);
        $override = DB::table('rental_panel_prices')->where('panel_id', $panel->id)->where('service_id', $service->id)->first();
        return round(self::pricePerK($service, $panel, $override, $cur) / ((float) $cur->conversion_rate ?: 1), 6);
    }

    /** Service ids a panel owner hid from their customers. */
    public static function hiddenIds(?User $user): array {
        $panel = self::panelFor($user);
        return $panel ? DB::table('rental_panel_prices')->where('panel_id', $panel->id)->where('hidden', 1)->pluck('service_id')->all() : [];
    }

    /** After a panel customer's order is accepted: credit the owner's profit. Call inside the order transaction. */
    public static function creditSale(Order $order, User $customer): void {
        $panel = self::panelFor($customer);
        if (!$panel || $order->rental_earning !== null) return;
        $service = Service::find($order->service_id);
        $cost = self::quantityBased($service)
            ? round(self::costPerK($service, $panel) * (int) $order->quantity / 1000, 6)
            : round(self::costPerK($service, $panel), 6);
        $fee = (float) self::settings()['platform_fee'];
        $earning = round(max(0, (float) $order->price - $cost) * (1 - $fee / 100), 6);
        $order->forceFill(['rental_panel_id' => $panel->id, 'rental_earning' => $earning])->save();
        $owner = User::lockForUpdate()->find($panel->owner_id);
        $owner->rental_earnings = (float) $owner->rental_earnings + $earning;
        $owner->save();
        self::audit($panel->id, 'system', 'customer_order', ['order' => $order->id, 'price' => $order->price, 'cost' => $cost, 'earning' => $earning]);
    }

    /** When a panel customer's order is (partly) refunded, take back the matching share of the owner's profit. */
    public static function clawBack(Order $order, float $refund): void {
        if (!$order->rental_panel_id || !$order->rental_earning || (float) $order->price <= 0 || $refund <= 0) return;
        $panel = RentalPanel::find($order->rental_panel_id);
        if (!$panel) return;
        $share = round((float) $order->rental_earning * min(1, $refund / (float) $order->price), 6);
        $owner = User::lockForUpdate()->find($panel->owner_id);
        $owner->rental_earnings = (float) $owner->rental_earnings - $share;   // can go below 0; settled from future sales
        $owner->save();
        $order->forceFill(['rental_earning' => round((float) $order->rental_earning - $share, 6)])->save();
        self::audit($panel->id, 'system', 'earning_reversed', ['order' => $order->id, 'refund' => $refund, 'reversed' => $share]);
    }

    /** Logo path (relative to assets/upload) for branding; generates a text logo when the owner has none. */
    public static function logoPath(RentalPanel $panel): string {
        if ($panel->logo && is_file(base_path('assets/upload/rentals/' . $panel->logo))) return 'rentals/' . $panel->logo;
        $file = 'rentals/auto-' . $panel->id . '-' . substr(md5($panel->name . $panel->color), 0, 8) . '.svg';
        $full = base_path('assets/upload/' . $file);
        if (!is_file($full)) {
            if (!is_dir(dirname($full))) mkdir(dirname($full), 0755, true);
            $name = htmlspecialchars($panel->name, ENT_XML1);
            $initial = htmlspecialchars(mb_strtoupper(mb_substr($panel->name, 0, 1)), ENT_XML1);
            $color = preg_match('/^#[0-9a-fA-F]{6}$/', (string) $panel->color) ? $panel->color : '#2563eb';
            $w = 56 + (int) ceil(mb_strlen($panel->name) * 11.5);
            file_put_contents($full, '<svg xmlns="http://www.w3.org/2000/svg" width="' . $w . '" height="40" viewBox="0 0 ' . $w . ' 40">'
                . '<rect width="40" height="40" rx="10" fill="' . $color . '"/>'
                . '<text x="20" y="27" text-anchor="middle" font-family="Arial,Helvetica,sans-serif" font-size="20" font-weight="700" fill="#fff">' . $initial . '</text>'
                . '<text x="50" y="27" font-family="Arial,Helvetica,sans-serif" font-size="19" font-weight="700" fill="#0f172a">' . $name . '</text></svg>');
        }
        return $file;
    }

    /** Settings object for this request with the panel's name and logo (so the whole customer area is rebranded). */
    public static function brand($basic) {
        $panel = self::current();
        if (!$panel || !$basic) return $basic;
        $b = clone $basic;
        $logo = self::logoPath($panel);
        $b->site_title = $panel->name;
        foreach (['logo', 'admin_logo', 'admin_dark_mode_logo', 'favicon'] as $f) {
            $b->{$f} = $logo;
            $b->{$f . '_driver'} = 'local';
        }
        return $b;
    }
}
