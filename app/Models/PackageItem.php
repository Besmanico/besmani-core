<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageItem extends Model
{
    use HasFactory;
    protected $guarded = [];
    
    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class, 'order_item_id');
    }
    
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
}
