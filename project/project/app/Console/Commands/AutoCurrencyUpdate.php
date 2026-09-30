<?php

namespace App\Console\Commands;

use App\Models\Currency;
use App\Traits\MultiCurrencyAutoUpdate;
use Illuminate\Console\Command;

class AutoCurrencyUpdate extends Command
{
    use MultiCurrencyAutoUpdate;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:auto-currency-update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update active currency rates from live market data (when enabled in Admin → Currencies)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!basicControl()->auto_currency_update) {
            $this->info('Automatic currency updates are turned off.');
            return;
        }

        $currencyData = Currency::where('status', 1)->get();
        $updated = $currencyData->isNotEmpty() ? $this->getCurrencyUpdateData($currencyData) : 0;

        $this->info("Updated {$updated} currency rate(s) from market data.");
    }
}
