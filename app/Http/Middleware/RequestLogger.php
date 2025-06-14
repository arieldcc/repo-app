<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;
use Jenssegers\Agent\Agent;

class RequestLogger
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Awal timestamp
        $start = microtime(true);

        // Proses request
        $response = $next($request);

        // Akhir timestamp
        $end = microtime(true);
        $duration = round(($end - $start) * 1000, 2); // dalam ms

        // Dapatkan user jika login
        $user = Auth::user();

        // IP Handling
        $ip = $request->ip() === '::1' ? '127.0.0.1' : $request->ip();

        // Agent
        $agent = new Agent();

        // Simpan log dalam bentuk JSON
        $log = [
            'log_id' => (string) Str::uuid(),
            'user_id' => $user?->id,
            'session_id' => session()->getId(),
            'ip_address' => $ip,
            'user_agent' => $request->userAgent(),
            'device' => $agent->device(),
            'method' => $request->method(),
            'referer' => $request->headers->get('referer'),
            'aksi' => Str::contains($request->fullUrl(), 'download') ? 'download' : 'detail',
            'tipe_data' => $request->is('skripsi*') ? 'skripsi' : null,
            'durasi_akses' => $duration,
            'response_latency_ms' => request()->server('REQUEST_TIME_FLOAT') ? microtime(true) - request()->server('REQUEST_TIME_FLOAT') : null,
            'user_type' => $user?->role ?? 'guest',
            'data_json' => json_encode([
                'query' => $request->query(),
                'body' => $request->post(),
            ]),
            'access_origin' => $request->headers->get('x-cache') ? 'cache' : 'storage',
            'cache_hit' => $request->headers->get('x-cache') === 'HIT' ? 1 : 0,
            'cache_status_at_request' => $request->headers->get('x-cache') ? 1 : 0,
            'created_at' => now(),
        ];

        Log::channel('requestlog')->info(json_encode($log));

        return $response;
    }
}
