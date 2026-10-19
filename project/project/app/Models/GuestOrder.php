<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class GuestOrder extends Model {
    protected $guarded=['id'];
    protected $hidden=['email','phone','checkout_key'];
    protected $casts=['paid_at'=>'datetime'];
    public function order(){return $this->belongsTo(Order::class);}
    public function service(){return $this->belongsTo(Service::class);}
}
