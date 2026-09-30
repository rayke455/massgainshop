<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'social_media_id',
        'sort_by',
        'category_title',
        'image',
        'image_driver',
        'status',
    ];

    public function service()
    {
        return $this->hasMany(Service::class, 'category_id', 'id');
    }


    public function socialMedia()
    {
        return $this->belongsTo(SocialMedia::class, 'social_media_id', 'id');
    }

}
