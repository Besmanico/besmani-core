<?php

namespace App\Models;

use App\Models\Concerns\HasPublicId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Reconciliation-stage identity record only.
 *
 * Phase 1 authentication remains on User and MainUser; this model must not
 * become an auth provider until an explicitly reviewed cutover phase.
 */
class CanonicalUser extends Model
{
    use HasFactory, HasPublicId, SoftDeletes;

    protected $guarded = [];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = ['email_verified_at' => 'datetime', 'phone_verified_at' => 'datetime', 'last_login_at' => 'datetime', 'password' => 'hashed'];

    public function profile()
    {
        return $this->hasOne(UserProfile::class, 'user_id');
    }

    public function contacts()
    {
        return $this->hasMany(UserContact::class, 'user_id');
    }

    public function consents()
    {
        return $this->hasMany(UserConsent::class, 'user_id');
    }

    public function memberships()
    {
        return $this->hasMany(BusinessMember::class, 'user_id');
    }

    public function businesses()
    {
        return $this->belongsToMany(CanonicalBusiness::class, 'business_members', 'user_id', 'business_id')->withPivot(['business_location_id', 'membership_role_id', 'status']);
    }

    public function providerProfile()
    {
        return $this->hasOne(ProviderProfile::class, 'user_id');
    }
}
