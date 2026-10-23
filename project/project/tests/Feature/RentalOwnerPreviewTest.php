<?php
namespace Tests\Feature;
use Tests\TestCase;

class RentalOwnerPreviewTest extends TestCase {
    public function test_owner_control_preview_renders_without_sign_in(): void {
        $this->withoutExceptionHandling();
        $this->get('/rental-preview/admin')->assertOk()->assertSee('Tenant-owner preview')->assertSee('fieldset disabled',false)
            ->assertSee('Common settings')->assertSee('soc-common-tabs', false)->assertSee('soc-tab-general', false)
            ->assertSee('URL or order id')->assertSee('Panel navigation')->assertSee('Instagram likes · sample order');
        $this->get('/rental-preview/admin?order_status=completed')->assertOk()
            ->assertSee('Facebook followers · sample order')->assertDontSee('Instagram likes · sample order');
    }
    public function test_content_and_promotion_preview_screens_render(): void {
        $this->withoutExceptionHandling();
        $this->get('/rental-preview/admin?screen=content')->assertOk()->assertSee('Pages &amp; announcements',false);
        $this->get('/rental-preview/admin?screen=promotions')->assertOk()->assertSee('Balance promotions');
        $this->get('/rental-preview/admin?screen=support')->assertOk()->assertSee('Customer support');
    }
}
