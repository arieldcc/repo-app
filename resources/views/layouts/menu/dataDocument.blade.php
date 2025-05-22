@if (!empty($permissions['permissionDocument']))
    <!-- MASTER Section -->
    <li class="nav-header">Dokumen</li>

    @if (!empty($permissions['permissionDocSkripsi']))
        <li class="nav-item">
        <a href="{{ url('doc/skripsi') }}" class="nav-link {{ Request::segment(2) == 'skripsi' ? 'active' : '' }}">
            <i class="fas fa-graduation-cap nav-icon"></i>
            <p>Data Skripsi</p>
        </a>
        </li>
    @endif

    @if (!empty($permissions['permissionDocTesis']))
        <li class="nav-item">
        <a href="{{ url('doc/tesis') }}" class="nav-link {{ Request::segment(2) == 'tesis' ? 'active' : '' }}">
            <i class="fas fa-university nav-icon"></i>
            <p>Data Tesis</p>
        </a>
        </li>
    @endif

    @if (!empty($permissions['permissionDocPenelitian']))
        <li class="nav-item">
        <a href="{{ url('doc/penelitian') }}" class="nav-link {{ Request::segment(2) == 'penelitian' ? 'active' : '' }}">
            <i class="fas fa-search nav-icon"></i>
            <p>Data Penelitian</p>
        </a>
        </li>
    @endif

    @if (!empty($permissions['permissionDocPengabdian']))
        <li class="nav-item">
        <a href="{{ url('doc/pengabdian') }}" class="nav-link {{ Request::segment(2) == 'pengabdian' ? 'active' : '' }}">
            <i class="fas fa-hands-helping nav-icon"></i>
            <p>Data Pengabdian</p>
        </a>
        </li>
    @endif

    @if (!empty($permissions['permissionDocLaporan']))
        <li class="nav-item">
        <a href="#" class="nav-link {{ Request::segment(2) == 'laporan' ? 'active' : '' }}">
            <i class="fas fa-file-alt nav-icon"></i>
            <p>Data Laporan</p>
        </a>
        </li>
    @endif

    @if (!empty($permissions['permissionDocBukuAjar']))
        <li class="nav-item">
        <a href="#" class="nav-link {{ Request::segment(2) == 'bukuajar' ? 'active' : '' }}">
            <i class="fas fa-book nav-icon"></i>
            <p>Data Buku Ajar</p>
        </a>
        </li>
    @endif
@endif
