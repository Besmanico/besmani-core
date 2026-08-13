<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessMember extends Model
{
    protected $guarded = [];

    protected static function booted(): void
    {
        static::saving(function (BusinessMember $member): void {
            $member->membership_scope_key = $member->business_location_id
                ? 'location:'.$member->business_location_id
                : 'business';
        });
    }

    protected $casts = ['starts_at' => 'datetime', 'ends_at' => 'datetime', 'is_primary' => 'boolean'];

    public function business()
    {
        return $this->belongsTo(CanonicalBusiness::class, 'business_id');
    }

    public function user()
    {
        return $this->belongsTo(CanonicalUser::class, 'user_id');
    }

    public function location()
    {
        return $this->belongsTo(BusinessLocation::class, 'business_location_id');
    }

    public function role()
    {
        return $this->belongsTo(MembershipRole::class, 'membership_role_id');
    }
}
