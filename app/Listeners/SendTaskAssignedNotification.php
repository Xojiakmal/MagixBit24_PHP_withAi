<?php

namespace App\Listeners;

use App\Events\TaskAssigned;
use App\Services\TelegramService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendTaskAssignedNotification implements ShouldQueue
{
    public function __construct(
        protected TelegramService $telegramService
    ) {}

    public function handle(TaskAssigned $event): void
    {
        $task = $event->task;
        $assignee = $task->assignee;

        if ($assignee && $assignee->telegram_chat_id) {
            $message = "🔔 <b>Sizga yangi vazifa biriktirildi!</b>\n\n";
            $message .= "<b>📌 Vazifa:</b> {$task->title}\n";
            $message .= "<b>🔴 Ustuvorlik:</b> " . ucfirst($task->priority) . "\n";
            
            if ($task->due_date) {
                $message .= "<b>📅 Muddat:</b> {$task->due_date}\n";
            }

            $this->telegramService->sendMessage($assignee->telegram_chat_id, $message);
        }
    }
}
