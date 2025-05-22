<!-- USER Section -->
<li class="nav-header">USER</li>
@if (!empty($permissions['permissionUser']))
<li class="nav-item">
    <a href="{{ url('panel/user') }}" class="nav-link {{ Request::segment(2) == 'user' ? 'active' : '' }}">
        <i class="nav-icon fas fa-user"></i>
        <p>
            User
        </p>
    </a>
</li>
@endif
@if (!empty($permissions['permissionRole']))
<li class="nav-item">
    <a href="{{ url('panel/role') }}" class="nav-link {{ Request::segment(2) == 'role' ? 'active' : '' }}">
        <i class="nav-icon fas fa-user-shield"></i>
        <p>
            Role
        </p>
    </a>
</li>
@endif
