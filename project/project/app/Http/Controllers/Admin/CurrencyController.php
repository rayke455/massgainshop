<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Currency\CurrencyStoreRequest;
use App\Models\BasicControl;
use App\Models\Currency;
use App\Services\MarketRateService;
use App\Traits\MultiCurrencyAutoUpdate;
use Facades\App\Services\CurrencyLayerService;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    use MultiCurrencyAutoUpdate;

    public function index(Request $request)
    {
        $search = $request->all();
        $data['currencies'] = Currency::orderBy('sort_by', 'asc')
            ->when(!empty($search['search']), function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search['search'] . '%')
                    ->orWhere('code', 'like', '%' . $search['search'] . '%');
            })
            ->when(isset($search['status']), function ($query) use ($search) {
                return $query->where('status', $search['status']);
            })
            ->paginate(basicControl()->paginate);
        return view('admin.currency.index', $data);
    }

    public function create()
    {
        return view('admin.currency.create');
    }

    public function store(CurrencyStoreRequest $request)
    {
        $data = $request->validated();
        try {
            $currencyRes = Currency::create([
                'name' => $data['name'],
                'code' => $data['code'],
                'symbol' => $data['symbol'],
                'conversion_rate' => $data['conversion_rate'],
                'status' => $data['status']
            ]);

            throw_if(!$currencyRes, 'Something went wrong, Please try again');

            return back()->with('success', 'Currency added successfully.');
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function edit(string $id)
    {
        try {
            $data['currency'] = Currency::where('id', $id)->firstOr(function () {
                throw new \Exception('Currency is not available.');
            });
            return view('admin.currency.edit', $data);
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function update(CurrencyStoreRequest $request, string $id)
    {
        $data = $request->validated();
        try {
            $currency = Currency::where('id', $id)->firstOr(function () {
                throw new \Exception('Currency is not available.');
            });

            $currencyRes = $currency->update([
                'name' => $data['name'],
                'code' => $data['code'],
                'symbol' => $data['symbol'],
                'conversion_rate' => $data['conversion_rate'],
                'status' => $data['status']
            ]);

            throw_if(!$currencyRes, 'Something went wrong, Please try again');

            return back()->with('success', 'Currency updated successfully.');
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function destroy(string $id)
    {
        try {
            $currency = Currency::where('id', $id)->firstOr(function () {
                throw new \Exception('Currency is not available.');
            });
            $currency->delete();
            return back()->with('success', 'Currency deleted successfully.');
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function statusChange(Request $request, $id)
    {
        try {
            $currency = Currency::where('id', $id)->firstOr(function () {
                throw new \Exception('Currency is not available.');
            });

            $currency->update([
                'status' => $request->status == 0 ? 1 : 0
            ]);

            throw_if(!$currency, 'Something went wrong, Please try again.');

            return back()->with('success', 'Currency status changed successfully.');
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }


    public function singleAutoUpdateCurrency(Request $request)
    {
        try {
            $code = $request->currency;

            $currencyData = Currency::where('code', $code)->get();

            $updated = $currencyData->isNotEmpty() ? $this->getCurrencyUpdateData($currencyData) : 0;

            if (!$updated) {
                return back()->with('error', 'Could not get a market rate right now. Please try again in a minute.');
            }

            return back()->with('success', "Updated {$updated} currency rate(s) from live market data.");
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function allAutoUpdateCurrency(Request $request)
    {
        try {
            $currencyData = Currency::where('status', 1)->get();

            $updated = $currencyData->isNotEmpty() ? $this->getCurrencyUpdateData($currencyData) : 0;

            if (!$updated) {
                return back()->with('error', 'Could not get a market rate right now. Please try again in a minute.');
            }

            return back()->with('success', "Updated {$updated} currency rate(s) from live market data.");
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    /**
     * JSON preview for the "Check market rates" modal: our rate vs the live market rate.
     */
    public function marketRates(MarketRateService $market)
    {
        $currencies = Currency::orderBy('sort_by')->get(['id', 'name', 'code', 'conversion_rate', 'status']);
        $result = $market->rates($currencies->pluck('code')->all(), request()->boolean('fresh'));

        $rows = $currencies->map(function ($c) use ($result) {
            $marketRate = $result['rates'][strtoupper($c->code)] ?? null;
            $current = (float)$c->conversion_rate;
            return [
                'id' => $c->id,
                'code' => $c->code,
                'name' => $c->name,
                'active' => (bool)$c->status,
                'current' => $current,
                'market' => $marketRate,
                'change' => ($marketRate && $current > 0) ? round(($marketRate - $current) / $current * 100, 2) : null,
            ];
        });

        return response()->json([
            'base' => $market->base(),
            'rows' => $rows,
            'sources' => $result['sources'],
            'updated_at' => $result['updated_at'],
            'missing' => $result['missing'],
        ]);
    }

    /**
     * Apply live market rates to the chosen currencies (or all of them).
     */
    public function applyMarketRates(Request $request)
    {
        $request->validate(['ids' => 'nullable|array', 'ids.*' => 'integer']);

        $currencies = Currency::when($request->filled('ids'), fn($q) => $q->whereIn('id', $request->ids))->get();
        if ($currencies->isEmpty()) {
            return back()->with('warning', 'Select at least one currency.');
        }

        $updated = $this->getCurrencyUpdateData($currencies, false);

        return $updated
            ? back()->with('success', "Applied live market rates to {$updated} currency(ies).")
            : back()->with('error', 'Could not reach the market data source. Please try again in a minute.');
    }

    /**
     * Rate + name/symbol for one code, used by the add/edit forms ("Use market rate").
     */
    public function marketQuote(Request $request, MarketRateService $market)
    {
        $code = strtoupper(trim((string)$request->query('code')));
        abort_unless(preg_match('/^[A-Z]{2,6}$/', $code), 422, 'Invalid currency code');

        $known = config("market_currencies.fiat.$code") ?? config("market_currencies.crypto.$code");
        $result = $market->rates([$code]);

        return response()->json([
            'code' => $code,
            'name' => $known[0] ?? null,
            'symbol' => $known[1] ?? null,
            'rate' => $result['rates'][$code] ?? null,
            'base' => $market->base(),
            'source' => $result['sources'][0] ?? null,
        ]);
    }

    /**
     * Add every world currency that is missing, priced at the live market rate.
     * Existing currencies (and their rates) are left untouched.
     */
    public function addAll(Request $request, MarketRateService $market)
    {
        $fiat = config('market_currencies.fiat', []);
        $result = $market->rates(array_keys($fiat), true);
        if (empty($result['rates'])) {
            return back()->with('error', 'Could not reach the market data source. Please try again in a minute.');
        }

        $existing = Currency::pluck('code')->map(fn($c) => strtoupper($c))->flip();
        $popular = array_flip(config('market_currencies.popular', []));
        $names = collect($fiat)->map(fn($info) => $info[0])->sort()->keys()->flip();
        $active = $request->boolean('active', true) ? 1 : 0;

        $added = 0;
        foreach ($fiat as $code => [$name, $symbol]) {
            $rate = $result['rates'][$code] ?? null;
            if (isset($existing[$code]) || !$rate) {
                continue;
            }
            Currency::create([
                'name' => $name,
                'code' => $code,
                'symbol' => $symbol,
                'conversion_rate' => $rate,
                'status' => $active,
                // Popular currencies first, then alphabetical by name.
                'sort_by' => isset($popular[$code]) ? $popular[$code] + 1 : 100 + $names[$code],
            ]);
            $added++;
        }

        // Keep the popular currencies you already had at the top too.
        foreach ($popular as $code => $i) {
            Currency::where('code', $code)->update(['sort_by' => $i + 1]);
        }

        $skipped = count($fiat) - $added - $existing->intersectByKeys($fiat)->count();
        return back()->with('success', $added
            ? "Added {$added} currencies with live market rates." . ($skipped > 0 ? " {$skipped} had no market price and were skipped." : '')
            : 'All world currencies are already added.');
    }

    public function toggleAutoUpdate(Request $request)
    {
        $basic = BasicControl::firstOrCreate();
        $basic->auto_currency_update = $request->boolean('enabled') ? 1 : 0;
        $basic->save();
        \Cache::forget('ConfigureSetting');

        return back()->with('success', $basic->auto_currency_update
            ? 'Rates will now update automatically every day.'
            : 'Automatic daily updates turned off.');
    }

    public function sortBy(Request $request)
    {
        $sortItems = $request->sort;
        foreach ($sortItems as $key => $value) {
            Currency::where('id', $value)->update(['sort_by' => $key + 1]);
        }
    }
}
