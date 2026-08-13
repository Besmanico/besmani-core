<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessType extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function vertical()
    {
        return $this->belongsTo(Vertical::class);
    }

    public function businesses()
    {
        return $this->hasMany(CanonicalBusiness::class);
    }
}
