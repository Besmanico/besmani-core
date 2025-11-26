<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomeDeleteItem extends Model
{
    use HasFactory;
    protected $guarded = [];  
    public function user()
    {
        return $this->belongsTo(MainUser::class, 'user_id');
    }
    public function cart()
    {
        return $this->belongsTo(Cart::class, 'cart_id');
    }
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
    public function packageService()
    {
        return $this->belongsTo(PackageService::class, 'package_service_id');
    }
    public function packageServiceItem()
    {
        return $this->belongsTo(PackageServiceItem::class, 'package_service_item_id');
    }
}
