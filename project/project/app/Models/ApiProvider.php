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
        'status'
    ];

    public function services()
    {
        return $this->hasMany(Service::class,'api_provider_id');
    }
}
