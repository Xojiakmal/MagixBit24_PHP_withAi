<?php

namespace App\Actions\CRM;

use App\Repositories\Contracts\DealRepositoryInterface;

class UpdateDealStageAction
{
    public function __construct(
        protected DealRepositoryInterface $dealRepository
    ) {}

    public function execute(int $dealId, int $newStageId): bool
    {
        // Later: Add authorization, fire events (DealStageChanged)
        return $this->dealRepository->updateStage($dealId, $newStageId);
    }
}
