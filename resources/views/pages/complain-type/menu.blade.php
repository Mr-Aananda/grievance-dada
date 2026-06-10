<nav class="navbar navbar-expand-lg">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link {{ Route::currentRouteName() == 'complain-type.create' ? 'active' : '' }}"
                href="{{ route('complain-type.create') }}">
                <i class="bi bi-plus-circle"></i>
                <span>New Complain Type</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ Route::currentRouteName() == 'complain-type.index' ? 'active' : '' }}"
                href="{{ route('complain-type.index') }}">
                <i class="bi bi-card-list"></i>
                <span>All Complain Types</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ Route::currentRouteName() == 'complain-type.trash' ? 'active bg-danger fw-semibold' : '' }}"
                href="{{ route('complain-type.trash') }}">
                <i class="bi bi-trash2-fill"></i>
                <span>Trash</span>
            </a>
        </li>
    </ul>
</nav>
