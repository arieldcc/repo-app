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

        $recentLogs = LogRequestModel::orderBy('created_at', 'desc')->limit(50)->get();

        $dailyCounts = LogRequestModel::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();

        $pemetaanIP = DB::table('log_requests')
            ->join('documents', 'log_requests.document_id', '=', 'documents.document_id')
            ->selectRaw('
                DATE(log_requests.created_at) as tanggal,
                log_requests.ip_address,
                documents.title,
                COUNT(*) as jumlah_akses
            ')
            ->groupBy('tanggal', 'log_requests.ip_address', 'documents.title')
            ->orderBy('tanggal', 'desc')
            ->orderBy('jumlah_akses', 'desc')
            ->get();

        return view('dashboard.log-requests', compact(
            'totalRequests', 'totalDownloads', 'totalDetails', 'totalBotRequests',
            'mostHitDoc', 'recentLogs', 'dailyCounts', 'pemetaanIP'
        ));
    }
}
