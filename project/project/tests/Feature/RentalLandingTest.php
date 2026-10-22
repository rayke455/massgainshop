<?php
namespace Tests\Feature;

use Tests\TestCase;

class RentalLandingTest extends TestCase
{
    public function test_landing_has_guest_link_without_catalogue_or_checkout(): void
    {
        $this->get('/rental-preview')->assertOk()->assertSee('Order as guest')
            ->assertSee('Sign in')->assertSee('Sign up')
            ->assertSee('account=login', false)->assertSee('account=register', false)
            ->assertSee('shop=1', false)->assertDontSee('id="list"', false)
            ->assertDontSee('id="orderForm"', false);
    }

    public function test_preview_account_links_do_not_use_main_shop_accounts(): void
    {
        $this->get('/rental-preview?account=login')->assertOk()
            ->assertSee('Welcome back')->assertSee('Account preview only.')
            ->assertDontSee('id="list"', false);
        $this->get('/rental-preview?account=register')->assertOk()
            ->assertSee('Create your account')->assertSee('fieldset disabled', false);
    }

    public function test_shopping_view_has_catalogue_checkout_and_home_link(): void
    {
        $this->get('/rental-preview?shop=1')->assertOk()->assertSee('Select network')
            ->assertSee('Back to home')->assertSee('id="list"', false)
            ->assertDontSee('id="orderForm"', false)->assertDontSee('class="hero-layout"', false);
    }
}
