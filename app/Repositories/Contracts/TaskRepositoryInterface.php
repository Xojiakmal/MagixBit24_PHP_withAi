<?php

namespace App\Repositories\Contracts;

use App\Models\Task;

interface TaskRepositoryInterface
{
    public function findById(int $id): ?Task;
    public function create(array $data): Task;
    public function update(int $id, array $data): bool;
    public function updateStatus(int $taskId, string $status): bool;
    public function getAllTasks();
}
