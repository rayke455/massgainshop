<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

/**
 * Live exchange rates from free, keyless sources:
 *  - fiat:   open.er-api.com (≈160 currencies, daily) → fallback api.frankfurter.dev (ECB, ≈30 currencies)
 *  - crypto: api.coingecko.com simple/price
 * Rates are "units of currency per 1 base currency", the same meaning as currencies.conversion_rate.
 */
class MarketRateService
{
    protected int $cacheMinutes = 60;

    public function base(): string
    {
        return strtoupper(basicControl()->base_currency ?: 'USD');
    }

    /**
     * @param string[] $codes
     * @return array{rates: array<string,float>, missing: string[], sources: string[], updated_at: ?string}
     */
    public function rates(array $codes, bool $fresh = false): array
    {
        $base = $this->base();
        $codes = array_values(array_unique(array_map('strtoupper', array_filter($codes))));
        $cryptoMap = config('market_currencies.crypto', []);

        $rates = [];
        $sources = [];
        $updatedAt = null;

        $fiatCodes = array_filter($codes, fn($c) => !isset($cryptoMap[$c]));
        if ($fiatCodes) {
            $fiat = $this->fiatTable($base, $fresh);
            foreach ($fiatCodes as $code) {
                if ($code === $base) {
                    $rates[$code] = 1.0;
                } elseif (isset($fiat['rates'][$code])) {
                    $rates[$code] = (float)$fiat['rates'][$code];
                }
            }
            if (!empty($fiat['source'])) {
                $sources[] = $fiat['source'];
                $updatedAt = $fiat['updated_at'];
            }
        }

        $cryptoCodes = array_filter($codes, fn($c) => isset($cryptoMap[$c]));
        if ($cryptoCodes) {
            $crypto = $this->cryptoRates($cryptoCodes, $base, $fresh);
            $rates += $crypto;
            if ($crypto) {
                $sources[] = 'CoinGecko';
            }
        }

        return [
            'rates' => $rates,
            'missing' => array_values(array_diff($codes, array_keys($rates))),
            'sources' => array_values(array_unique($sources)),
            'updated_at' => $updatedAt,
        ];
    }

    /** All fiat codes the market feed knows about (for the "add currency" picker). */
    public function availableFiatCodes(): array
    {
        return array_keys($this->fiatTable($this->base())['rates'] ?? []);
    }

    protected function fiatTable(string $base, bool $fresh = false): array
    {
        $key = "market_rates_fiat_{$base}";
        if ($fresh) {
            Cache::forget($key);
        }

        return Cache::remember($key, now()->addMinutes($this->cacheMinutes), function () use ($base) {
            try {
                $res = Http::timeout(12)->acceptJson()->get("https://open.er-api.com/v6/latest/{$base}");
                if ($res->ok() && $res->json('result') === 'success') {
                    return [
                        'rates' => $res->json('rates', []),
                        'source' => 'ExchangeRate-API',
                        'updated_at' => $res->json('time_last_update_utc'),
                    ];
                }
            } catch (\Throwable $e) {
                report($e);
            }

            try {
                $res = Http::timeout(12)->acceptJson()->get('https://api.frankfurter.dev/v1/latest', ['base' => $base]);
                if ($res->ok() && is_array($res->json('rates'))) {
                    return [
                        'rates' => $res->json('rates'),
                        'source' => 'European Central Bank (Frankfurter)',
                        'updated_at' => $res->json('date'),
                    ];
                }
            } catch (\Throwable $e) {
                report($e);
            }

            return ['rates' => [], 'source' => null, 'updated_at' => null];
        });
    }

    protected function cryptoRates(array $codes, string $base, bool $fresh = false): array
    {
        $map = config('market_currencies.crypto', []);
        $ids = [];
        foreach ($codes as $code) {
            $ids[$map[$code][2]] = $code;
        }
        ksort($ids);

        $key = 'market_rates_crypto_' . $base . '_' . md5(implode(',', array_keys($ids)));
        if ($fresh) {
            Cache::forget($key);
        }

        return Cache::remember($key, now()->addMinutes(10), function () use ($ids, $base) {
            try {
                $vs = strtolower($base);
                $res = Http::timeout(12)->acceptJson()->get('https://api.coingecko.com/api/v3/simple/price', [
                    'ids' => implode(',', array_keys($ids)),
                    'vs_currencies' => $vs,
                ]);
                if (!$res->ok()) {
                    return [];
                }
                $out = [];
                foreach ($res->json() as $id => $prices) {
                    $price = (float)($prices[$vs] ?? 0);
                    if ($price > 0 && isset($ids[$id])) {
                        $out[$ids[$id]] = 1 / $price; // coins per 1 base currency
                    }
                }
                return $out;
            } catch (\Throwable $e) {
                report($e);
                return [];
            }
        });
    }

    /**
     * Refresh a payment gateway's receivable_currencies[].conversion_rate from live market data.
     * Returns the number of currencies updated.
     */
    public function updateGatewayRates(\App\Models\Gateway $gateway, bool $fresh = false): int
    {
        $list = collect($gateway->receivable_currencies ?? []);
        if ($list->isEmpty()) {
            return 0;
        }

        $codes = $list->map(fn($c) => strtoupper((string)(data_get($c, 'name') ?? data_get($c, 'currency'))))->filter()->all();
        $rates = $this->rates($codes, $fresh)['rates'];

        $updated = 0;
        $new = $list->map(function ($c) use ($rates, &$updated) {
            $code = strtoupper((string)(data_get($c, 'name') ?? data_get($c, 'currency')));
            if (isset($rates[$code])) {
                $rate = $rates[$code];
                $value = $rate >= 1 ? round($rate, 6) : round($rate, 10);
                if (is_array($c)) {
                    $c['conversion_rate'] = (string)$value;
                } else {
                    $c->conversion_rate = (string)$value;
                }
                $updated++;
            }
            return $c;
        })->values()->all();

        if ($updated) {
            $gateway->receivable_currencies = $new;
            $gateway->save();
        }

        return $updated;
    }
}
