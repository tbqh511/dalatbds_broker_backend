<?php

namespace App\Http\Middleware;

use Closure;
// use Illuminate\Console\View\Components\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Alert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class DemoMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // DEMO_MODE has been removed — always pass through
        return $next($request);
    }
}
