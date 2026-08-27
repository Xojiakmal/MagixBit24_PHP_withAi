<?php

namespace App\Repositories\Contracts;

use App\Models\Deal;

interface DealRepositoryInterface
{
    public function findById(int $id): ?Deal;
    public function create(array $data): Deal;
    public function update(int $id, array $data): bool;
    public function updateStage(int $dealId, int $stageId): bool;
    public function getDealsByPipeline(int $pipelineId);
}
