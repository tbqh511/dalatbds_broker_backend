<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TelegramWebAppAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if user is logged in via 'webapp' guard
        if (Auth::guard('webapp')->check()) {
            // User is authenticated, proceed
            return $next($request);
        }

        // If not logged in:
        
        // 1. Allow access to the root /webapp so the JS can run and perform login
        // We check strict equality or pattern matching
        if ($request->is('webapp')) {
             return $next($request);
        }
        
        // 2. For any other sub-route (e.g. /webapp/profile), redirect to /webapp
        // to force the initial Telegram check
        return redirect('/webapp');
    }
}
