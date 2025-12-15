<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            
            if (!$user) {
                return response()->json(['error' => true, 'message' => 'User not found'], 404);
            }

            // Check if user has one of the required roles
            // If the user's role is not in the allowed list, deny access
            // Default role is 'customer' if column is missing or null
            $userRole = $user->role ?? 'customer';

            if (!in_array($userRole, $roles)) {
                return response()->json([
                    'error' => true, 
                    'message' => 'Unauthorized. Required role: ' . implode(', ', $roles)
                ], 403);
            }

        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => 'Token invalid or expired'], 401);
        }

        return $next($request);
    }
}
