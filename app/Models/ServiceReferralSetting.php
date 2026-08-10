<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceReferralSetting extends Model
{
    protected $guarded = [];

    protected $casts = [
        'enabled' => 'boolean',
        'reward_bc' => 'integer',
        'discount_value' => 'decimal:2',
    ];
}
