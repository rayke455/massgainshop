<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialMedia extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'icon',
        'icon_driver',
        'status',
        'sort_by',
    ];

    public function category()
    {
        return $this->hasMany(Category::class, 'social_media_id', 'id');
    }
}
