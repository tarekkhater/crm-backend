<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class CheckUserToken
{
    public function handle($request, Closure $next)
    {
        $user = Auth::guard('api')->user();

        if ($user) {
            $cachedToken = Cache::get('user_token_'.$user->id);
            if ($cachedToken != $request->bearerToken()) {
                return response()->json([
                    'status' => 403,
                    'message' => 'This account is logged in from another device.'
                ], 403);
            }
        }

        return $next($request);
    }
}
