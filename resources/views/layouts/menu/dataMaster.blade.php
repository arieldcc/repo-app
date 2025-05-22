@if (!empty($permissions['permissionDataMaster']))
          <!-- MASTER Section -->
          <li class="nav-header">MASTER</li>

          @if (!empty($permissions['permissionDataMasterFakultas']))
          <li class="nav-item">
            <a href="{{ url('master/fakultas') }}" class="nav-link {{ Request::segment(2) == 'fakultas' ? 'active' : '' }}">
                <i class="fas fa-university nav-icon"></i>
              <p>
                Data Fakultas
              </p>
            </a>
          </li>
          @endif

          @if (!empty($permissions['permissionDosen']))
          <li class="nav-item">
            <a href="{{ url('master/dosen') }}" class="nav-link {{ Request::segment(2) == 'dosen' ? 'active' : '' }}">
                <i class="nav-icon fas fa-chalkboard-teacher"></i>
              <p>
                Data Dosen
              </p>
            </a>
          </li>
          @endif

          @if (!empty($permissions['permissionMahasiswa']))
          <li class="nav-item">
            <a href="{{ url('master/mahasiswa') }}" class="nav-link {{ Request::segment(2) == 'mahasiswa' ? 'active' : '' }}">
                <i class="nav-icon fas fa-user-graduate"></i>
              <p>
                Data Mahasiswa
              </p>
            </a>
          </li>
          @endif

          @if (!empty($permissions['permissionTahunAjar']))
          <li class="nav-item">
            <a href="{{ url('master/tahunajar') }}" class="nav-link {{ Request::segment(2) == 'tahunajar' ? 'active' : '' }}">
                <i class="nav-icon fas fa-calendar-alt"></i>
              <p>
                Tahun Ajaran
              </p>
            </a>
          </li>
          @endif

          @if (!empty($permissions['permissionDataRev']))
            <li class="nav-item {{ Request::segment(2) == 'datarev' ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ Request::segment(2) == 'datarev' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-database"></i>
                    <p>
                        Data Reverensi
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>

                <ul class="nav nav-treeview">
                    @if (!empty($permissions['permissionDataPokokJenisSMS']))
                        <li class="nav-item">
                            <a href="{{ url('master/datarev/jenis-sms') }}" class="nav-link {{ Request::segment(3) == 'jenis-sms' ? 'active' : '' }}">
                                <i class="fas fa-envelope nav-icon"></i>
                                <p>Jenis SMS</p>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif

          @if (!empty($permissions['permissionDataPokok']))
            <li class="nav-item {{ Request::segment(1) == 'datapokok' ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ Request::segment(1) == 'datapokok' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-book"></i>
                    <p>
                        Data Pokok
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    @if (!empty($permissions['permissionDataPokokJenisJabatan']))
                        <li class="nav-item">
                            <a href="{{ url('datapokok/jenis-jabatan') }}" class="nav-link {{ Request::segment(2) == 'jenis-jabatan' ? 'active' : '' }}">
                                <i class="fas fa-user-tie nav-icon"></i>
                                <p>Jenis Jabatan</p>
                            </a>
                        </li>
                    @endif

                    @if (!empty($permissions['permissionDataMasterFakultas']))
                        <li class="nav-item">
                            <a href="{{ url('datapokok/fakultas') }}" class="nav-link {{ Request::segment(2) == 'fakultas' ? 'active' : '' }}">
                                <i class="fas fa-university nav-icon"></i>
                                <p>Fakultas</p>
                            </a>
                        </li>
                    @endif
                    @if (!empty($permissions['permissionDataPokokProdi']))
                        <li class="nav-item">
                            <a href="{{ url('datapokok/prodi') }}" class="nav-link {{ Request::segment(2) == 'prodi' ? 'active' : '' }}">
                                <i class="fas fa-graduation-cap nav-icon"></i>
                                <p>Prodi</p>
                            </a>
                        </li>
                    @endif

                    @if (!empty($permissions['permissionDataPokokJenisPembayaran']))
                        <li class="nav-item">
                            <a href="{{ url('datapokok/jenis-pembayaran') }}" class="nav-link {{ Request::segment(2) == 'jenis-pembayaran' ? 'active' : '' }}">
                                <i class="fas fa-money-check-alt nav-icon"></i>
                                <p>Jenis Pembayaran</p>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif

          @if (!empty($permissions['permissionPembayaran']))
          <li class="nav-item {{ request()->is('pembayaran*') ? 'active' : '' }}">
            <a href="#" class="nav-link">
                <i class="nav-icon fas fa-credit-card"></i>
              <p>
                Jenis Pembayaran
                <span class="right badge badge-danger">New</span>
              </p>
            </a>
          </li>
          @endif

          @if (!empty($permissions['permissionPejabat']))
          <li class="nav-item {{ request()->is('jabatan*') ? 'active' : '' }}">
            <a href="#" class="nav-link">
                <i class="nav-icon fas fa-id-badge"></i>
              <p>
                Data Jabatan
              </p>
            </a>
          </li>
          @endif
    @endif
