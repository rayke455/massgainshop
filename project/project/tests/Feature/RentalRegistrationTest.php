<?php

namespace Tests\Feature;

use App\Models\RentalPanel;
use App\Models\User;
use App\Services\RentalRegistration;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class RentalRegistrationTest extends TestCase
{
    use DatabaseTransactions;

    private function panel(): RentalPanel
    {
        return new RentalPanel(['name' => 'Registration test', 'status' => 'active', 'features' => ['accounts'], 'expires_at' => now()->addDay(),
            'workspace_settings' => ['registration_fields' => [
                'field_1' => ['label' => 'Business name', 'required' => true],
                'field_2' => ['label' => 'Preferred contact time', 'required' => false],
                'field_3' => ['label' => '', 'required' => true],
            ]]]);
    }

    public function test_required_fields_and_unexpected_answers_are_validated(): void
    {
        $rules = RentalRegistration::rules($this->panel());
        $this->assertTrue(Validator::make([], $rules)->fails());
        $this->assertFalse(Validator::make(['rental_fields' => ['field_1' => 'Example shop']], $rules)->fails());
        $this->assertTrue(Validator::make(['rental_fields' => ['field_1' => ['array']]], $rules)->fails());
        $this->assertTrue(Validator::make(['rental_fields' => ['field_1' => 'Shop', 'field_5' => 'Not configured']], $rules)->fails());
        $this->assertTrue(Validator::make(['rental_fields' => ['field_1' => str_repeat('a', 201)]], $rules)->fails());
    }

    public function test_disabled_accounts_do_not_offer_fields(): void
    {
        $panel = $this->panel(); $panel->features = [];
        $this->assertSame([], RentalRegistration::fields($panel));
        $this->assertSame([], RentalRegistration::fields(null));
        $this->assertSame([], RentalRegistration::answers(null, ['rental_fields' => ['field_1' => 'Ignored on main site']]));
    }

    public function test_answers_keep_labels_and_are_hidden_from_user_serialization(): void
    {
        $panel = $this->panel();
        $answers = RentalRegistration::answers($panel, ['rental_fields' => ['field_1' => 'Example shop']]);
        $panel->workspace_settings = ['registration_fields' => ['field_1' => ['label' => 'New question', 'required' => true]]];
        $this->assertSame('Business name', $answers['field_1']['label']);
        $user = new User(['rental_profile' => $answers]);
        $this->assertSame('Example shop', $user->rental_profile['field_1']['value']);
        $this->assertArrayNotHasKey('rental_profile', $user->attributesToArray());
    }

    public function test_form_escapes_custom_labels(): void
    {
        $panel = $this->panel();
        $panel->workspace_settings = ['registration_fields' => ['field_1' => ['label' => '<script>alert(1)</script>', 'required' => true]]];
        $this->app->instance('rental.panel', $panel);
        $html = view('rentals.registration-fields', [
            'errors' => new \Illuminate\Support\ViewErrorBag(),
        ])->render();
        $this->assertStringContainsString('&lt;script&gt;', $html);
        $this->assertStringNotContainsString('<script>alert', $html);
        $this->assertStringContainsString('name="rental_fields[field_1]"', $html);
    }

    public function test_signup_persists_answers_on_the_correct_panel_customer(): void
    {
        $name = 'signup_'.\Illuminate\Support\Str::lower(\Illuminate\Support\Str::random(12));
        $owner = User::create(['username' => $name, 'email' => $name.'@example.test', 'password' => 'test-only']);
        $panel = $this->panel();
        $panel->forceFill(['owner_id' => $owner->id, 'slug' => $name, 'domain_token' => \Illuminate\Support\Str::random(48)])->save();
        $this->app->instance('rental.panel', $panel);
        $method = new \ReflectionMethod(\App\Http\Controllers\Auth\RegisterController::class, 'create');
        $method->setAccessible(true);
        $customer = $method->invoke(new \App\Http\Controllers\Auth\RegisterController(), [
            'username' => $name.'_client', 'email' => $name.'_client@example.test',
            'phone' => '+254712345678', 'password' => 'test-only-password',
            'rental_fields' => ['field_1' => 'Example shop'],
        ]);
        $this->assertEquals($panel->id, $customer->fresh()->rental_panel_id);
        $this->assertSame('Example shop', $customer->fresh()->rental_profile['field_1']['value']);
        $this->assertEmpty($owner->fresh()->rental_profile);
    }
}
