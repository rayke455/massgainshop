<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoticeDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'notice_id',
        'language_id',
        'title',
        'description'
    ];
}
