<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopAdmin extends Model
{
    use HasFactory;

    protected $connection = 'shop_mysql';
    protected $table = 'users';
    protected $guarded = [];
 
 
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
