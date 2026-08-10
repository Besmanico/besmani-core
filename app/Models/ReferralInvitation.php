<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferralInvitation extends Model
{
    protected $guarded = [];

    protected $hidden = ['token_hash'];

    protected $casts = [
        'sent_at' => 'datetime',
        'accepted_at' => 'datetime',
        'expires_at' => 'datetime',
    ];
}
