<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureCompanySelected
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        if ($user && !$user->is_superadmin) {
            if (!$user->current_tenant_id) {
                // If they have a pending request, send to pending page
                $hasPending = \App\Models\TenantUser::where('user_id', $user->id)->where('status', 'pending')->exists();
                
                if ($hasPending) {
                    if (!$request->routeIs('company.pending')) {
                        return redirect()->route('company.pending');
                    }
                } else {
                    if (!$request->routeIs('company.select') && !$request->routeIs('company.*')) {
                        return redirect()->route('company.select');
                    }
                }
            }
        }
        return $next($request);
    }
}
