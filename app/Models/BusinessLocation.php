<?php

namespace App\Models;

use App\Models\Concerns\HasPublicId;
use Illuminate\Database\Eloquent\Model;

class BusinessLocation extends Model
{
    use HasPublicId;

    protected $guarded = [];

    protected $casts = ['is_primary' => 'boolean', 'is_virtual' => 'boolean', 'latitude' => 'decimal:7', 'longitude' => 'decimal:7'];

    public function business()
    {
        return $this->belongsTo(CanonicalBusiness::class, 'business_id');
    }

    public function members()
    {
        return $this->hasMany(BusinessMember::class);
    }
}
