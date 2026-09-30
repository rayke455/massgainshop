<?php

namespace App\Models;

use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthorDetails extends Model
{
    use HasFactory, Translatable;

    protected $fillable = [
        'author_id',
        'language_id',
        'name',
        'slug',
        'address',
        'description',
        'social_media'
    ];

    protected $casts = [
        'social_media' => 'array'
    ];
}
