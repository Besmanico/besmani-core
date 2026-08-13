<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProviderSpecialty extends Model
{
    protected $guarded = [];

    protected $casts = ['is_primary' => 'boolean', 'verified_at' => 'datetime'];

    public function provider()
    {
        return $this->belongsTo(ProviderProfile::class, 'provider_profile_id');
    }

    public function specialty()
    {
        return $this->belongsTo(Specialty::class);
    }
}
