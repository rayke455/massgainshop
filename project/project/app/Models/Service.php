<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'service_title',
        'min_amount',
        'max_amount',
        'original_price',
        'price',
        'service_type',
        'service_status',
        'price_percentage_increase',
        'api_provider_id',
        'api_service_id',
        'api_provider_price',
        'drip_feed',
        'refill',
        'is_refill_automatic',
        'description'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function provider()
    {
        return $this->belongsTo(ApiProvider::class, 'api_provider_id', 'id');
    }

    protected function scopeUserRate($query)
    {
        $query->addSelect(['user_rate' => UserServiceRate::select('price')
            ->whereColumn('service_id', 'services.id')
            ->where('user_id', auth()->id())
        ]);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function getRefillDaysAttribute(): int
    {
        $title = $this->service_title ?? '';
        if (preg_match('/Refill:\s*(\d+)\s*[DdMm]/i', $title, $matches)) {
            return (int) $matches[1];
        }

        if (stripos($title, 'lifetime') !== false) {
            return 9999;
        }

        if (stripos($title, 'no') !== false) {
            return 0;
        }

        return 0;
    }
}
