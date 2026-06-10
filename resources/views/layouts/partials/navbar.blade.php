<!-- Start Navbar =================================== -->

<!-- Start Left logo area ======================================= -->
<x-nav-logo-component />
<!-- End Left logo area ======================================= -->

<!-- Start Navbar =================================== -->
<header>
    <nav class="navbar primary shadow-sm">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <!-- Start Left logo area ======================================= -->
            <x-nav-logo-component />

            <!-- Start navMenu ======================================= -->
            <ul class="navbar-nav ms-auto mb-lg-0">

                <!-- Service button ======================================= -->
                <li class="nav-item dropdown service">
                    <a class="nav-link" href="#" role="button" id="service-dropdown" data-bs-toggle="dropdown"
                        aria-expanded="false"><i class="bi bi-grid"></i></a>
                    <div class="dropdown-menu" aria-labelledby="service-dropdown">
                        <p class="fw-bold mb-0 px-2 border-bottom">Services</p>
                        <ul>
                            @canany(['dashboard'])
                                <li>
                                    <a class="dropdown-item" href="{{ route('dashboard') }}">
                                        <i class="bi bi-speedometer"></i>
                                        <span>Dashboard </span>
                                    </a>
                                </li>
                            @endcanany

                            @canany(['user.index'])
                                <li>
                                    <a class="dropdown-item" href="{{ route('user.index') }}">
                                        <i class="bi bi-people"></i>
                                        <span>Users</span>
                                    </a>
                                </li>
                            @endcanany

                            @canany(['role.index'])
                                <li>
                                    <a class="dropdown-item" href="{{ route('role.index') }}">
                                        <i class="bi bi-shield-lock-fill"></i>
                                        <span> Roles & Permissions </span>
                                    </a>
                                </li>
                            @endcanany
                        </ul>
                    </div>
                </li>

                <!-- Notification button ======================================= -->
                {{-- <li class="nav-item dropdown notification-dropdown">
                    <a class="nav-link notification-button has-notifications" href="#" role="button"
                        id="notification-dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-bell"></i>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="notification-dropdown">
                        <div class="dropdown-header d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">Notifications</h6>
                            <span class="badge bg-primary">3</span>
                        </div>
                        <div class="dropdown-divider"></div>
                        <div class="notification-item unread">
                            <div class="notification-content">
                                <div class="notification-icon">
                                    <i class="bi bi-calendar-check"></i>
                                </div>
                                <div class="notification-text">
                                    <p>New meeting scheduled for tomorrow</p>
                                    <small>2 minutes ago</small>
                                </div>
                            </div>
                        </div>
                        <div class="notification-item">
                            <div class="notification-content">
                                <div class="notification-icon">
                                    <i class="bi bi-people"></i>
                                </div>
                                <div class="notification-text">
                                    <p>You have been added to a project team</p>
                                    <small>1 hour ago</small>
                                </div>
                            </div>
                        </div>
                        <div class="notification-item">
                            <div class="notification-content">
                                <div class="notification-icon">
                                    <i class="bi bi-chat"></i>
                                </div>
                                <div class="notification-text">
                                    <p>New message from John Doe</p>
                                    <small>3 hours ago</small>
                                </div>
                            </div>
                        </div>
                        <div class="dropdown-divider"></div>
                        <div class="dropdown-footer">
                            <a href="#">View all notifications</a>
                        </div>
                    </div>
                </li> --}}

                <!-- User button ======================================= -->
                <li class="nav-item dropdown">
                    <a class="nav-link user-button" href="#" role="button" id="user-dropdown"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ Vite::asset('resources/assets/images/users/user.png') }}" alt="user">
                    </a>
                    <div class="dropdown-menu" aria-labelledby="user-dropdown">
                        <ul>
                            <li>
                                <a class="dropdown-item double-line"
                                    href="{{ route('user.show', auth()->user()?->id) }}">
                                    <img src="{{ Vite::asset('resources/assets/images/users/user.png') }}"
                                        alt="User Image">
                                    <div>
                                        <h5>{{ auth()->user()?->name }}</h5>
                                        <span>{{ auth()->user()?->roles->first()?->name }}</span>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <hr>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('user.show', auth()->user()?->id) }}">
                                    <i class="bi bi-gear"></i>
                                    <span>Profile &amp; Settings</span>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" target="__blank" href="{{ route('help-and-support') }}">
                                    <i class="bi bi-question-circle"></i>
                                    <span>Help &amp; Support</span>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="bi bi-box-arrow-right"></i>
                                    Logout
                                </a>
                                <form id="logout-form" class="d-none" action="{{ route('logout') }}" method="POST">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
            <!-- End navMenu ======================================= -->

            <!-- Start search-bar =================================== -->
            <div class="search-bar" id="search-bar">
                <form class="h-100">
                    <input class="form-control" id="search-input" type="text" placeholder="Search">
                    <button type="button" class="close-button" onclick="searchFunction()"><i
                            class="bi bi-x-lg"></i></button>
                </form>
            </div>
            <!-- End search-bar =================================== -->

        </div>
    </nav>
</header>
<!-- End Navbar =================================== -->
