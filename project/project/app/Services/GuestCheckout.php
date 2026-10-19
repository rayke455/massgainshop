<?php
namespace App\Services;
use App\Models\{GuestOrder,Order,Service,ApiProvider,Currency,Gateway,User,Transaction};
use Illuminate\Support\Facades\{DB,Http};
class GuestCheckout {
    public static function paymentLimits(): array {
        $gateway=Gateway::where('code','mpesa')->where('status',1)->first();
        $currency=collect($gateway->receivable_currencies ?? [])->first(fn($c)=>strtoupper((string)($c->name ?? $c->currency ?? ''))==='KES');
        return ['min'=>max(1,(int)ceil((float)($currency->min_limit ?? 1))),
            'max'=>isset($currency->max_limit) ? (int)floor((float)$currency->max_limit) : null];
    }
    public static function catalogue() {
        return Service::with('category.socialMedia')->where('service_status',1)->where('service_type','Default')
            ->whereHas('category',fn($q)=>$q->where('status',1))->orderBy('category_id')->get();
    }
    public static function rate(): float {
        return strtoupper(basicControl()->base_currency)==='KES' ? 1 : (float) Currency::where('code','KES')->where('status',1)->value('conversion_rate');
    }
    public static function sync(GuestOrder $guest): void {
        if ($guest->status!=='awaiting_payment' || !$guest->payment_ref) return;
        $verified=app(MpesaService::class)->stkQuery($guest->payment_ref);
        if (!$verified) return;
        if ($verified['code']==='0') self::fulfil($guest);
        elseif (in_array($verified['code'],['1032','1037','1','2001'],true)) {
            GuestOrder::whereKey($guest->id)->where('status','awaiting_payment')->update(['status'=>'payment_failed']);
        }
    }
    /** Claim payment exactly once before contacting a supplier. Never automatically resend ambiguous requests. */
    public static function fulfil(GuestOrder $guest): void {
        $order=DB::transaction(function() use($guest) {
            $locked=GuestOrder::lockForUpdate()->findOrFail($guest->id);
            if ($locked->status!=='awaiting_payment') return null;
            $service=Service::find($locked->service_id);
            $locked->update(['paid_at'=>now(),'status'=>'review']);
            if ($locked->auto_account) {
                $user=$locked->user_id ? User::lockForUpdate()->find($locked->user_id) : null;
                if (!$locked->user_id) {
                    // Never attach an unverified guest email to an existing account.
                    if (User::withTrashed()->where('email',$locked->email)->lockForUpdate()->first()) {
                        $locked->update(['status'=>'account_review']); return null;
                    }
                    $user=User::create(['username'=>'customer_'.strtolower(\Illuminate\Support\Str::random(12)),
                        'email'=>$locked->email,'password'=>\Illuminate\Support\Str::random(64),
                        'currency'=>'KES','country_code'=>'KE','phone_code'=>'+254','phone'=>substr($locked->phone,3),
                        'email_verification'=>0,'sms_verification'=>basicControl()->sms_verification ? 0 : 1]);
                }
                if (!$user || $user->rental_panel_id) { $locked->update(['status'=>'account_review']); return null; }
                $user->increment('balance',$locked->wallet_credit);
                Transaction::create(['user_id'=>$user->id,'amount'=>$locked->wallet_credit,'trx_type'=>'+','charge'=>0,
                    'balance'=>$user->fresh()->balance,'remarks'=>'Unused guest checkout deposit #'.$locked->id]);
                $locked->update(['user_id'=>$user->id]);
            }
            if (!$service) return null;
            $order=Order::create(['user_id'=>$locked->user_id,'service_id'=>$service->id,'category_id'=>$service->category_id,
                'link'=>$locked->link,'quantity'=>$locked->quantity,'price'=>$locked->base_amount,'status'=>'pending',
                'reason'=>'Guest checkout #'.$locked->id.' — contact/payment details in Guest orders.']);
            $locked->update(['order_id'=>$order->id]);
            return $order;
        });
        if (!$order) return;
        $service=Service::find($order->service_id);
        if (!$service->api_provider_id) { $guest->update(['status'=>'submitted']); return; }
        $provider=ApiProvider::where('status',1)->find($service->api_provider_id);
        if (!$provider) return;
        try {
            $response=Http::asForm()->timeout(30)->post($provider->url,['key'=>$provider->api_key,'action'=>'add',
                'service'=>$service->api_service_id,'link'=>$order->link,'quantity'=>$order->quantity]);
            $external=$response->json('order');
            if ($response->successful() && is_scalar($external) && (string)$external!=='') {
                $order->update(['api_order_id'=>$external,'api_provider_id'=>$provider->id,'status'=>'processing']);
                $guest->update(['status'=>'submitted']);
            }
        } catch (\Throwable $e) {
            // Keep paid order for human reconciliation; a timeout can still mean the supplier accepted it.
            $order->update(['status_description'=>'Guest supplier response uncertain. Reconcile before resending.']);
        }
    }
}
