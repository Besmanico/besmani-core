<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserConsent extends Model
{
    protected $guarded = [];

    protected $casts = ['accepted_at' => 'datetime', 'revoked_at' => 'datetime', 'metadata_json' => 'array'];

    public function user()
    {
        return $this->belongsTo(CanonicalUser::class, 'user_id');
    }
}
