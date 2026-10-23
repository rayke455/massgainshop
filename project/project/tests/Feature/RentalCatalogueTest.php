<?php
namespace Tests\Feature;

use App\Http\Controllers\RentalCatalogueController;
use App\Models\{Category, RentalPanel, Service, SocialMedia, User};
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

class RentalCatalogueTest extends TestCase
{
    use DatabaseTransactions;

    private function fixture(): array
    {
        $name = Str::lower(Str::random(15));
        $user = User::create(['username' => $name, 'email' => $name.'@example.test', 'password' => 'test-only', 'balance' => 0]);
        $panel = RentalPanel::create(['owner_id' => $user->id, 'slug' => $name, 'name' => 'Test tenant', 'status' => 'active', 'expires_at' => now()->addDay(), 'features' => ['accounts','catalogue','orders','branding'], 'markup' => 20]);
        $user->forceFill(['rental_panel_id' => $panel->id])->save();
        $this->actingAs($user); $this->app->instance('rental.panel', $panel);
        $network = SocialMedia::create(['name' => 'Instagram', 'status' => 1, 'sort_by' => 1]);
        $category = Category::create(['social_media_id' => $network->id, 'category_title' => 'Test Likes', 'status' => 1, 'sort_by' => 1]);
        $service = Service::create(['category_id' => $category->id, 'service_title' => 'Test real service', 'service_status' => 1, 'service_type' => 'Default', 'min_amount' => 10, 'max_amount' => 10000, 'price' => 10, 'original_price' => 5]);
        return [$user, $panel, $network, $category, $service];
    }

    private function renderFor(User $user, array $query = [])
    {
        $request = Request::create('/user/order/create', 'GET', $query);
        $request->setUserResolver(fn() => $user);
        return app(RentalCatalogueController::class)->index($request);
    }

    public function test_real_catalogue_and_checkout_render_without_supplier_details(): void
    {
        [$user, $panel, $network, $category, $service] = $this->fixture();
        $html = $this->renderFor($user)->render();
        $this->assertStringContainsString('Select platform', $html);
        $this->assertStringContainsString('network='.$network->id, $html);
        $html = $this->renderFor($user, ['serviceId' => $service->id])->render();
        $this->assertStringContainsString('Test real service', $html);
        $this->assertStringContainsString(route('user.order.store'), $html);
        $this->assertStringContainsString('name="quantity"', $html);
        $this->assertStringNotContainsString('api_provider_price', $html);
        $this->assertStringNotContainsString('disabled in preview', $html);
    }

    public function test_guest_ordering_is_staged_and_selected_service_uses_real_quote(): void
    {
        [$user, $panel, $network, $category, $service] = $this->fixture();
        auth()->logout();
        $this->app->forgetInstance('rental.panel');
        auth()->logout();
        $this->get('/rental-preview?shop=1')->assertOk()->assertSee('Select network')->assertDontSee('id="orderForm"', false);
        $this->get('/rental-preview?shop=1&network=Instagram')->assertOk()->assertSee('Test Likes')->assertDontSee('id="orderForm"', false);
        $this->get('/rental-preview?shop=1&network=Instagram&category='.$category->id)->assertOk()->assertSee('Test real service')->assertDontSee('id="orderForm"', false);
        $this->get('/rental-preview?shop=1&service='.$service->id)->assertOk()->assertSee('id="orderForm"', false)->assertSee('Choose own')->assertSee('Email for your receipt')->assertSee('Ordering is disabled in preview');
        $service->update(['service_status' => 0]);
        $this->get('/rental-preview?shop=1&service='.$service->id)->assertNotFound();
    }

    public function test_hidden_service_cannot_be_opened_by_direct_id(): void
    {
        [$user, $panel, , , $service] = $this->fixture();
        DB::table('rental_panel_prices')->insert(['panel_id' => $panel->id, 'service_id' => $service->id, 'hidden' => 1]);
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
        $this->renderFor($user, ['serviceId' => $service->id]);
    }

    public function test_customer_of_another_panel_is_rejected(): void
    {
        [$user] = $this->fixture();
        $user->rental_panel_id = null;
        $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);
        $this->renderFor($user);
    }

    public function test_disabled_service_cannot_be_opened(): void
    {
        [$user, , , , $service] = $this->fixture();
        $service->update(['service_status' => 0]);
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
        $this->renderFor($user, ['serviceId' => $service->id]);
    }

    public function test_history_search_cannot_read_another_customer_or_panel(): void
    {
        [$user, $panel, , $category, $service] = $this->fixture();
        $base = ['user_id' => $user->id, 'category_id' => $category->id, 'service_id' => $service->id,
            'link' => 'https://example.test/matching', 'quantity' => 100, 'price' => 1, 'status' => 'completed'];
        $own = \App\Models\Order::create($base);
        $own->forceFill(['rental_panel_id' => $panel->id])->save();
        $foreign = \App\Models\Order::create($base);
        $foreign->forceFill(['rental_panel_id' => null])->save();
        $request = Request::create('/user/orders/completed', 'GET', ['q' => 'matching']);
        $view = app(\App\Http\Controllers\User\OrderController::class)->index($request, 'completed');
        $this->assertSame([$own->id], $view->getData()['orders']->pluck('id')->all());
        $this->assertSame('completed', $view->getData()['activeStatus']);
        $this->assertStringContainsString('URL or order ID', $view->render());
        $request = Request::create('/user/orders', 'GET', ['q' => (string) $foreign->id]);
        $view = app(\App\Http\Controllers\User\OrderController::class)->index($request);
        $this->assertCount(0, $view->getData()['orders']);
        $own->forceFill(['user_id' => $user->id + 1000000])->save();
        $view = app(\App\Http\Controllers\User\OrderController::class)->index(Request::create('/user/orders'));
        $this->assertCount(0, $view->getData()['orders']);
    }

    private function orderRequest(array $data)
    {
        $request = \Mockery::mock(\App\Http\Requests\User\OrderStoreRequest::class)->makePartial();
        $request->initialize([], $data, [], [], [], ['REQUEST_METHOD' => 'POST']);
        $request->shouldReceive('validated')->once()->andReturn($data);
        return $request;
    }

    public function test_selected_imports_control_both_customer_and_guest_catalogues(): void
    {
        [$user, $panel, , $category, $service] = $this->fixture();
        $other = $service->replicate(); $other->service_title = 'Not imported'; $other->save();
        $controller = app(\App\Http\Controllers\RentalServiceImportController::class);
        $controller->store(Request::create('/', 'POST', ['mode'=>'selected','services'=>[$service->id]]), $panel->id);
        $this->app->instance('rental.panel', $panel->fresh());
        $this->assertTrue(\App\Services\PanelRentals::serviceImported($panel->fresh(), $service->id));
        $this->assertFalse(\App\Services\PanelRentals::serviceImported($panel->fresh(), $other->id));
        $this->assertSame([$service->id], \App\Services\PanelRentals::catalogue($panel->fresh())->pluck('id')->all());
        $this->assertContains($other->id, \App\Services\PanelRentals::hiddenIds($user));
        $html = $controller->index(Request::create('/'), $panel->id)->render();
        $this->assertStringContainsString('Import selected', $html);
        $controller->store(Request::create('/', 'POST', ['mode'=>'selected','services'=>[$other->id]]), $panel->id);
        $this->assertCount(2, $panel->fresh()->workspace()['imported_service_ids']);
        $this->assertEquals(0, $user->fresh()->balance);
    }

    public function test_import_cannot_change_another_owners_panel(): void
    {
        [$user, $panel, , , $service] = $this->fixture();
        $panel->owner_id = $user->id + 100000; $panel->save();
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
        app(\App\Http\Controllers\RentalServiceImportController::class)->store(Request::create('/', 'POST', ['mode'=>'selected','services'=>[$service->id]]), $panel->id);
    }

    public function test_insufficient_balance_does_not_create_order_or_ledger_entry(): void
    {
        [$user, , , $category, $service] = $this->fixture();
        $request = $this->orderRequest(['service' => $service->id, 'category' => $category->id,
            'quantity' => 100, 'order_type' => 'Default', 'link' => 'https://example.test/order', 'check' => 1]);
        $response = app(\App\Http\Controllers\User\OrderController::class)->store($request);
        $this->assertSame('insufficient_balance', json_decode($response->getContent(), true)['code']);
        $this->assertEquals(0, $user->fresh()->balance);
        $this->assertDatabaseMissing('orders', ['user_id' => $user->id]);
        $this->assertDatabaseMissing('transactions', ['user_id' => $user->id]);
    }

    public function test_tampered_category_is_rejected_before_charging(): void
    {
        [$user, , , $category, $service] = $this->fixture();
        $request = $this->orderRequest(['service' => $service->id, 'category' => $category->id + 1,
            'quantity' => 100, 'order_type' => 'Default', 'link' => 'https://example.test/order', 'check' => 1]);
        $response = app(\App\Http\Controllers\User\OrderController::class)->store($request);
        $this->assertSame('error', json_decode($response->getContent(), true)['status']);
        $this->assertDatabaseMissing('orders', ['user_id' => $user->id]);
    }
}
