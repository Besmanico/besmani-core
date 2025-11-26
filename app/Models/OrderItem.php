<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PackageItem;

class OrderItem extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function packageItems()
    {
        return $this->hasMany(PackageItem::class, 'order_item_id');
    }
    public function customePackageItem()
    {
        return $this->hasOne(CustomePackageItem::class, 'order_item_id');
    }
}
