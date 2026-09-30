<?php

namespace App\Traits;

use App\Models\Currency;
use Illuminate\Support\Facades\Http;

trait MultiCurrencyAutoUpdate
{
    protected function getCurrencyUpdateData($currencyData)
    {
        $endpoint = 'live';
        $source = basicControl()->base_currency;
        $currency_layer_url = "http://api.currencylayer.com";
        $currency_layer_access_key = basicControl()->currency_layer_access_key;

        $currencyCode = [];
        foreach ($currencyData as $currency) {
            $currencyCode[] = $currency->code;
        }

        $currencyCode = array_unique($currencyCode);
        $currencies = implode(',', $currencyCode);

        $CurrencyAPIUrl = "$currency_layer_url/$endpoint?access_key=$currency_layer_access_key&source=$source&currencies=$currencies";

        $response = Http::acceptJson()
            ->get($CurrencyAPIUrl);

        $responseData = $response->json();

        $currencyUpdateRate = [];
        foreach ($responseData['quotes'] as $key => $quote) {
            $strReplace = str_replace($responseData['source'], '', $key);
            $currencyUpdateRate[$strReplace] = $quote;
        }

        foreach ($currencyUpdateRate as $currencyCode => $conversionRate) {
            $currency = Currency::where('code', $currencyCode)->first();
            if ($currency) {
                $currency->update([
                    'conversion_rate' => $conversionRate
                ]);
            }
        }

        return true;
    }
}
