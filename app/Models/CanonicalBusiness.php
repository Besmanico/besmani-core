<?php

namespace App\Models;

use App\Models\Concerns\HasPublicId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CanonicalBusiness extends Model
{
    use HasFactory,HasPublicId,SoftDeletes;

    protected $table = 'businesses_v2';

    protected $guarded = [];

    public function owner()
    {
        return $this->belongsTo(CanonicalUser::class, 'owner_user_id');
    }

    public function type()
    {
        return $this->belongsTo(BusinessType::class, 'business_type_id');
    }

    public function locations()
    {
        return $this->hasMany(BusinessLocation::class, 'business_id');
    }

    public function members()
    {
        return $this->hasMany(BusinessMember::class, 'business_id');
    }

    public function users()
    {
        return $this->belongsToMany(CanonicalUser::class, 'business_members', 'business_id', 'user_id');
    }

    public function verticals()
    {
        return $this->belongsToMany(Vertical::class, 'business_verticals', 'business_id', 'vertical_id')->withPivot(['is_primary', 'status']);
    }

    public function settings()
    {
        return $this->hasMany(BusinessSetting::class, 'business_id');
    }
}
