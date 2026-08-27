<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TelegramAuthController extends Controller
{
    public function handleTelegramCallback(Request $request)
    {
        // Simple verification for demonstration (in production, verify the hash with BOT_TOKEN)
        $telegramId = $request->get('id');
        $username = $request->get('username');
        $firstName = $request->get('first_name');
        
        if (!$telegramId) {
            return redirect()->route('login')->with('error', 'Telegram authentication failed.');
        }

        // Check if SuperAdmin registration is allowed (if no SuperAdmin exists)
        $isSuperAdmin = !User::where('is_superadmin', true)->exists();

        // Check if user exists by telegram_chat_id or create new
        $user = User::firstOrCreate(
            ['telegram_chat_id' => $telegramId],
            [
                'name' => $firstName ?? $username ?? 'Telegram User',
                'email' => $telegramId . '@telegram.local', // Dummy email required by Laravel
                'password' => Hash::make(Str::random(24)),
                'telegram_username' => $username,
                'is_superadmin' => $isSuperAdmin,
                'email_verified_at' => now(),
            ]
        );

        Auth::login($user);

        if ($user->is_superadmin) {
            // Redirect to the admin subdomain
            return redirect()->route('superadmin.dashboard');
        }

        return redirect()->route('dashboard');
    }
}
