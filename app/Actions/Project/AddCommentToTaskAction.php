<?php

namespace App\Actions\Project;

use App\Models\TaskComment;
use App\Models\Task;

class AddCommentToTaskAction
{
    public function execute(Task $task, string $commentText, int $userId): TaskComment
    {
        return TaskComment::create([
            'task_id' => $task->id,
            'user_id' => $userId,
            'comment' => $commentText,
        ]);
    }
}
