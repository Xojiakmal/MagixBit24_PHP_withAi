<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class TelegramWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $update = $request->all();
        Log::info('Telegram Webhook Received', $update);

        if (isset($update['message']['text'])) {
            $text = $update['message']['text'];
            $chatId = $update['message']['chat']['id'];
            $username = $update['message']['from']['username'] ?? null;
            $firstName = $update['message']['from']['first_name'] ?? 'Telegram User';

            // Handle /start <token>
            if (str_starts_with($text, '/start ')) {
                $token = explode(' ', $text)[1] ?? null;
                
                if ($token) {
                    $status = Cache::get('login_token_' . $token);
                    
                    if ($status === 'pending') {
                        // Find or Create the User
                        $user = User::firstOrCreate(
                            ['telegram_chat_id' => $chatId],
                            [
                                'name' => $firstName,
                                'email' => $chatId . '@telegram.local',
                                'password' => Hash::make(Str::random(24)),
                                'telegram_username' => $username,
                                'email_verified_at' => now(),
                            ]
                        );
                        
                        // Update cache with the user ID so the frontend can log them in
                        Cache::put('login_token_' . $token, $user->id, now()->addMinutes(5));
                        
                        // Send success message back to Telegram
                        $this->sendMessage($chatId, "✅ Tizimga muvaffaqiyatli kirdingiz! Sayt sahifasiga qaytishingiz mumkin.");
                        
                        Log::info("User {$user->id} authenticated via bot for token {$token}");
                    } else {
                        $this->sendMessage($chatId, "❌ Xatolik yoki eskirgan token. Iltimos saytdan yangilap qayta urinib ko'ring.");
                    }
                }
            }

            // Handle /setstorage <unique_link>
            if (str_starts_with($text, '/setstorage ')) {
                $uniqueLink = explode(' ', $text)[1] ?? null;
                
                if ($uniqueLink) {
                    $tenant = \App\Models\Tenant::where('unique_link', $uniqueLink)->first();
                    if ($tenant) {
                        $tenant->storage_chat_id = (string) $chatId;
                        $tenant->save();
                        $this->sendMessage($chatId, "✅ Ushbu guruh \"{$tenant->name}\" kompaniyasi uchun fayl ombori (Storage) sifatida belgilandi!");
                    } else {
                        $this->sendMessage($chatId, "❌ Kechirasiz, bunday kompaniya topilmadi.");
                    }
                }
            }
        }

        return response()->json(['status' => 'ok']);
    }
    
    private function sendMessage($chatId, $text)
    {
        $botToken = env('TELEGRAM_BOT_TOKEN');
        if (!$botToken) return;
        
        $url = "https://api.telegram.org/bot{$botToken}/sendMessage";
        
        $data = [
            'chat_id' => $chatId,
            'text' => $text,
        ];
        
        $options = [
            'http' => [
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data),
            ],
        ];
        
        $context  = stream_context_create($options);
        @file_get_contents($url, false, $context);
    }
}
