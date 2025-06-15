@extends('layouts.main')
@section('title', 'Log Request Overview')

@section('css')
<link rel="stylesheet" href="{{ asset('public/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
@endsection

@section('content')
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $totalRequests }}</h3>
                <p>Total Request</p>
            </div>
            <div class="icon">
                <i class="fas fa-chart-line"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $totalDownloads }}</h3>
                <p>Download</p>
            </div>
            <div class="icon">
                <i class="fas fa-download"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $totalDetails }}</h3>
                <p>Detail Views</p>
            </div>
            <div class="icon">
                <i class="fas fa-eye"></i>
            </div>
        </div>
    </div>
    {{-- <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $mostHitDoc->document_id ?? '-' }}</h3>
                <p>Most Accessed Document</p>
            </div>
            <div class="icon">
                <i class="fas fa-file-alt"></i>
            </div>
        </div>
    </div> --}}
</div>

<!-- Chart -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Jumlah Request per Hari</h3>
    </div>
    <div class="card-body">
        <canvas id="requestChart" height="100"></canvas>
    </div>
</div>

<!-- Data Table -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Recent Requests</h3>
    </div>
    <div class="card-body">
        <table id="requestTable" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>IP</th>
                    <th>Device</th>
                    <th>Aksi</th>
                    <th>Tipe Data</th>
                    <th>User Agent</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentLogs as $log)
                <tr>
                    <td>{{ $log->created_at }}</td>
                    <td>{{ $log->ip_address }}</td>
                    <td>{{ $log->device }}</td>
                    <td>{{ $log->aksi }}</td>
                    <td>{{ $log->tipe_data }}</td>
                    <td>{{ Str::limit($log->user_agent, 80) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">
        <h3 class="card-title">Pemetaan Aktivitas per IP per Hari</h3>
    </div>
    <div class="card-body table-responsive">
        <table id="pemetaanTable" class="table table-bordered table-striped">
            <thead class="thead-light">
                <tr>
                    <th>Tanggal</th>
                    <th>IP Address</th>
                    <th>Judul Dokumen</th>
                    <th>Jumlah Akses</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pemetaanIP as $data)
                <tr>
                    <td>{{ $data->tanggal }}</td>
                    <td>{{ $data->ip_address }}</td>
                    <td>{{ $data->title }}</td>
                    <td>{{ $data->jumlah_akses }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection

@section('js')
<!-- DataTables -->
<script src="{{ asset('public/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('public/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('public/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('public/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
$(function () {
    $('#requestTable').DataTable({
        responsive: true,
        autoWidth: false,
    });

    const ctx = document.getElementById('requestChart').getContext('2d');
    const chartData = {
        labels: {!! json_encode($dailyCounts->pluck('date')) !!},
        datasets: [{
            label: 'Request / Hari',
            data: {!! json_encode($dailyCounts->pluck('count')) !!},
            backgroundColor: 'rgba(54, 162, 235, 0.5)',
            borderColor: 'rgba(54, 162, 235, 1)',
            fill: true,
            tension: 0.2
        }]
    };

    new Chart(ctx, {
        type: 'line',
        data: chartData,
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    precision: 0
                }
            }
        }
    });
});
</script>
@endsection
