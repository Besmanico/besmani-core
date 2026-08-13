<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProviderProfile extends Model
{
    protected $guarded = [];

    protected $casts = ['accepting_clients' => 'boolean'];

    public function user()
    {
        return $this->belongsTo(CanonicalUser::class, 'user_id');
    }

    public function specialties()
    {
        return $this->belongsToMany(Specialty::class, 'provider_specialties')->withPivot(['is_primary', 'years_experience', 'verified_at']);
    }

    public function licenses()
    {
        return $this->hasMany(ProviderLicense::class);
    }
}
