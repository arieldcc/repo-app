<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class ThrottleBots
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ua = strtolower($request->userAgent());

        if (str_contains($ua, 'bot') || str_contains($ua, 'crawl') || str_contains($ua, 'spider')) {
            $key = 'bot:' . md5($ua);
            if (RateLimiter::tooManyAttempts($key, 10)) {
                abort(429, 'Terlalu banyak permintaan dari bot.');
            }

            RateLimiter::hit($key, 60); // 10 attempt per 60 seconds
        }

        return $next($request);
    }
}
