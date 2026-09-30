<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Author extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'image_driver',
        'status'
    ];

    public function details()
    {
        return $this->hasOne(AuthorDetails::class, 'author_id', 'id');
    }

    public function getLanguageEditClass($id, $languageId): string
    {
        return DB::table('author_details')->where(['author_id' => $id, 'language_id' => $languageId])->exists() ? 'bi-check2' : 'bi-pencil';
    }
}
