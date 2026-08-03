<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferralPartner extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'total_sent' => 'integer',
        'total_received' => 'integer',
        'total_earned_tokens' => 'integer',
        'total_given_tokens' => 'integer',
        'last_referral_at' => 'datetime',
    ];

    public function ownerUser()
    {
        return $this->belongsTo(MainUser::class, 'owner_user_id');
    }

    public function ownerBusiness()
    {
        return $this->belongsTo(InfoActivity::class, 'owner_business_id');
    }

    public function partnerUser()
    {
        return $this->belongsTo(MainUser::class, 'partner_user_id');
    }

    public function partnerBusiness()
    {
        return $this->belongsTo(InfoActivity::class, 'partner_business_id');
    }
}
