<nav class="navbar navbar-expand-lg px-3 py-2 rounded bg-body-secondary shadow-sm mb-3">
    <!-- Left: Navigation Links -->
    <ul class="navbar-nav gap-2 flex-row">
        <li class="nav-item">
            <a class="nav-link px-3 rounded {{ Route::currentRouteName() == 'admin.grievance.index' ? 'active bg-primary text-white' : '' }}"
                href="{{ route('admin.grievance.index') }}">
                <i class="bi bi-ticket-perforated me-1"></i>
                <span>{{ __('All Tickets') }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link px-3 rounded {{ Route::currentRouteName() == 'admin.grievance.trash' ? 'active bg-danger text-white fw-semibold' : '' }}"
                href="{{ route('admin.grievance.trash') }}">
                <i class="bi bi-trash2-fill me-1"></i>
                <span>{{ __('Trash') }}</span>
            </a>
        </li>
    </ul>
    
    <!-- Right Side Buttons -->
    <div class="ms-auto d-flex align-items-center gap-1">
        <button type="button" class="btn btn-sm btn-outline-secondary collapsed" title="{{ __('Search Filters') }}"
                data-bs-toggle="collapse" data-bs-target="#tableSearch" aria-controls="tableSearch"
                aria-expanded="false">
            <i class="bi bi-funnel-fill"></i> {{ __('Filter') }}
        </button>
        <button type="button" class="btn btn-sm btn-outline-secondary" title="{{ __('Reload') }}" onclick="location.reload()">
            <i class="bi bi-arrow-clockwise"></i>
        </button>
        <button type="button" class="btn btn-sm btn-outline-secondary" title="{{ __('Go back') }}" onclick="history.back()">
            <i class="bi bi-arrow-left"></i>
        </button>
    </div>
</nav>
