@php
    $permissions = getUserPermissions();
    // dd($permissions['permissionRole']);
@endphp
<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      <img src="{{ url('') }}/public/dist/img/repository.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">app-Repo</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">

      <!-- SidebarSearch Form -->
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            @if (!empty($permissions['permissionDashboard']))
          <!-- Dashboard Section -->
          <!-- Menu Multi-Level -->
          <li class="nav-item {{ Request::segment(1) == 'dashboard' ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ Request::segment(1) == 'dashboard' ? 'active' : '' }}">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>
                    Dashboard
                    <i class="fas fa-angle-left right"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{ url('dashboard/repodashboard') }}" class="nav-link {{ Request::segment(2) == 'repodashboard' ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Dashboard Repository</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('dashboard/v2') }}" class="nav-link {{ Request::segment(2) == 'v2' ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Dashboard v2</p>
                    </a>
                </li>
            </ul>
        </li>
        @endif

        @include('layouts.menu.dataMaster')

        @include('layouts.menu.dataDocument')

        {{-- Setting --}}
        @include('layouts.menu.dataSetting')

        {{-- User --}}
        @include('layouts.menu.user')

        <!-- TRANSAKSI Section -->
        <li class="nav-header">TOOLS</li>

        @if (!empty($permissions['permissionSetting']))

        <li class="nav-item">
            <a href="{{ url('/') }}" class="nav-link {{ Request::segment(2) == 'setting' ? 'active' : '' }}">
                <i class="nav-icon fas fa-credit-card"></i>
                <p>
                    Halaman Depan
                </p>
            </a>
        </li>
        @endif

          <li class="nav-item">
            <a href="{{ url('logout') }}" class="nav-link {{ Request::segment(2) == 'logout' ? 'active' : '' }}">
              <i class="nav-icon fas fa-credit-card"></i>
              <p>
                Log Out
              </p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
