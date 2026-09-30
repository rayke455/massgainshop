<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'author_id',
        'views',
        'thumbnail_image',
        'thumbnail_image_driver',
        'thumbnail_image_two',
        'thumbnail_image_two_driver',
        'description_image',
        'description_image_driver',
        'background_image',
        'background_image_driver',
        'breadcrumb_image',
        'breadcrumb_image_driver',
        'breadcrumb_status',
        'status',
        'page_title',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'og_description',
        'meta_robots',
        'meta_image',
        'meta_image_driver',
        'is_static_footer'
    ];

    protected $casts = ['meta_keywords' => 'array', 'meta_robots' => 'array'];

    public function details()
    {
        return $this->hasOne(BlogDetails::class, 'blog_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'category_id', 'id');
    }

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function getLanguageEditClass($id, $languageId)
    {
        return DB::table('blog_details')->where(['blog_id' => $id, 'language_id' => $languageId])->exists() ? 'bi-check2' : 'bi-pencil';
    }

    public function getMetaRobots()
    {
        return explode(",", $this->meta_robots);
    }
}
