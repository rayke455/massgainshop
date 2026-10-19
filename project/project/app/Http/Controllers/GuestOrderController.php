<?php
namespace App\Http\Controllers;
use App\Models\GuestOrder;
use App\Services\{GuestCheckout,MpesaService,PanelRentals};
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
class GuestOrderController extends Controller {
    public function index() {
        abort_if(PanelRentals::current(),404);
        $key=session('guest_checkout_key');
        if (!$key || GuestOrder::where('checkout_key',$key)->exists()) $key=(string)Str::uuid();
        session(['guest_checkout_key'=>$key]);
        return view('guest.checkout',['services'=>GuestCheckout::catalogue(),'rate'=>GuestCheckout::rate(),
            'ready'=>app(MpesaService::class)->ready(),'checkoutKey'=>$key,'limits'=>GuestCheckout::paymentLimits()]);
    }
    public function store(Request $request) {
        abort_if(PanelRentals::current(),404);
        $data=$request->validate(['checkout_key'=>'required|uuid','service_id'=>'required|integer','quantity'=>'required|integer|min:1|max:2147483647',
            'link'=>'required|url:http,https|max:2048','email'=>'required|email|max:255','phone'=>'required|string|max:20','expected_total'=>'required|integer|min:1','terms'=>'accepted']);
        abort_unless(hash_equals((string)session('guest_checkout_key',''),$data['checkout_key']),419);
        if ($existing=GuestOrder::where('checkout_key',$data['checkout_key'])->first()) return redirect()->route('guest.track',$existing->token);
        abort_unless(app(MpesaService::class)->ready(),503,'M-Pesa is temporarily unavailable.');
        $service=GuestCheckout::catalogue()->firstWhere('id',(int)$data['service_id']);
        if (!$service || $data['quantity']<$service->min_amount || $data['quantity']>$service->max_amount) throw ValidationException::withMessages(['quantity'=>'Select an available service and a quantity within its limits.']);
        $rate=GuestCheckout::rate();
        abort_unless($rate>0,503,'KES exchange rate is unavailable.');
        $base=round((float)$service->price*$data['quantity']/1000,8); $orderTotal=(int)ceil($base*$rate);
        $limits=GuestCheckout::paymentLimits(); $total=max($orderTotal,$limits['min']);
        if ($limits['max']!==null && $total>$limits['max']) throw ValidationException::withMessages(['expected_total'=>'This payment exceeds the configured M-Pesa maximum. Reduce the quantity.']);
        if ($total<1 || $total!==(int)$data['expected_total']) throw ValidationException::withMessages(['expected_total'=>'The price changed. Refresh and review the total before paying.']);
        $phone=MpesaService::normalizePhone($data['phone']);
        if (!$phone) throw ValidationException::withMessages(['phone'=>'Enter a Kenyan M-Pesa number, e.g. 0712 345 678.']);
        $autoAccount=$orderTotal<$limits['min']; $account=null;
        if ($autoAccount) {
            $request->validate(['account_consent'=>'accepted']);
            $existing=\App\Models\User::withTrashed()->where('email',$data['email'])->first();
            if ($existing && (!auth()->check() || auth()->id()!==$existing->id || $existing->trashed() || $existing->rental_panel_id)) {
                throw ValidationException::withMessages(['email'=>'Please sign in to the account for this email before adding wallet credit.']);
            }
            $account=$existing;
        }
        $guest=GuestOrder::create(['token'=>(string)Str::uuid(),'checkout_key'=>$data['checkout_key'],'service_id'=>$service->id,
            'quantity'=>$data['quantity'],'link'=>$data['link'],'email'=>$data['email'],'phone'=>$phone,'base_amount'=>$base,'amount_kes'=>$total,
            'order_amount_kes'=>$orderTotal,'auto_account'=>$autoAccount,'user_id'=>$account?->id,
            'wallet_credit'=>$autoAccount ? round(($total-$orderTotal)/$rate,8) : 0]);
        try {
            $push=app(MpesaService::class)->stkPush($phone,$total,'G'.$guest->id,'Guest order');
            $guest->update(['payment_ref'=>$push['checkout_id'],'status'=>$push['ok']?'awaiting_payment':'payment_failed']);
        } catch (\Throwable $e) { $guest->update(['status'=>'payment_review']); }
        return redirect()->route('guest.track',$guest->token);
    }
    public function track(string $token) {
        return response()->view('guest.track',['guest'=>GuestOrder::with('order','service')->where('token',$token)->firstOrFail()])
            ->header('Cache-Control','private, no-store')->header('Referrer-Policy','no-referrer')->header('X-Robots-Tag','noindex');
    }
    public function status(string $token) {
        $guest=GuestOrder::where('token',$token)->firstOrFail(); GuestCheckout::sync($guest); $guest->refresh();
        return response()->json(['status'=>$guest->status,'order_status'=>optional($guest->order)->status])->header('Cache-Control','no-store');
    }
    public function admin() {
        abort_unless(PanelRentals::isSuperAdmin(),403);
        return view('admin.rentals.guest-orders',['orders'=>GuestOrder::with('order','service')->latest()->paginate(30)]);
    }
}
