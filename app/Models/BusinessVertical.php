<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessVertical extends Model
{
    protected $guarded = [];

    protected $casts = ['is_primary' => 'boolean'];

    public function business()
    {
        return $this->belongsTo(CanonicalBusiness::class, 'business_id');
    }

    public function vertical()
    {
        return $this->belongsTo(Vertical::class);
    }
}
