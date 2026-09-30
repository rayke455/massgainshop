<?php

namespace App\Models;

use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogDetails extends Model
{
    use HasFactory, Translatable;

    protected $fillable = [
        'blog_id',
        'language_id',
        'title',
        'slug',
        'tags',
        'description',
        'quote',
        'quote_author',
    ];

    protected $casts = [
        'tags' => 'array'
    ];

    public function blog()
    {
        return $this->belongsTo(Blog::class, 'blog_id', 'id');
    }
}
