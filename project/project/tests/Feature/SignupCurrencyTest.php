<?php
namespace Tests\Feature;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\Currency;
use App\Http\Controllers\Auth\RegisterController;
class SignupCurrencyTest extends TestCase {
    use DatabaseTransactions;
    private function currency(array $data): ?string {
        $method = new \ReflectionMethod(RegisterController::class, 'signupCurrency'); $method->setAccessible(true);
        return $method->invoke(new RegisterController(), $data);
    }
    public function test_tanzania_location_selects_tanzanian_shillings(): void {
        $this->assertTrue(Currency::where('code', 'TZS')->exists());
        Currency::where('code', 'TZS')->update(['status' => 1]);
        request()->cookies->remove('currency');
        $this->assertSame('TZS', $this->currency(['signup_country' => 'TZ', 'country_code' => 'ke']));
    }
    public function test_existing_currency_choice_wins_over_location(): void {
        Currency::where('code', 'USD')->update(['status' => 1]);
        request()->cookies->set('currency', json_encode(['code' => 'USD']));
        $this->assertSame('USD', $this->currency(['signup_country' => 'TZ', 'country_code' => 'tz']));
    }
    public function test_unknown_location_falls_back_to_phone_country(): void {
        Currency::where('code', 'TZS')->update(['status' => 1]);
        request()->cookies->remove('currency');
        $this->assertSame('TZS', $this->currency(['signup_country' => 'invalid', 'country_code' => 'tz']));
    }
    public function test_new_location_overrides_old_automatic_guest_currency(): void {
        Currency::whereIn('code', ['TZS', 'KES'])->update(['status' => 1]);
        request()->cookies->set('currency', json_encode(['code' => 'KES', '_source' => 'automatic']));
        $this->assertSame('TZS', $this->currency(['signup_country' => 'TZ', 'country_code' => 'ke']));
    }
}
