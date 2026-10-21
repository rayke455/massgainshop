<?php
namespace Tests\Feature;

use App\Http\Controllers\RentalContentController;
use App\Http\Middleware\ResolveRentalHost;
use App\Models\{RentalContent, RentalPanel, User};
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class RentalContentTest extends TestCase
{
    use DatabaseTransactions;
    private function panel(): RentalPanel
    {
        $name = 'content_'.Str::lower(Str::random(12));
        $owner = User::create(['username' => $name, 'email' => $name.'@example.test', 'password' => 'test-only']);
        $this->actingAs($owner);
        return RentalPanel::create(['owner_id' => $owner->id, 'name' => 'Content panel', 'slug' => $name,
            'status' => 'active', 'features' => ['branding', 'accounts'], 'domain_token' => Str::random(48), 'expires_at' => now()->addDay()]);
    }
    private function save(RentalPanel $panel, array $data = [])
    {
        return app(RentalContentController::class)->store(Request::create('/', 'POST', array_replace([
            'kind' => 'page', 'title' => 'About us', 'slug' => 'about-us', 'body' => '<script>alert(1)</script>', 'published' => '1', 'position' => 0,
        ], $data)), $panel->id);
    }
    public function test_owner_publishes_escaped_page_on_own_host(): void
    {
        $panel = $this->panel(); $panel->update(['subdomain' => 'content.example.test']);
        $this->save($panel);
        auth()->logout();
        $response = (new ResolveRentalHost())->handle(Request::create('https://content.example.test/pages/about-us'), fn() => response('wrong'));
        $this->assertStringContainsString('&lt;script&gt;', $response->getContent());
        $this->assertStringNotContainsString('<script>alert', $response->getContent());
        $this->assertDatabaseHas('rental_audit', ['panel_id' => $panel->id, 'action' => 'content_saved']);
    }
    public function test_drafts_are_not_public(): void
    {
        $panel = $this->panel(); $this->save($panel, ['published' => '0']);
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
        app(RentalContentController::class)->page($panel, 'about-us');
    }
    public function test_another_panel_cannot_read_or_edit_content(): void
    {
        $first = $this->panel(); $this->save($first);
        $entry = RentalContent::where('panel_id', $first->id)->firstOrFail();
        $second = $this->panel();
        $this->assertSame(0, RentalContent::visibleFor($second, ['page'])->count());
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
        $this->save($second, ['id' => $entry->id]);
    }
    public function test_external_links_reject_script_schemes(): void
    {
        $panel = $this->panel();
        $this->expectException(ValidationException::class);
        $this->save($panel, ['kind' => 'link', 'url' => 'javascript:alert(1)']);
    }
    public function test_publishing_faq_links_and_announcements_is_scoped(): void
    {
        $panel = $this->panel();
        $this->save($panel, ['kind' => 'faq', 'slug' => 'question', 'title' => 'How does it work?']);
        $this->save($panel, ['kind' => 'link', 'slug' => 'contact', 'url' => 'https://example.test/contact']);
        $this->save($panel, ['kind' => 'notice', 'slug' => 'news', 'title' => 'Service update']);
        $this->assertStringContainsString('How does it work?', app(RentalContentController::class)->faq($panel)->getContent());
        $html = view('rentals.content-links', compact('panel'))->render();
        $this->assertStringContainsString('https://example.test/contact', $html);
        $this->assertStringContainsString('/panels/'.$panel->slug.'/faq', $html);
        $this->assertStringNotContainsString('Service update', $html);
        $this->assertCount(1, RentalContent::visibleFor($panel, ['notice'])->get());
    }
    public function test_disabled_branding_blocks_content_management(): void
    {
        $panel = $this->panel(); $panel->update(['features' => ['accounts']]);
        $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);
        $this->save($panel);
    }
}
