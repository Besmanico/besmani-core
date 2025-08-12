<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhoneCountry extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function province()
    {
        return $this->hasMany(Province::class,'phone_country_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function provinces()
    {
        return $this->hasMany(Province::class,'phone_country_id');
    }
} 
