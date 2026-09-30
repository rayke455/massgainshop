<?php

namespace App\Console\Commands;

use App\Models\ApiProvider;
use App\Models\Service;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ServicePriceUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:service-price-update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Service price update';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $apiProviders = ApiProvider::with('services')->where('status', 1)->get();

            foreach ($apiProviders as $provider) {

                $response = Http::post($provider->url, [
                    'key' => $provider->api_key,
                    'action' => 'services',
                ]);

                if ($response->failed()) {
                    Log::error("API request failed for provider ID: {$provider->id}", ['response' => $response->body()]);
                    continue;
                }

                $responseData = collect($response->json());

                $updates = [];

                foreach ($provider->services as $service) {

                    if (is_null($service->original_price)) {
                        continue;
                    }

                    if (!isset($service->price)) {
                        continue;
                    }

                    $apiRate = collect($responseData)->firstWhere('service', $service->api_service_id)['price']
                        ?? collect($responseData)->firstWhere('service', $service->api_service_id)['rate']
                        ?? $service->api_provider_price;

                    $conversionRate = $provider->conversion_rate ?: 1;
                    $originalPrice = $service->original_price ?: 1;
                    $percentageIncrease = (($service->price - $originalPrice) / $originalPrice) * 100;

                    if ($apiRate) {
                        $increased_price = $apiRate * ($percentageIncrease / 100);

                        $updates[] = [
                            'id' => $service->id,
                            'original_price' => $apiRate * $provider->conversion_rate,
                            'api_provider_price' => $apiRate,
                            'price' => ($apiRate+ $increased_price) * $conversionRate,
                            'updated_at' => now(),
                        ];
                    }
                }

                if (!empty($updates)) {
                    foreach ($updates as $update) {
                        Service::where('id', $update['id'])->update($update);
                    }
                }
            }
        } catch (\Exception $exception) {

        }
    }
}
