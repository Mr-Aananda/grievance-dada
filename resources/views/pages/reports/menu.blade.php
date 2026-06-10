<nav class="navbar navbar-expand-lg">
    <ul class="navbar-nav">
        @can('reports.overall-report')
            <li class="nav-item">
                <a class="nav-link {{ Route::currentRouteName() == 'reports.overall-report' ? 'active' : '' }}"
                    href="{{ route('reports.overall-report') }}">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>Overall Report</span>
                </a>
            </li>
        @endcan

    </ul>
</nav>
