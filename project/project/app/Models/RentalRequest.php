<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class RentalRequest extends Model {
    protected $guarded = ['id'];
    public function providerOrder() { return $this->belongsTo(Order::class, 'provider_order_id'); }
    public function service() { return $this->belongsTo(Service::class); }
}
