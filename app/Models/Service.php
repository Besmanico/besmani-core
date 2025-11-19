<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function categoryWebDesigns()
    {
        return $this->hasMany(CategoryWebDesign::class , 'service_id');
    }
    public function packageItems()
    {
        return $this->hasMany(PackageItem::class, 'service_id');
    }
    public function packageServices()
    {
        return $this->hasMany(PackageService::class, 'service_id');
    }
   

}
