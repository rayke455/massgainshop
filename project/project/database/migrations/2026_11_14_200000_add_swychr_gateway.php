<?php

use App\Models\Gateway;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $existing = Gateway::where('code', 'swychr')->first();

        if (!$existing) {
            $nextId = 53;

            DB::table('gateways')->insert([
                'id' => $nextId,
                'name' => 'Swychr Pay',
                'code' => 'swychr',
                'status' => 0,
                'sort_by' => $nextId,
                'driver' => 'local',
                'image' => 'gateway/swychr.png',
                'parameters' => json_encode([
                    'api_key' => '',
                    'webhook_secret' => '',
                    'country_code' => 'KE',
                    'pass_digital_charge' => '0',
                ]),
                'extra_parameters' => json_encode([
                    'webhook_url' => 'ipn',
                ]),
                'currencies' => json_encode([
                    '0' => [
                        'KES' => 'KES',
                        'USD' => 'USD',
                        'NGN' => 'NGN',
                        'GHS' => 'GHS',
                        'XAF' => 'XAF',
                        'XOF' => 'XOF',
                        'UGX' => 'UGX',
                        'TZS' => 'TZS',
                    ],
                ], JSON_FORCE_OBJECT),
                'supported_currency' => json_encode(['KES', 'USD', 'NGN', 'GHS', 'XAF', 'XOF', 'UGX', 'TZS']),
                'receivable_currencies' => json_encode([
                    [
                        'name' => 'KES',
                        'currency_symbol' => 'KSh',
                        'conversion_rate' => '130.00',
                        'min_limit' => '100',
                        'max_limit' => '500000',
                        'percentage_charge' => '0',
                        'fixed_charge' => '0',
                    ],
                    [
                        'name' => 'USD',
                        'currency_symbol' => '$',
                        'conversion_rate' => '1',
                        'min_limit' => '1',
                        'max_limit' => '5000',
                        'percentage_charge' => '0',
                        'fixed_charge' => '0',
                    ],
                    [
                        'name' => 'NGN',
                        'currency_symbol' => '₦',
                        'conversion_rate' => '1600.00',
                        'min_limit' => '1000',
                        'max_limit' => '5000000',
                        'percentage_charge' => '0',
                        'fixed_charge' => '0',
                    ],
                    [
                        'name' => 'GHS',
                        'currency_symbol' => 'GH₵',
                        'conversion_rate' => '15.50',
                        'min_limit' => '10',
                        'max_limit' => '50000',
                        'percentage_charge' => '0',
                        'fixed_charge' => '0',
                    ],
                ]),
                'description' => 'Pay with Mobile Money (M-Pesa, MTN, Airtel, Orange, Telecel) and Card/Crypto with Swychr Pay.',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Gateway::where('code', 'swychr')->delete();
    }
};
