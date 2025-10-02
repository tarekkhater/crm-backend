<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OwnCors
{
    protected $allowedOrigins = [
        'https://crm.quantumprime.app',
        'https://trade.quantumprime.app',
        'https://crm.bbstechnology.net',
    ];

    public function handle(Request $request, Closure $next)
    {
        $origin = $request->headers->get('Origin');

        $allowOrigin = in_array($origin, $this->allowedOrigins) ? $origin : '*';
        $allowCredentials = in_array($origin, $this->allowedOrigins) ? 'true' : 'false';

        if (!in_array($origin, $this->allowedOrigins) && $origin) {
            Log::warning('CORS blocked origin detected', [
                'origin' => $origin,
                'path' => $request->path(),
                'ip' => $request->ip(),
                'method' => $request->method(),
            ]);
        }

        $headers = [
            'Access-Control-Allow-Origin' => $allowOrigin,
            'Access-Control-Allow-Methods' => 'GET, POST, PUT, PATCH, DELETE, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, Authorization, X-Requested-With, X-CSRF-TOKEN',
            'Access-Control-Allow-Credentials' => $allowCredentials,
            'Access-Control-Max-Age' => '86400',
        ];

        if ($request->isMethod('OPTIONS')) {
            return response()->noContent(204, $headers);
        }

        try {
            $response = $next($request);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Server error'], 500, $headers);
        }

        foreach ($headers as $key => $value) {
            $response->headers->set($key, $value);
        }

        return $response;
    }
}



/*
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class OwnCors
{
   
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $response->headers->set('Content-Security-Policy', "default-src 'self'; script-src 'self'; object-src 'none'; style-src 'self'; font-src 'self'; connect-src 'self'; img-src 'self'; frame-ancestors 'none';");

        $response->headers->set('Content-Security-Policy', "default-src 'self'; script-src 'self'; object-src 'none';");
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'geolocation=(self), microphone=()');

        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');

        return $response;

    }
}
*/
