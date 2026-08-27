<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SetTelegramWebhook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:set-webhook {url?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set the Telegram Webhook URL based on APP_URL or provided URL';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $botToken = env('TELEGRAM_BOT_TOKEN');
        
        if (!$botToken) {
            $this->error('TELEGRAM_BOT_TOKEN is not set in .env');
            return Command::FAILURE;
        }

        $url = $this->argument('url') ?? env('APP_URL');

        if (!$url || $url === 'http://localhost') {
            $this->error('Please provide a valid URL or set APP_URL in .env. It must be HTTPS.');
            return Command::FAILURE;
        }

        // Ensure URL uses HTTPS
        if (!str_starts_with($url, 'https://')) {
            $this->warn('Telegram requires HTTPS. Changing URL to https://');
            $url = str_replace('http://', 'https://', $url);
        }

        // Clean trailing slash
        $url = rtrim($url, '/');
        $webhookUrl = $url . '/telegram/webhook';

        $this->info("Setting webhook to: $webhookUrl");

        $response = Http::post("https://api.telegram.org/bot{$botToken}/setWebhook", [
            'url' => $webhookUrl
        ]);

        if ($response->successful() && $response->json('ok')) {
            $this->info('Webhook set successfully!');
            $this->line($response->json('description'));
            return Command::SUCCESS;
        }

        $this->error('Failed to set webhook:');
        $this->error($response->body());
        
        return Command::FAILURE;
    }
}
