<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Maintenance | {{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- AdminLTE + FontAwesome -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">

    <style>
        body {
            background-color: #f4f6f9;
        }

        .maintenance-box {
            margin-top: 50px;
            text-align: center;
        }

        .maintenance-image {
            max-width: 500px;
            width: 100%;
            height: auto;
        }

        .maintenance-message {
            font-size: 1.2rem;
            margin-top: 20px;
        }

        .footer {
            margin-top: 30px;
            font-size: 0.9rem;
            color: #888;
        }
    </style>
</head>
<body class="hold-transition login-page">
    <div class="maintenance-box">
        <!-- Gambar Under Construction -->
        <img src="{{ url('') }}/public/img/nc-1.png" alt="Under Construction" class="maintenance-image">

        <!-- Judul dan pesan -->
        <h2 class="mt-4"><strong>Website Sedang Dalam Perawatan</strong></h2>
        <p class="maintenance-message">
            Kami sedang meningkatkan performa sistem.<br>Silakan kembali lagi beberapa saat lagi 🙏
        </p>

        <!-- Icon tools -->
        <p><i class="fas fa-tools fa-3x text-warning"></i></p>

        <!-- Optional footer -->
        <div class="footer">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </div>
    </div>
</body>
</html>
