<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class BotLoginController extends Controller
{
    public function show(Request $request)
    {
        // Agar foydalanuvchi allaqachon tizimga kirgan bo'lsa, tegishli panelga yo'naltirish
        if (Auth::check()) {
            $user = Auth::user();
            $redirectUrl = route('dashboard'); // fallback
            
            if (!env('USE_SUBDOMAINS', false)) {
                if ($user->hasRole('SuperAdmin')) {
                    $redirectUrl = url('/admin/dashboard');
                } elseif (!$user->current_tenant_id) {
                    $redirectUrl = url('/company/dashboard');
                }
            }
            return redirect($redirectUrl);
        }

        // Generate a unique token for this session
        $loginToken = Str::random(32);
        
        // Store it in cache for 10 minutes (unauthenticated state)
        Cache::put('login_token_' . $loginToken, 'pending', now()->addMinutes(10));
        
        // Store the intent path so we know where to redirect after auth
        Cache::put('login_intent_' . $loginToken, $request->path(), now()->addMinutes(10));
        
        // Ensure you change 'YourBotUsername' to the actual bot username
        $botUsername = env('TELEGRAM_BOT_USERNAME', 'WipeBitrixBot'); // Using a dummy default

        return view('auth.login', compact('loginToken', 'botUsername'));
    }

    public function check(Request $request, $token)
    {
        $status = Cache::get('login_token_' . $token);
        
        if (!$status) {
            return response()->json(['status' => 'expired']);
        }
        
        if ($status !== 'pending') {
            // The status is the user ID stored by the webhook
            $user = User::find($status);
            if ($user) {
                Auth::login($user);
                
                $intentPath = Cache::get('login_intent_' . $token);
                
                // Clear the caches
                Cache::forget('login_token_' . $token);
                Cache::forget('login_intent_' . $token);
                
                // Smart Redirect Logic based on the endpoint they visited
                $redirectUrl = route('dashboard'); // fallback
                
                if (!env('USE_SUBDOMAINS', false)) {
                    if ($intentPath === 'admin/login') {
                        $redirectUrl = url('/admin/dashboard');
                    } elseif ($intentPath === 'company/login') {
                        $redirectUrl = url('/company/dashboard');
                        session(['registration_mode' => 'create']);
                    } else {
                        // Regular login or any other
                        $redirectUrl = url('/dashboard');
                        session(['registration_mode' => 'join']);
                    }
                }
                
                return response()->json([
                    'status' => 'authenticated', 
                    'redirect' => $redirectUrl
                ])->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
            }
        }
        
        return response()->json(['status' => 'pending'])->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
    }
}
