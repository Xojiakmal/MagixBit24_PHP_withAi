<?php

namespace App\Actions\Project;

use App\Repositories\Contracts\TaskRepositoryInterface;
use App\Events\TaskCompleted;

class ChangeTaskStatusAction
{
    public function __construct(
        protected TaskRepositoryInterface $taskRepository
    ) {}

    public function execute(int $taskId, string $newStatus): bool
    {
        $task = $this->taskRepository->findById($taskId);
        if (!$task) return false;

        $oldStatus = $task->status;
        $updated = $this->taskRepository->updateStatus($taskId, $newStatus);

        if ($updated && $newStatus === 'done' && $oldStatus !== 'done') {
            event(new TaskCompleted($task));
        }

        return $updated;
    }
}
