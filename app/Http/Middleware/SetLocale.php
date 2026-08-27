<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = session('locale');
        
        if (!$locale && auth()->check()) {
            $locale = auth()->user()->locale;
            if ($locale) {
                session()->put('locale', $locale);
            }
        }
        
        if ($locale && in_array($locale, ['uz', 'en'])) {
            app()->setLocale($locale);
        } else {
            app()->setLocale(config('app.locale'));
        }
        
        return $next($request);
    }
}
