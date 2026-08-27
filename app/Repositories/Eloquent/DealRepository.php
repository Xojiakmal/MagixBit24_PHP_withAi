<?php

namespace App\Repositories\Eloquent;

use App\Models\Deal;
use App\Repositories\Contracts\DealRepositoryInterface;

class DealRepository implements DealRepositoryInterface
{
    public function findById(int $id): ?Deal
    {
        return Deal::find($id);
    }

    public function create(array $data): Deal
    {
        return Deal::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $deal = Deal::find($id);
        if ($deal) {
            return $deal->update($data);
        }
        return false;
    }

    public function updateStage(int $dealId, int $stageId): bool
    {
        $deal = Deal::find($dealId);
        if ($deal) {
            $deal->pipeline_stage_id = $stageId;
            return $deal->save();
        }
        return false;
    }

    public function getDealsByPipeline(int $pipelineId)
    {
        return Deal::whereHas('stage', function($q) use ($pipelineId) {
            $q->where('pipeline_id', $pipelineId);
        })->get();
    }
}
