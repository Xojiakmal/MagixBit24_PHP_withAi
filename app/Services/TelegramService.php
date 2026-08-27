<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    protected string $botToken;
    protected string $apiUrl;

    public function __construct()
    {
        $this->botToken = config('services.telegram.bot_token') ?? env('TELEGRAM_BOT_TOKEN', '');
        $this->apiUrl = "https://api.telegram.org/bot{$this->botToken}/";
    }

    public function sendMessage(string $chatId, string $message): bool
    {
        if (empty($this->botToken) || empty($chatId)) {
            Log::warning("Telegram bot token or chat ID is missing. Message not sent.");
            return false;
        }

        try {
            $response = Http::post($this->apiUrl . 'sendMessage', [
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => 'HTML',
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error("Failed to send Telegram message: " . $e->getMessage());
            return false;
        }
    }
}
