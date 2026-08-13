<?php

namespace App\Repositories;

use App\Models\LegacyEntityMap;
use Illuminate\Database\QueryException;

class LegacyEntityMapRepository
{
    public function map(string $sourceSystem, string $sourceTable, string|int $sourceId, string $targetEntityType, int $targetId, ?int $migrationBatchId = null, array $attributes = []): LegacyEntityMap
    {
        $key = compact('sourceSystem', 'sourceTable', 'sourceId', 'targetEntityType');
        $columns = ['source_system' => $sourceSystem, 'source_table' => $sourceTable, 'source_id' => (string) $sourceId, 'target_entity_type' => $targetEntityType];
        $existing = LegacyEntityMap::where($columns)->first();
        if ($existing) {
            if ((int) $existing->target_id !== $targetId) {
                throw new \DomainException('Legacy source is already mapped to a different target.');
            }

            return $existing;
        }
        try {
            return LegacyEntityMap::create($columns + ['target_id' => $targetId, 'migration_batch_id' => $migrationBatchId, 'migrated_at' => now()] + $attributes);
        } catch (QueryException $e) {
            $existing = LegacyEntityMap::where($columns)->first();
            if ($existing && (int) $existing->target_id === $targetId) {
                return $existing;
            }
            throw $e;
        }
    }

    public function lookup(string $sourceSystem, string $sourceTable, string|int $sourceId, string $targetEntityType): ?LegacyEntityMap
    {
        return LegacyEntityMap::where(['source_system' => $sourceSystem, 'source_table' => $sourceTable, 'source_id' => (string) $sourceId, 'target_entity_type' => $targetEntityType])->first();
    }
}
