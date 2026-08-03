<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class MainUser extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $guarded = [];

    // Define the password field for authentication
    protected $hidden = [
        'password',
    ];

    // Note: Password hashing is handled manually with Hash::make() in controllers
    // Removed 'hashed' cast to avoid conflicts with existing passwords

    public function license_checks()
    {
        // primary key is phone and foreign key is mobile_moaref
        return $this->hasMany(MainUser::class, 'mobile_moaref', 'phone');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'user_id');
    }

    public function InfoActivity()
    {
        return $this->hasMany(InfoActivity::class, 'user_id');
    }

    public function WsPortfolio()
    {
        return $this->hasMany(WsPortfolio::class, 'user_id');
    }

    public function ClinicService()
    {
        return $this->hasMany(ClinicService::class, 'user_id');
    }

    public function WomenServiceSalon()
    {
        return $this->hasMany(WomenServiceSalon::class, 'user_id');
    }

    public function menSalonService()
    {
        return $this->hasMany(MenSalonService::class, 'user_id');
    }

    public function WomenAcademyCourse()
    {
        return $this->hasMany(WomenAcademyCourse::class, 'user_id');
    }

    public function MenAcademyCourse()
    {
        return $this->hasMany(MenAcademyCourse::class, 'user_id');
    }

    public function customer_apis()
    {
        return $this->hasMany(CustomerApi::class, 'user_id');
    }

    public function outgoingReferrals()
    {
        return $this->hasMany(Referral::class, 'referrer_user_id');
    }

    public function incomingReferrals()
    {
        return $this->hasMany(Referral::class, 'receiver_user_id');
    }
} 
