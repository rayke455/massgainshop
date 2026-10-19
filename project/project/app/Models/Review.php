<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    public const PENDING = 0;
    public const APPROVED = 1;
    public const REJECTED = 2;

    protected $fillable = ['user_id', 'order_id', 'rating', 'comment', 'display_name', 'country', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
