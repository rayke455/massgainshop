<?php

namespace App\Traits;

use App\Models\Currency;
use App\Services\MarketRateService;

trait MultiCurrencyAutoUpdate
{
    /**
     * Update the given currencies to live market rates (free sources, no API key needed).
     * Returns the number of currencies updated; 0 means the market feed was unreachable.
     */
    protected function getCurrencyUpdateData($currencyData, bool $fresh = true)
    {
        $codes = collect($currencyData)->pluck('code')->all();
        $market = app(MarketRateService::class)->rates($codes, $fresh);

        $updated = 0;
        foreach ($market['rates'] as $code => $rate) {
            $updated += Currency::where('code', $code)->update(['conversion_rate' => $rate]);
        }

        return $updated;
    }
}
