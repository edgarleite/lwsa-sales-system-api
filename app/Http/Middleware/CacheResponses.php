<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class CacheResponses
{
    public function handle(Request $request, Closure $next, $ttl = 60): Response
    {
        // Não cachear em ambiente local ou para métodos não-GET
        if (app()->environment('local') || !$request->isMethod('GET')) {
            return $next($request);
        }

        $key = 'route_'.sha1($request->url());

        return Cache::remember($key, now()->addMinutes($ttl), function () use ($request, $next) {
            $response = $next($request);
            
            // Adiciona header para identificar que a resposta veio do cache
            return $response->header('X-Cache', 'HIT');
        });
    }
}