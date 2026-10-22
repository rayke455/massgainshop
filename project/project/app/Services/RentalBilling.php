<?php
namespace App\Services;

use App\Models\{Order, RentalPanel, Transaction, User};
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/** Separate prepaid platform-fee accounting. Does not modify customer deposits or rental earnings. */
class RentalBilling
{
    public static function configure(RentalPanel $panel, bool $enabled, int $graceHours): void
    {
        if ($graceHours < 0 || $graceHours > 720) throw ValidationException::withMessages(['grace_hours' => 'Choose 0–720 hours.']);
        DB::transaction(function () use ($panel, $enabled, $graceHours) {
            self::account($panel->id);
            DB::table('rental_billing_accounts')->where('panel_id', $panel->id)->update([
                'enabled' => $enabled, 'grace_hours' => $graceHours, 'updated_at' => now(),
            ]);
            // Existing debt deadlines are not silently extended by configuration changes.
        });
    }

    private static function account(int $panelId): object
    {
        DB::table('rental_billing_accounts')->insertOrIgnore(['panel_id' => $panelId, 'created_at' => now(), 'updated_at' => now()]);
        return DB::table('rental_billing_accounts')->where('panel_id', $panelId)->lockForUpdate()->first();
    }

    /** Called when accepting a NEW order, never as a backfill of historical orders. */
    public static function snapshot(Order $order, RentalPanel $panel, float $profit, float $rate): bool
    {
        if (!is_finite($profit) || !is_finite($rate) || $rate < 0 || $rate > 100 || abs($profit) > 100000000) {
            throw ValidationException::withMessages(['billing' => 'Invalid billing profit or commission rate.']);
        }
        if ((int) $order->rental_panel_id !== (int) $panel->id) abort(403);
        return DB::transaction(function () use ($order, $panel, $profit, $rate) {
            $account = self::account($panel->id);
            if (!$account->enabled) return false;
            $profit = round(max(0, $profit), 6);
            $rate = round($rate, 4);
            DB::table('rental_billing_charges')->insertOrIgnore([
                'panel_id' => $panel->id, 'order_id' => $order->id, 'profit' => $profit, 'rate' => $rate,
                'fee' => round($profit * $rate / 100, 6), 'created_at' => now(), 'updated_at' => now(),
            ]);
            return true;
        });
    }

    /** Synchronize the NET charge, making repeated completion/refund notifications harmless. */
    public static function settle(int $orderId): void
    {
        DB::transaction(function () use ($orderId) {
            $order = Order::whereKey($orderId)->lockForUpdate()->firstOrFail();
            $snapshot = DB::table('rental_billing_charges')->where('order_id', $orderId)->first();
            if (!$snapshot) return; // Legacy orders must never acquire a retrospective fee.
            $account = self::account((int) $snapshot->panel_id);
            $charge = DB::table('rental_billing_charges')->where('id', $snapshot->id)->lockForUpdate()->first();
            $target = match ($order->status) {
                'completed' => (float) $charge->fee,
                'canceled', 'refunded', 'failed', 'fail' => 0.0,
                'partial' => $order->quantity > 0 && $order->remains !== null
                    ? round((float) $charge->fee * (1 - min(1, max(0, (float) $order->remains / (float) $order->quantity))), 6)
                    : (float) $charge->charged,
                default => (float) $charge->charged,
            };
            $delta = round($target - (float) $charge->charged, 6);
            if ($delta == 0) return;
            $revision = (int) $charge->revision + 1;
            self::post($account, -$delta, 'order:'.$orderId.':'.$revision, $delta > 0 ? 'commission' : 'commission_refund', $orderId);
            DB::table('rental_billing_charges')->where('id', $charge->id)->update(['charged' => $target, 'revision' => $revision, 'updated_at' => now()]);
        });
    }

    /** Transfer existing OWNER wallet funds, never tenant-customer funds, with an idempotency token. */
    public static function transfer(User $owner, RentalPanel $panel, float $amount, string $token): void
    {
        if (!is_finite($amount) || $amount <= 0 || $amount > 100000000 || round($amount, 6) != $amount || !preg_match('/^[a-zA-Z0-9-]{16,64}$/D', $token)) {
            throw ValidationException::withMessages(['amount' => 'Enter a valid positive amount and transfer token.']);
        }
        DB::transaction(function () use ($owner, $panel, $amount, $token) {
            $user = User::whereKey($owner->id)->lockForUpdate()->firstOrFail();
            $owned = RentalPanel::whereKey($panel->id)->where('owner_id', $user->id)->firstOrFail();
            abort_if($user->rental_panel_id, 403);
            $account = self::account($owned->id);
            $reference = 'topup:'.$owned->id.':'.$token;
            $prior = DB::table('rental_billing_ledger')->where('reference', $reference)->first();
            if ($prior) {
                if (round((float) $prior->amount, 6) !== round($amount, 6)) throw ValidationException::withMessages(['amount' => 'This transfer token was already used for another amount.']);
                return;
            }
            if ((float) $user->balance < $amount) throw ValidationException::withMessages(['amount' => 'Insufficient owner wallet balance.']);
            $user->balance = round((float) $user->balance - $amount, 6);
            $user->save();
            Transaction::create(['user_id' => $user->id, 'amount' => $amount, 'trx_type' => '-', 'charge' => 0,
                'balance' => $user->balance, 'remarks' => 'Panel billing credit #'.$owned->id]);
            self::post($account, $amount, $reference, 'wallet_transfer', null, $user->id);
        });
    }

    private static function post(object $account, float $amount, string $reference, string $kind, ?int $orderId = null, ?int $actorId = null): void
    {
        $balance = round((float) $account->balance + $amount, 6);
        $deadline = $balance >= 0 ? null : ($account->grace_ends_at ?: now()->addHours((int) $account->grace_hours));
        DB::table('rental_billing_ledger')->insert(['panel_id' => $account->panel_id, 'reference' => $reference, 'kind' => $kind,
            'amount' => $amount, 'balance' => $balance, 'order_id' => $orderId, 'actor_id' => $actorId, 'created_at' => now()]);
        DB::table('rental_billing_accounts')->where('panel_id', $account->panel_id)->update(['balance' => $balance, 'grace_ends_at' => $deadline, 'updated_at' => now()]);
    }

    public static function blocked(int $panelId): bool
    {
        $account = DB::table('rental_billing_accounts')->where('panel_id', $panelId)->first();
        return $account && $account->enabled && (float) $account->balance < 0 && $account->grace_ends_at
            && Carbon::parse($account->grace_ends_at)->lte(now());
    }
}
