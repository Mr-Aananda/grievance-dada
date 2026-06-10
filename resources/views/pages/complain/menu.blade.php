<nav class="navbar navbar-expand-lg">
    <ul class="navbar-nav">

        @can('complain.index')
            <li class="nav-item">
                <a class="nav-link {{ in_array(Route::currentRouteName(), ['complain.index', 'complain.show']) ? 'active' : '' }}"
                    href="{{ route('complain.index') }}">
                    <i class="bi bi-card-list"></i>
                    <span>All Entries</span>
                    @if (Route::currentRouteName() == 'complain.show')
                        <i class="bi bi-chevron-right small mx-1"></i>
                        <span class="small">Details</span>
                    @endif
                </a>
            </li>
        @endcan

        @can('complain.create')
            <li class="nav-item">
                <a class="nav-link {{ Route::currentRouteName() == 'complain.create' ? 'active' : '' }}"
                    href="{{ route('complain.create') }}">
                    <i class="bi bi-plus-circle"></i>
                    <span>New Complain</span>
                </a>
            </li>
        @endcan

        @can('complain.manual')
            <li class="nav-item">
                <a class="nav-link {{ Route::currentRouteName() == 'complain.manual' ? 'active' : '' }}"
                    href="{{ route('complain.manual') }}">
                    <i class="bi bi-plus-circle"></i>
                    <span>New Manual</span>
                </a>
            </li>
        @endcan

        @can('complain.trash')
            <li class="nav-item">
                <a class="nav-link {{ Route::currentRouteName() == 'complain.trash' ? 'active bg-danger fw-semibold' : '' }}"
                    href="{{ route('complain.trash') }}">
                    <i class="bi bi-trash2-fill"></i>
                    <span>Trash</span>
                </a>
            </li>
        @endcan

    </ul>
</nav>
