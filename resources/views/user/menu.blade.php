<nav class="navbar navbar-expand-lg">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link {{Route::currentRouteName() =='user.create' ? 'active' : '' }}" href="{{ route('user.create') }}">
                <i class="bi bi-plus-circle"></i>
                <span>New User</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{Route::currentRouteName() =='user.index' ? 'active' : '' }}" href="{{ route('user.index') }}">
                <i class="bi bi-card-list"></i>
                <span>Manage Users</span>
            </a>
        </li>
{{--        <li class="nav-item">--}}
{{--            <a class="nav-link {{Route::currentRouteName() =='user.trash' ? 'active' : '' }}" href="{{ route('user.trash') }}">--}}
{{--                <i class="bi bi-card-checklist"></i>--}}
{{--                <span>Recycle bin</span>--}}
{{--            </a>--}}
{{--        </li>--}}

    </ul>
</nav>
