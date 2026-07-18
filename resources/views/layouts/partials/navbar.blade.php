<nav class="app-header navbar navbar-expand bg-body">
    <div class="container-fluid">
        <!-- Start Left Navbar Links -->
        <ul class="navbar-nav">
            <!-- Sidebar toggle -->
            <li class="nav-item">
                <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button" title="{{ __('Toggle Sidebar') }}">
                    <i class="bi bi-list"></i>
                </a>
            </li>
            <!-- Dada Logo -->
            <li class="nav-item d-flex align-items-center ms-2 me-3">
                <a href="{{ route('dashboard') }}">
                    <img src="{{ Vite::asset('resources/assets/images/logo/dada_bg.png') }}" alt="Dada Logo" style="max-height: 38px;">
                </a>
            </li>
        </ul>
        <!-- End Left Navbar Links -->

        <!-- Start Right Navbar Links -->
        <ul class="navbar-nav ms-auto align-items-center">
            
            <!-- Language Switcher Dropdown -->
            <li class="nav-item dropdown me-2">
                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" title="{{ __('Change Language') }}">
                    <i class="bi bi-translate me-1"></i>
                    <span class="d-none d-md-inline small fw-semibold">
                        @if(app()->getLocale() == 'bn')
                            বাংলা
                        @elseif(app()->getLocale() == 'ko')
                            한국어
                        @else
                            English
                        @endif
                    </span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow">
                    <li>
                        <a class="dropdown-item d-flex align-items-center {{ app()->getLocale() == 'en' ? 'active' : '' }}" href="{{ route('lang.switch', 'en') }}">
                            <span class="me-2">🇺🇸</span> English
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center {{ app()->getLocale() == 'bn' ? 'active' : '' }}" href="{{ route('lang.switch', 'bn') }}">
                            <span class="me-2">🇧🇩</span> বাংলা (Bangla)
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center {{ app()->getLocale() == 'ko' ? 'active' : '' }}" href="{{ route('lang.switch', 'ko') }}">
                            <span class="me-2">🇰🇷</span> 한국어 (Korean)
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Dark / Light Theme Mode Toggle -->
            <li class="nav-item">
                <a class="nav-link" href="#" onclick="event.preventDefault(); themeColorChange();" role="button" title="{{ __('Toggle Dark/Light Mode') }}">
                    <i class="bi bi-moon" id="bi-moon"></i>
                </a>
            </li>

            <!-- Fullscreen Toggle -->
            <li class="nav-item">
                <a class="nav-link" data-lte-toggle="fullscreen" href="#" role="button" title="{{ __('Toggle Fullscreen') }}">
                    <i class="bi bi-arrows-fullscreen"></i>
                </a>
            </li>

            <!-- Services Dropdown Button -->
            <li class="nav-item dropdown me-2">
                <a class="nav-link" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" title="{{ __('Services') }}">
                    <i class="bi bi-grid-3x3-gap-fill"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow">
                    <li><span class="dropdown-header fw-bold">{{ __('Services') }}</span></li>
                    <li><hr class="dropdown-divider"></li>
                    @canany(['dashboard'])
                        <li>
                            <a class="dropdown-item" href="{{ route('dashboard') }}">
                                <i class="bi bi-speedometer2 me-2 text-primary"></i> {{ __('Dashboard') }}
                            </a>
                        </li>
                    @endcanany

                    <li>
                        <a class="dropdown-item" href="{{ route('admin.grievance.index') }}">
                            <i class="bi bi-ticket-perforated me-2 text-info"></i> {{ __('Grievance Tickets') }}
                        </a>
                    </li>

                    @canany(['department.index'])
                        <li>
                            <a class="dropdown-item" href="{{ route('department.index') }}">
                                <i class="bi bi-building me-2 text-warning"></i> {{ __('Departments') }}
                            </a>
                        </li>
                    @endcanany

                    @canany(['section.index'])
                        <li>
                            <a class="dropdown-item" href="{{ route('section.index') }}">
                                <i class="bi bi-diagram-3 me-2 text-muted"></i> {{ __('Sections') }}
                            </a>
                        </li>
                    @endcanany

                    @canany(['category.index'])
                        <li>
                            <a class="dropdown-item" href="{{ route('category.index') }}">
                                <i class="bi bi-tags me-2 text-secondary"></i> {{ __('Categories') }}
                            </a>
                        </li>
                    @endcanany

                    @canany(['user.index'])
                        <li>
                            <a class="dropdown-item" href="{{ route('user.index') }}">
                                <i class="bi bi-people me-2 text-success"></i> {{ __('Users') }}
                            </a>
                        </li>
                    @endcanany

                    @canany(['role.index'])
                        <li>
                            <a class="dropdown-item" href="{{ route('role.index') }}">
                                <i class="bi bi-shield-lock-fill me-2 text-danger"></i> {{ __('Roles & Permissions') }}
                            </a>
                        </li>
                    @endcanany
                </ul>
            </li>

            <!-- User profile Dropdown Menu -->
            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown">
                    <img src="{{ Vite::asset('resources/assets/images/users/user.png') }}" class="user-image rounded-circle shadow-sm me-2" style="width: 1.6rem; height: 1.6rem; object-fit: cover;" alt="{{ __('User Image') }}">
                    <span class="d-none d-md-inline fw-semibold">{{ auth()->user()?->name }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end shadow">
                    <!-- User Profile Header -->
                    <li class="user-header text-bg-primary text-center p-4">
                        <img src="{{ Vite::asset('resources/assets/images/users/user.png') }}" class="rounded-circle shadow-sm mb-2" style="width: 5.5rem; height: 5.5rem; object-fit: cover;" alt="{{ __('User Image') }}">
                        <p class="mb-0 fw-bold fs-5 text-white">
                            {{ auth()->user()?->name }}
                        </p>
                        <small class="text-white-50">{{ auth()->user()?->roles->first()?->name ?? __('User') }}</small>
                    </li>
                    
                    <!-- Menu Body (Profile & Support links) -->
                    <li class="user-body p-3">
                        <div class="row">
                            <div class="col-6 text-center">
                                <a href="{{ route('user.show', auth()->user()?->id) }}" class="btn btn-sm btn-outline-secondary w-100">
                                    <i class="bi bi-person me-1"></i> {{ __('Profile') }}
                                </a>
                            </div>
                            <div class="col-6 text-center">
                                <a target="_blank" href="{{ route('help-and-support') }}" class="btn btn-sm btn-outline-secondary w-100">
                                    <i class="bi bi-question-circle me-1"></i> {{ __('Support') }}
                                </a>
                            </div>
                        </div>
                    </li>
                    
                    <!-- Menu Footer (Logout button) -->
                    <li class="user-footer bg-body-secondary p-3 border-top d-flex justify-content-end">
                        <a href="#" class="btn btn-danger btn-sm" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bi bi-box-arrow-right me-1"></i> {{ __('Logout') }}
                        </a>
                        <form id="logout-form" class="d-none" action="{{ route('logout') }}" method="POST">
                            @csrf
                        </form>
                    </li>
                </ul>
            </li>

        </ul>
        <!-- End Right Navbar Links -->
    </div>
</nav>
<!-- End AdminLTE 4 Navbar =================================== -->
