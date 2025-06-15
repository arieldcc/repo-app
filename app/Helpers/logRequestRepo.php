<?php

namespace App\Helpers;

use App\Models\Document\LogRequestModel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Jenssegers\Agent\Agent;

class logRequestRepo
{
    public static function logAccess(array $options = [])
    {
        $agent = new Agent();
        $response_latency = microtime(true) - request()->server('REQUEST_TIME_FLOAT');
        $start_time = $options['start_time'] ?? microtime(true);
        $document = $options['document'] ?? null;

        $log = new LogRequestModel();
        $log->document_id = $document?->document_id ?? null;
        $log->file_size = $document?->file_size ?? null;
        $log->file_extension = pathinfo($document?->file_path, PATHINFO_EXTENSION) ?? null;

        $log->ip_address = Request::ip();
        $log->user_agent = Request::userAgent();
        $log->device = $agent->device();
        $log->method = Request::method();
        $log->referer = Request::header('referer');
        $log->tipe_data = 'halaman web';
        $log->aksi = $options['aksi'] ?? 'detail';
        $log->durasi_akses = microtime(true) - $start_time;
        $log->client_response_expected = Request::server('REQUEST_TIME_FLOAT') ? microtime(true) - Request::server('REQUEST_TIME_FLOAT') : null;

        // Field baru
        $log->access_origin = $options['access_origin'] ?? null;
        $log->cache_hit = $options['cache_hit'] ?? null;
        $log->response_latency_ms = $response_latency ?? null;
        $log->transfer_duration_ms = $options['transfer_duration'] ?? null;
        $log->user_type = $options['user_type'] ?? (auth()->check() ? 'registered' : 'guest');
        $log->cache_status_at_request = $options['cache_status'] ?? null;

        // Data JSON jika tersedia
        $log->data_json = $options['data_json'] ?? json_encode($document);

        $log->save();

        Log::channel('requestlog')->info(json_encode($log->toArray()));
        //  dd([
        //     'log_id' => $log->log_id,
        //     'is_saved' => $log->wasRecentlyCreated,
        // ]);
    }
}
