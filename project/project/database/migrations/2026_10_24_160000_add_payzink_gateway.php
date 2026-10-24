<?php

use App\Models\Gateway;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $existing = Gateway::where('code', 'payzink')->first();

        if (!$existing) {
            $nextId = 52;

            \Illuminate\Support\Facades\DB::table('gateways')->insert([
                'id' => $nextId,
                'name' => 'Payzink',
                'code' => 'payzink',
                'status' => 0,
                'sort_by' => $nextId,
                'driver' => 'local',
                'image' => 'gateway/payzink.webp',
                'parameters' => json_encode([
                    'publishable_key' => '',
                    'secret_key' => '',
                    'environment' => 'live',
                ]),
                'currencies' => json_encode([
                    '0' => [
                        'USD' => 'USD',
                        'EUR' => 'EUR',
                        'AED' => 'AED',
                        'GBP' => 'GBP',
                    ],
                ], JSON_FORCE_OBJECT),
                'supported_currency' => json_encode(['USD', 'EUR', 'AED', 'GBP']),
                'receivable_currencies' => [
                    [
                        'name' => 'USD',
                        'currency_symbol' => '$',
                        'conversion_rate' => '1',
                        'min_limit' => '1',
                        'max_limit' => '50000',
                        'percentage_charge' => '0',
                        'fixed_charge' => '0',
                    ],
                ],
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Gateway::where('code', 'payzink')->delete();
    }
};
