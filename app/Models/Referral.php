<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Referral extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'token_amount' => 'integer',
        'referral_reward_bc' => 'integer',
        'customer_discount_value' => 'decimal:2',
        'referral_terms_snapshot_at' => 'datetime',
        'expiration_date' => 'date',
        'accepted_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'settled_at' => 'datetime',
    ];

    public function referrerUser()
    {
        return $this->belongsTo(MainUser::class, 'referrer_user_id');
    }

    public function referrerBusiness()
    {
        return $this->belongsTo(InfoActivity::class, 'referrer_business_id');
    }

    public function receiverUser()
    {
        return $this->belongsTo(MainUser::class, 'receiver_user_id');
    }

    public function receiverBusiness()
    {
        return $this->belongsTo(InfoActivity::class, 'receiver_business_id');
    }

    public function customerUser()
    {
        return $this->belongsTo(MainUser::class, 'customer_user_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function statusHistories()
    {
        return $this->hasMany(ReferralStatusHistory::class);
    }

    public function tokenLedgerEntries()
    {
        return $this->hasMany(TokenLedger::class);
    }
}
