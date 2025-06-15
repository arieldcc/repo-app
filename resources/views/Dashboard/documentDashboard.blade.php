@extends('layouts.main')

@section('title') Dashboard Repository @endsection

@section('content')
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $countSkripsi }}</h3>
                <p>Total Skripsi</p>
            </div>
            <div class="icon">
                <i class="fas fa-file-alt"></i>
            </div>
            <a href="{{ url('doc/skripsi') }}" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $countTesis }}</h3>
                <p>Total Tesis</p>
            </div>
            <div class="icon">
                <i class="fas fa-file-word"></i>
            </div>
            <a href="{{ url('doc/tesis') }}" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $countPenelitian }}</h3>
                <p>Total Penelitian</p>
            </div>
            <div class="icon">
                <i class="fas fa-flask"></i>
            </div>
            <a href="{{ url('doc/penelitian') }}" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $countPengabdian }}</h3>
                <p>Total Pengabdian</p>
            </div>
            <div class="icon">
                <i class="fas fa-hands-helping"></i>
            </div>
            <a href="{{ url('doc/pengabdian') }}" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

</div>
<div class="row">
    <div class="col-md-12">
        {{-- Grafik Upload per Tahun --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Grafik Upload Dokumen per Tahun</h3>
            </div>
            <div class="card-body">
                <canvas id="uploadChart" height="100"></canvas>
            </div>
        </div>
    </div>
</div>
    {{-- Statistik Status --}}
    <div class="row">
        <div class="col-md-6">
            <div class="info-box bg-success">
                <span class="info-box-icon"><i class="fas fa-check-circle"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Approved</span>
                    <span class="info-box-number">{{ $statusCounts['approved'] ?? 0 }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="info-box bg-warning">
                <span class="info-box-icon"><i class="fas fa-hourglass-half"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Pending</span>
                    <span class="info-box-number">{{ $statusCounts['pending'] ?? 0 }}</span>
                </div>
            </div>
        </div>
    </div>

<div class="row">
    {{-- Top 5 Penulis Penelitian --}}
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Top 5 Penulis Penelitian</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped m-0">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Penulis</th>
                            <th>Jumlah Dokumen</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($topAuthorsPenelitian as $author)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $author->penulis_nama }}</td>
                                <td>{{ $author->total }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Top 5 Penulis Pengabdian --}}
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Top 5 Penulis Pengabdian</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped m-0">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Penulis</th>
                            <th>Jumlah Dokumen</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($topAuthorsPengabdian as $author)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $author->penulis_nama }}</td>
                                <td>{{ $author->total }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
    {{-- Filter Form --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Filter Seluruh Dokumen</h3>
            </div>
            <div class="card-body">
                <form id="filterForm" class="row mt-4 mb-2">
                    <div class="col-md-3">
                        <input type="text" name="tahun_akademik" class="form-control" placeholder="Tahun Akademik">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-control">
                            <option value="">-- Status --</option>
                            <option value="approved">Approved</option>
                            <option value="pending">Pending</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="start_date" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="end_date" class="form-control">
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Pie Chart --}}
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Proporsi Dokumen per Tipe (Hasil Filter)</h3>
            </div>
            <div class="card-body">
                <div style="position: relative; width: 100%; max-width: 400px; margin: auto;">
                    <canvas id="pieChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    {{-- Filter Pie Chart Prodi --}}
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Filter Dokumen Skripsi dan Tesis</h3>
            </div>
            <div class="card-body">
                <div class="row mt-4">
                    <div class="col-md-6">
                        <select id="docTypeSelect" class="form-control">
                            <option value="skripsi">Skripsi</option>
                            <option value="tesis">Tesis</option>
                        </select>
                    </div>
                </div>
            </div>
            </div>
    </div>

    {{-- Pie Chart Prodi --}}
    <div class="col-md-12">
        <div class="card mt-3">
            <div class="card-header">
                <h3 class="card-title">Distribusi Dokumen per Program Studi (Skripsi/Tesis)</h3>
            </div>
            <div class="card-body">
                <div style="position: relative; width: 100%; max-width: 500px; margin: auto;">
                    <canvas id="prodiPieChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Filter bertingkat: Tipe Dokumen Prodi --}}
<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Filter Dokumen Skripsi dan Tesis per prodi</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <select id="docTypeFilter" class="form-control">
                        <option value="">-- Pilih Jenis Dokumen --</option>
                        <option value="skripsi">Skripsi</option>
                        <option value="tesis">Tesis</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <select id="prodiFilter" class="form-control" disabled>
                        <option value="">-- Pilih Program Studi --</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-md-12">
    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title">Grafik Jumlah Dokumen per Tahun (per Prodi)</h3>
        </div>
        <div class="card-body">
            <canvas id="chartPerProdi"></canvas>
        </div>
    </div>
</div>

@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('uploadChart').getContext('2d');
    const uploadChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($yearlyUploads->pluck('tahun_akademik')) !!},
            datasets: [{
                label: 'Total Dokumen',
                data: {!! json_encode($yearlyUploads->pluck('total')) !!},
                backgroundColor: 'rgba(60, 141, 188, 0.9)',
                borderColor: 'rgba(60, 141, 188, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    const pieCtx = document.getElementById('pieChart').getContext('2d');
    let pieChart;

    function updatePieChart(data) {
        if (pieChart) pieChart.destroy();

        pieChart = new Chart(pieCtx, {
            type: 'pie',
            data: {
                labels: ['Skripsi', 'Tesis', 'Penelitian', 'Pengabdian'],
                datasets: [{
                    data: [data.skripsi, data.tesis, data.penelitian, data.pengabdian],
                    backgroundColor: ['#007bff', '#28a745', '#ffc107', '#dc3545']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    }

    function fetchFilteredData() {
        $.ajax({
            url: '{{ url("dashboard/repodashboard/data-filter") }}',
            data: $('#filterForm').serialize(),
            success: function (res) {
                updatePieChart(res);
            }
        });
    }

    // Inisialisasi awal
    fetchFilteredData();

    // Saat filter berubah
    $('#filterForm input, #filterForm select').on('change', function () {
        fetchFilteredData();
    });

    const prodiCtx = document.getElementById('prodiPieChart').getContext('2d');
    let prodiPieChart;

    function fetchProdiChart(docType = 'skripsi') {
        $.ajax({
            url: '{{ route("dashboard.pie-prodi") }}',
            data: { type: docType },
            success: function (res) {
                if (prodiPieChart) prodiPieChart.destroy();

                prodiPieChart = new Chart(prodiCtx, {
                    type: 'pie',
                    data: {
                        labels: res.labels,
                        datasets: [{
                            data: res.data,
                            backgroundColor: res.labels.map(() => getRandomColor())
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { position: 'bottom' }
                        }
                    }
                });
            }
        });
    }

    function getRandomColor() {
        const colors = ['#007bff', '#28a745', '#ffc107', '#dc3545', '#17a2b8', '#6610f2', '#e83e8c'];
        return colors[Math.floor(Math.random() * colors.length)];
    }

    // Inisialisasi awal dengan skripsi
    fetchProdiChart('skripsi');

    // Ubah chart saat pilihan dropdown berubah
    $('#docTypeSelect').on('change', function () {
        fetchProdiChart($(this).val());
    });

    let chartProdi;

    const ctxProdi = document.getElementById('chartPerProdi').getContext('2d');

    // Ambil daftar Prodi saat tipe dokumen dipilih
    $('#docTypeFilter').on('change', function() {
        const type = $(this).val();
        $('#prodiFilter').html('<option value="">-- Pilih Program Studi --</option>').prop('disabled', true);
        if (type) {
            $.get('{{ route("dashboard.prodi-by-type") }}', { type }, function(data) {
                if (data.length) {
                    data.forEach(prodi => {
                        $('#prodiFilter').append(`<option value="${prodi}">${prodi}</option>`);
                    });
                    $('#prodiFilter').prop('disabled', false);
                }
            });
        }
    });

    // Ambil dan tampilkan chart saat prodi dipilih
    $('#prodiFilter').on('change', function() {
        const prodi = $(this).val();
        const type = $('#docTypeFilter').val();

        if (type && prodi) {
            $.get('{{ route("dashboard.chart-by-prodi") }}', { type, prodi }, function(res) {
                if (chartProdi) chartProdi.destroy();

                chartProdi = new Chart(ctxProdi, {
                    type: 'bar',
                    data: {
                        labels: res.labels,
                        datasets: [{
                            label: `Jumlah Dokumen ${type.toUpperCase()}`,
                            data: res.data,
                            backgroundColor: 'rgba(54, 162, 235, 0.7)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: { beginAtZero: true }
                        }
                    }
                });
            });
        }
    });
</script>
@endsection
