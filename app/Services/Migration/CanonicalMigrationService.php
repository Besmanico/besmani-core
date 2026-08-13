<?php

namespace App\Services\Migration;

use App\Models\MigrationBatch;
use Illuminate\Support\Facades\DB;

class CanonicalMigrationService
{
    public function begin(string $name, ?string $commitSha = null): MigrationBatch
    {
        return MigrationBatch::create(['name' => $name, 'status' => 'running', 'started_at' => now(), 'code_commit_sha' => $commitSha]);
    }

    public function complete(MigrationBatch $batch): MigrationBatch
    {
        return DB::transaction(fn () => tap($batch)->update(['status' => 'completed', 'completed_at' => now()]));
    }

    /** Rollback means feature cutover rollback; canonical records remain for reconciliation. */
    public function rollBack(MigrationBatch $batch, ?string $notes = null): MigrationBatch
    {
        return DB::transaction(fn () => tap($batch)->update(['status' => 'rolled_back', 'completed_at' => now(), 'notes' => $notes]));
    }
}
