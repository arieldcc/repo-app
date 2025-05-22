<!-- KONFIGURASI Section -->
@if (!empty($permissions['permissionKonfigurasi']))
    <li class="nav-header">KONFIGURASI</li>
    @if (!empty($permissions['permissionConfRepo']))
        <li class="nav-item {{ Request::segment(1) == 'conf-repo' ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ Request::segment(1) == 'conf-repo' ? 'active' : '' }}">
                <i class="nav-icon fas fa-database"></i>
                <p>
                    Repository
                    <i class="fas fa-angle-left right"></i>
                </p>
            </a>

            <ul class="nav nav-treeview">
                @if (!empty($permissions['permissionConfRepoFrontendSettings']))
                    <li class="nav-item">
                        <a href="{{ url('conf-repo/frontend-setting') }}" class="nav-link {{ Request::segment(2) == 'frontend-setting' ? 'active' : '' }}">
                            <i class="fas fa-cog nav-icon"></i>
                            <p>FrontEnd Setting</p>
                        </a>
                    </li>
                @endif

                @if (!empty($permissions['permissionConfRepoSliders']))
                    <li class="nav-item">
                        <a href="{{ url('conf-repo/sliderbar') }}" class="nav-link {{ Request::segment(2) == 'sliderbar' ? 'active' : '' }}">
                            <i class="fas fa-images nav-icon"></i>
                            <p>Conf. Sliderbar</p>
                        </a>
                    </li>
                @endif

                @if (!empty($permissions['permissionConfRepoCustomPages']))
                <li class="nav-item">
                    <a href="{{ url('conf-repo/custom-pages') }}" class="nav-link {{ Request::segment(2) == 'custom-pages' ? 'active' : '' }}">
                        <i class="fas fa-file-alt nav-icon"></i>
                        <p>Conf. Halaman Utama</p>
                    </a>
                </li>
            @endif

            </ul>
        </li>

    @endif
@endif
