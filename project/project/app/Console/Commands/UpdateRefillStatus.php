<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class UpdateRefillStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update-refill:status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Provider Refill Status for Order';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $orders = Order::with(['service.provider'])
            ->whereNotIn('status', ['completed', 'refunded', 'canceled'])
            ->whereNotIn('refill_status', ['completed', 'refunded', 'canceled'])
            ->whereNotNull('refilled_at')
            ->whereNotNull('api_refill_id')
            ->whereHas('service', function ($query) {
                $query->whereNotNull('api_provider_id')
                    ->where('api_provider_id', '!=', 0);
            })
            ->get();

        foreach ($orders as $order) {
            $service = $order->service;
            $provider = $service?->provider;

            if (!$provider || !$provider->url || !$provider->api_key) {
                continue;
            }

            try {
                $response = Http::asForm()->post($provider->url, [
                    'key' => $provider->api_key,
                    'action' => 'refill_status',
                    'refill' => $order->api_refill_id,
                ]);

                $data = $response->json();

                if (isset($data['status'])) {
                    $order->refill_status = strtolower($data['status']);
                    $order->save();
                }
            } catch (\Throwable $e) {
            }
        }
    }
}
