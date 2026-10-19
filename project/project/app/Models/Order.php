<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Modules\ChildPanel\App\Models\ChildPanel;
use Modules\ChildPanel\App\Models\ChildPanelService;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'child_panel_id',
        'category_id',
        'service_id',
        'api_order_id',
        'api_provider_id',
        'api_refill_id',
        'link',
        'quantity',
        'price',
        'custom_comments',
        'status',
        'refill_status',
        'status_description',
        'reason',
        'comments',
        'agree',
        'start_counter',
        'remains',
        'runs',
        'interval',
        'drip_feed',
        'refilled_at',
        'added_on',
        'order_type',
        'order_type_info',
        'completed_at'
    ];

    protected $dates = [
        'refilled_at'
    ];

    protected $casts = [
        'order_type_info' => 'object',
    ];

    /** Services priced per 1,000; everything else (packages, subscriptions…) is a flat price. */
    public const QUANTITY_BASED_TYPES = [
        'Default', 'Mentions', 'Mentions with Hashtags', 'Custom Comments', 'Mentions Custom List', 'Mentions Hashtag',
        'Mentions User Followers', 'Mentions Media Likers', 'Comment Likes', 'Poll', 'Invites from Groups', 'Web Traffic',
    ];

    /**
     * Remember what the provider charges for this order at the moment it's placed (base currency),
     * so reports can show real profit (price - provider_cost) even after provider prices change.
     * Works for every place that creates orders: website, mass order, reseller API, admin.
     */
    protected static function booted(): void
    {
        static::creating(function (Order $order) {
            if ($order->provider_cost !== null || !$order->service_id) {
                return;
            }
            $service = Service::find($order->service_id, ['id', 'original_price', 'service_type']);
            if (!$service || $service->original_price === null || $service->original_price === '') {
                return;
            }
            $cost = (float)$service->original_price;
            $order->provider_cost = in_array($service->service_type ?: 'Default', self::QUANTITY_BASED_TYPES, true)
                ? round($cost * (float)$order->quantity / 1000, 8)
                : round($cost, 8);
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /** The provider that actually took this order (could be the backup provider), else the service's provider. */
    public function effectiveProvider()
    {
        return $this->api_provider_id
            ? ApiProvider::find($this->api_provider_id)
            : optional($this->service)->provider;
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id', 'id');
    }

    public function panelService()
    {
        return $this->belongsTo(ChildPanelService::class, 'service_id', 'id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function childPanel()
    {
        return $this->belongsTo(ChildPanel::class);
    }

    public function transaction()
    {
        return $this->hasMany(Transaction::class);
    }

    public static function boot()
    {
        parent::boot();
        static::saved(function () {
            Cache::forget('orderRecord');
        });
    }


    public function getStatusClass($status)
    {
        $statusClasses = [
            'awaiting' => 'dark',
            'pending' => 'warning',
            'processing' => 'info',
            'progress' => 'primary',
            'completed' => 'success',
            'partial' => 'secondary',
            'canceled' => 'danger',
            'refunded' => 'danger',
            'fail' => 'fail',
        ];
        return $statusClasses[$status] ?? 'text-secondary';
    }

    public function canBeRefilled()
    {
        return $this->status === 'completed'
            && $this->remains > 0
            && optional($this->service)->refill == 1
            && now()->diffInDays($this->completed_at) <= optional($this->service)->refill_days
            && (!isset($this->refill_status) || in_array($this->refill_status, ['completed','partial','canceled','refunded']))
            && ($this->refilled_at === null || $this->refilled_at->lt(now()->subHours(24)));
    }
}
