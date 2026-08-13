<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IdentityMergeCandidate extends Model
{
    protected $guarded = [];

    protected $casts = ['reasons_json' => 'array', 'reviewed_at' => 'datetime'];
}
