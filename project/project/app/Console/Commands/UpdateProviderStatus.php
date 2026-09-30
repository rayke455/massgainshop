<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class UpdateProviderStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update-provider:status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Provider Status for Order';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $orders = Order::with(['service', 'service.provider', 'users'])
            ->whereNotIn('status', ['completed', 'refunded', 'canceled'])->whereHas('service', function ($query) {
                $query->whereNotNull('api_provider_id')->orWhere('api_provider_id', '!=', 0);
            })->get();


        foreach ($orders as $order) {
            $service = $order->service;
            if (isset($service->api_provider_id)) {
                $apiproviderdata = $service->provider;
                $response = Http::asForm()->post($apiproviderdata['url'], [
                    'key' => $apiproviderdata['api_key'],
                    'action' => 'status',
                    'order' => $order->api_order_id,
                ]);
                $apidata = $response->json();

                if (isset($apidata['status'])) {
                    $order->status = (strtolower($apidata['status']) == 'in progress') ? 'progress' : strtolower($apidata['status']);
                    $order->start_counter = @$apidata['start_count'];
                    $order->remains = @$apidata['remains'];
                    $order->completed_at = strtolower($apidata['status']) == 'completed' ? now() : null;
                }

                if (isset($apidata['error'])) {
                    $order->status_description = "error: {" . @$apidata['error'] . "}";
                }
                $order->save();


                if (in_array($order->status, ['partial', 'refunded', 'canceled'])) {
                    $user = $order->user;

                    if ($order->status === 'canceled') {
                        $getBackAmo = $order->price;
                    } elseif ($order->remains != 0) {
                        $perOrder = $order->price / $order->quantity;
                        $getBackAmo = $order->remains * $perOrder;
                    } else {
                        $getBackAmo = 0;
                    }

                    if ($getBackAmo > 0) {
                        $user->balance += $getBackAmo;
                        $user->save();

                        $transaction = new Transaction();
                        $transaction->user_id = $user->id;
                        $transaction->child_panel_id = $order->child_panel_id ?? null;
                        $transaction->trx_type = '+';
                        $transaction->amount = $getBackAmo;
                        $transaction->remarks = ucfirst($order->status) . " order on #{$order->id}";
                        $transaction->charge = 0;
                        $transaction->save();
                    }
                }
            }
        }
    }
}
