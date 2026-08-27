<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminTwoFactorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        
        if ($user) {
            // Check if they passed Local Admin 2FA
            if (!session('admin_verified', false)) {
                // Ignore routes for local auth so we don't loop
                if (!$request->routeIs('admin.local.*')) {
                    return redirect()->route('admin.local.login');
                }
            }
        }

        return $next($request);
    }
}
