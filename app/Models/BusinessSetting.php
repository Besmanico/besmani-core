<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessSetting extends Model
{
    protected $guarded = [];

    protected $casts = ['value_json' => 'array'];

    public function business()
    {
        return $this->belongsTo(CanonicalBusiness::class, 'business_id');
    }
}
