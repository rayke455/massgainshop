<?php

namespace Tests\Feature;

use App\Http\Controllers\PanelRentalController;
use App\Http\Controllers\RentalSupportController;
use App\Http\Middleware\ResolveRentalHost;
use App\Models\RentalPanel;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class RentalWorkspaceTest extends TestCase
{
    use DatabaseTransactions;

    private function user(): User
    {
        $name = 'test_'.Str::lower(Str::random(12));
        return User::create(['username' => $name, 'email' => $name.'@example.test', 'password' => 'test-only-password']);
    }

    private function panel(User $owner): RentalPanel
    {
        return RentalPanel::create([
            'owner_id' => $owner->id, 'slug' => Str::lower(Str::random(12)),
            'name' => 'Example panel', 'domain_token' => Str::random(48), 'status' => 'active',
            'features' => ['accounts', 'branding'], 'expires_at' => now()->addDays(30),
        ]);
    }

    public function test_customer_support_conversation_can_be_answered_by_its_owner(): void
    {
        $owner = $this->user();
        $panel = $this->panel($owner);
        $customer = $this->user();
        $customer->forceFill(['rental_panel_id' => $panel->id])->save();
        $this->app->instance('rental.panel', $panel);
        $this->actingAs($customer);
        $controller = app(RentalSupportController::class);
        $controller->store(Request::create('/', 'POST', ['subject' => 'Order question', 'body' => 'Please check my order.']));
        $ticket = DB::table('rental_tickets')->where('panel_id', $panel->id)->first();
        $this->assertSame($customer->id, (int) $ticket->customer_id);
        $this->actingAs($owner);
        $controller->store(Request::create('/', 'POST', ['ticket' => $ticket->id, 'body' => 'We are checking.']), $panel->id);
        $this->assertDatabaseHas('rental_tickets', ['id' => $ticket->id, 'status' => 'answered']);
        $this->assertSame(2, DB::table('rental_ticket_messages')->where('ticket_id', $ticket->id)->count());
        $data = $controller->index(Request::create('/?ticket='.$ticket->id), $panel->id)->getData();
        $this->assertCount(2, $data['messages']);
        $html = $controller->index(Request::create('/?ticket='.$ticket->id), $panel->id)->render();
        $this->assertStringContainsString('We are checking.', $html);
    }

    public function test_customer_cannot_read_another_customers_conversation(): void
    {
        $panel = $this->panel($this->user());
        $customer = $this->user();
        $customer->forceFill(['rental_panel_id' => $panel->id])->save();
        $id = DB::table('rental_tickets')->insertGetId([
            'panel_id' => $panel->id, 'customer_id' => $this->user()->id,
            'subject' => 'Private', 'status' => 'open', 'created_at' => now(), 'updated_at' => now(),
        ]);
        $this->app->instance('rental.panel', $panel);
        $this->actingAs($customer);
        $this->expectException(HttpException::class);
        app(RentalSupportController::class)->index(Request::create('/?ticket='.$id));
    }

    public function test_owner_cannot_reply_to_a_ticket_on_another_panel(): void
    {
        $owner = $this->user();
        $panel = $this->panel($owner);
        $other = $this->panel($owner);
        $id = DB::table('rental_tickets')->insertGetId([
            'panel_id' => $other->id, 'customer_id' => $this->user()->id,
            'subject' => 'Private', 'status' => 'open', 'created_at' => now(), 'updated_at' => now(),
        ]);
        $this->actingAs($owner);
        $this->expectException(HttpException::class);
        app(RentalSupportController::class)->store(Request::create('/', 'POST', ['ticket' => $id, 'body' => 'Wrong panel']), $panel->id);
    }

    public function test_closed_registration_rejects_direct_post(): void
    {
        $panel = $this->panel($this->user());
        $panel->update(['subdomain' => 'registration.example.test', 'workspace_settings' => ['registration' => false]]);
        $this->expectException(HttpException::class);
        (new ResolveRentalHost())->handle(Request::create('https://registration.example.test/register', 'POST'), fn() => response('unexpected'));
    }

    public function test_preview_uses_customer_actions_without_owner_controls(): void
    {
        $response = app(PanelRentalController::class)->customerPreview(Request::create('/'));
        $html = $response->getContent();
        $this->assertStringContainsString('screen=services', $html);
        $this->assertStringContainsString('screen=support', $html);
        $this->assertStringNotContainsString('Top providers', $html);
        $this->assertStringNotContainsString('>Users<', $html);
        $this->assertStringNotContainsString('href="#"', $html);
    }

    public function test_workspace_settings_persist_without_replacing_platform_feature_permissions(): void
    {
        $owner = $this->user(); $panel = $this->panel($owner);
        $this->actingAs($owner);
        app(PanelRentalController::class)->update(Request::create('/', 'POST', [
            'name' => 'Updated panel', 'markup' => 20,
            'workspace' => ['registration' => '0', 'show_spent' => '1', 'show_orders' => '0', 'announcement' => 'Welcome',
                'registration_fields' => ['field_1' => ['label' => 'Business name', 'required' => '1']]],
        ]), $panel->id);
        $this->assertFalse($panel->fresh()->workspace()['registration']);
        $this->assertSame('Welcome', $panel->fresh()->workspace()['announcement']);
        $this->assertSame('Business name', $panel->fresh()->workspace()['registration_fields']['field_1']['label']);
        $this->assertFalse($panel->fresh()->allows('custom_domain'));
    }

    public function test_owner_can_suspend_only_their_panels_customer(): void
    {
        $owner = $this->user(); $panel = $this->panel($owner);
        $customer = $this->user();
        $customer->forceFill(['rental_panel_id' => $panel->id, 'status' => 1])->save();
        $this->actingAs($owner);
        app(PanelRentalController::class)->customerStatus(Request::create('/', 'POST', ['status' => 0]), $panel->id, $customer->id);
        $this->assertEquals(0, $customer->fresh()->status);
        $this->assertDatabaseHas('rental_audit', ['panel_id' => $panel->id, 'action' => 'customer_status_updated']);
        $outsider = $this->user();
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
        app(PanelRentalController::class)->customerStatus(Request::create('/', 'POST', ['status' => 0]), $panel->id, $outsider->id);
    }

    public function test_suspended_customer_cannot_use_tenant_routes_even_with_social_login(): void
    {
        $panel = $this->panel($this->user());
        $panel->update(['subdomain' => 'suspended.example.test']);
        $customer = $this->user();
        $customer->forceFill(['rental_panel_id' => $panel->id, 'status' => 0, 'google_id' => 'test-social'])->save();
        $this->actingAs($customer);
        $this->expectException(HttpException::class);
        (new ResolveRentalHost())->handle(Request::create('https://suspended.example.test/user/dashboard'), fn() => response('unexpected'));
    }

    public function test_public_pages_escape_content_and_are_scoped_to_the_host(): void
    {
        $panel = $this->panel($this->user());
        $panel->update(['subdomain' => 'policies.example.test', 'workspace_settings' => ['terms' => '<script>alert(1)</script>Our terms']]);
        $response = (new ResolveRentalHost())->handle(Request::create('https://policies.example.test/terms'), fn() => response('unexpected'));
        $this->assertStringContainsString('&lt;script&gt;', $response->getContent());
        $this->assertStringNotContainsString('<script>alert', $response->getContent());
    }

    public function test_live_workspace_uses_selected_currency_and_tenant_support(): void
    {
        $owner = $this->user(); $panel = $this->panel($owner);
        $customer = $this->user(); $customer->forceFill(['rental_panel_id' => $panel->id])->save();
        $this->actingAs($customer);
        $currency = new \App\Models\Currency(['code' => 'TZS', 'conversion_rate' => 20]);
        $html = view('rentals.customer-workspace', [
            'panel' => $panel, 'currency' => $currency, 'walletBalance' => 100,
            'orders' => collect(['total' => 0, 'active' => 0, 'spent' => 0]), 'recentOrders' => collect(),
        ])->render();
        $this->assertStringContainsString('2,000.00 TZS', $html);
        $this->assertStringContainsString(route('rental.support'), $html);
        $this->assertStringContainsString('Account settings', $html);
        $this->assertStringNotContainsString('Top providers', $html);
    }
}
