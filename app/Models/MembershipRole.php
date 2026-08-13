<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MembershipRole extends Model
{
    protected $guarded = [];

    public function members()
    {
        return $this->hasMany(BusinessMember::class);
    }
}
