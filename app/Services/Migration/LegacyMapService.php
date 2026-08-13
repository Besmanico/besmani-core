<?php

namespace App\Services\Migration;

use App\Models\LegacyEntityMap;
use App\Repositories\LegacyEntityMapRepository;

class LegacyMapService
{
    public function __construct(private LegacyEntityMapRepository $maps) {}

    public function map(string $sourceSystem, string $sourceTable, string|int $sourceId, string $targetEntityType, int $targetId, ?int $migrationBatchId = null, array $attributes = []): LegacyEntityMap
    {
        return $this->maps->map($sourceSystem, $sourceTable, $sourceId, $targetEntityType, $targetId, $migrationBatchId, $attributes);
    }

    public function lookup(string $sourceSystem, string $sourceTable, string|int $sourceId, string $targetEntityType): ?LegacyEntityMap
    {
        return $this->maps->lookup($sourceSystem, $sourceTable, $sourceId, $targetEntityType);
    }
}
