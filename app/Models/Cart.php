<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function user()
    {
        return $this->belongsTo(MainUser::class,'user_id');
    }
    public function service()
    {
        return $this->belongsTo(Service::class,'service_id');
    }
    public function packageService()
    {
        return $this->belongsTo(PackageService::class,'package_service_id');
    } 
}
