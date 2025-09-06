<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\InfoActivity;

class MainUser extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function products()
    {
        return $this->hasMany(Product::class,'user_id');
    }
    public function InfoActivity()
    {
        return $this->hasMany(InfoActivity::class,'user_id'); 
    }
}
