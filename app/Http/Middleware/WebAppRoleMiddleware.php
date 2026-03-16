<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WebAppRoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): mixed
    {
        $user = Auth::guard('webapp')->user();

        if (!$user || !$user->hasRole(...$roles)) {
            if ($request->ajax()) {
                return response()->json(['message' => 'Không có quyền truy cập'], 403);
            }
            return redirect()->route('webapp')->with('error', 'Không có quyền truy cập');
        }

        return $next($request);
    }
}
