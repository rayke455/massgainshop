<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\ChildPanel\App\Models\ChildPanelService;

class DraftMassOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'child_panel_id',
        'order_id',
        'service_id',
        'quantity',
        'link',
        'price',
        'remarks',
        'status'
    ];

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id', 'id');
    }

    public function panelService()
    {
        return $this->belongsTo(ChildPanelService::class, 'service_id', 'id');
    }
}
