<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Specialty extends Model
{
    protected $guarded = [];

    public function vertical()
    {
        return $this->belongsTo(Vertical::class);
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function providers()
    {
        return $this->belongsToMany(ProviderProfile::class, 'provider_specialties');
    }
}
