<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferralBonus extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function bonusBy(){
        return $this->belongsTo(User::class,'to_user_id','id');
    }
}
