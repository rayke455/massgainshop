<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

/**
 * Public "track my order" lookup.
 * Only non-identifying progress data is returned (no link, customer or price),
 * and the route is rate-limited so order IDs can't be scraped in bulk.
 */
class OrderTrackController extends Controller
{
    private const LABELS = [
        'awaiting' => ['Awaiting', 'Your order is queued and will be sent shortly.'],
        'pending' => ['Pending', 'Your order was received and is waiting to start.'],
        'processing' => ['Processing', 'Your order is being prepared by the provider.'],
        'progress' => ['In progress', 'Delivery has started and is running now.'],
        'completed' => ['Completed', 'Delivery finished. Enjoy the results!'],
        'partial' => ['Partial', 'Part of the order was delivered; the rest was refunded to the balance.'],
        'canceled' => ['Canceled', 'This order was canceled and the amount refunded to the balance.'],
        'refunded' => ['Refunded', 'This order was refunded to the balance.'],
        'fail' => ['Failed', 'Something went wrong. Please contact support with this order ID.'],
    ];

    public function track(Request $request)
    {
        $id = (int) preg_replace('/\D/', '', (string) $request->query('id'));
        if ($id <= 0) {
            return response()->json(['found' => false, 'message' => __('Enter the order ID shown in your orders list, e.g. 1024.')], 422);
        }

        $order = Order::with(['service:id,service_title', 'category:id,category_title,social_media_id', 'category.socialMedia:id,name'])
            ->find($id, ['id', 'service_id', 'category_id', 'status', 'quantity', 'start_counter', 'remains', 'created_at', 'updated_at', 'completed_at']);

        if (!$order) {
            return response()->json(['found' => false, 'message' => __('We could not find an order with that ID. Check the number and try again.')], 404);
        }

        [$label, $hint] = self::LABELS[$order->status] ?? [ucfirst((string) $order->status), ''];
        $qty = (int) $order->quantity;
        $remains = max(0, (int) $order->remains);
        $progress = match ($order->status) {
            'completed' => 100,
            'progress', 'partial' => $qty > 0 ? (int) round(min(100, max(0, ($qty - $remains) / $qty * 100))) : 0,
            'processing' => 5,
            default => 0,
        };

        return response()->json([
            'found' => true,
            'id' => $order->id,
            'service' => optional($order->service)->service_title ?? __('Service'),
            'platform' => optional(optional($order->category)->socialMedia)->name,
            'status' => $order->status,
            'label' => __($label),
            'hint' => __($hint),
            'quantity' => number_format($qty),
            'start_count' => $order->start_counter !== null ? number_format((int) $order->start_counter) : null,
            'remains' => in_array($order->status, ['progress', 'partial']) ? number_format($remains) : null,
            'progress' => $progress,
            'placed' => optional($order->created_at)->diffForHumans(),
            'updated' => optional($order->updated_at)->diffForHumans(),
            'mine' => auth()->check() && Order::whereKey($order->id)->where('user_id', auth()->id())->exists(),
        ]);
    }
}
