<?php
namespace Tests\Feature;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Models\{GuestOrder,User,Order};
use App\Services\{GuestCheckout,MpesaService};
class GuestCheckoutTest extends TestCase {
    use DatabaseTransactions;
    protected function setUp():void {parent::setUp();Http::preventStrayRequests();$this->minimum(1);}
    private function minimum(int $amount):void {
        \App\Models\Gateway::where('code','mpesa')->firstOrFail()->update(['receivable_currencies'=>[['name'=>'KES','min_limit'=>$amount,'max_limit'=>1000000]]]);
    }
    private function guest(array $extra=[]): GuestOrder {
        $service=GuestCheckout::catalogue()->firstOrFail();
        return GuestOrder::create(array_replace(['token'=>(string)Str::uuid(),'checkout_key'=>(string)Str::uuid(),'service_id'=>$service->id,
            'quantity'=>100,'link'=>'https://example.test/profile','email'=>'private@example.test','phone'=>'254712345678',
            'base_amount'=>1,'amount_kes'=>100,'status'=>'awaiting_payment','payment_ref'=>'mock-'.Str::random(12)],$extra));
    }
    public function test_guest_form_opens_without_login():void {
        $this->get('/order-without-account')->assertOk()->assertSee('Select network')->assertDontSee('id="guestOrder"',false);
        $service = GuestCheckout::catalogue()->firstOrFail();
        $network = $service->category->socialMedia->name;
        $this->get(route('guest.order',['network'=>$network]))->assertOk()->assertSee($service->category->category_title);
        $this->get(route('guest.order',['network'=>$network,'category'=>$service->category_id]))->assertOk()->assertSee('Select service');
        $this->get(route('guest.order',['service'=>$service->id]))->assertOk()->assertSee('Order without an account.')->assertSee('Kenya M-Pesa number');
    }
    public function test_unknown_service_cannot_open_checkout():void {
        $this->get('/order-without-account?service=2147483647')->assertNotFound();
    }
    public function test_activity_popup_can_be_disabled_by_setting():void {
        $basic = \App\Models\BasicControl::firstOrFail();
        try {
            $basic->update(['activity_popup_enabled'=>false]);
            $html = view('themes.light_green.partials.activity_popup')->render();
            $this->assertStringNotContainsString('id="zkLive"', $html);
            $this->assertFalse((bool)$basic->fresh()->activity_popup_enabled);
            $basic->update(['activity_popup_enabled'=>true]);
            $html = view('themes.light_green.partials.activity_popup')->render();
            $this->assertStringContainsString('id="zkLive"', $html);
            $this->assertStringContainsString('Kenya', $html);
            $this->assertStringNotContainsString('Canada', $html);
        } finally {
            \Illuminate\Support\Facades\Cache::forget('ConfigureSetting');
        }
    }
    public function test_tracking_does_not_expose_contact_details():void {
        $guest=$this->guest();
        $this->get('/guest-orders/'.$guest->token)->assertOk()->assertDontSee($guest->email)->assertDontSee($guest->phone)->assertHeader('Referrer-Policy','no-referrer');
        $this->get('/guest-orders/'.Str::uuid())->assertNotFound();
    }
    public function test_unconfirmed_payment_never_places_order():void {
        $guest=$this->guest();
        $this->mock(MpesaService::class,fn($m)=>$m->shouldReceive('stkQuery')->once()->andReturn(null));
        GuestCheckout::sync($guest);
        $this->assertNull($guest->fresh()->order_id);Http::assertNothingSent();
    }
    public function test_paid_guest_creates_one_order_and_no_account():void {
        $guest=$this->guest();$users=User::count();
        Http::fake(['*'=>Http::response(['order'=>987654],200)]);
        $this->mock(MpesaService::class,fn($m)=>$m->shouldReceive('stkQuery')->andReturn(['code'=>'0','desc'=>'Paid']));
        GuestCheckout::sync($guest);GuestCheckout::sync($guest->fresh());
        $this->assertNotNull($guest->fresh()->order_id);
        $this->assertNull($guest->fresh()->order->user_id);
        $this->assertSame($users,User::count());
        $this->assertSame(1,Order::where('id',$guest->fresh()->order_id)->count());
        $this->assertLessThanOrEqual(1,Http::recorded()->count());
    }
    public function test_duplicate_submit_does_not_send_another_prompt():void {
        $guest=$this->guest();
        $this->withSession(['guest_checkout_key'=>$guest->checkout_key])->post('/order-without-account',[
            'checkout_key'=>$guest->checkout_key,'service_id'=>$guest->service_id,'quantity'=>100,'link'=>$guest->link,
            'email'=>$guest->email,'phone'=>$guest->phone,'expected_total'=>100,'terms'=>1,
        ])->assertRedirect(route('guest.track',$guest->token));
        Http::assertNothingSent();
    }
    public function test_tampered_price_cannot_start_payment():void {
        $service=GuestCheckout::catalogue()->firstOrFail();$key=(string)Str::uuid();
        $this->mock(MpesaService::class,fn($m)=>$m->shouldReceive('ready')->andReturn(true));
        $this->withSession(['guest_checkout_key'=>$key])->post('/order-without-account',[
            'checkout_key'=>$key,'service_id'=>$service->id,'quantity'=>$service->min_amount,'link'=>'https://example.test',
            'email'=>'guest@example.test','phone'=>'0712345678','expected_total'=>999999999,'terms'=>1,
        ])->assertSessionHasErrors('expected_total');
        $this->assertDatabaseMissing('guest_orders',['checkout_key'=>$key]);Http::assertNothingSent();
    }
    public function test_valid_checkout_sends_only_the_server_calculated_amount():void {
        $service=GuestCheckout::catalogue()->firstOrFail();$key=(string)Str::uuid();
        $quantity=(int)$service->min_amount;
        $total=(int)ceil(round((float)$service->price*$quantity/1000,8)*GuestCheckout::rate());
        $users=User::count();
        $this->mock(MpesaService::class,function($m)use($total){
            $m->shouldReceive('ready')->andReturn(true);
            $m->shouldReceive('stkPush')->once()->with('254712345678',$total,\Mockery::type('string'),'Guest order')
                ->andReturn(['ok'=>true,'checkout_id'=>'verified-checkout-test','message'=>'sent']);
        });
        $this->withSession(['guest_checkout_key'=>$key])->post('/order-without-account',[
            'checkout_key'=>$key,'service_id'=>$service->id,'quantity'=>$quantity,'link'=>'https://example.test',
            'email'=>'guest@example.test','phone'=>'0712345678','expected_total'=>$total,'terms'=>1,
        ])->assertRedirect();
        $this->assertDatabaseHas('guest_orders',['checkout_key'=>$key,'amount_kes'=>$total,'status'=>'awaiting_payment']);
        $this->assertSame($users,User::count());
    }
    public function test_new_checkout_gets_a_fresh_key_after_previous_order():void {
        $guest=$this->guest();
        $this->withSession(['guest_checkout_key'=>$guest->checkout_key])->get('/order-without-account')->assertOk();
        $this->assertNotSame($guest->checkout_key,session('guest_checkout_key'));
    }
    public function test_admin_minimum_is_applied_and_remainder_credited_once_after_payment():void {
        $this->minimum(50);
        $service=GuestCheckout::catalogue()->firstOrFail();
        $service->update(['price'=>10/GuestCheckout::rate(),'min_amount'=>1,'max_amount'=>10000]);
        $key=(string)Str::uuid();$email='auto_'.Str::random(10).'@example.test';
        $this->mock(MpesaService::class,function($m){
            $m->shouldReceive('ready')->andReturn(true);
            $m->shouldReceive('stkPush')->once()->with('254712345678',50,\Mockery::type('string'),'Guest order')->andReturn(['ok'=>true,'checkout_id'=>'minimum-test','message'=>'sent']);
            $m->shouldReceive('stkQuery')->andReturn(['code'=>'0','desc'=>'Paid']);
        });
        $this->withSession(['guest_checkout_key'=>$key])->post('/order-without-account',[
            'checkout_key'=>$key,'service_id'=>$service->id,'quantity'=>1000,'link'=>'https://example.test',
            'email'=>$email,'phone'=>'0712345678','expected_total'=>50,'terms'=>1,'account_consent'=>1,
        ])->assertRedirect();
        $this->assertDatabaseMissing('users',['email'=>$email]);
        $guest=GuestOrder::where('checkout_key',$key)->firstOrFail();
        $this->assertTrue((bool)$guest->auto_account);
        $this->assertEquals(50,$guest->amount_kes);
        Http::fake(['*'=>Http::response(['order'=>987654],200)]);
        GuestCheckout::sync($guest);GuestCheckout::sync($guest->fresh());
        $user=User::where('email',$email)->firstOrFail();
        $this->assertEqualsWithDelta($guest->wallet_credit,$user->balance,0.000001);
        $this->assertSame($user->id,$guest->fresh()->order->user_id);
        $this->assertSame(1,\App\Models\Transaction::where('user_id',$user->id)->count());
    }
    public function test_existing_email_cannot_receive_guest_credit_without_sign_in():void {
        $this->minimum(50);$service=GuestCheckout::catalogue()->firstOrFail();
        $service->update(['price'=>1/GuestCheckout::rate(),'min_amount'=>1,'max_amount'=>10000]);
        $user=User::create(['username'=>'existing_'.Str::random(10),'email'=>Str::random(15).'@example.test','password'=>'test-only']);
        $key=(string)Str::uuid();
        $this->mock(MpesaService::class,fn($m)=>$m->shouldReceive('ready')->andReturn(true));
        $this->withSession(['guest_checkout_key'=>$key])->post('/order-without-account',[
            'checkout_key'=>$key,'service_id'=>$service->id,'quantity'=>1000,'link'=>'https://example.test',
            'email'=>$user->email,'phone'=>'0712345678','expected_total'=>50,'terms'=>1,'account_consent'=>1,
        ])->assertSessionHasErrors('email');
        $this->assertDatabaseMissing('guest_orders',['checkout_key'=>$key]);Http::assertNothingSent();
    }
    public function test_account_credit_requires_explicit_checkout_consent():void {
        $this->minimum(50);$service=GuestCheckout::catalogue()->firstOrFail();
        $service->update(['price'=>1/GuestCheckout::rate(),'min_amount'=>1,'max_amount'=>10000]);
        $key=(string)Str::uuid();$this->mock(MpesaService::class,fn($m)=>$m->shouldReceive('ready')->andReturn(true));
        $this->withSession(['guest_checkout_key'=>$key])->post('/order-without-account',[
            'checkout_key'=>$key,'service_id'=>$service->id,'quantity'=>1000,'link'=>'https://example.test',
            'email'=>'consent@example.test','phone'=>'0712345678','expected_total'=>50,'terms'=>1,
        ])->assertSessionHasErrors('account_consent');
        Http::assertNothingSent();
    }
}
