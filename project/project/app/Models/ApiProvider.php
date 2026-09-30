<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiProvider extends Model
{
    use HasFactory;

    protected $fillable = [
        'api_name',
        'url',
        'api_key',
        'balance',
        'currency',
        'conversion_rate',
        'status',
        'low_balance_alert',
        'balance_checked_at',
        'balance_alerted_at',
    ];

    protected $casts = [
        'balance_checked_at' => 'datetime',
        'balance_alerted_at' => 'datetime',
    ];

    public function services()
    {
        return $this->hasMany(Service::class,'api_provider_id');
    }
}
