<?php
namespace Tests\Feature;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\{User, Admin, RentalPanel, RentalPayout};
use App\Services\PayoutDestination;
use App\Http\Controllers\PanelRentalController;
class RentalPayoutSafetyTest extends TestCase {
    use DatabaseTransactions;
    protected function setUp(): void { parent::setUp(); Http::preventStrayRequests(); }
    private function owner(): User {
        $key = 'payout_'.strtolower(\Illuminate\Support\Str::random(12));
        $user = User::create(['username'=>$key,'email'=>$key.'@example.test','password'=>'test-only-password']);
        $user->forceFill(['rental_earnings'=>100])->save();
        RentalPanel::create(['owner_id'=>$user->id,'slug'=>$key,'name'=>'Test','domain_token'=>'test','status'=>'active','expires_at'=>now()->addDay(),'features'=>[]]);
        $this->actingAs($user);
        return $user;
    }
    private function payout(User $user, array $extra=[]): RentalPayout {
        return RentalPayout::create(array_replace(['user_id'=>$user->id,'amount'=>10,'amount_kes'=>100,'payout_amount'=>100,'payout_currency'=>'KES','country'=>'KE','method'=>'b2c','account'=>'254712345678','status'=>'processing','callback_token_hash'=>hash('sha256','test-secret')],$extra));
    }
    private function payoutCallback(RentalPayout $payout, array $result, string $token='test-secret'): Request {
        return Request::create('/rentals/payout-result?payout='.$payout->id.'&token='.$token,'POST',[],[],[],['CONTENT_TYPE'=>'application/json'],json_encode(['Result'=>$result]));
    }
    public function test_both_country_numbers_normalize_without_cross_country_routing(): void {
        foreach ([['KE','0712345678','254712345678'],['TZ','0754123456','255754123456']] as [$country,$input,$expected]) {
            $data = PayoutDestination::validate(Request::create('/','POST',['country'=>$country,'method'=>'b2c','account'=>$input]));
            $this->assertSame($expected,$data['account']);
        }
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        PayoutDestination::validate(Request::create('/','POST',['country'=>'KE','method'=>'b2c','account'=>'+255754123456']));
    }
    public function test_paybill_reference_is_required(): void {
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        PayoutDestination::validate(Request::create('/','POST',['country'=>'KE','method'=>'paybill','account'=>'123456']));
    }
    public function test_panel_management_renders_country_and_destination_controls(): void {
        $user=$this->owner();
        $panel=RentalPanel::where('owner_id',$user->id)->firstOrFail();
        $html=app(PanelRentalController::class)->show($panel->id)->with('errors', new \Illuminate\Support\ViewErrorBag())->render();
        $this->assertStringContainsString('Save payout destination',$html);
        $this->assertStringContainsString('Tanzania · TZS',$html);
        $this->assertStringContainsString('Paybill account reference',$html);
    }
    public function test_failed_callback_refunds_only_once(): void {
        $user=$this->owner(); $payout=$this->payout($user);
        $request=$this->payoutCallback($payout,['ResultCode'=>1,'ResultDesc'=>'Declined']);
        app(PanelRentalController::class)->payoutResult($request);
        app(PanelRentalController::class)->payoutResult($request);
        $this->assertEquals(110,$user->fresh()->rental_earnings);
        $this->assertSame('rejected',$payout->fresh()->status);
    }
    public function test_unsigned_callback_cannot_change_earnings(): void {
        $user=$this->owner(); $payout=$this->payout($user);
        try { app(PanelRentalController::class)->payoutResult($this->payoutCallback($payout,['ResultCode'=>1],'wrong')); $this->fail('Must reject'); }
        catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) { $this->assertSame(403,$e->getStatusCode()); }
        $this->assertEquals(100,$user->fresh()->rental_earnings);
    }
    public function test_amount_mismatch_is_held_for_review(): void {
        $user=$this->owner(); $payout=$this->payout($user);
        app(PanelRentalController::class)->payoutResult($this->payoutCallback($payout,['ResultCode'=>0,'TransactionID'=>'TEST123','ResultParameters'=>['ResultParameter'=>[['Key'=>'TransactionAmount','Value'=>999]]]]));
        $this->assertSame('review',$payout->fresh()->status);
        $this->assertEquals(100,$user->fresh()->rental_earnings);
    }
    public function test_tanzania_request_snapshots_saved_destination_and_consumes_token(): void {
        $user=$this->owner();
        DB::table('rental_settings')->updateOrInsert(['id'=>1],['settings'=>json_encode(['min_payout'=>1])]);
        DB::table('currencies')->where('code','TZS')->update(['status'=>1,'conversion_rate'=>100]);
        app(PanelRentalController::class)->savePayoutDestination(Request::create('/','POST',['country'=>'TZ','method'=>'b2c','account'=>'0754123456']));
        session(['rental_payout_token'=>'one-request']);
        $request=Request::create('/','POST',['amount'=>'10.00','payout_token'=>'one-request']);
        app(PanelRentalController::class)->withdraw($request);
        $payout=RentalPayout::where('user_id',$user->id)->firstOrFail();
        $this->assertSame('TZS',$payout->payout_currency);
        $this->assertSame('255754123456',$payout->account);
        $this->assertEquals(90,$user->fresh()->rental_earnings);
        try { app(PanelRentalController::class)->withdraw($request); $this->fail('Duplicate must fail'); }
        catch (\Illuminate\Validation\ValidationException $e) { $this->assertEquals(90,$user->fresh()->rental_earnings); }
    }
    public function test_tanzania_cannot_be_sent_through_safaricom(): void {
        $user=$this->owner(); $payout=$this->payout($user,['country'=>'TZ','status'=>'pending','payout_currency'=>'TZS']);
        $admin=new Admin(); $admin->id=config('rentals.super_admin_id'); $this->actingAs($admin,'admin');
        try { app(PanelRentalController::class)->adminPayoutAction(Request::create('/','POST',['action'=>'send']),$payout->id); $this->fail('Must reject'); }
        catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) { $this->assertSame(422,$e->getStatusCode()); }
        $this->assertSame('pending',$payout->fresh()->status); Http::assertNothingSent();
    }
    public function test_rejecting_twice_cannot_refund_twice(): void {
        $user=$this->owner(); $payout=$this->payout($user,['status'=>'pending']);
        $admin=new Admin(); $admin->id=config('rentals.super_admin_id'); $this->actingAs($admin,'admin');
        $request=Request::create('/','POST',['action'=>'reject']);
        app(PanelRentalController::class)->adminPayoutAction($request,$payout->id);
        try { app(PanelRentalController::class)->adminPayoutAction($request,$payout->id); $this->fail('Must reject'); }
        catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) { $this->assertSame(409,$e->getStatusCode()); }
        $this->assertEquals(110,$user->fresh()->rental_earnings);
    }
}
