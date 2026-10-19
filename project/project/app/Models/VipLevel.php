<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VipLevel extends Model
{
    protected $fillable = ['name', 'min_spent', 'discount_percent', 'color'];
}
