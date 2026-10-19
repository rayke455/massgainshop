<?php

namespace Tests\Unit;

use App\Http\Controllers\Auth\RegisterController;
use Tests\TestCase;

class SignupPhoneTest extends TestCase
{
    private function normalize(array $data): array
    {
        $controller = (new \ReflectionClass(RegisterController::class))->newInstanceWithoutConstructor();
        $method = new \ReflectionMethod($controller, 'normalizePhone');
        $method->setAccessible(true);
        return $method->invoke($controller, $data);
    }

    public function test_international_number_is_split_for_existing_storage(): void
    {
        $phone = $this->normalize(['phone' => '+254 712 345 678']);
        $this->assertSame('+254', $phone['phone_code']);
        $this->assertSame('712345678', $phone['phone']);
        $this->assertSame('ke', $phone['country_code']);
    }

    public function test_shared_calling_code_does_not_guess_country(): void
    {
        $phone = $this->normalize(['phone' => '+1 (202) 555-0123']);
        $this->assertSame('+1', $phone['phone_code']);
        $this->assertSame('2025550123', $phone['phone']);
        $this->assertNull($phone['country_code']);
    }

    public function test_invalid_or_missing_international_prefix_is_rejected(): void
    {
        foreach (['0712345678', '+999712345678', '+254hello712345678', '+2541234567890123456'] as $input) {
            $this->assertSame('', $this->normalize(['phone' => $input])['phone_code']);
        }
    }

    public function test_other_themes_can_still_submit_separate_fields(): void
    {
        $phone = $this->normalize(['phone' => '0712345678', 'phone_code' => '+254', 'country_code' => 'ke']);
        $this->assertSame('712345678', $phone['phone']);
        $this->assertSame('+254', $phone['phone_code']);
    }
}
