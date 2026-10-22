<?php
namespace Tests\Feature;

use App\Http\Controllers\RentalLandingController;
use App\Models\{RentalPanel, User};
use App\Services\RentalLanding;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Tests\TestCase;

class RentalLandingEditorTest extends TestCase
{
    use DatabaseTransactions;

    private function panel(): RentalPanel
    {
        $name = 'landing_'.Str::lower(Str::random(10));
        $owner = User::create(['username' => $name, 'email' => $name.'@example.test', 'password' => 'test-only']);
        $this->actingAs($owner);
        return RentalPanel::create(['owner_id' => $owner->id, 'name' => 'Test landing', 'slug' => $name,
            'status' => 'active', 'features' => ['branding','accounts','orders'], 'domain_token' => Str::random(48), 'expires_at' => now()->addDay()]);
    }

    private function save(RentalPanel $panel, string $action, string $html = '<h1>My design</h1>')
    {
        return app(RentalLandingController::class)->store(Request::create('/', 'POST', ['action' => $action, 'html' => $html, 'css' => 'body{color:blue}']), $panel->id);
    }

    public function test_draft_publish_edit_and_restore_keep_independent_versions(): void
    {
        $panel = $this->panel();
        $this->save($panel, 'save');
        $this->assertArrayNotHasKey('landing_published', $panel->fresh()->workspace());
        $this->save($panel, 'publish');
        $this->save($panel, 'save', '<h1>New draft</h1>');
        $this->assertSame('<h1>My design</h1>', $panel->fresh()->workspace()['landing_published']['html']);
        $this->save($panel, 'restore');
        $this->assertArrayNotHasKey('landing_published', $panel->fresh()->workspace());
        $this->assertSame('<h1>New draft</h1>', $panel->fresh()->workspace()['landing_draft']['html']);
    }

    public function test_preview_is_sandboxed_and_does_not_save(): void
    {
        $panel = $this->panel();
        $response = $this->save($panel, 'preview');
        $this->assertStringContainsString('sandbox;', $response->headers->get('Content-Security-Policy'));
        $this->assertArrayNotHasKey('landing_draft', $panel->fresh()->workspace());
    }

    public function test_active_content_and_navigation_are_removed(): void
    {
        $html = RentalLanding::document(['html' => '<script>alert(1)</script><form><input></form><iframe src="https://evil.test"></iframe><meta http-equiv="refresh" content="0;url=https://evil.test"><h1 onclick="alert(1)">Hello</h1><a href="javascript:alert(1)">Link</a><img src="javascript:alert(1)" onerror="alert(1)">', 'css' => '</style><script>alert(2)</script>']);
        foreach (['<script', '<form', '<iframe', 'onclick=', 'onerror=', 'href=', 'javascript:', 'http-equiv="refresh"'] as $unsafe) $this->assertStringNotContainsString($unsafe, $html);
        $this->assertStringContainsString('<h1>Hello</h1>', $html);
        $this->assertStringContainsString("default-src 'none'", $html);
    }

    public function test_other_owner_cannot_edit(): void
    {
        $panel = $this->panel(); $this->panel();
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
        $this->save($panel, 'publish');
    }

    public function test_branding_disabled_blocks_editor(): void
    {
        $panel = $this->panel(); $panel->update(['features' => []]);
        $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);
        $this->save($panel, 'publish');
    }

    public function test_public_editor_preview_renders(): void
    {
        $this->get('/rental-preview/admin?screen=landing')->assertOk()->assertSee('Landing page designer')->assertSee('sandbox=""', false);
    }

    public function test_published_template_is_landing_only_and_admin_can_disable_it(): void
    {
        $panel = $this->panel();
        $this->save($panel, 'publish', '<h1>Unique published heading</h1>');
        $url = '/rental-preview?panel='.$panel->id;
        $this->get($url)->assertOk()->assertSee('Unique published heading')
            ->assertSee('sandbox=""', false)->assertSee('Sign in')->assertSee('Sign up')->assertSee('Order as guest');
        $this->get($url.'&shop=1')->assertOk()->assertDontSee('Unique published heading');
        $panel->update(['features' => ['accounts','orders']]);
        $this->get($url)->assertOk()->assertDontSee('Unique published heading')->assertSee('Your audience. Your next chapter.');
    }
}
