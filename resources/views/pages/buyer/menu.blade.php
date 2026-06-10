<nav class="navbar navbar-expand-lg">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link {{ Route::currentRouteName() == 'buyer.create' ? 'active' : '' }}"
                href="{{ route('buyer.create') }}">
                <i class="bi bi-plus-circle"></i>
                <span>New Buyer</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ Route::currentRouteName() == 'buyer.index' ? 'active' : '' }}"
                href="{{ route('buyer.index') }}">
                <i class="bi bi-card-list"></i>
                <span>All Buyers</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ Route::currentRouteName() == 'buyer.import-page' ? 'active' : '' }}"
                href="{{ route('buyer.import-page') }}">
                <i class="bi bi-filetype-xlsx"></i>
                <span>Excel Import</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ Route::currentRouteName() == 'buyer.trash' ? 'active bg-danger fw-semibold' : '' }}"
                href="{{ route('buyer.trash') }}">
                <i class="bi bi-trash2-fill"></i>
                <span>Trash</span>
            </a>
        </li>
    </ul>
</nav>
