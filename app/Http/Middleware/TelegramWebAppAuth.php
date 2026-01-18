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
        // TEMPORARY: Dev mode to allow running WebApp outside Telegram for UI development
        if (env('WEBAPP_DEV_MODE', false)) {
            if (!Auth::guard('webapp')->check()) {
                $devCustomer = null;

                $devCustomerId = env('WEBAPP_DEV_CUSTOMER_ID');
                if ($devCustomerId) {
                    $devCustomer = Customer::find($devCustomerId);
                }

                if (!$devCustomer) {
                    $devCustomer = Customer::first();
                }

                if ($devCustomer) {
                    Auth::guard('webapp')->login($devCustomer, true);
                }
            }

            return $next($request);
        }

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
