<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BlogCategory extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function details()
    {
        return $this->hasOne(BlogCategoryDetails::class, 'category_id', 'id');
    }

    public function blog()
    {
        return $this->hasMany(Blog::class, 'category_id', 'id');
    }

    public function getLanguageEditClass($id, $languageId){
        return DB::table('blog_category_details')->where(['category_id' => $id, 'language_id' => $languageId])->exists() ? 'bi-check2' : 'bi-pencil';
    }
}
