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
    
}
