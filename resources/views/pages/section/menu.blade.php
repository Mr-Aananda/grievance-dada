<nav class="navbar navbar-expand-lg">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link {{ Route::currentRouteName() == 'section.create' ? 'active' : '' }}"
                href="{{ route('section.create') }}">
                <i class="bi bi-plus-circle"></i>
                <span>New section</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ Route::currentRouteName() == 'section.index' ? 'active' : '' }}"
                href="{{ route('section.index') }}">
                <i class="bi bi-card-list"></i>
                <span>All sections</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ Route::currentRouteName() == 'section.trash' ? 'active bg-danger fw-semibold' : '' }}"
                href="{{ route('section.trash') }}">
                <i class="bi bi-trash2-fill"></i>
                <span>Trash</span>
            </a>
        </li>
    </ul>
</nav>
