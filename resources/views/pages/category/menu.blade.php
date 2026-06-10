<nav class="navbar navbar-expand-lg">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link {{ Route::currentRouteName() == 'category.create' ? 'active' : '' }}"
                href="{{ route('category.create') }}">
                <i class="bi bi-plus-circle"></i>
                <span>New Category</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ Route::currentRouteName() == 'category.index' ? 'active' : '' }}"
                href="{{ route('category.index') }}">
                <i class="bi bi-card-list"></i>
                <span>All Categories</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ Route::currentRouteName() == 'category.trash' ? 'active bg-danger fw-semibold' : '' }}"
                href="{{ route('category.trash') }}">
                <i class="bi bi-trash2-fill"></i>
                <span>Trash</span>
            </a>
        </li>
    </ul>
</nav>
