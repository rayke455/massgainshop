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

                try {
                    // Shared client: form-encoded, long timeout, fresh list; indexed once by service id.
                    $responseData = collect(app(\App\Services\ProviderApi::class)->services($provider, true))
                        ->keyBy(fn($s) => (string)$s['service']);
                } catch (\Exception $e) {
                    Log::error("API request failed for provider ID: {$provider->id}", ['error' => $e->getMessage()]);
                    continue;
                }

                $updates = [];

                foreach ($provider->services as $service) {

                    if (is_null($service->original_price)) {
                        continue;
                    }

                    if (!isset($service->price)) {
                        continue;
                    }

                    $remote = $responseData[(string)$service->api_service_id] ?? null;

                    // Loss protection: the provider no longer sells this service → stop selling it too.
                    if (!$remote && $responseData->isNotEmpty()) {
                        $this->protect($service, 'removed by the provider');
                        continue;
                    }

                    $apiRate = $remote['price'] ?? $remote['rate'] ?? $service->api_provider_price;

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
                        // Loss protection: never keep selling at or below cost.
                        if ((float) $update['price'] <= (float) $update['original_price']) {
                            $this->protect(Service::find($update['id']), 'selling price is not above the provider cost');
                        }
                    }
                }
            }
        } catch (\Exception $exception) {

        }
    }

    /** Switch a service off (if loss protection is on) and remember it for the daily report / dashboard. */
    private function protect($service, string $reason): void
    {
        if (!$service || !$service->service_status || !\App\Services\Growth::get('loss.auto_disable', true)) {
            return;
        }
        $service->update(['service_status' => 0]);
        $events = \Illuminate\Support\Facades\Cache::get('zk_loss_events', []);
        $events[] = ['id' => $service->id, 'title' => \Illuminate\Support\Str::limit($service->service_title, 80), 'reason' => $reason, 'at' => now()->toDateTimeString()];
        \Illuminate\Support\Facades\Cache::put('zk_loss_events', array_slice($events, -50), now()->addDays(7));
        Log::warning('Loss protection switched a service off', ['service' => $service->id, 'reason' => $reason]);
    }
}
