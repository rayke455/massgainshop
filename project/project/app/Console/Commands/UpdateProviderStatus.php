<?php

namespace App\Console\Commands;

use App\Models\ApiProvider;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

/**
 * Syncs order progress from the providers and refunds canceled / partial orders.
 *
 * Refunds happen exactly once: only when an order *moves into* canceled, refunded or partial,
 * inside a locked transaction. (The old version refunded partial orders again on every run.)
 */
class UpdateProviderStatus extends Command
{
    use \App\Traits\Notify;

    protected $signature = 'update-provider:status';

    protected $description = 'Update order status from API providers and refund canceled/partial orders';

    /** Final states: never checked again. */
    private const FINAL = ['completed', 'refunded', 'canceled', 'partial'];

    public function handle(): int
    {
        $orders = Order::with('service:id,api_provider_id')
            ->whereNotIn('status', self::FINAL)
            ->whereNotNull('api_order_id')
            ->whereHas('service', fn($q) => $q->whereNotNull('api_provider_id')->where('api_provider_id', '!=', 0))
            ->get(['id', 'service_id', 'api_order_id', 'api_provider_id', 'status']);

        $checked = 0;
        // The provider that actually took the order (may be the backup provider), else the service's provider.
        foreach ($orders->groupBy(fn($o) => $o->api_provider_id ?: $o->service->api_provider_id) as $providerId => $group) {
            $provider = ApiProvider::find($providerId);
            if (!$provider || !$provider->url || !$provider->api_key) {
                continue;
            }

            foreach ($group->chunk(100) as $chunk) {
                $statuses = $this->fetchStatuses($provider, $chunk);
                foreach ($chunk as $order) {
                    $data = $statuses[(string) $order->api_order_id] ?? null;
                    if (is_array($data)) {
                        $this->apply($order->id, $data);
                        $checked++;
                    }
                }
            }
        }

        $this->info("Checked {$checked} order(s).");
        return self::SUCCESS;
    }

    /** One request per 100 orders (standard SMM API v2 "orders" call), falling back to one-by-one. */
    private function fetchStatuses(ApiProvider $provider, $chunk): array
    {
        $ids = $chunk->pluck('api_order_id')->map(fn($v) => (string) $v)->all();
        try {
            $res = Http::asForm()->timeout(40)->post($provider->url, [
                'key' => $provider->api_key,
                'action' => 'status',
                'orders' => implode(',', $ids),
            ])->json();
            if (is_array($res) && array_intersect(array_keys($res), $ids)) {
                return array_map(fn($v) => is_array($v) ? $v : [], $res);
            }
        } catch (\Throwable $e) {
            report($e);
        }

        $out = [];
        foreach ($ids as $id) {
            try {
                $res = Http::asForm()->timeout(25)->post($provider->url, [
                    'key' => $provider->api_key,
                    'action' => 'status',
                    'order' => $id,
                ])->json();
                if (is_array($res)) {
                    $out[$id] = $res;
                }
            } catch (\Throwable $e) {
                report($e);
            }
        }
        return $out;
    }

    private function apply(int $orderId, array $data): void
    {
        $completed = DB::transaction(function () use ($orderId, $data) {
            $order = Order::lockForUpdate()->find($orderId);
            if (!$order || in_array($order->status, self::FINAL, true)) {
                return false; // already finished (and refunded if needed) by another run
            }

            if (isset($data['error'])) {
                $order->status_description = 'error: {' . $data['error'] . '}';
                $order->save();
                return false;
            }
            if (!isset($data['status'])) {
                return false;
            }

            $status = strtolower(trim($data['status']));
            $status = match ($status) {
                'in progress', 'inprogress' => 'progress',
                'cancelled' => 'canceled',
                default => $status,
            };

            $order->status = $status;
            if (isset($data['start_count']) && $data['start_count'] !== '') {
                $order->start_counter = (int) $data['start_count'];
            }
            if (isset($data['remains']) && $data['remains'] !== '') {
                $order->remains = max(0, (int) $data['remains']);
            }
            if ($status === 'completed') {
                $order->completed_at = now();
            }
            $order->save();

            // Refund what wasn't delivered — once, because these states are final.
            $refund = match ($status) {
                'canceled', 'refunded' => (float) $order->price,
                'partial' => $order->quantity > 0
                    ? (float) $order->price / (int) $order->quantity * min((int) $order->remains, (int) $order->quantity)
                    : 0,
                default => 0,
            };

            if ($refund > 0 && $order->user_id) {
                \App\Services\PanelRentals::clawBack($order, (float) $refund);   // panel customer order: reverse reseller profit share
                $user = User::lockForUpdate()->find($order->user_id);
                if ($user) {
                    $user->balance += $refund;
                    $user->save();

                    $trx = new Transaction();
                    $trx->user_id = $user->id;
                    $trx->child_panel_id = $order->child_panel_id ?? null;
                    $trx->trx_type = '+';
                    $trx->amount = round($refund, 8);
                    $trx->balance = $user->balance;
                    $trx->remarks = ucfirst($status) . " order on #{$order->id}: undelivered part refunded";
                    $trx->charge = 0;
                    $trx->save();
                }
            }
            return $status === 'completed' ? $order : false;
        });

        if ($completed && \App\Services\Growth::get('notify.order_completed', true)) {
            $this->notifyCompleted($completed);
        }
    }

    /** "Your order is complete" to the customer (dashboard bell + email/SMS as set in the template). */
    private function notifyCompleted(Order $order): void
    {
        try {
            $user = User::find($order->user_id);
            if (!$user) {
                return;
            }
            $params = [
                'order_id' => $order->id,
                'service' => \Illuminate\Support\Str::limit(optional($order->service)->service_title ?? 'service', 80),
                'quantity' => number_format((int) $order->quantity),
            ];
            $this->userPushNotification($user, 'ORDER_COMPLETED', $params, ['link' => route('user.order.index'), 'icon' => 'fa-light fa-circle-check']);
            $this->sendMailSms($user, 'ORDER_COMPLETED', $params);
        } catch (\Throwable $e) {
            report($e);
        }
    }
}
