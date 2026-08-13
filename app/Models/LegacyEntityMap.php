<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LegacyEntityMap extends Model
{
    protected $guarded = [];

    protected $casts = ['migrated_at' => 'datetime', 'verified_at' => 'datetime'];

    public function batch()
    {
        return $this->belongsTo(MigrationBatch::class, 'migration_batch_id');
    }
}
