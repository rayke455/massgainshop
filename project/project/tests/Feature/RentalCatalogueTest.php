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
}
