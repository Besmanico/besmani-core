<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartService extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function cart()
    {
        return $this->belongsTo(Cart::class, 'cart_id');
    }
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
    public function serviceInfo()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
    public function packageService()
    {
        return $this->belongsTo(PackageService::class, 'package_service_id');
    }
    public function packageServiceItems()
    {
        return $this->hasMany(PackageServiceItem::class, 'package_service_id', 'package_service_id');
    }
    public function customePackageItems()
    {
        return $this->hasMany(CustomePackageItem::class, 'cart_service_id');
    }
}
