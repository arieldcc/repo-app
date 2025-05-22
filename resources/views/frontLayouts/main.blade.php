<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="Shortcut Icon" type="image/x-icon" href="https://siakun.unisan.ac.id/images/favicon.ico">
    <title>@yield('title', $setting->site_name ?? 'UNISAN Repository')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('public/repo/css/style.css') }}" rel="stylesheet">
    <!-- IziToast CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast/dist/css/iziToast.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @php
        use Illuminate\Support\Facades\Auth;
    @endphp


    @yield('css')
</head>
<body class="d-flex flex-column min-vh-100">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-green fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand text-white" href="{{ url('/') }}">{{ $setting->site_name ?? 'UNISAN Repository' }}</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active text-white" aria-current="page" href="{{ url('/') }}">Beranda</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white" href="#" id="configDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Direktory
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="configDropdown">
                            <li><a class="dropdown-item" href="{{ url('penelitian') }}">Penelitian</a></li>
                            <li><a class="dropdown-item" href="">Pengabdian</a></li>
                            <li><a class="dropdown-item" href="{{ url('skripsi') }}">Skripsi</a></li>
                            <li><a class="dropdown-item" href="{{ url('tesis') }}">Tesis</a></li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link text-white" href="#">Tentang</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white" href="#" id="configDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Tools
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="configDropdown">
                            <li><a class="dropdown-item" href="{{ url('history') }}">Riwayat</a></li>
                            <li><a class="dropdown-item" href="{{ url('info_cache') }}">Cache File Download (PPO+Knapsack+LRU)</a></li>
                            <li><a class="dropdown-item" href="#" onclick="startCheck()">Cek Cache - BG</a></li>
                            <li><a class="dropdown-item" href="{{ url('drl') }}">DRL - PPO</a></li>
                        </ul>
                    </li>
                </ul>

                <!-- Menu Login di kanan -->
                <ul class="navbar-nav ms-auto">
                    @if(Auth::check())
                        <li class="nav-item">
                            <a href="{{ url('logout') }}" class="nav-link text-white">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a href="{{ url('login') }}" class="nav-link text-white">
                                <i class="fas fa-sign-in-alt"></i> Login
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <!-- Spacer for fixed navbar -->
    <div style="height: 6px;"></div>

    <!-- Slider Bar (Carousel) -->
    @yield('slidebar')

    <div class="container mt-5 mb-5">
        <div class="row">
            @yield('content')
        </div>
    </div>

    <!-- Add a spacer here before the footer -->
    <div class="container mb-5"></div>

    <!-- Footer -->
    <footer class="bg-green text-white text-center py-3 mt-auto">
        <p>
            {!! $setting->footer_text ?? 'UNISAN Repository' !!}
            <small>Versi: {{ $setting->version ?? 'v.000' }} &copy; {{ now()->year }}</small>
        </p>
    </footer>

    <!-- jQuery (required for AJAX) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- IziToast JS -->
    <script src="https://cdn.jsdelivr.net/npm/izitoast/dist/js/iziToast.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function startCheck() {
            $.get("{{ url('start_check') }}", function(response) {
                iziToast.success({
                    title: 'Proses Dimulai',
                    message: 'Proses background pengecekan cache sudah dimulai.',
                    position: 'topRight'
                });
            });
        }
    </script>
    @yield('js')
</body>
</html>
