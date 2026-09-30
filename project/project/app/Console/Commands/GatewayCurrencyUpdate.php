<?php

namespace App\Console\Commands;

use App\Models\Gateway;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GatewayCurrencyUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:gateway-currency-update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (basicControl()->automatic_currency_update_permission != 1) {
            $this->info('Automatic gateway rate updates are turned off.');
            return 0;
        }

        $service = app(\App\Services\MarketRateService::class);
        $total = 0;
        foreach (Gateway::where('status', 1)->get() as $gateway) {
            $total += $service->updateGatewayRates($gateway);
        }

        $this->info("Updated {$total} gateway currency rate(s) from market data.");
        return 0;
    }

    public function getCheck($currency, $autoUp)
    {
        foreach ($autoUp as $key => $auto) {
            if ($key == $currency) {
                return $auto;
            }
        }
    }
}
