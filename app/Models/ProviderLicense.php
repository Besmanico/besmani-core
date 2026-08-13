<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProviderLicense extends Model
{
    protected $guarded = [];

    protected $casts = ['issued_at' => 'date', 'expires_at' => 'date', 'license_number' => 'encrypted'];

    public function provider()
    {
        return $this->belongsTo(ProviderProfile::class, 'provider_profile_id');
    }
}
