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
        $this->assertDatabaseHas('transactions', ['user_id' => $user->id, 'amount' => 2.5, 'trx_type' => '-']);
        $this->assertEquals(7.5, \App\Models\Transaction::where('user_id', $user->id)->firstOrFail()->balance);
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
    public function test_domain_only_save_preserves_branding_and_requires_fresh_verification(): void {
        $user = $this->payer(0); $this->actingAs($user);
        $panel = $this->panel(['owner_id' => $user->id, 'custom_domain' => 'old.example.test', 'domain_verified_at' => now(), 'workspace_settings' => ['announcement' => 'Keep this']]);
        $token = $panel->domain_token;
        app(PanelRentalController::class)->saveDomain(Request::create('/', 'POST', ['custom_domain' => 'shop.example.test']), $panel->id);
        $panel->refresh();
        $this->assertSame('shop.example.test', $panel->custom_domain);
        $this->assertNull($panel->domain_verified_at);
        $this->assertNotSame($token, $panel->domain_token);
        $this->assertSame('Test panel', $panel->name);
        $this->assertSame('Keep this', $panel->workspace()['announcement']);
        $token = $panel->domain_token;
        app(PanelRentalController::class)->saveDomain(Request::create('/', 'POST', ['custom_domain' => 'shop.example.test']), $panel->id);
        $this->assertSame($token, $panel->fresh()->domain_token);
        app(PanelRentalController::class)->saveDomain(Request::create('/', 'POST', ['custom_domain' => '']), $panel->id);
        $this->assertNull($panel->fresh()->custom_domain);
    }
    public function test_domain_save_cannot_change_another_owners_panel(): void {
        $panel = $this->panel(); $this->actingAs($this->payer(0));
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
        app(PanelRentalController::class)->saveDomain(Request::create('/', 'POST', ['custom_domain' => 'shop.example.test']), $panel->id);
    }
    public function test_domain_save_cannot_claim_another_panels_domain(): void {
        $user = $this->payer(0); $this->actingAs($user);
        $panel = $this->panel(['owner_id' => $user->id]);
        $this->panel(['custom_domain' => 'claimed.example.test']);
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        app(PanelRentalController::class)->saveDomain(Request::create('/', 'POST', ['custom_domain' => 'claimed.example.test']), $panel->id);
    }
    public function test_domain_save_respects_disabled_feature(): void {
        $user = $this->payer(0); $this->actingAs($user);
        $panel = $this->panel(['owner_id' => $user->id, 'features' => []]);
        $this->expectException(HttpException::class);
        app(PanelRentalController::class)->saveDomain(Request::create('/', 'POST', ['custom_domain' => 'shop.example.test']), $panel->id);
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
    public function test_control_navigation_respects_tenant_features(): void {
        $panel = $this->panel(['features' => ['markup', 'accounts']]);
        $html = view('rentals.control-navigation', ['panel' => $panel, 'customerTotal' => 0])->render();
        $this->assertStringContainsString('href="#services"', $html);
        $this->assertStringContainsString('href="#customers"', $html);
        $this->assertStringContainsString('href="#domains"', $html);
        $panel->features = [];
        $html = view('rentals.control-navigation', ['panel' => $panel, 'customerTotal' => 0])->render();
        $this->assertStringNotContainsString('href="#services"', $html);
        $this->assertStringNotContainsString('href="#customers"', $html);
    }
    public function test_customer_search_cannot_include_another_panels_customers(): void {
        $owner = $this->payer(10);
        $this->actingAs($owner);
        $panel = $this->panel(['owner_id' => $owner->id]);
        $other = $this->panel(['owner_id' => $owner->id]);
        $customer = $this->payer(0);
        $customer->forceFill(['rental_panel_id' => $panel->id])->save();
        $outsider = $this->payer(0);
        $outsider->forceFill(['rental_panel_id' => $other->id])->save();
        request()->merge(['customer_search' => '@example.test']);
        $data = app(PanelRentalController::class)->show($panel->id)->getData();
        $this->assertTrue($data['customers']->getCollection()->contains('id', $customer->id));
        $this->assertFalse($data['customers']->getCollection()->contains('id', $outsider->id));
        $this->assertSame(1, $data['customerTotal']);
    }
    public function test_panel_overview_counts_are_scoped_to_the_panel(): void {
        $owner = $this->payer(10); $this->actingAs($owner);
        $panel = $this->panel(['owner_id' => $owner->id]);
        $other = $this->panel(['owner_id' => $owner->id]);
        $customer = $this->payer(0); $customer->forceFill(['rental_panel_id' => $panel->id])->save();
        $firstOrder = \App\Models\Order::create(['user_id' => $customer->id, 'service_id' => 0, 'category_id' => 0, 'link' => 'https://example.test', 'quantity' => 10, 'price' => 1, 'status' => 'pending']);
        $firstOrder->forceFill(['rental_panel_id' => $panel->id])->save();
        $secondOrder = \App\Models\Order::create(['user_id' => $customer->id, 'service_id' => 0, 'category_id' => 0, 'link' => 'https://example.test', 'quantity' => 10, 'price' => 1, 'status' => 'pending']);
        $secondOrder->forceFill(['rental_panel_id' => $other->id])->save();
        $data = app(PanelRentalController::class)->show($panel->id)->getData();
        $this->assertSame(1, $data['orderTotal']);
        $this->assertCount(1, $data['orderStats']);
        $this->assertCount(1, $data['topServices']);
    }
    public function test_customer_order_filter_stays_inside_panel_scope(): void {
        $owner = $this->payer(10); $this->actingAs($owner);
        $panel = $this->panel(['owner_id' => $owner->id]); $other = $this->panel(['owner_id' => $owner->id]);
        $customer = $this->payer(0); $customer->forceFill(['rental_panel_id' => $panel->id])->save();
        $first = \App\Models\Order::create(['user_id' => $customer->id, 'service_id' => 0, 'category_id' => 0, 'link' => 'https://example.test', 'quantity' => 10, 'price' => 1, 'status' => 'completed']);
        $first->forceFill(['rental_panel_id' => $panel->id])->save();
        $second = \App\Models\Order::create(['user_id' => $customer->id, 'service_id' => 0, 'category_id' => 0, 'link' => 'https://example.test', 'quantity' => 10, 'price' => 1, 'status' => 'completed']);
        $second->forceFill(['rental_panel_id' => $other->id])->save();
        request()->merge(['customer_id' => $customer->id]);
        $data = app(PanelRentalController::class)->show($panel->id)->getData();
        $this->assertSame([$first->id], $data['customerOrders']->pluck('id')->all());
        request()->merge(['order_search' => 'example.test']);
        $data = app(PanelRentalController::class)->show($panel->id)->getData();
        $this->assertSame([$first->id], $data['customerOrders']->pluck('id')->all());
        request()->merge(['order_search' => (string) $second->id]);
        $data = app(PanelRentalController::class)->show($panel->id)->getData();
        $this->assertCount(0, $data['customerOrders']);
        request()->merge(['order_search' => (string) $first->id, 'order_status' => 'pending']);
        $data = app(PanelRentalController::class)->show($panel->id)->getData();
        $this->assertCount(0, $data['customerOrders']);
    }
}
