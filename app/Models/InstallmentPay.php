<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstallmentPay extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function user()
    {
        return $this->belongsTo(MainUser::class,'user_id');
    }
    public function cart()
    {
        return $this->belongsTo(Cart::class,'cart_id');
    }
}
