<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ApiProvider\ApiProviderStoreRequest;
use App\Http\Requests\Admin\ApiProvider\SetCurrencyUpdateRequest;
use App\Models\ApiProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class ApiProviderController extends Controller
{
    public function index(Request $request): View
    {
        $data['providerData'] = collect(ApiProvider::selectRaw('COUNT(id) AS totalApiProvider')
            ->selectRaw('COUNT(CASE WHEN status = 0 THEN id END) AS inactiveApiProvider')
            ->selectRaw('COUNT(CASE WHEN status = 1 THEN id END) AS activeApiProvider')
            ->get()
            ->toArray())->collapse();
        $search = $request->all();
        $data['apiProviders'] = ApiProvider::orderBy('id', 'desc')
            ->when(!empty($search['provider_name']), function ($query) use ($search) {
                return $query->where('name', 'like', '%' . $search['provider_name'] . '%');
            })
            ->when(!empty($search['status']) && isset($search['status']), function ($query) use ($search) {
                return $query->where('status', $search['status']);
            })
            ->paginate(basicControl()->paginate);

        return view('admin.api_providers.index', $data);
    }

    public function create(): View
    {
        return view('admin.api_providers.create');
    }

    public function store(ApiProviderStoreRequest $request)
    {
        try {
            $response = Http::post($request->url, [
                'key' => $request->api_key,
                'action' => 'balance',
            ]);

            $currencyData = $response->json();

            if (!$currencyData) {
                throw new \Exception('Please Check your API URL Or API Key.');
            }

            $providerResponse = ApiProvider::create([
                'api_name' => $request->api_name,
                'url' => $request->url,
                'api_key' => $request->api_key,
                'conversion_rate' => $request->conversion_rate,
                'balance' => $currencyData['balance'] ?? 0,
                'currency' => $currencyData['currency'] ?? 'USD',
                'status' => $request->status
            ]);

            throw_if(!$providerResponse, 'Something went wrong while storing api provider data. Please try again later.');

            return back()->with('success', 'Api provider added successfully.');
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }


    public function edit(string $id)
    {
        try {
            $apiProvider = ApiProvider::where('id', $id)->firstOr(function () {
                throw new \Exception('Api provider is not available.');
            });
            return view('admin.api_providers.edit', compact('apiProvider'));
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function update(ApiProviderStoreRequest $request, string $id)
    {
        try {
            $response = Http::post($request->url, [
                'key' => $request->api_key,
                'action' => 'balance',
            ]);

            $currencyData = $response->json();
            if (!$currencyData) {
                throw new \Exception('Please Check your API URL Or API Key.');
            }

            $apiProvider = ApiProvider::where('id', $id)->firstOr(function () {
                throw new \Exception('API provider is not available.');
            });
            $providerResponse = $apiProvider->update([
                'api_name' => $request->api_name,
                'url' => $request->url,
                'api_key' => $request->api_key,
                'conversion_rate' => $request->conversion_rate,
                'balance' => $currencyData['balance'] ?? 0,
                'currency' => $currencyData['currency'] ?? 'USD',
                'status' => $request->status
            ]);

            throw_if(!$providerResponse, 'Something went wrong while updating api provider data. Please try again later.');
            return back()->with('success', 'API Provider updated successfully.');

        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }


    public function destroy(string $id)
    {
        try {
            $apiProvider = ApiProvider::where('id', $id)->firstOr(function () {
                throw new \Exception('api provider is not available.');
            });

            $apiProvider->delete();

            return back()->with('success', 'Api provider deleted successfully.');

        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }


    public function providerStatus(Request $request, $id)
    {
        try {
            $apiProvider = ApiProvider::where('id', $id)->firstOr(function () {
                throw new \Exception('API provider is not available.');
            });

            $apiProvider->update([
                'status' => $request->status == 0 ? 1 : 0
            ]);

            throw_if(!$apiProvider, 'Something went wrong, Please try again.');

            return back()->with('success', 'Provider status changed successfully.');
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }


    public function setCurrency(SetCurrencyUpdateRequest $request, $id)
    {
        try {
            $apiProvider = ApiProvider::where('id', $id)->firstOr(function () {
                throw new \Exception('API provider is not available.');
            });
            $apiProvider->update([
                'conversion_rate' => $request->conversion_rate
            ]);

            throw_if(!$apiProvider, 'Something went wrong, Please try again.');

            return back()->with('success', 'Provider conversion rate updated successfully.');
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function priceUpdate($id)
    {

        try {
            $apiProvider = ApiProvider::with('services')->where('id', $id)->firstOr(function () {
                throw new \Exception('API provider is not available.');
            });

            $response = Http::post($apiProvider->url, [
                'key' => $apiProvider->api_key,
                'action' => 'services',
            ]);

            $responseData = $response->json();

            if (!$responseData) {
                throw new \Exception('Please Check your API URL Or API Key.');
            }

            foreach ($apiProvider->services as $k => $data) {
                if (isset($data->price)) {
                    $apiRate = collect($responseData)->where('service', $data->api_service_id)->pluck('price')[0] ?? collect($responseData)->where('service', $data->api_service_id)->pluck('rate')[0] ?? $data->api_provider_price;

                    $conversionRate = $apiProvider->conversion_rate ?: 1;
                    $originalPrice = $data->original_price ?: 1;
                    $percentageIncrease = (($data->price - $originalPrice) / $originalPrice) * 100;

                    if ($apiRate) {
                        $increased_price = $apiRate * ($percentageIncrease / 100);
                        $data->update([
                            'original_price' => $apiRate * $apiProvider->conversion_rate,
                            'api_provider_price' => $apiRate,
                            'price' => ($apiRate + $increased_price) * $conversionRate,
                        ]);
                    }
                }
            }
            return back()->with('success', 'Successfully price updated.');
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function balanceUpdate($id)
    {
        try {
            $apiProvider = ApiProvider::where('id', $id)->firstOr(function () {
                throw new \Exception('API provider is not available.');
            });

            $response = Http::post($apiProvider->url, [
                'key' => $apiProvider->api_key,
                'action' => 'balance',
            ]);

            $providerBalance = $response->json();

            if (!$providerBalance) {
                throw new \Exception('Please Check your API URL Or API Key.');
            }

            $apiProvider->update([
                'balance' => $providerBalance['balance'] ?? $apiProvider->balance,
                'currency' => $providerBalance['currency'] ?? $apiProvider->currency,
            ]);

            return back()->with('success', 'Provider balance updated successfully.');
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }

    }
}
