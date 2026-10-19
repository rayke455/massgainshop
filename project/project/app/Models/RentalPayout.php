<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RentalPayout extends Model
{
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
