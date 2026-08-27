<?php

namespace App\Listeners;

use App\Events\TaskCompleted;
use App\Services\TelegramService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendTaskCompletedNotification implements ShouldQueue
{
    public function __construct(
        protected TelegramService $telegramService
    ) {}

    public function handle(TaskCompleted $event): void
    {
        $task = $event->task;
        $creator = $task->creator;

        if ($creator && $creator->telegram_chat_id) {
            $assigneeName = $task->assignee ? $task->assignee->name : 'Noma\'lum xodim';
            
            $message = "✅ <b>Vazifa yakunlandi!</b>\n\n";
            $message .= "<b>📌 Vazifa:</b> {$task->title}\n";
            $message .= "<b>👤 Bajaruvchi:</b> {$assigneeName}\n";

            $this->telegramService->sendMessage($creator->telegram_chat_id, $message);
        }
    }
}
