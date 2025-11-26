<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageService extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function packageServiceItems()
    {
        return $this->hasMany(PackageServiceItem::class, 'package_service_id');
    }

    public function customePackageItem()
    {
        return $this->hasOne(CustomePackageItem::class, 'package_service_id');
    }
    
     
}
