<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Document\LogRequestModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardRequestLogController extends Controller
{
    public function index(){
        $totalRequests = LogRequestModel::count();
        $totalDownloads = LogRequestModel::where('aksi', 'download')->count();
        $totalDetails = LogRequestModel::where('aksi', 'detail')->count();
        $totalBotRequests = LogRequestModel::where('device', 'Bot')->count();

        $mostHitDoc = LogRequestModel::selectRaw('document_id, COUNT(*) as hits')
            ->groupBy('document_id')
            ->orderByDesc('hits')
            ->first();

        $recentLogs = DB::table('log_requests')
            ->leftJoin('documents', 'log_requests.document_id', '=', 'documents.document_id')
            ->select(
                'log_requests.*',
                'documents.title as document_title'
            )
            ->orderBy('log_requests.created_at', 'desc')
            ->limit(50)
            ->get();

        $dailyCounts = LogRequestModel::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();

        $pemetaanDokumen = DB::table('log_requests')
            ->join('documents', 'log_requests.document_id', '=', 'documents.document_id')
            ->select(
                'documents.title',
                'log_requests.document_id',
                DB::raw("SUM(CASE WHEN aksi = 'detail' THEN 1 ELSE 0 END) as jumlah_detail"),
                DB::raw("SUM(CASE WHEN aksi = 'download' THEN 1 ELSE 0 END) as jumlah_download")
            )
            ->groupBy('log_requests.document_id', 'documents.title')
            ->orderByRaw("SUM(CASE WHEN aksi = 'detail' THEN 1 ELSE 0 END) + SUM(CASE WHEN aksi = 'download' THEN 1 ELSE 0 END) DESC")
            ->get();

        $grafikDokumenHarian = DB::table('log_requests')
            ->join('documents', 'log_requests.document_id', '=', 'documents.document_id')
            ->selectRaw("
                DATE(log_requests.created_at) as tanggal,
                SUM(CASE WHEN aksi = 'detail' THEN 1 ELSE 0 END) as jumlah_detail,
                SUM(CASE WHEN aksi = 'download' THEN 1 ELSE 0 END) as jumlah_download
            ")
            ->where('log_requests.created_at', '>=', now()->subDays(10))
            ->groupBy(DB::raw('DATE(log_requests.created_at)'))
            ->orderBy('tanggal', 'asc')
            ->get();

        return view('Dashboard.log-requests', compact(
            'totalRequests', 'totalDownloads', 'totalDetails', 'totalBotRequests',
            'mostHitDoc', 'recentLogs', 'dailyCounts', 'pemetaanDokumen', 'grafikDokumenHarian'
        ));
    }
}
