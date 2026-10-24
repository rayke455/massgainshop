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

        // Profit overview across all active services (cost = provider price in base currency).
        $all = Service::where('service_status', 1)->get(['original_price', 'price']);
        $withCost = $all->filter(fn($s) => $s->original_price !== null && $s->original_price !== '' && (float)$s->original_price > 0);
        $data['profitSummary'] = [
            'active' => $all->count(),
            'avg_margin' => $withCost->count() ? $withCost->avg(fn($s) => ((float)$s->price - (float)$s->original_price) / (float)$s->original_price * 100) : null,
            'avg_profit' => $withCost->count() ? $withCost->avg(fn($s) => (float)$s->price - (float)$s->original_price) : null,
            'losing' => $withCost->filter(fn($s) => (float)$s->price < (float)$s->original_price)->count(),
            'no_cost' => $all->count() - $withCost->count(),
        ];

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
                'service_status' => $data['status'] ?? 0, // unchecked toggles aren't submitted
                'service_type' => 'Default',
                'drip_feed' => $data['drip_feed'] ?? 0,
                'manual_api' => $data['manual_api'],
                'api_provider_id' => isset($request->api_provider_id) ? $request->api_provider_id : null,
                'api_service_id' => isset($request->api_service_id) ? $request->api_service_id : null,
                'refill' => $refillData['refill'] ?? 0,
                'is_refill_automatic' => $refillData['is_refill_automatic'] ?? 0,
                'description' => $data['description'] ?? null,
            ] + (($data['manual_api'] ?? 0) == 0 ? ['original_price' => $data['cost'] ?? null] : [])
              + [
                  // Backup provider only applies to provider services.
                  'backup_api_provider_id' => ($data['manual_api'] ?? 0) == 1 ? ($data['backup_api_provider_id'] ?? null) : null,
                  'backup_api_service_id' => ($data['manual_api'] ?? 0) == 1 && !empty($data['backup_api_provider_id']) ? ($data['backup_api_service_id'] ?? null) : null,
              ]);

            throw_if(!$serviceRes, 'Something went wrong, while storing data.Please try again.');

            $this->handleApiServiceCreation($data, $serviceRes);

            return back()->with('success', ($data['manual_api'] ?? 0) == 1 ? 'API service created successfully.' : 'Service created successfully.');

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
                'service_status' => $data['status'] ?? 0, // unchecked toggles aren't submitted
                'drip_feed' => $data['drip_feed'] ?? 0,
                'manual_api' => $data['manual_api'],
                'api_provider_id' => isset($request->api_provider_id) ? $request->api_provider_id : null,
                'api_service_id' => isset($request->api_service_id) ? $request->api_service_id : null,
                'refill' => $refillData['refill'] ?? 0,
                'is_refill_automatic' => $refillData['is_refill_automatic'] ?? 0,
                'description' => $data['description'] ?? null,
            ] + (($data['manual_api'] ?? 0) == 0 ? ['original_price' => $data['cost'] ?? null] : [])
              + [
                  // Backup provider only applies to provider services.
                  'backup_api_provider_id' => ($data['manual_api'] ?? 0) == 1 ? ($data['backup_api_provider_id'] ?? null) : null,
                  'backup_api_service_id' => ($data['manual_api'] ?? 0) == 1 && !empty($data['backup_api_provider_id']) ? ($data['backup_api_service_id'] ?? null) : null,
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
            $apiServiceData = app(\App\Services\ProviderApi::class)->services($provider);

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

            // Fetch once (and cache) so the table below loads instantly instead of downloading again.
            $remote = collect(app(\App\Services\ProviderApi::class)->services($apiProvider, $request->boolean('refresh')));

            $importedCounts = Service::where('api_provider_id', $apiProvider->id)->pluck('api_service_id')->map(fn($v) => (string)$v)->flip();
            $providerCategories = $remote->groupBy(fn($s) => trim($s['category'] ?? 'Other') ?: 'Other')
                ->map(function ($items, $title) use ($importedCounts) {
                    return [
                        'title' => $title,
                        'platform' => $this->guessPlatform($title . ' ' . ($items->first()['name'] ?? '')),
                        'count' => $items->count(),
                        'imported' => $items->filter(fn($i) => isset($importedCounts[(string)$i['service']]))->count(),
                        'min_rate' => (float)$items->min(fn($i) => (float)$i['rate']),
                    ];
                })
                ->sortBy([['platform', 'asc'], ['title', 'asc']])
                ->values();

            $categories = Category::where('status', 1)->get();
            $smartMatrix = (new \App\Services\SmartImport(self::PLATFORMS))->matrix($remote);
            return view('admin.services.show_api_services', compact('apiProvider', 'categories', 'providerCategories', 'smartMatrix'));
        } catch (\Exception $exp) {
            return back()->with('error', $exp->getMessage());
        }
    }


    public function listOfApiServices($id): \Illuminate\Http\JsonResponse
    {
        $apiProvider = ApiProvider::where('id', $id)->firstOr(function () {
            throw new \Exception('API provider is not available.');
        });

        try {
            $apiServiceLists = app(\App\Services\ProviderApi::class)->services($apiProvider);
        } catch (\Exception $e) {
            return response()->json(['draw' => (int)request('draw'), 'recordsTotal' => 0, 'recordsFiltered' => 0, 'data' => [], 'error' => $e->getMessage()]);
        }

        $imported = Service::where('api_provider_id', $apiProvider->id)->pluck('api_service_id')->flip();

        return DataTables::of(collect($apiServiceLists))
            ->addColumn('checkbox', function ($item) {
                return ' <input type="checkbox" id="chk-' . e($item['service']) . '"
                               class="form-check-input row-tic tic-check" name="check" value="' . e($item['service']) . '"
                               data-id="' . e($item['service']) . '">';
            })
            ->addColumn('service_id', fn($item) => $item['service'])
            ->addColumn('name', fn($item) => e(Str::limit($item['name'], 60)))
            ->addColumn('category', fn($item) => e($item['category'] ?? '-'))
            ->addColumn('price', fn($item) => e($item['rate'] . ' ' . ($apiProvider->currency ?: 'USD')) . ' <small class="text-muted">/1k</small>')
            // The old column labelled drip-feed support as "Active/Inactive"; show what the service actually offers.
            ->addColumn('status', function ($item) use ($imported) {
                $badges = '';
                if (isset($imported[$item['service']])) {
                    $badges .= '<span class="badge bg-soft-primary text-primary me-1">' . trans('Imported') . '</span>';
                }
                if (!empty($item['refill'])) {
                    $badges .= '<span class="badge bg-soft-success text-success me-1">' . trans('Refill') . '</span>';
                }
                if (!empty($item['dripfeed'] ?? $item['drip_feed'] ?? null)) {
                    $badges .= '<span class="badge bg-soft-info text-info me-1">' . trans('Drip-feed') . '</span>';
                }
                return $badges ?: '<span class="text-muted">—</span>';
            })
            ->addColumn('action', function ($item) use ($apiProvider) {
                $importRoute = route('admin.api.service.import', ['id' => @$item['service'], 'name' => @$item['name'], 'category' => @$item['category'], 'rate' => $item['rate'], 'min' => @$item['min'], 'max' => @$item['max'], 'dripfeed' => @$item['dripfeed'], 'type' => @$item['type'], 'refill' => @$item['refill'], 'provider' => @$apiProvider->id]);
                return '<button type="button" class="btn btn-white btn-sm import-single"
                            data-bs-toggle="modal" data-bs-target="#addServiceModal" data-route="' . e($importRoute) . '">
                    <i class="bi bi-plus me-1"></i> ' . trans("Add Service") . '
                  </button>';
            })->rawColumns(['action', 'checkbox', 'category', 'price', 'status', 'name'])
            ->make(true);
    }


    public function import(Request $request): \Illuminate\Http\RedirectResponse
    {
        try {
            $req = $request->all();

            $apiProvider = ApiProvider::where('id', $req['provider'])->firstOr(function () {
                throw new \Exception('API provider is not available.');
            });

            $serviceExists = Service::where('api_service_id', $req['id'])
                ->where('api_provider_id', $apiProvider->id)
                ->exists();
            if ($serviceExists) {
                return redirect()->route('admin.service.index')->with('success', 'Already have this service.');
            }

            $categoryId = !empty($req['category_id'])
                ? $req['category_id']
                : $this->categoryFor($req['category'] ?? 'Other', $req['name'] ?? '')->id;

            $percentage = (float)($req['price_percentage_increase'] ?? 0);
            $rate = (float)$req['rate'];

            Service::create([
                'service_title' => $req['name'],
                'category_id' => $categoryId,
                'min_amount' => $req['min'],
                'max_amount' => $req['max'],
                'original_price' => $rate * $apiProvider->conversion_rate,
                'price' => ($rate + $rate * $percentage / 100) * $apiProvider->conversion_rate,
                'price_percentage_increase' => $percentage,
                'service_status' => 1,
                'service_type' => $req['type'] ?? 'Default',
                'api_provider_id' => $apiProvider->id,
                'api_service_id' => $req['id'],
                'drip_feed' => filter_var($req['dripfeed'] ?? false, FILTER_VALIDATE_BOOLEAN) ? 1 : 0,
                'api_provider_price' => $rate,
                'refill' => filter_var($req['refill'] ?? false, FILTER_VALIDATE_BOOLEAN) ? 1 : 0,
                'is_refill_automatic' => filter_var($req['refill'] ?? false, FILTER_VALIDATE_BOOLEAN) ? 1 : 0,
            ]);

            return redirect()->route('admin.service.index')->with('success', 'Service imported successfully.');
        } catch (\Exception $exp) {
            return back()->with('error', $exp->getMessage());
        }
    }

    public function importMultiple(Request $request): \Illuminate\Http\RedirectResponse
    {
        @set_time_limit(0); // importing thousands of services can take a while on shared hosting

        try {
            $req = $request->all();
            $apiProvider = ApiProvider::where('id', $req['provider'])->firstOr(function () {
                throw new \Exception('API provider is not available.');
            });

            $apiServicesData = collect(app(\App\Services\ProviderApi::class)->services($apiProvider));

            if (($req['import_quantity'] ?? '') === 'categories') {
                $picked = array_filter((array)$request->input('categories', []));
                if (!$picked) {
                    throw new \Exception('Tick at least one category to import.');
                }
                $apiServicesData = $apiServicesData->filter(fn($s) => in_array(trim($s['category'] ?? 'Other') ?: 'Other', $picked, true))->values();
            } elseif (($req['import_quantity'] ?? 'all') === 'selectItem') {
                $picked = array_filter(explode(',', (string)$request->select_service));
                if (!$picked) {
                    throw new \Exception('Select at least one service to import.');
                }
                $apiServicesData = $apiServicesData->whereIn('service', $picked)->values();
            } elseif (is_numeric($req['import_quantity'] ?? null)) {
                $apiServicesData = $apiServicesData->take((int)$req['import_quantity']);
            }

            $percentage = (float)($req['price_percentage_increase'] ?? 0);
            $existing = Service::where('api_provider_id', $apiProvider->id)->pluck('api_service_id')->map(fn($v) => (string)$v)->flip();
            $added = 0;
            $skipped = 0;

            DB::transaction(function () use ($apiServicesData, $apiProvider, $percentage, $existing, &$added, &$skipped, $req) {
                foreach ($apiServicesData as $apiService) {
                    if (isset($existing[(string)$apiService['service']])) {
                        $skipped++;
                        continue;
                    }

                    $rate = (float)$apiService['rate'];
                    $refill = !empty($apiService['refill']);
                    $category = $this->categoryFor($apiService['category'] ?? 'Other', $apiService['name']);

                    Service::create([
                        'service_title' => Str::limit($apiService['name'], 250, ''),
                        'category_id' => $category->id,
                        'min_amount' => (int)($apiService['min'] ?? 1),
                        'max_amount' => (int)($apiService['max'] ?? 1),
                        'original_price' => $rate * $apiProvider->conversion_rate,
                        'price' => ($rate + $rate * $percentage / 100) * $apiProvider->conversion_rate,
                        'service_status' => 1,
                        'service_type' => $apiService['type'] ?? 'Default',
                        'price_percentage_increase' => $percentage,
                        'api_provider_id' => $apiProvider->id,
                        'api_service_id' => $apiService['service'],
                        'drip_feed' => !empty($apiService['dripfeed'] ?? $apiService['drip_feed'] ?? null) ? 1 : 0,
                        'api_provider_price' => $rate,
                        'refill' => $refill ? 1 : 0,
                        'is_refill_automatic' => ($refill && !empty($req['refill'])) ? 1 : 0,
                        'description' => $apiService['desc'] ?? $apiService['description'] ?? null,
                    ]);
                    $existing[(string)$apiService['service']] = true;
                    $added++;
                }
            });

            $msg = "Imported {$added} service(s)" . ($skipped ? ", {$skipped} were already imported" : '') . '.';
            return redirect()->route('admin.service.index')->with('success', $msg);
        } catch (\Exception $exp) {
            return back()->with('error', $exp->getMessage());
        }
    }

    /** Quick import, step 1: preview the services that would be imported (nothing is saved). */
    public function smartPreview(Request $request): \Illuminate\Http\JsonResponse
    {
        @set_time_limit(300);
        $data = $this->smartRequest($request);
        try {
            [$provider, $picked] = $this->smartPick($data);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        $rows = $picked->map(function ($s) use ($provider, $data) {
            $cost = (float) $s['rate'] * $provider->conversion_rate;
            $sell = $cost * (1 + $data['markup'] / 100);
            return [
                'id' => (string) $s['service'],
                'name' => \App\Services\SmartImport::cleanName($s['name']),
                'original' => $s['name'],
                'category' => $this->smartCategoryTitle($s['_platform'], $s['_type']),
                'platform' => $s['_platform'],
                'min' => number_format((int) ($s['min'] ?? 0)),
                'max' => number_format((int) ($s['max'] ?? 0)),
                'refill' => !empty($s['refill']),
                'cost' => zkPerK($cost),
                'sell' => zkPerK($sell),
                'profit' => zkPerK($sell - $cost),
            ];
        })->values();

        return response()->json([
            'rows' => $rows,
            'categories' => $rows->pluck('category')->unique()->count(),
        ]);
    }

    /** Quick import, step 2: import the ticked services into clean categories like "Instagram Followers". */
    public function smartImport(Request $request): \Illuminate\Http\RedirectResponse
    {
        @set_time_limit(0);
        $data = $this->smartRequest($request);
        $request->validate(['ids' => 'required|array|min:1', 'ids.*' => 'string|max:32'], ['ids.required' => 'Tick at least one service in the preview.']);

        try {
            [$provider, $picked] = $this->smartPick($data);
            $ids = array_flip(array_map('strval', $request->input('ids')));
            $picked = $picked->filter(fn($s) => isset($ids[(string) $s['service']]));
            throw_if($picked->isEmpty(), new \Exception('Nothing to import. Tick at least one service in the preview.'));

            $added = 0;
            DB::transaction(function () use ($picked, $provider, $data, &$added) {
                foreach ($picked as $s) {
                    $rate = (float) $s['rate'];
                    $refill = !empty($s['refill']);
                    $category = $this->categoryFor($this->smartCategoryTitle($s['_platform'], $s['_type']), $s['_platform']);
                    Service::create([
                        'service_title' => $data['clean'] ? \App\Services\SmartImport::cleanName($s['name']) : Str::limit($s['name'], 250, ''),
                        'category_id' => $category->id,
                        'min_amount' => (int) ($s['min'] ?? 1),
                        'max_amount' => (int) ($s['max'] ?? 1),
                        'original_price' => $rate * $provider->conversion_rate,
                        'price' => $rate * (1 + $data['markup'] / 100) * $provider->conversion_rate,
                        'service_status' => 1,
                        'service_type' => $s['type'] ?? 'Default',
                        'price_percentage_increase' => $data['markup'],
                        'api_provider_id' => $provider->id,
                        'api_service_id' => $s['service'],
                        'drip_feed' => !empty($s['dripfeed'] ?? $s['drip_feed'] ?? null) ? 1 : 0,
                        'api_provider_price' => $rate,
                        'refill' => $refill ? 1 : 0,
                        'is_refill_automatic' => $refill ? 1 : 0,
                        'description' => $s['desc'] ?? $s['description'] ?? null,
                    ]);
                    $added++;
                }
            });

            return redirect()->route('admin.service.index')->with('success', "Imported {$added} service(s). They are live for customers now.");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    private function smartRequest(Request $request): array
    {
        $v = $request->validate([
            'provider' => 'required|integer|exists:api_providers,id',
            'platforms' => 'required|array|min:1',
            'platforms.*' => 'string|max:60',
            'types' => 'required|array|min:1',
            'types.*' => 'string|max:60',
            'per_type' => 'required|integer|min:1|max:20',
            'strategy' => 'required|in:best,cheapest,refill',
            'markup' => 'required|numeric|min:0|max:1000',
            'clean' => 'nullable|boolean',
        ], [
            'platforms.required' => 'Pick at least one platform.',
            'types.required' => 'Pick at least one service type.',
        ]);
        $v['clean'] = $request->boolean('clean', true);
        $v['markup'] = (float) $v['markup'];
        return $v;
    }

    /** Re-picks on the server from the provider list, so the browser can't change prices or names. */
    private function smartPick(array $data): array
    {
        $provider = ApiProvider::findOrFail($data['provider']);
        $all = collect(app(\App\Services\ProviderApi::class)->services($provider));
        $existing = Service::where('api_provider_id', $provider->id)->pluck('api_service_id')->map(fn($v) => (string) $v)->flip()->all();
        $picked = (new \App\Services\SmartImport(self::PLATFORMS))
            ->pick($all, $data['platforms'], $data['types'], (int) $data['per_type'], $data['strategy'], $existing);
        return [$provider, $picked];
    }

    private function smartCategoryTitle(string $platform, string $type): string
    {
        if ($type === 'Other') {
            return $platform === 'Other' ? 'Other Services' : $platform . ' Services';
        }
        if ($platform === 'Other') {
            return $type;
        }
        if (str_contains(strtolower($platform), strtolower($type))) {
            return $platform; // "Website Traffic" + "Traffic"
        }
        return $platform . ' ' . $type;
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

    /** Platforms we recognise in provider names, in match order (more specific first). */
    public const PLATFORMS = [
        'Instagram' => ['instagram', 'insta ', 'ig '], 'TikTok' => ['tiktok', 'tik tok'], 'YouTube' => ['youtube', 'yt '],
        'Facebook' => ['facebook', 'fb '], 'Telegram' => ['telegram'], 'Twitter' => ['twitter', ' x ', 'x.com', 'tweet'],
        'Spotify' => ['spotify'], 'LinkedIn' => ['linkedin'], 'Threads' => ['threads'], 'Snapchat' => ['snapchat'],
        'Discord' => ['discord'], 'Twitch' => ['twitch'], 'Pinterest' => ['pinterest'], 'Reddit' => ['reddit'],
        'SoundCloud' => ['soundcloud'], 'WhatsApp' => ['whatsapp'], 'Kick' => ['kick.com', 'kick '], 'Website Traffic' => ['traffic', 'website'],
    ];

    /** Best-guess platform name for display only (never creates anything). */
    protected function guessPlatform(string $text): string
    {
        $haystack = ' ' . strtolower($text) . ' ';
        foreach (self::PLATFORMS as $label => $needles) {
            foreach ($needles as $needle) {
                if (str_contains($haystack, $needle)) {
                    return $label;
                }
            }
        }
        return 'Other';
    }

    /** Find or create the category, attached to the right platform so it shows on the New order page. */
    protected function categoryFor(string $title, string $serviceName = '')
    {
        static $cache = [];
        $title = trim(Str::limit(strip_tags($title), 190, '')) ?: 'Other';
        if (isset($cache[$title])) {
            return $cache[$title];
        }

        $category = Category::where('category_title', $title)->first();
        if (!$category) {
            $platform = $this->findSocialMedia($serviceName, $title);
            $category = Category::create([
                'social_media_id' => $platform->id ?? null,
                'category_title' => $title,
                'status' => 1,
            ]);
        } elseif (!$category->social_media_id) {
            // Older imports left categories without a platform, which hides them from customers.
            $platform = $this->findSocialMedia($serviceName, $title);
            if ($platform) {
                $category->update(['social_media_id' => $platform->id]);
            }
        }

        return $cache[$title] = $category;
    }

    /**
     * Match a service/category to a platform ("Instagram Followers" → Instagram), creating the platform
     * when it doesn't exist yet. Falls back to an "Other" platform so nothing is ever hidden.
     */
    public function findSocialMedia($name, $category)
    {
        static $platforms = null;
        $platforms ??= SocialMedia::all()->keyBy(fn($m) => strtolower($m->name));

        $haystack = ' ' . strtolower($category . ' ' . $name) . ' ';

        // 1) an existing platform whose name appears in the text
        foreach ($platforms as $key => $media) {
            if ($key !== 'others' && $key !== 'other' && str_contains($haystack, $key)) {
                return $media;
            }
        }

        // 2) a known platform keyword → create it
        foreach (self::PLATFORMS as $label => $needles) {
            foreach ($needles as $needle) {
                if (str_contains($haystack, $needle)) {
                    $media = SocialMedia::create(['name' => $label, 'status' => 1, 'sort_by' => $platforms->count() + 1]);
                    return $platforms[strtolower($label)] = $media;
                }
            }
        }

        // 3) catch-all
        if (!isset($platforms['other']) && !isset($platforms['others'])) {
            $platforms['other'] = SocialMedia::create(['name' => 'Other', 'status' => 1, 'sort_by' => 99]);
        }
        return $platforms['other'] ?? $platforms['others'];
    }

    /**
     * Delete a single service.
     */
    public function destroy(string $id)
    {
        try {
            $service = Service::findOrFail($id);
            $service->delete();

            return back()->with('success', 'Service deleted successfully.');
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    /**
     * Delete multiple selected services via AJAX.
     */
    public function deleteMultiple(Request $request)
    {
        if (empty($request->strIds)) {
            session()->flash('error', 'You did not select any service.');
            return response()->json(['error' => 1]);
        }

        try {
            Service::whereIn('id', (array) $request->strIds)->delete();
            session()->flash('success', 'Selected services have been deleted successfully.');
            return response()->json(['success' => 1]);
        } catch (\Exception $exception) {
            session()->flash('error', $exception->getMessage());
            return response()->json(['error' => 1]);
        }
    }

    /**
     * Delete all services.
     */
    public function deleteAll()
    {
        try {
            Service::query()->delete();
            return back()->with('success', 'All services have been deleted successfully.');
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }
}

