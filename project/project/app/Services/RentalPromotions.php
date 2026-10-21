<?php
namespace App\Services;
use App\Models\{RentalPanel, User, Transaction};
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RentalPromotions
{
    public static function redeem(RentalPanel $panel, User $customer, string $code): void
    {
        abort_unless($panel->allows('accounts') && $customer->status && (int)$customer->rental_panel_id === (int)$panel->id, 403);
        DB::transaction(function () use ($panel, $customer, $code) {
            $promo = DB::table('rental_promotions')->where('panel_id', $panel->id)->where('code', strtoupper(trim($code)))->lockForUpdate()->first();
            if (!$promo || !$promo->active || ($promo->expires_at && now()->gte($promo->expires_at)) || $promo->uses >= $promo->max_uses) {
                throw ValidationException::withMessages(['code' => 'This code is invalid, expired or fully used.']);
            }
            if (DB::table('rental_promotion_redemptions')->where('promotion_id', $promo->id)->where('user_id', $customer->id)->exists()) {
                throw ValidationException::withMessages(['code' => 'You have already redeemed this code.']);
            }
            // Consistent lock ordering prevents competing transfers spending the same funds.
            $wallets = User::whereIn('id', [$panel->owner_id, $customer->id])->orderBy('id')->lockForUpdate()->get()->keyBy('id');
            $owner = $wallets->get($panel->owner_id); $recipient = $wallets->get($customer->id);
            abort_unless($owner && $recipient && $owner->id !== $recipient->id && $recipient->status && (int)$recipient->rental_panel_id === (int)$panel->id, 403);
            if (!$owner->status || bccomp((string)$owner->balance, (string)$promo->amount, 4) < 0) {
                throw ValidationException::withMessages(['code' => 'This promotion is temporarily unavailable. Contact panel support.']);
            }
            $owner->decrement('balance', $promo->amount);
            $recipient->increment('balance', $promo->amount);
            foreach ([[$owner, '-'], [$recipient, '+']] as [$user, $direction]) {
                Transaction::create(['user_id' => $user->id, 'amount' => $promo->amount, 'trx_type' => $direction,
                    'balance' => $user->fresh()->balance, 'charge' => 0, 'remarks' => 'Panel promotion #'.$promo->id]);
            }
            DB::table('rental_promotion_redemptions')->insert(['promotion_id' => $promo->id, 'user_id' => $customer->id, 'amount' => $promo->amount, 'created_at' => now()]);
            DB::table('rental_promotions')->where('id', $promo->id)->increment('uses');
            PanelRentals::audit($panel->id, 'user:'.$customer->id, 'promotion_redeemed', ['promotion_id' => $promo->id, 'amount' => $promo->amount]);
        });
    }
}
