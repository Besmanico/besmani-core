<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageServiceItem extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function packageService()
    {
        return $this->belongsTo(PackageService::class, 'package_service_id');
    }

    public function OrderItem()
    {
        return $this->belongsTo(OrderItem::class, 'order_item_id');
    }

    



}
