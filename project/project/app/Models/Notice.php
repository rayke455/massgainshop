<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Notice extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'image_driver',
        'status'
    ];

    public function details()
    {
        return $this->hasOne(NoticeDetails::class, 'notice_id', 'id');
    }

    public function getLanguageEditClass($id, $languageId)
    {
        return DB::table('notice_details')->where(['notice_id' => $id, 'language_id' => $languageId])->exists() ? 'bi-check2' : 'bi-pencil';
    }
}
