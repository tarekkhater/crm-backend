<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DynamicCors
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // نجيب origin اللي جاي منه request
        $origin = $request->headers->get('Origin');

        // لو مفيش origin، نخلي request عادي
        if (!$origin) {
            return $response;
        }

        // قائمة الـ origins المسموح بيهم (ممكن تجيبها من config أو .env)
        $allowedOrigins = [
            '*',
            'https://crm.quantumprime.app/',
            'https://dashboard.bbstechnology.net',
            'https://app.bbstechnology.net',
        ];

        // لو الـ origin مش موجود في القائمة، مانبعثش headers
        if (!in_array($origin, $allowedOrigins)) {
            return $response;
        }

        // مفتاح الكاش لكل origin
        $cacheKey = 'cors_sent_' . md5($origin);

        // لو headers متبعتش من فترة، نبعته ونخزنه في الكاش
        if (!Cache::has($cacheKey)) {
            $response->headers->set('Access-Control-Allow-Origin', $origin);
            $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization');

            // خزنه في الكاش لمدة دقيقة (أو أي مدة تناسبك)
            Cache::put($cacheKey, true, 600);
        }

        return $response;
    }
}
