<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function cart()
    {
        return $this->belongsTo(Cart::class, 'cart_id');
    }

    public function user()
    {
        return $this->belongsTo(MainUser::class, 'user_id');
    }

    public function installmentPays()
    {
        return $this->hasMany(InstallmentPay::class, 'cart_id', 'cart_id');
    }
}
