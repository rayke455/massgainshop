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
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $currencyData = Currency::where('status', 1)->get();

        if ($currencyData->isNotEmpty())
            $this->getCurrencyUpdateData($currencyData);


        $this->info("Currency rate updated successfully.");
    }
}
