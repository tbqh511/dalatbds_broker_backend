<?php

namespace App\Http\Middleware;

use App\Models\Customer;
use App\Models\User;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $token = JWTAuth::getToken();
            if($token == ''){
                return response()->json([
                    'error' => true,
                    'message' => 'Authorization Token not found'
                ],401);
            }
            
            // Use getPayload to verify signature and get claims
            $payload = JWTAuth::getPayload($token);
            $authModel = $payload->get('auth_model') ?? 'customer'; 
            
            Log::info("JwtMiddleware: Token payload auth_model: " . $authModel);

            if ($authModel === 'user') {
                // User Logic (Stateless JWT)
                // Use manual resolution to avoid provider config complexity
                $userId = $payload->get('sub');
                Log::info("JwtMiddleware: User ID from sub: " . $userId);

                $user = \App\Models\User::find($userId);

                if (!$user) {
                    Log::error("JwtMiddleware: User not found in DB: " . $userId);
                    return response()->json(['error' => true, 'message' => 'User not found'], 404);
                }
                
                // Check status if column exists
                if (isset($user->status) && $user->status == 0) { 
                     return response()->json(['error' => true, 'message' => 'Account inactive'], 401);
                }
                
                // Set the user for the request and default guard
                Auth::guard('web')->setUser($user);
                $request->setUserResolver(function () use ($user) {
                    return $user;
                });
                
            } else {
                // Customer Logic (Stateful JWT via api_token check)
                // Set the provider for this request to 'customers' (webapp guard)
                Config::set('auth.defaults.guard', 'webapp');
                Config::set('jwt.user', \App\Models\Customer::class);

                // For backward compatibility or specific payload structure
                $userId = $payload->get('customer_id') ?? $payload->get('sub');
                $res = Customer::find($userId);
                
                if(!empty($res)){
                    if($res->api_token != $token){
                        return response()->json([
                            'error' => true,
                            'message' => 'Unauthorized access'
                        ],401);
                    } else {
                        if($res->isActive == 0){
                            return response()->json([
                                'error' => true,
                                'message' => 'your account has been deactive! please contact admin'
                            ],401);
                        }
                    }
                    // Set user
                    Auth::guard('webapp')->setUser($res);
                    $request->setUserResolver(function () use ($res) {
                        return $res;
                    });
                } else {
                    return response()->json([
                        'error' => true,
                        'message' => 'Unauthorized access'
                    ],401);
                }
            }

        } catch (Exception $e) {
            Log::error("JwtMiddleware Error: " . $e->getMessage());
            
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                return response()->json([
                    'error' => true,
                    'message' => 'Token is Invalid'
                ],401);
            } else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                return response()->json([
                    'error' => true,
                    'message' => 'Token is Expired'
                ],401);
            } else{
                return response()->json([
                    'error' => true,
                    'message' => 'Authorization Token not found: ' . $e->getMessage()
                ],401);
            }
        }
        return $next($request);
    }
}
