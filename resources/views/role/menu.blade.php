<nav class="navbar navbar-expand-lg">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link {{ Route::currentRouteName() == 'role.create' ? 'active' : '' }}"
                href="{{ route('role.create') }}">
                <i class="bi bi-plus-circle"></i>
                <span>New Role</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ Route::currentRouteName() == 'role.index' ? 'active' : '' }}"
                href="{{ route('role.index') }}">
                <i class="bi bi-card-list"></i>
                <span>All Roles</span>
            </a>
        </li>
    </ul>
</nav>
