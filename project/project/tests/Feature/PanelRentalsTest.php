<?php
namespace Tests\Feature;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\{RentalPanel, RentalRequest, User};
use App\Services\PanelRentals;
use App\Http\Controllers\{PanelRentalController, RentalStorefrontController};
use App\Http\Middleware\ResolveRentalHost;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
class PanelRentalsTest extends TestCase {
    use DatabaseTransactions;
    private function payer(float $balance): User {
        $key = strtolower(\Illuminate\Support\Str::random(16));
        return User::create(['username' => $key, 'email' => $key.'@example.test', 'password' => 'test-only-password', 'balance' => $balance]);
    }
    public function test_rental_charge_records_exact_balance_and_ledger(): void {
        $user = $this->payer(10);
        \Illuminate\Support\Facades\DB::transaction(fn() => PanelRentals::charge($user, ['price' => 2.5], 'Test rental'));
        $this->assertEquals(7.5, $user->fresh()->balance);
        $this->assertDatabaseHas('transactions', ['user_id' => $user->id, 'amount' => 2.5, 'trx_type' => '-', 'balance' => '7.50']);
    }
    public function test_insufficient_funds_leave_balance_unchanged(): void {
        $user = $this->payer(1);
        try {
            \Illuminate\Support\Facades\DB::transaction(fn() => PanelRentals::charge($user, ['price' => 2.5], 'Test rental'));
            $this->fail('Insufficient funds must be rejected.');
        } catch (\Illuminate\Validation\ValidationException $exception) {
            $this->assertEquals(1, $user->fresh()->balance);
            $this->assertDatabaseMissing('transactions', ['user_id' => $user->id]);
        }
    }
    public function test_repeated_renewal_token_cannot_charge_twice(): void {
        $user = $this->payer(10); $this->actingAs($user);
        $panel = $this->panel(['owner_id' => $user->id]);
        \Illuminate\Support\Facades\DB::table('rental_settings')->updateOrInsert(['id' => 1], ['settings' => json_encode(['enabled' => true, 'price' => 2.5])]);
        session(['rental_renew_'.$panel->id => 'test-renewal']);
        $request = Request::create('/', 'POST', ['price' => 2.5, 'renewal_token' => 'test-renewal']);
        app(PanelRentalController::class)->renew($request, $panel->id);
        app(PanelRentalController::class)->renew($request, $panel->id);
        $this->assertEquals(7.5, $user->fresh()->balance);
        $this->assertSame(1, \App\Models\Transaction::where('user_id', $user->id)->count());
    }
    private function panel(array $override = []): RentalPanel {
        return RentalPanel::create(array_replace(['owner_id' => 999999999, 'slug' => 'test-'.strtolower(\Illuminate\Support\Str::random(10)), 'name' => 'Test panel', 'domain_token' => str_repeat('a',48), 'status' => 'active', 'features' => ['catalogue','orders','branding','custom_domain'], 'expires_at' => now()->addDays(30)], $override));
    }
    public function test_expiry_and_suspension_disable_all_features(): void {
        $panel = $this->panel(); $this->assertTrue($panel->allows('orders'));
        $panel->status = 'suspended'; $this->assertFalse($panel->allows('orders'));
        $panel->status = 'active'; $panel->expires_at = now()->subMinute(); $this->assertFalse($panel->available());
    }
    public function test_owner_cannot_access_another_panel(): void {
        $panel = $this->panel(); $user = new User(); $user->id = 999999998; $this->actingAs($user);
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
        app(PanelRentalController::class)->show($panel->id);
    }
    public function test_disabled_orders_reject_requests(): void {
        $panel = $this->panel(['features' => ['catalogue']]);
        $this->expectException(HttpException::class);
        app(RentalStorefrontController::class)->order(Request::create('/', 'POST'), $panel->slug);
    }
    public function test_custom_domain_requires_ownership_verification(): void {
        $panel = $this->panel(['custom_domain' => 'not-verified.example.test']);
        $this->expectException(HttpException::class);
        (new ResolveRentalHost())->handle(Request::create('https://'.$panel->custom_domain.'/'), fn() => response('main'));
    }
    public function test_verified_host_cannot_reach_main_admin(): void {
        $panel = $this->panel(['custom_domain' => 'verified.example.test', 'domain_verified_at' => now()]);
        $this->expectException(HttpException::class);
        (new ResolveRentalHost())->handle(Request::create('https://'.$panel->custom_domain.'/admin/dashboard'), fn() => response('main admin'));
    }
    public function test_tracking_token_is_scoped_to_panel(): void {
        $first = $this->panel(); $second = $this->panel();
        $item = RentalRequest::create(['panel_id' => $first->id, 'token' => (string)\Illuminate\Support\Str::uuid(), 'name' => 'Example', 'email' => 'example@example.test', 'service_id' => 0, 'quantity' => 100, 'link' => 'https://example.test', 'quote' => 1, 'currency' => 'USD']);
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
        app(RentalStorefrontController::class)->track($second->slug, $item->token);
    }
    public function test_tenant_is_not_super_admin(): void {
        $user = new User(); $user->id = 999999999; $this->actingAs($user);
        $this->assertFalse(PanelRentals::isSuperAdmin());
        $this->expectException(HttpException::class); app(PanelRentalController::class)->admin();
    }
    public function test_domains_reject_urls_ips_and_paths(): void {
        $this->assertTrue(PanelRentals::validHost('panel.example.com'));
        foreach (['https://example.com', '127.0.0.1', 'example.com/path', '*.example.com', 'localhost'] as $host) $this->assertFalse(PanelRentals::validHost($host));
    }
    public function test_active_storefront_renders_without_customer_data(): void {
        $panel = $this->panel();
        $this->get(config('app.url').'/panels/'.$panel->slug)->assertOk()->assertSee('Test panel')->assertDontSee('example@example.test');
    }
    public function test_rental_screen_is_available_to_signed_in_user(): void {
        $user = new User(); $user->forceFill(['id' => 999999999, 'username' => 'preview_customer', 'balance' => 0]);
        $this->actingAs($user);
        $view = app(PanelRentalController::class)->index();
        $this->assertSame('rentals.manage', $view->name());
    }
}
