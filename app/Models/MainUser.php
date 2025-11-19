<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Product;
use App\Models\InfoActivity;

class MainUser extends Authenticatable
{
    use HasFactory;
    protected $guarded = [];
    
    // Define the password field for authentication
    protected $hidden = [
        'password',
    ];
    
    // Cast password as hashed
     protected $casts = [
        'password' => 'hashed',
    ];

    public function license_checks()
    {
        // primary key is phone and foreign key is mobile_moaref
        return $this->hasMany(MainUser::class,'mobile_moaref','phone');
    }
    public function products()
    {
        return $this->hasMany(Product::class,'user_id');
    }
    public function InfoActivity()
    {
        return $this->hasMany(InfoActivity::class,'user_id'); 
    }
    public function WsPortfolio()
    {
        return $this->hasMany(WsPortfolio::class,'user_id'); 
    }
    public function ClinicService()
    {
        return $this->hasMany(ClinicService::class, 'user_id'); 
    }
    public function WomenServiceSalon()
    {
        return $this->hasMany(WomenServiceSalon::class,'user_id'); 
    } 
    public function menSalonService()
    {
        return $this->hasMany(MenSalonService::class,'user_id'); 
    } 
    public function WomenAcademyCourse()
    {
        return $this->hasMany(WomenAcademyCourse::class,'user_id'); 
    } 
    public function MenAcademyCourse()
    {
        return $this->hasMany(MenAcademyCourse::class,'user_id'); 
    } 
}
 