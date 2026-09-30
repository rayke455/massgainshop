<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLogin extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'child_panel_id', 'longitude', 'latitude', 'country_code', 'location', 'country', 'ip_address', 'browser', 'os', 'get_device'];

}
