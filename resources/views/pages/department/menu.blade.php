<nav class="navbar navbar-expand-lg">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link {{ Route::currentRouteName() == 'department.create' ? 'active' : '' }}"
                href="{{ route('department.create') }}">
                <i class="bi bi-plus-circle"></i>
                <span>New Department</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ Route::currentRouteName() == 'department.index' ? 'active' : '' }}"
                href="{{ route('department.index') }}">
                <i class="bi bi-card-list"></i>
                <span>All Departments</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ Route::currentRouteName() == 'department.trash' ? 'active bg-danger fw-semibold' : '' }}"
                href="{{ route('department.trash') }}">
                <i class="bi bi-trash2-fill"></i>
                <span>Trash</span>
            </a>
        </li>
    </ul>
</nav>
