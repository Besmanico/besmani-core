<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserContact extends Model
{
    protected $guarded = [];

    protected $casts = ['is_primary' => 'boolean', 'verified_at' => 'datetime'];

    public function user()
    {
        return $this->belongsTo(CanonicalUser::class, 'user_id');
    }
}
