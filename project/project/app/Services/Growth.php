<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\Deposit;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\User;
use App\Models\VipLevel;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Growth features in one place: settings, VIP levels, coupons and deposit bonuses.
 * Money is always calculated in the base currency.
 */
class Growth
{
    // ---------------------------------------------------------------- settings

    public const DEFAULTS = [
        'bonus.first_deposit_percent' => 0,
        'bonus.tiers' => [],                 // [['min' => 50, 'percent' => 5], ...]
        'vip.enabled' => true,
        'whatsapp.number' => '',
        'whatsapp.message' => 'Hi, I need help with my order',
        'notify.order_completed' => true,
        'report.daily' => true,
        'loss.auto_disable' => true,
        'trial.enabled' => false,
        'trial.service_id' => null,
        'trial.quantity' => 100,
        'fraud.block_disposable' => true,
        'fraud.signups_per_ip_day' => 3,
        'reviews.enabled' => true,
    ];

    public static function all(): array
    {
        return Cache::rememberForever('zk_site_settings', function () {
            $rows = DB::table('site_settings')->pluck('value', 'key')->map(fn($v) => json_decode($v, true))->all();
            return array_replace(self::DEFAULTS, $rows);
        });
    }

    public static function get(string $key, $default = null)
    {
        $all = self::all();
        return array_key_exists($key, $all) ? $all[$key] : $default;
    }

    public static function set(array $values): void
    {
        foreach ($values as $key => $value) {
            DB::table('site_settings')->updateOrInsert(['key' => $key], ['value' => json_encode($value), 'updated_at' => now(), 'created_at' => now()]);
        }
        Cache::forget('zk_site_settings');
    }

    // ---------------------------------------------------------------- VIP levels

    /** Lifetime spend on orders that weren't refunded. */
    public static function spent(User $user): float
    {
        return (float) Order::where('user_id', $user->id)->whereNotIn('status', ['canceled', 'refunded', 'fail'])->sum('price');
    }

    /** ['level' => VipLevel|null, 'next' => VipLevel|null, 'spent' => float] */
    /** Per-request memo (a page asks several times); cleared with forgetVip(). */
    private static array $vipMemo = [];

    public static function forgetVip(): void
    {
        self::$vipMemo = [];
    }

    public static function vip(?User $user): array
    {
        if (!$user || $user->rental_panel_id || !self::get('vip.enabled', true)) {
            return ['level' => null, 'next' => null, 'spent' => 0.0];
        }
        $memo = &self::$vipMemo;
        if (isset($memo[$user->id])) {
            return $memo[$user->id];
        }
        $spent = self::spent($user);
        $levels = VipLevel::orderBy('min_spent')->get();
        $level = $levels->filter(fn($l) => $spent >= (float) $l->min_spent)->last();
        $next = $levels->first(fn($l) => (float) $l->min_spent > $spent);
        return $memo[$user->id] = ['level' => $level, 'next' => $next, 'spent' => $spent];
    }

    public static function vipDiscount(?User $user): float
    {
        $level = self::vip($user)['level'];
        return $level ? max(0, min(90, (float) $level->discount_percent)) : 0.0;
    }

    /** Price per 1,000 this customer pays (base currency): panel price for panel customers, else special rate + VIP. */
    public static function rateFor($service, ?User $user): float
    {
        if ($user && $user->rental_panel_id) {
            return \App\Services\PanelRentals::customerRate($service, $user);
        }
        return self::applyVip((float) ($service->user_rate ?? $service->price), $user);
    }

    /** Price after the customer's VIP discount. */
    public static function applyVip(float $price, ?User $user): float
    {
        $pct = self::vipDiscount($user);
        return $pct > 0 ? round($price * (1 - $pct / 100), (int) (basicControl()->fraction_number ?? 2) + 2) : $price;
    }

    // ---------------------------------------------------------------- coupons & bonuses

    /**
     * Check a coupon for a deposit of $amountBase (base currency).
     * @return array{ok:bool, message:string, coupon:?Coupon, bonus:float}
     */
    public static function checkCoupon(?string $code, User $user, float $amountBase): array
    {
        $code = strtoupper(trim((string) $code));
        if ($code === '') {
            return ['ok' => false, 'message' => '', 'coupon' => null, 'bonus' => 0.0];
        }
        $coupon = Coupon::where('code', $code)->first();
        $fail = fn($m) => ['ok' => false, 'message' => $m, 'coupon' => null, 'bonus' => 0.0];

        if (!$coupon || !$coupon->status) {
            return $fail(__('This coupon code is not valid.'));
        }
        if ($coupon->expires_at && $coupon->expires_at->isPast()) {
            return $fail(__('This coupon has expired.'));
        }
        if ($coupon->max_uses !== null && $coupon->used_count >= $coupon->max_uses) {
            return $fail(__('This coupon has been fully used.'));
        }
        $usedByUser = DB::table('coupon_redemptions')->where('coupon_id', $coupon->id)->where('user_id', $user->id)->count();
        if ($usedByUser >= max(1, (int) $coupon->per_user)) {
            return $fail(__('You have already used this coupon.'));
        }
        if ($coupon->first_deposit_only && Deposit::where('user_id', $user->id)->where('status', 1)->exists()) {
            return $fail(__('This coupon is only for your first deposit.'));
        }
        if ($amountBase < (float) $coupon->min_deposit) {
            return $fail(__('Add at least :min to use this coupon.', ['min' => trim(currencyPosition($coupon->min_deposit))]));
        }

        $bonus = $coupon->type === 'percent' ? $amountBase * (float) $coupon->value / 100 : (float) $coupon->value;
        return ['ok' => true, 'message' => __('Coupon :code applied ✓ (see the bonus in the summary)', ['code' => $coupon->code]), 'coupon' => $coupon, 'bonus' => round($bonus, 4)];
    }

    /** Automatic bonus: the best of the first-deposit bonus and the amount tiers (percent of the deposit). */
    public static function autoBonusPercent(User $user, float $amountBase, ?int $excludeDepositId = null): float
    {
        $pct = 0.0;
        $isFirst = !Deposit::where('user_id', $user->id)->where('status', 1)
            ->when($excludeDepositId, fn($q) => $q->where('id', '!=', $excludeDepositId))->exists();
        if ($isFirst) {
            $pct = max($pct, (float) self::get('bonus.first_deposit_percent', 0));
        }
        foreach ((array) self::get('bonus.tiers', []) as $tier) {
            if ($amountBase >= (float) ($tier['min'] ?? INF)) {
                $pct = max($pct, (float) ($tier['percent'] ?? 0));
            }
        }
        return min($pct, 100);
    }

    /**
     * Called once when a deposit is credited (inside the same transaction).
     * Credits coupon bonus + automatic bonus as a separate "Bonus" transaction.
     */
    public static function creditDepositBonus(Deposit $deposit, User $user): float
    {
        $amount = (float) $deposit->payable_amount_in_base_currency; // what lands on the balance, base currency
        if ($amount <= 0 || $deposit->bonus_amount !== null || $user->rental_panel_id) {
            return 0.0;
        }

        $bonus = $amount * self::autoBonusPercent($user, $amount, $deposit->id) / 100;
        $coupon = null;
        if ($deposit->coupon_id && ($coupon = Coupon::lockForUpdate()->find($deposit->coupon_id))) {
            // Re-check at payment time (limits may have been reached since the deposit was created).
            $check = self::checkCoupon($coupon->code, $user, $amount);
            if ($check['ok']) {
                $bonus += $check['bonus'];
            } else {
                $coupon = null;
            }
        }

        $bonus = round($bonus, 4);
        $deposit->bonus_amount = $bonus;
        $deposit->save();
        if ($bonus <= 0) {
            return 0.0;
        }

        $user->balance += $bonus;
        $user->save();
        Transaction::create([
            'user_id' => $user->id,
            'trx_type' => '+',
            'amount' => $bonus,
            'charge' => 0,
            'balance' => $user->balance,
            'remarks' => 'Deposit bonus' . ($coupon ? ' (coupon ' . $coupon->code . ')' : ''),
        ]);
        if ($coupon) {
            DB::table('coupon_redemptions')->insert(['coupon_id' => $coupon->id, 'user_id' => $user->id, 'deposit_id' => $deposit->id,
                'bonus' => $bonus, 'created_at' => now(), 'updated_at' => now()]);
            $coupon->increment('used_count');
        }
        return $bonus;
    }

    // ---------------------------------------------------------------- fraud

    public const DISPOSABLE_DOMAINS = ['mailinator.com', 'guerrillamail.com', 'guerrillamail.net', '10minutemail.com', 'tempmail.com', 'temp-mail.org',
        'yopmail.com', 'trashmail.com', 'getnada.com', 'sharklasers.com', 'dispostable.com', 'fakeinbox.com', 'maildrop.cc', 'throwawaymail.com',
        'mintemail.com', 'mohmal.com', 'emailondeck.com', 'tempail.com', 'moakt.com', 'spamgourmet.com', 'mailnesia.com', 'tempmailo.com',
        'burnermail.io', 'mail.tm', 'tmail.ws', 'tempinbox.com', 'discard.email', 'mytemp.email', 'emailfake.com', '1secmail.com'];

    public static function isDisposableEmail(?string $email): bool
    {
        $domain = strtolower(substr(strrchr((string) $email, '@') ?: '', 1));
        return $domain !== '' && in_array($domain, self::DISPOSABLE_DOMAINS, true);
    }
}
