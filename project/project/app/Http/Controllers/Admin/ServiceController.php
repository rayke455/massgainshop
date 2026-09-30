<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Service\GetApiServiceRequest;
use App\Http\Requests\Admin\Service\ServiceStoreRequest;
use App\Models\ApiProvider;
use App\Models\Category;
use App\Models\Service;
use App\Models\SocialMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Cache;


class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->all();
        $data['categories'] = Category::with('service', 'service.provider')
            ->has('service')
            ->whereHas('service', function ($query) {
                $query->whereHas('provider', function ($q) {
                    $q->where('status', 1);
                })->orWhereDoesntHave('provider');
            })->when(isset($search['name']), function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('service', function ($s) use ($search) {
                        $s->where('service_title', 'LIKE', "%{$search['name']}%");
                    })->orWhere('category_title', 'LIKE', "%{$search['name']}%");
                });
            })
            ->when(isset($search['provider']), function ($query) use ($search) {
                $query->whereHas('service.provider', function ($q) use ($search) {
                    $q->where('id', $search['provider']);
                });
            })
            ->when(isset($search['status']), function ($query) use ($search) {
                if ($search['status'] === 'all') {
                    $query->whereHas('service', function ($q) {
                        $q->whereIn('service_status', [0, 1]);
                    });
                } else {
                    $query->whereHas('service', function ($q) use ($search) {
                        $q->where('service_status', $search['status']);
                    });
                }
            })
            ->paginate(basicControl()->paginate);

        $data['apiProviders'] = ApiProvider::orderBy('id', 'DESC')->where('status', 1)->get();
        return view('admin.services.index', $data);
    }

    public function create()
    {
        $data['apiProviders'] = ApiProvider::orderBy('id', 'DESC')->where('status', 1)->get();
        $data['categories'] = Category::orderBy('id', 'DESC')->where('status', 1)->get();
        return view('admin.services.create', $data);
    }


    public function store(ServiceStoreRequest $request)
    {
        $data = $request->validated();
        try {

            $refillData = $this->handleRefill($data['refill']);

            $serviceRes = Service::create([
                'service_title' => $data['name'],
                'category_id' => $data['category'],
                'min_amount' => $data['min_amount'],
                'max_amount' => $data['max_amount'],
                'price' => $data['price'],
                'service_status' => $data['status'],
                'service_type' => 'Default',
                'drip_feed' => $data['drip_feed'],
                'manual_api' => $data['manual_api'],
                'api_provider_id' => isset($request->api_provider_id) ? $request->api_provider_id : null,
                'api_service_id' => isset($request->api_service_id) ? $request->api_service_id : null,
                'refill' => $refillData['refill'] ?? 0,
                'is_refill_automatic' => $refillData['is_refill_automatic'] ?? 0,
                'description' => $data['description'],
            ]);

            throw_if(!$serviceRes, 'Something went wrong, while storing data.Please try again.');

            $this->handleApiServiceCreation($data, $serviceRes);

            return back()->with('success', 'API service created successfully.');

        } catch (\Exception $exp) {
            return back()->with('error', $exp->getMessage());
        }
    }

    public function edit(string $id)
    {
        try {
            $data['service'] = Service::where('id', $id)->firstOr(function () {
                throw new \Exception('Service is not available.');
            });

            $data['apiProviders'] = ApiProvider::orderBy('id', 'DESC')->where('status', 1)->get();
            $data['categories'] = Category::orderBy('id', 'DESC')->where('status', 1)->get();

            return view('admin.services.edit', $data);
        } catch (\Exception $exp) {
            return back()->with('error', $exp->getMessage());
        }
    }


    public function update(ServiceStoreRequest $request, string $id)
    {

        $data = $request->validated();
        try {
            $refillData = $this->handleRefill($data['refill']);
            $service = Service::where('id', $id)->firstOr(function () {
                throw new \Exception('Service is not available.');
            });

            $serviceRes = $service->update([
                'service_title' => $data['name'],
                'category_id' => $data['category'],
                'min_amount' => $data['min_amount'],
                'max_amount' => $data['max_amount'],
                'price' => $data['price'],
                'service_status' => $data['status'],
                'drip_feed' => $data['drip_feed'],
                'manual_api' => $data['manual_api'],
                'api_provider_id' => isset($request->api_provider_id) ? $request->api_provider_id : null,
                'api_service_id' => isset($request->api_service_id) ? $request->api_service_id : null,
                'refill' => $refillData['refill'] ?? 0,
                'is_refill_automatic' => $refillData['is_refill_automatic'] ?? 0,
                'description' => $data['description'],
            ]);

            throw_if(!$serviceRes, 'Something went wrong, while updating data.Please try again.');

            $this->handleApiServiceCreation($data, $service);

            return back()->with('success', 'Service updated successfully.');

        } catch (\Exception $exp) {
            return back()->with('error', $exp->getMessage());
        }
    }

    public function handleRefill($refillValue)
    {
        $result = [
            'refill' => 0,
            'is_refill_automatic' => 0
        ];

        if ($refillValue == 1) {
            $result['refill'] = 1;
            $result['is_refill_automatic'] = 0;
        } elseif ($refillValue == 2) {
            $result['refill'] = 1;
            $result['is_refill_automatic'] = 1;
        } elseif ($refillValue == 3) {
            $result['refill'] = 0;
            $result['is_refill_automatic'] = 0;
        }

        return $result;
    }

    public function handleApiServiceCreation($data, $serviceRes)
    {
        $provider = ApiProvider::find($data['api_provider_id'] ?? null);

        if ($data['manual_api'] == 1) {
            $response = Http::post($provider['url'], [
                'key' => $provider['api_key'],
                'action' => 'services',
            ]);

            $apiServiceData = json_decode($response->body(), true);

            foreach ($apiServiceData as $current) {
                if ($current['service'] == $data['api_service_id']) {
                    $serviceRes->update([
                        'original_price' => $current['rate'] * $provider->conversion_rate,
                        'api_provider_price' => $current['rate'],
                        'service_type' => $current['type'] ?? null,
                    ]);
                    return back()->with('success', 'API service successfully created.');
                }
            }
            return back()->with('error', 'Please check again API Service ID')->withInput();
        }

        return true;
    }


    public function destroy(string $id): \Illuminate\Http\RedirectResponse
    {
        try {
            $service = Service::with('orders')->where('id', $id)->firstOr(function () {
                throw new \Exception('service is not available.');
            });
            if (count($service->orders) > 0) {
                return back()->with('error', "Service has many orders; you can't delete this service.");
            }
            $service->delete();
            return back()->with('success', 'Service deleted successfully.');
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }


    }

    public function serviceStatusChange(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        try {
            $service = Service::where('id', $id)->firstOr(function () {
                throw new \Exception('Service is not available.');
            });

            $service->update([
                'service_status' => $request->status == 0 ? 1 : 0
            ]);

            throw_if(!$service, 'Something went wrong, Please try again.');

            return back()->with('success', 'Service status changed successfully.');
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }


    public function getApiServices(GetApiServiceRequest $request)
    {
        try {
            $apiProvider = ApiProvider::where('id', $request->api_provider_id)->firstOr(function () {
                throw new \Exception('API provider is not available.');
            });

            $response = Http::post($apiProvider->url, [
                'key' => $apiProvider->api_key,
                'action' => 'services',
            ]);

            $responseData = $response->json();

            if (isset($responseData['error'])) {
                throw new \Exception($responseData['error']);
            }

            $categories = Category::where('status', 1)->get();

            if ($response->successful()) {
                return view('admin.services.show_api_services', compact('apiProvider', 'categories'));
            }
            throw new \Exception('Please Check your API URL Or API Key.');
        } catch (\Exception $exp) {
            return back()->with('error', $exp->getMessage());
        }
    }


    public function listOfApiServices($id): \Illuminate\Http\JsonResponse
    {

        $apiProvider = ApiProvider::where('id', $id)->firstOr(function () {
            throw new \Exception('API provider is not available.');
        });

        $apiServiceLists = Cache::remember('api_service_lists', 500, function () use ($apiProvider) {
            $response = Http::post($apiProvider->url, [
                'key' => $apiProvider->api_key,
                'action' => 'services',
            ]);
            return $response->json();
        });

        return DataTables::of(collect($apiServiceLists))
            ->addColumn('checkbox', function ($item) {
                return ' <input type="checkbox" id="chk-' . $item['service'] . '"
                               class="form-check-input row-tic tic-check" name="check" value="' . $item['service'] . '"
                               data-id="' . $item['service'] . '">';
            })
            ->addColumn('service_id', function ($item) {
                return $item['service'];
            })
            ->addColumn('name', function ($item) {
                return Str::limit($item['name'], 30);
            })
            ->addColumn('category', function ($item) {
                return $item['category'];
            })
            ->addColumn('price', function ($item) use ($apiProvider) {
                return $item['rate'] . " " . $apiProvider->currency ?? ' USD';
            })
            ->addColumn('status', function ($item) {
                if ($item['dripfeed'] ?? $item['drip_feed']) {
                    return '<span class="badge bg-soft-success text-success">
                    <span class="legend-indicator bg-success"></span>' . trans("Active") . '
                  </span>';
                } else {
                    return '<span class="badge bg-soft-danger text-danger">
                    <span class="legend-indicator bg-danger"></span>' . trans("Inactive") . '
                  </span>';
                }
            })
            ->addColumn('action', function ($item) use ($apiProvider) {
                $importRoute = route('admin.api.service.import', ['id' => @$item['service'], 'name' => @$item['name'], 'category' => $item['category'], 'rate' => $item['rate'], 'min' => $item['min'], 'max' => @$item['max'], 'dripfeed' => @$item['dripfeed'], 'type' => @$item['type'], 'refill' => @$item['refill'], 'provider' => @$apiProvider->id]);
                return '<button type="button" class="btn btn-white btn-sm import-single"
                            data-bs-toggle="modal" data-bs-target="#addServiceModal" data-route="' . $importRoute . '">
                    <i class="bi bi-plus me-1"></i> ' . trans("Add Service") . '
                  </button>';
            })->rawColumns(['action', 'checkbox', 'category', 'price', 'status', 'action'])
            ->make(true);
    }


    public function import(Request $request): \Illuminate\Http\RedirectResponse
    {
        try {
            $req = $request->all();

            $apiProvider = ApiProvider::where('id', $req['provider'])->firstOr(function () {
                throw new \Exception('API provider is not available.');
            });

            $categoryExists = Category::where('category_title', $req['category'])->exists();
            $socialMediaId = $this->findSocialMedia($req['name'], $req['category']);

            if (!$categoryExists && empty($req['category_id'])) {
                Category::create([
                    'social_media_id' => $socialMediaId->id ?? null,
                    'category_title' => $req['category'],
                    'status' => 1
                ]);
            }

            $serviceExists = Service::where('api_service_id', $req['id'])
                ->where('api_provider_id', $apiProvider->id)
                ->exists();
            $increased_price = ($req['rate'] * $req['price_percentage_increase']) / 100;

            if (!$serviceExists) {
                if (empty($req['category_id'])) {
                    $idCat = Category::where('category_title', $req['category'])->first()->id;
                }

                Service::create([
                    'service_title' => $req['name'],
                    'category_id' => empty($req['category_id']) ? $idCat : $req['category_id'],
                    'min_amount' => $req['min'],
                    'max_amount' => $req['max'],
                    'original_price' => $req['rate'] * $apiProvider->conversion_rate,
                    'price' => ($req['rate'] + $increased_price) * $apiProvider->conversion_rate,
                    'price_percentage_increase' => $req['price_percentage_increase'],
                    'service_status' => 1,
                    'service_type' => $req['type'] ?? null,
                    'api_provider_id' => $req['provider'],
                    'api_service_id' => $req['id'],
                    'drip_feed' => $req['dripfeed'],
                    'api_provider_price' => $req['rate'],
                    'refill' => $req['refill'] ?? 0,
                    'is_refill_automatic' => $req['refill'] ?? 0
                ]);

                return redirect()->route('admin.service.index')->with('success', 'Service imported successfully.');
            } else {
                return redirect()->route('admin.service.index')->with('success', 'Already have this service.');
            }
        } catch (\Exception $exp) {
            return back()->with('error', $exp->getMessage());
        }
    }

    public function importMultiple(Request $request): \Illuminate\Http\RedirectResponse
    {
        try {
            $req = $request->all();
            $apiProvider = ApiProvider::where('id', $req['provider'])->firstOr(function () {
                throw new \Exception('API provider is not available.');
            });

            $response = Http::post($apiProvider->url, [
                'key' => $apiProvider->api_key,
                'action' => 'services',
            ]);

            $apiServicesData = $response->json();

            if (!$apiServicesData) {
                throw new \Exception('Please Check your API URL Or API Key.');
            }

            if ($request->import_quantity == 'selectItem') {
                $getService = explode(',', $request->select_service);
                $apiServicesData = collect($apiServicesData)->whereIn('service', $getService)->values();
            }

            $count = 0;
            foreach ($apiServicesData as $apiService) {

                $socialMediaId = $this->findSocialMedia($apiService['name'], $apiService['category']);
                $categoryExists = Category::where('category_title', $apiService['category'])->exists();

                if (!$categoryExists) {
                    Category::create([
                        'social_media_id' => $socialMediaId->id ?? null,
                        'category_title' => $apiService['category'],
                        'status' => 1
                    ]);
                }

                $serviceExists = Service::where('api_service_id', $apiService['service'])->exists();

                if (!$serviceExists) {

                    $idCat = Category::where('category_title', $apiService['category'])->first()->id ?? null;
                    $increased_price = ($apiService['rate'] * $req['price_percentage_increase']) / 100;
                    $description = $apiService['desc'] ?? $apiService['description'] ?? null;

                    Service::create([
                        'service_title' => $apiService['name'],
                        'category_id' => $idCat,
                        'min_amount' => $apiService['min'],
                        'max_amount' => $apiService['max'],
                        'original_price' => $apiService['rate'] * $apiProvider->conversion_rate,
                        'price' => ($apiService['rate'] + $increased_price) * $apiProvider->conversion_rate,
                        'service_status' => 1,
                        'service_type' => $apiService['type'] ?? null,
                        'price_percentage_increase' => $req['price_percentage_increase'],
                        'api_provider_id' => $req['provider'],
                        'api_service_id' => $apiService['service'],
                        'drip_feed' => $apiService['dripfeed'] ?? $apiService['drip_feed'],
                        'api_provider_price' => $apiService['rate'],
                        'refill' => $apiService['refill'] ?? 0,
                        'is_refill_automatic' => $req['refill'] ?? 0,
                        'description' => $description ?? null
                    ]);

                }
                $count++;

                if ($req['import_quantity'] == 'all') {
                    continue;
                } elseif ($req['import_quantity'] == $count) {
                    break;
                } elseif ($req['import_quantity'] == 'selectItem') {
                    continue;
                }
            }

            return redirect()->route('admin.service.index')->with('success', 'Services Imported Successfully');
        } catch (\Exception $exp) {
            return back()->with('error', $exp->getMessage());
        }
    }

    public function activeInactiveMultiple(Request $request): \Illuminate\Http\JsonResponse
    {
        if ($request->strIds === null) {
            session()->flash('error', 'You did not select any service.');
            return response()->json(['error' => 1]);
        } else {
            Service::whereIn('id', $request->strIds)->each(function ($service) use ($request) {
                $service->update([
                    'service_status' => $request->status
                ]);
            });
            session()->flash('success', 'Service have been updated successfully.');
            return response()->json(['success' => 1]);
        }
    }

    public function singlePriceUpdate(Request $request, $id)
    {
        try {
            $request->validate([
                'price_percentage_increase' => 'required|numeric'
            ]);

            $percentage = (float)$request->price_percentage_increase;

            $service = Service::findOrFail($id);
            $service->update([
                'price' => $service->price + ($service->price * $percentage / 100),
                'price_percentage_increase' => $percentage
            ]);

            return back()->with('success', 'Price has been updated successfully.');
        } catch (\Exception $exp) {
            return back()->with('error', $exp->getMessage());
        }
    }


    public function multiplePriceUpdate(Request $request): bool|\Illuminate\Http\JsonResponse
    {
        if ($request->strIds == null) {
            session()->flash('error', "You didn't select any row");
            return response()->json(['error' => 1]);
        } else {
            $ids = explode(",", $request->strIds);

            if (count($ids) > 0) {

                $percentage = $request->percentage;
                $ids = explode(',', $request->strIds);

                DB::table('services')
                    ->whereIn('id', $ids)
                    ->update([
                        'price' => DB::raw("price + (price * $percentage / 100)"),
                        'price_percentage_increase' => $percentage
                    ]);

                session()->flash('success', 'Price updated successfully.');
                return response()->json(['success' => 1]);
            }
        }
        return false;
    }

    public function deleteMultiple(Request $request): \Illuminate\Http\JsonResponse
    {
        if ($request->strIds === null) {
            session()->flash('error', "You didn't select any services.");
            return response()->json(['error' => 1]);
        } else {
            Service::whereIn('id', $request->strIds)->delete();
            session()->flash('success', 'Services have been deleted successfully.');
            return response()->json(['success' => 1]);
        }
    }

    public function deleteAll(Request $request)
    {
        try {
            Service::query()->delete();
            return back()->with('success', 'All services have been deleted successfully.');
        } catch (\Exception $exp) {
            return back()->with('error', 'Something went wrong while deleting services.');
        }
    }

    public function findSocialMedia($name, $category)
    {

        $socialMediaList = SocialMedia::all();

        foreach ($socialMediaList as $media) {
            if (
                strpos(strtolower($name), strtolower($media->name)) !== false ||
                strpos(strtolower($category), strtolower($media->name)) !== false
            ) {
                return $media;
            } else {
                if (strtolower($media->name) == 'others') {
                    return $media;
                }
            }
        }
    }

}
