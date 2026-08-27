<?php

namespace App\Repositories\Eloquent;

use App\Models\Task;
use App\Repositories\Contracts\TaskRepositoryInterface;

class TaskRepository implements TaskRepositoryInterface
{
    public function findById(int $id): ?Task
    {
        return Task::with(['assignee', 'comments.user'])->find($id);
    }

    public function create(array $data): Task
    {
        return Task::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $task = Task::find($id);
        if ($task) {
            return $task->update($data);
        }
        return false;
    }

    public function updateStatus(int $taskId, string $status): bool
    {
        $task = Task::find($taskId);
        if ($task) {
            $task->status = $status;
            return $task->save();
        }
        return false;
    }

    public function getAllTasks()
    {
        return Task::all();
    }
}
