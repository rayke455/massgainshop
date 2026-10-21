<?php
namespace Tests\Feature;
use App\Http\Controllers\{RentalApiController, ApiController};
use App\Models\{RentalPanel, User};
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

class RentalApiTest extends TestCase {
    use DatabaseTransactions;
    private function setupToken(): array {
        $name='api_'.Str::lower(Str::random(12));
        $owner=User::create(['username'=>$name,'email'=>$name.'@example.test','password'=>'test','status'=>1]);
        $panel=RentalPanel::create(['owner_id'=>$owner->id,'name'=>'API panel','slug'=>$name,'status'=>'active','expires_at'=>now()->addDay(),'features'=>['accounts'],'domain_token'=>Str::random(48)]);
        $user=User::create(['username'=>$name.'_c','email'=>$name.'_c@example.test','password'=>'test','status'=>1,'balance'=>50,'rental_panel_id'=>$panel->id]);
        $secret=bin2hex(random_bytes(32));
        DB::table('rental_api_tokens')->insert(['user_id'=>$user->id,'panel_id'=>$panel->id,'token_hash'=>hash('sha256',$secret),'expires_at'=>now()->addDay(),'created_at'=>now(),'updated_at'=>now()]);
        $this->app->instance('rental.panel',$panel);
        return [$panel,$user,$secret];
    }
    private function callApi(string $secret, array $data=['action'=>'balance']) {
        $request=Request::create('/api/panel/v1','POST',$data,[],[],['HTTP_AUTHORIZATION'=>'Bearer '.$secret]);
        return app(RentalApiController::class)->dispatch($request);
    }
    public function test_hashed_key_reads_only_customer_balance(): void {
        [$panel,$user,$secret]=$this->setupToken();
        $this->assertEquals(50,$this->callApi($secret)->getData(true)['balance']);
        $this->assertDatabaseMissing('rental_api_tokens',['token_hash'=>$secret]);
    }
    public function test_cross_panel_token_is_rejected(): void {
        [$panel,$user,$secret]=$this->setupToken();
        DB::table('rental_api_tokens')->where('user_id',$user->id)->update(['panel_id'=>$panel->id+1]);
        $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);
        $this->callApi($secret);
    }
    public function test_expired_key_is_rejected(): void {
        [$panel,$user,$secret]=$this->setupToken();
        DB::table('rental_api_tokens')->where('user_id',$user->id)->update(['expires_at'=>now()->subMinute()]);
        $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);
        $this->callApi($secret);
    }
    public function test_suspended_customer_cannot_use_api(): void {
        [$panel,$user,$secret]=$this->setupToken(); $user->update(['status'=>0]);
        $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);
        $this->callApi($secret);
    }
    public function test_revoke_invalidates_key(): void {
        [$panel,$user,$secret]=$this->setupToken(); $this->actingAs($user);
        app(RentalApiController::class)->rotate(Request::create('/','POST',['operation'=>'revoke']));
        $this->assertDatabaseMissing('rental_api_tokens',['user_id'=>$user->id]);
        $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);
        $this->callApi($secret);
    }
    public function test_legacy_endpoint_rejects_tenant_token(): void {
        [$panel,$user,$secret]=$this->setupToken(); $user->forceFill(['api_token'=>$secret])->save();
        $response=app(ApiController::class)->apiV2(Request::create('/api/v2','POST',['key'=>$secret,'action'=>'balance']));
        $this->assertSame(422,$response->status());
    }
    public function test_write_actions_are_rejected(): void {
        [$panel,$user,$secret]=$this->setupToken();
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $this->callApi($secret,['action'=>'add']);
    }
}
