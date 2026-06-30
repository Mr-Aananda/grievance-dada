<!-- Start AdminLTE 4 Sidebar =================================== -->
<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <!-- Brand Logo -->
    <div class="sidebar-brand">
        <a href="{{ route('dashboard') }}" class="brand-link text-decoration-none">
            <span class="brand-text fw-bold" style="font-size:22px; letter-spacing:1px;">GMS Admin</span>
        </a>
    </div>

    <!-- Sidebar Wrapper -->
    <div class="sidebar-wrapper">


        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">

                <!-- Dashboard Section -->
                @can('dashboard')
                    <li class="nav-item mb-2">
                        <a href="{{ route('dashboard') }}"
                            class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-speedometer2"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                @endcan

                <!-- Modules Section Header -->
                @canany(['department.index', 'department.show', 'section.index', 'section.show', 'category.index',
                    'category.show'])
                    <li class="nav-header">MODULES</li>
                @endcanany

                <!-- Grievance Tickets Tracking -->
                <li class="nav-item mb-2">
                    <a href="{{ route('admin.grievance.index') }}"
                        class="nav-link {{ request()->is('admin/grievances*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-ticket-perforated"></i>
                        <p>Grievance Tickets</p>
                    </a>
                </li>

                <!-- Departments Link -->
                @canany(['department.index', 'department.show', 'department.create', 'department.edit'])
                    <li class="nav-item">
                        <a href="{{ route('department.index') }}"
                            class="nav-link {{ request()->is('admin/department*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-building"></i>
                            <p>Departments</p>
                        </a>
                    </li>
                @endcanany

                <!-- Sections Link -->
                @canany(['section.index', 'section.show', 'section.create', 'section.edit'])
                    <li class="nav-item">
                        <a href="{{ route('section.index') }}"
                            class="nav-link {{ request()->is('admin/section*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-diagram-3"></i>
                            <p>Sections</p>
                        </a>
                    </li>
                @endcanany

                <!-- Categories Link -->
                @canany(['category.index', 'category.show', 'category.create', 'category.edit'])
                    <li class="nav-item">
                        <a href="{{ route('category.index') }}"
                            class="nav-link {{ request()->is('admin/category*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-tags"></i>
                            <p>Categories</p>
                        </a>
                    </li>
                @endcanany

            </ul>
        </nav>
    </div>
</aside>
<!-- End AdminLTE 4 Sidebar =================================== -->
