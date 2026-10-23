<?php
namespace Tests\Feature;

use App\Models\{Admin, RentalPanel, User};
use App\Services\AdminSupportSession;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB};
use Illuminate\Support\Str;
use Tests\TestCase;

class AdminSupportSessionTest extends TestCase
{
    use DatabaseTransactions;
    private function setupUser(bool $customer = false): User
    {
        $admin = Admin::firstOrFail();
        $admin->forceFill(['status'=>1,'two_fa_verify'=>1])->save();
        config(['support_session.super_admin_id'=>$admin->id]);
        $this->actingAs($admin, 'admin');
        $name = 'support_'.Str::lower(Str::random(10));
        $user = User::create(['username'=>$name,'email'=>$name.'@example.test','password'=>'test-only','status'=>1]);
        if ($customer) {
            $panel = RentalPanel::create(['owner_id'=>$user->id,'name'=>'Support tenant','slug'=>$name,'status'=>'active','features'=>['accounts','catalogue','branding'],'expires_at'=>now()->addDay()]);
            $user->forceFill(['rental_panel_id'=>$panel->id])->save();
        }
        return $user;
    }
    public function test_owner_support_session_is_audited_and_returns_to_admin(): void
    {
        $user = $this->setupUser(); AdminSupportSession::start($user->id);
        $this->assertSame($user->id, Auth::guard('web')->id());
        $this->assertNotNull(AdminSupportSession::current());
        $this->assertDatabaseHas('admin_support_sessions',['user_id'=>$user->id,'end_reason'=>null]);
        AdminSupportSession::end();
        $this->assertFalse(Auth::guard('web')->check());
        $this->assertTrue(Auth::guard('admin')->check());
        $this->assertDatabaseHas('admin_support_sessions',['user_id'=>$user->id,'end_reason'=>'returned']);
    }
    public function test_tenant_customer_support_context_and_banner(): void
    {
        $user = $this->setupUser(true); AdminSupportSession::start($user->id);
        $request = Request::create('/user/dashboard'); $request->setLaravelSession(session()->driver());
        $response = app(\App\Http\Middleware\AdminSupportSession::class)->handle($request, function ($request) use ($user) {
            $this->assertSame($user->rental_panel_id, $request->attributes->get('support_panel')->id);
            return response('<html><body>Customer dashboard</body></html>',200,['Content-Type'=>'text/html']);
        });
        $this->assertStringContainsString('Read-only support session', $response->getContent());
        $this->assertStringContainsString('Return to admin', $response->getContent());
    }
    public function test_expiry_revokes_user_session(): void
    {
        $user = $this->setupUser(); AdminSupportSession::start($user->id);
        DB::table('admin_support_sessions')->where('id',session('admin_support_session'))->update(['expires_at'=>now()->subMinute()]);
        $this->assertNull(AdminSupportSession::current()); $this->assertFalse(Auth::guard('web')->check());
    }
    public function test_non_superadmin_cannot_impersonate(): void
    {
        $user = $this->setupUser(); config(['support_session.super_admin_id'=>999999]);
        $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);
        AdminSupportSession::start($user->id);
    }
    public function test_support_session_blocks_financial_writes(): void
    {
        $user = $this->setupUser(); AdminSupportSession::start($user->id);
        $request = Request::create('/user/order/store','POST'); $request->setLaravelSession(session()->driver());
        $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);
        app(\App\Http\Middleware\AdminSupportSession::class)->handle($request, fn()=>response('must not run'));
    }
    public function test_legacy_mutating_get_is_blocked(): void
    {
        $user = $this->setupUser(); AdminSupportSession::start($user->id);
        $request = Request::create('/user/selected-currency'); $request->setLaravelSession(session()->driver());
        $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);
        app(\App\Http\Middleware\AdminSupportSession::class)->handle($request, fn()=>response('must not run'));
    }
    public function test_customer_support_session_can_browse_and_exit_on_main_domain(): void
    {
        $user = $this->setupUser(true);
        $user->forceFill(['email_verification'=>1,'sms_verification'=>1,'two_fa_verify'=>1])->save();
        AdminSupportSession::start($user->id);
        Auth::shouldUse('web');
        $this->get('/user/order/create')->assertOk()->assertSee('Support tenant')->assertSee('Read-only support session');
        $this->post('/support-session/stop')->assertRedirect(route('admin.dashboard'));
        $this->assertFalse(Auth::guard('web')->check());
    }
}
