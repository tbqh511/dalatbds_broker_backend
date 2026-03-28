<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;

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
            return $next($request);
        }

        // Not logged in:
        
        // Allow access to root /webapp so the JS auto-login can run
        if ($request->is('webapp')) {
            return $next($request);
        }
        
        // For any other sub-route, reject
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        return redirect('/webapp');
    }
}
