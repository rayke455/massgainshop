<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = ['code', 'type', 'value', 'min_deposit', 'max_uses', 'per_user', 'first_deposit_only', 'expires_at', 'status', 'used_count'];

    protected $casts = ['expires_at' => 'datetime', 'first_deposit_only' => 'boolean', 'status' => 'boolean'];

    public function setCodeAttribute($value): void
    {
        $this->attributes['code'] = strtoupper(trim((string) $value));
    }
}
