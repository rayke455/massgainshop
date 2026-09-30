<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Page extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'template_name', 'custom_link', 'page_title', 'meta_title', 'meta_keywords', 'meta_description',
        'meta_image', 'og_description', 'meta_robots', 'meta_image_driver', 'breadcrumb_image', 'breadcrumb_image_driver', 'breadcrumb_status', 'type', 'status'];

    protected $casts = ['meta_keywords' => 'object'];


    public function details()
    {
        return $this->hasOne(PageDetail::class, 'page_id', 'id');
    }

    public function getLanguageEditClass($id, $languageId)
    {
        return DB::table('page_details')->where(['page_id' => $id, 'language_id' => $languageId])->exists() ? 'bi-check2' : 'bi-pencil';
    }

    public function getMetaRobots()
    {
        return explode(",", $this->meta_robots);
    }
}
