<?php

namespace App\Actions\Project;

use App\Repositories\Contracts\TaskRepositoryInterface;
use App\DTOs\Project\TaskData;
use App\Models\Task;
use App\Events\TaskAssigned;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CreateTaskAction
{
    public function __construct(
        protected TaskRepositoryInterface $taskRepository
    ) {}

    public function execute(TaskData $taskData): Task
    {
        return DB::transaction(function () use ($taskData) {
            $createdTasks = [];
            
            // Handle multiple assignees
            $assignees = is_array($taskData->assigned_to) ? $taskData->assigned_to : [$taskData->assigned_to];
            $departments = is_array($taskData->assigned_to_department_id) ? $taskData->assigned_to_department_id : [$taskData->assigned_to_department_id];

            if ($taskData->assigned_to_everyone) {
                // If everyone, we just create one task for everyone (assigned_to_everyone = true)
                $assignees = [null];
                $departments = [null];
            }

            foreach ($departments as $departmentId) {
                foreach ($assignees as $assigneeId) {
                    $task = $this->taskRepository->create([
                        'title' => $taskData->title,
                        'deal_id' => $taskData->deal_id,
                        'description' => $taskData->description,
                        'status' => $taskData->status,
                        'priority' => $taskData->priority,
                        'start_date' => $taskData->start_date,
                        'due_date' => $taskData->due_date,
                        'assigned_to' => $assigneeId,
                        'assigned_to_department_id' => $departmentId,
                        'assigned_to_everyone' => $taskData->assigned_to_everyone,
                        'created_by' => $taskData->created_by,
                        'observers' => $taskData->observers,
                        'available_to_everyone' => $taskData->available_to_everyone,
                    ]);

                    if ($task->assigned_to) {
                        event(new TaskAssigned($task));
                    }

                    $createdTasks[] = $task;
                }
            }

            // Just return the first created task for the action signature
            return $createdTasks[0] ?? new Task();
        });
    }
}
