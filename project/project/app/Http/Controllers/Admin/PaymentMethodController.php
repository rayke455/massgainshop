<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Modules\ChildPanel\App\Models\ChildPanel;
use Modules\ChildPanel\App\Models\ChildPanelGateway;
use App\Models\Gateway;
use App\Traits\Upload;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Validator;
use Facades\App\Services\CurrencyLayerService;

class PaymentMethodController extends Controller
{
    use Upload;

    public function index()
    {
        if (class_exists('Modules\ChildPanel\App\Models\ChildPanel')) {
            $data['childPanels'] = ChildPanel::where('status', 1)->get();
        } else {
            $data['childPanels'] = [];
        }
        $data['paymentGateways'] = Gateway::automatic()->orderBy('sort_by', 'ASC')->get();
        return view('admin.payment_methods.list', $data);
    }

    public function sortPaymentMethods(Request $request)
    {
        $sortItems = $request->sort;
        foreach ($sortItems as $key => $value) {
            Gateway::where('code', $value)->update(['sort_by' => $key + 1]);
        }
    }

    public function edit($id)
    {
        try {
            $data['basicControl'] = basicControl();
            $data['method'] = Gateway::findOrFail($id);
            return view('admin.payment_methods.edit', $data);
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'required|string',
            'receivable_currencies' => 'required|array',
            'receivable_currencies.*.name' => 'required|string',
            'receivable_currencies.*.currency_symbol' => 'required|string|max:255',
            'receivable_currencies.*.conversion_rate' => 'required|numeric',
            'receivable_currencies.*.min_limit' => 'required|numeric',
            'receivable_currencies.*.max_limit' => 'required|numeric',
            'receivable_currencies.*.percentage_charge' => 'required|numeric',
            'receivable_currencies.*.fixed_charge' => 'required|numeric',
            'description' => 'required|string|min:3',
            'is_active' => 'nullable|integer|in:0,1',
            'test_environment' => 'sometimes|required|string|in:test,live',
            'image' => 'nullable|mimes:png,jpeg,gif|max:4096',
        ];

        $customMessages = [
            'receivable_currencies.*.currency_symbol.required' => 'The receivable currency currency symbol field is required.',
            'receivable_currencies.*.conversion_rate.required' => 'The receivable currency convention rate field is required.',
            'receivable_currencies.*.conversion_rate.numeric' => 'The convention rate for receivable currency must be a number.',
            'receivable_currencies.*.min_limit.required' => 'The receivable currency min limit field is required.',
            'receivable_currencies.*.min_limit.numeric' => 'The min limit for receivable currency must be a number.',
            'receivable_currencies.*.max_limit.required' => 'The receivable currency max limit field is required.',
            'receivable_currencies.*.max_limit.numeric' => 'The max limit for receivable currency must be a number.',
            'receivable_currencies.*.percentage_charge.required' => 'The receivable currency percentage charge field is required.',
            'receivable_currencies.*.percentage_charge.numeric' => 'The percentage charge for receivable currency must be a number.',
            'receivable_currencies.*.fixed_charge.required' => 'The receivable currency fixed charge name is required.',
            'receivable_currencies.*.fixed_charge.numeric' => 'The fixed charge for receivable currency must be a number.',
        ];


        $gateway = Gateway::where('id', $id)->firstOr(function () {
            throw new Exception('No payment method found');
        });

        $parameters = [];
        foreach ($request->except('_token', '_method', 'image') as $k => $v) {
            foreach ($gateway->parameters as $key => $cus) {
                if ($k != $key) {
                    continue;
                } else {
                    // Callback URLs and M-Pesa's Till number are optional; everything else is a required credential.
                    $rules[$key] = in_array($key, ['webhook', 'till_number', 'webhook_secret'], true) ? 'nullable|max:5000' : 'required|max:5000';
                    $parameters[$key] = $v ?? '';
                }
            }
        }

        $validator = Validator::make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
            $names = collect(request()->receivable_currencies)
                ->filter(function ($item) {
                    return isset($item['name']) && $item['name'] !== null;
                })
                ->pluck('name')
                ->toArray();
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput($request->input())
                ->with('selectedCurrencyList', $names);
        }

        if ($request->hasFile('image')) {
            try {
                $image = $this->fileUpload($request->image, config('filelocation.gateway.path'), null, null, 'webp', 70, $gateway->image, $gateway->driver);
                if ($image) {
                    $gatewayImage = $image['path'];
                    $driver = $image['driver'];
                }
            } catch (\Exception $exp) {
                return back()->with('error', 'Image could not be uploaded.');
            }
        }

        try {
            $collection = collect($request->receivable_currencies);
            $supportedCurrency = $collection->pluck('name')->all();
            $response = $gateway->update([
                'supported_currency' => $supportedCurrency,
                'receivable_currencies' => $request->receivable_currencies,
                'description' => $request->description,
                'parameters' => $parameters,
                'image' => $gatewayImage ?? $gateway->image,
                'driver' => $driver ?? $gateway->driver,
                'environment' => $request->test_environment ?? null,
                'status' => $request->is_active
            ]);

            if (!$response) {
                throw new \Exception('Unexpected error! Please try again.');
            }
            return back()->with('success', 'Gateway data has been updated.');

        } catch (\Exception $exp) {
            return back()->with('error', $exp->getMessage());
        }
    }


    public function deactivate(Request $request)
    {
        try {
            $gateway = Gateway::where('code', $request->code)->firstOrFail();
            $gateway->update([
                'status' => $gateway->status == 1 ? 0 : 1
            ]);
            return back()->with('success', 'Gateway status updated successfully.');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function addChildPanel(Request $request, $id)
    {
        $rules = [
            'child_panel_id' => 'required|integer|exists:child_panels,id',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $addError = $validator->getMessageBag();
            $addError->add('errorMessage', 1);
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $childPanelGatewayExists = ChildPanelGateway::where([
            ['gateway_id', $id],
            ['child_panel_id', $request->child_panel_id]
        ])->exists();

        if ($childPanelGatewayExists) {
            return back()->with('warning', 'This gateway is already added.');
        }

        try {
            $gateway = Gateway::where('id', $id)->firstOr(function () {
                throw new Exception('No payment method found');
            });

            $response = ChildPanelGateway::create([
                'child_panel_id' => $request->child_panel_id,
                'gateway_id' => $gateway->id,
                'parameters' => $gateway->parameters,
                'extra_parameters' => $gateway->extra_parameters,
                'supported_currency' => $gateway->supported_currency,
                'receivable_currencies' => $gateway->receivable_currencies,
                'environment' => $gateway->environment,
            ]);

            if (!$response) {
                throw new \Exception('Unexpected error! Please try again.');
            }
            return back()->with('success', 'Gateway added successfully.');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }


    public function autoUpdate($id)
    {
        $updateForMethods = Gateway::query()->where('id', $id)->first();
        if (!$updateForMethods) {
            return back()->with('warning', 'Gateway not found');
        }
        $paymentCurrencies = Gateway::query()
            ->where('code', '!=', 'coinbase')
            ->pluck('supported_currency')->toArray();

        $autoCurrencyUpdate = CurrencyLayerService::getCurrencyRate($paymentCurrencies);

        $currencyUpdate = is_array($autoCurrencyUpdate) ? (object) $autoCurrencyUpdate : $autoCurrencyUpdate;
        if (!$currencyUpdate->success) {
            return back()->with('error', 'Failed to update currency rate. Please try again.');
        }

        $autoUp = [];
        foreach ($currencyUpdate->quotes as $key => $quote) {
            $strReplace = str_replace($currencyUpdate->source, '', $key);
            $autoUp[$strReplace] = $quote;
        }
        $autoUp['USD'] = 1.00;

        $usdToBase = 1.00;
        $currenciesArr = [];
        foreach ($updateForMethods->receivable_currencies as $key => $receivable_currency) {
            $currencyName = $receivable_currency->name ?? $receivable_currency->currency;
            $resRate = $this->getCheck($currencyName, $autoUp);
            if ($resRate) {
                /*$curRate = round($resRate / $usdToBase, 2);*/
                $curRate = $resRate / $usdToBase;
                $formattedRate = floatval(number_format($curRate, 6, '.', ''));

                $receivable_currency->conversion_rate = $formattedRate;
            }
            $currenciesArr[] = $receivable_currency;
        }

        $updateForMethods->receivable_currencies = $currenciesArr;
        $updateForMethods->save();

        return back()->with('success', 'Auto Currency Updated Successfully');

    }


    public function autoUpdateCurrency(Request $request, $id)
    {
        $gateway = Gateway::query()->where('id', $id)->first();
        if (!$gateway) {
            return back()->with('warning', 'Gateway not found.');
        }

        $updated = app(\App\Services\MarketRateService::class)->updateGatewayRates($gateway, true);

        return $updated
            ? back()->with('success', "Updated {$updated} currency rate(s) for {$gateway->name} from live market data.")
            : back()->with('error', 'Could not get market rates for this gateway right now. Please try again.');
    }

    /** M-Pesa "Test connection" — no prompt is sent to any phone. */
    public function testMpesa($id)
    {
        $gateway = Gateway::where('id', $id)->where('code', 'mpesa')->firstOrFail();
        $findings = app(\App\Http\Controllers\MpesaController::class)->diagnose($gateway);

        return back()->with('mpesa_findings', $findings);
    }

    /** One click: refresh the rates of every active payment gateway. */
    public function autoUpdateAllCurrencies()
    {
        $service = app(\App\Services\MarketRateService::class);
        $total = 0;
        foreach (Gateway::where('status', 1)->get() as $gateway) {
            $total += $service->updateGatewayRates($gateway);
        }

        return $total
            ? back()->with('success', "Updated {$total} gateway currency rate(s) from live market data.")
            : back()->with('error', 'Could not reach the market data source. Please try again in a minute.');
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
