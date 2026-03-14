<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequireCustomerPhone
{
    public function handle(Request $request, Closure $next)
    {
        $customer = Auth::guard('webapp')->user();

        if ($customer && empty($customer->mobile)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vui lòng cập nhật số điện thoại trước khi sử dụng tính năng này.',
                    'redirect' => route('webapp.profile'),
                ], 403);
            }

            return redirect()->route('webapp.profile')
                ->with('warning', 'Vui lòng cập nhật số điện thoại để sử dụng tính năng này.');
        }

        return $next($request);
    }
}
