<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferralStatusHistory extends Model
{
    use HasFactory;

    public const UPDATED_AT = null;

    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function referral()
    {
        return $this->belongsTo(Referral::class);
    }

    public function changedByUser()
    {
        return $this->belongsTo(MainUser::class, 'changed_by_user_id');
    }
}
