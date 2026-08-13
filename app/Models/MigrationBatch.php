<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MigrationBatch extends Model
{
    protected $guarded = [];

    protected $casts = ['started_at' => 'datetime', 'completed_at' => 'datetime'];

    public function maps()
    {
        return $this->hasMany(LegacyEntityMap::class);
    }
}
