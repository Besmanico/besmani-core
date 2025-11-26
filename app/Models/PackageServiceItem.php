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

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class, 'orderitem_id');
    }

    public function customeDeleteItem()
    {
        return $this->hasOne(CustomeDeleteItem::class, 'package_service_item_id');
    }
    



}
