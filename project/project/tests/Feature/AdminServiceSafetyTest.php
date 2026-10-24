<?php

namespace Tests\Feature;

use App\Http\Controllers\Admin\ServiceController;
use App\Http\Requests\Admin\Service\ServiceStoreRequest;
use App\Models\{ApiProvider, Category, Order, Service};
use App\Services\ProviderApi;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Http, Validator};
use Illuminate\Support\Str;
use Tests\TestCase;

class AdminServiceSafetyTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        Http::preventStrayRequests();
    }

    private function payload(array $extra = []): array
    {
        return array_replace(['name' => 'Audit '.Str::uuid(), 'category' => Category::firstOrFail()->id,
            'min_amount' => 10, 'max_amount' => 1000, 'price' => 20, 'manual_api' => 0,
            'refill' => 3, 'status' => 1, 'cost' => 5], $extra);
    }

    private function form(array $data): ServiceStoreRequest
    {
        $request = ServiceStoreRequest::create('/admin/service', 'POST', $data);
        $request->setContainer($this->app);
        $request->setValidator(Validator::make($data, $request->rules()));
        return $request;
    }

    private function service(): Service
    {
        $data = $this->payload();
        (new ServiceController())->store($this->form($data));
        return Service::where('service_title', $data['name'])->firstOrFail();
    }

    public function test_manual_service_can_be_created_updated_and_removed(): void
    {
        $controller = new ServiceController();
        $service = $this->service();
        $this->assertEquals(5, $service->original_price);
        $data = $this->payload(['name' => $service->service_title, 'price' => 35, 'status' => 0]);
        $controller->update($this->form($data), (string) $service->id);
        $this->assertEquals(35, $service->fresh()->price);
        $this->assertEquals(0, $service->fresh()->service_status);
        $controller->destroy((string) $service->id);
        $this->assertDatabaseMissing('services', ['id' => $service->id]);
        Http::assertNothingSent();
    }

    public function test_invalid_prices_quantities_and_missing_provider_fields_are_rejected(): void
    {
        foreach ([['price' => -1], ['price' => 0], ['min_amount' => -10], ['min_amount' => 1.5],
            ['min_amount' => 100, 'max_amount' => 10], ['manual_api' => 1]] as $invalid) {
            $this->assertTrue(Validator::make($this->payload($invalid), (new ServiceStoreRequest())->rules())->fails());
        }
    }

    private function provider(): ApiProvider
    {
        return ApiProvider::create(['api_name' => 'Audit provider', 'url' => 'https://example.test/api/v2',
            'api_key' => 'test-only', 'status' => 1, 'conversion_rate' => 2, 'currency' => 'USD']);
    }

    public function test_unknown_provider_service_neither_creates_nor_modifies_a_service(): void
    {
        $provider = $this->provider();
        $this->mock(ProviderApi::class, fn($mock) => $mock->shouldReceive('services')->twice()->andReturn([]));
        $controller = new ServiceController();
        $data = $this->payload(['manual_api' => 1, 'api_provider_id' => $provider->id, 'api_service_id' => 123]);
        $controller->store($this->form($data));
        $this->assertDatabaseMissing('services', ['service_title' => $data['name']]);
        $this->assertTrue(session()->has('error'));
        $service = $this->service();
        $controller->update($this->form($data), (string) $service->id);
        $this->assertSame($service->service_title, $service->fresh()->service_title);
        $this->assertNull($service->fresh()->api_provider_id);
    }

    public function test_valid_provider_service_is_saved_with_verified_cost(): void
    {
        $provider = $this->provider();
        $this->mock(ProviderApi::class, fn($mock) => $mock->shouldReceive('services')->once()->andReturn([
            ['service' => 123, 'rate' => 3, 'type' => 'Default'],
        ]));
        $data = $this->payload(['manual_api' => 1, 'api_provider_id' => $provider->id, 'api_service_id' => 123]);
        (new ServiceController())->store($this->form($data));
        $service = Service::where('service_title', $data['name'])->firstOrFail();
        $this->assertEquals(6, $service->original_price);
        $this->assertEquals(3, $service->api_provider_price);
        $this->assertEquals($provider->id, $service->api_provider_id);
    }

    public function test_single_and_bulk_deletion_preserve_services_with_order_history(): void
    {
        $controller = new ServiceController();
        $used = $this->service();
        $unused = $this->service();
        $order = Order::create(['service_id' => $used->id, 'category_id' => $used->category_id,
            'quantity' => 10, 'price' => 1, 'link' => 'https://example.test/audit', 'status' => 'completed']);
        $controller->destroy((string) $used->id);
        $this->assertDatabaseHas('services', ['id' => $used->id]);
        $controller->deleteMultiple(Request::create('/admin/service/delete-multiple', 'POST', ['strIds' => [$used->id, $unused->id]]));
        $this->assertDatabaseHas('services', ['id' => $used->id]);
        $this->assertDatabaseHas('orders', ['id' => $order->id, 'service_id' => $used->id]);
        $this->assertDatabaseMissing('services', ['id' => $unused->id]);
        Http::assertNothingSent();
    }

    public function test_provider_failure_does_not_leave_a_partial_service(): void
    {
        $provider = $this->provider();
        $this->mock(ProviderApi::class, fn($mock) => $mock->shouldReceive('services')->once()
            ->andThrow(new \RuntimeException('Provider unavailable')));
        $data = $this->payload(['manual_api' => 1, 'api_provider_id' => $provider->id, 'api_service_id' => 123]);
        (new ServiceController())->store($this->form($data));
        $this->assertDatabaseMissing('services', ['service_title' => $data['name']]);
        $this->assertSame('Provider unavailable', session('error'));
    }

    public function test_admin_service_list_create_and_edit_views_render(): void
    {
        $this->actingAs(\App\Models\Admin::firstOrFail(), 'admin');
        view()->share('errors', new \Illuminate\Support\ViewErrorBag());
        $controller = new ServiceController();
        $service = $this->service();
        $this->assertStringContainsString('Add Service', $controller->create()->render());
        $this->assertStringContainsString($service->service_title, $controller->edit((string) $service->id)->render());
        $this->assertStringContainsString('Manage Service', $controller->index(Request::create('/admin/service', 'GET', ['name' => $service->service_title]))->render());
    }
}
