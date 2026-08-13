<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vertical extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function businessTypes()
    {
        return $this->hasMany(BusinessType::class);
    }

    public function businesses()
    {
        return $this->belongsToMany(CanonicalBusiness::class, 'business_verticals', 'vertical_id', 'business_id');
    }

    public function specialties()
    {
        return $this->hasMany(Specialty::class);
    }
}
