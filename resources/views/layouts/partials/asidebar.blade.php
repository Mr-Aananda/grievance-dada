<!-- Start aside =================================== -->
<aside id="left-aside" class="left-aside" onmouseenter="handleMouseEnter()" onmouseleave="handleMouseLeave()">
    <ul class="accordion" id="dropdown-menu">

        <!-- Dashboard Section -->
        @can('dashboard')
            <li class="accordion-item mb-3">
                <a href="{{ route('dashboard') }}"
                    class="single-nav-link dashboard-active {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                    data-dashboard="true">
                    <i class="bi bi-grid-1x2-fill"></i>
                    <span>Dashboard</span>
                </a>
            </li>
        @endcan

        <!-- Modules Section Header -->
        @canany(['complain.index', 'complain.show', 'complain.create', 'complain.edit', 'department.index',
            'department.show', 'section.index', 'section.show', 'category.index', 'category.show', 'complain-type.index',
            'complain-type.show'])
            <li class="accordion-item">
                <span class="accordion-item-title">MODULES</span>
            </li>
        @endcanany

        <!-- Complain Dropdown -->
        @canany(['complain.index', 'complain.create', 'complain.edit', 'complain.show'])
            <li class="accordion-item">
                <a href="#" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#complain"
                    aria-expanded="false" aria-controls="complain">
                    <i class="bi bi-exclamation-square"></i>
                    <span class="me-auto">Complains/Manuals</span>
                    <i class="bi bi-chevron-down"></i>
                </a>

                <ul id="complain" class="accordion-collapse collapse" data-bs-parent="#dropdown-menu">
                    <li>
                        <x-link route="complain.index" :checkActive="['complain.index', 'complain.show']" name="All Entries" />
                    </li>
                    <li>
                        <x-link route="complain.create" :checkActive="['complain.create', 'complain.edit']" name="New Complain" />
                    </li>
                    <li>
                        <x-link route="complain.manual" :checkActive="['complain.manual', 'complain.edit']" name="New Manual" />
                    </li>
                </ul>
            </li>
        @endcanany

        <!-- Settings Dropdown -->
        @canany(['department.index', 'department.show', 'section.index', 'section.show', 'category.index',
            'category.show', 'complain-type.index', 'complain-type.show', 'buyer.index', 'buyer.show'])
            <li class="accordion-item">
                <a href="#" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#settings"
                    aria-expanded="false" aria-controls="settings">
                    <i class="bi bi-gear-fill"></i>
                    <span class="me-auto">Settings</span>
                    <i class="bi bi-chevron-down"></i>
                </a>

                <ul id="settings" class="accordion-collapse collapse" data-bs-parent="#dropdown-menu">
                    <!-- Complain Type -->
                    @canany(['complain-type.index', 'complain-type.show', 'complain-type.create', 'complain-type.edit'])
                        <li>
                            <x-link route="complain-type.index" :checkActive="[
                                'complain-type.index',
                                'complain-type.show',
                                'complain-type.create',
                                'complain-type.edit',
                            ]" name="Complain/Manual Types" />
                        </li>
                    @endcanany

                    <!-- Category -->
                    @canany(['category.index', 'category.show', 'category.create', 'category.edit'])
                        <li>
                            <x-link route="category.index" :checkActive="['category.index', 'category.show', 'category.create', 'category.edit']" name="Categories" />
                        </li>
                    @endcanany

                    <!-- Department -->
                    @canany(['department.index', 'department.show', 'department.create', 'department.edit'])
                        <li>
                            <x-link route="department.index" :checkActive="['department.index', 'department.show', 'department.create', 'department.edit']" name="Departments" />
                        </li>
                    @endcanany

                    <!-- Section -->
                    @canany(['section.index', 'section.show', 'section.create', 'section.edit'])
                        <li>
                            <x-link route="section.index" :checkActive="['section.index', 'section.show', 'section.create', 'section.edit']" name="Sections" />
                        </li>
                    @endcanany
                    <!-- Buyer -->
                    @canany(['buyer.index', 'buyer.show', 'buyer.create', 'buyer.edit', 'buyer.trash'])
                        <li>
                            <x-link route="buyer.index" :checkActive="['buyer.index', 'buyer.show', 'buyer.create', 'buyer.edit', 'buyer.trash']" name="Buyers" />
                        </li>
                    @endcanany




                </ul>
            </li>
        @endcanany

        <!-- Report Dropdown -->
        @canany(['reports.overall-report'])
            <li class="accordion-item">
                <a href="#" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#report"
                    aria-expanded="false" aria-controls="report">
                    <i class="bi bi-bar-chart-line"></i>
                    <span class="me-auto">Reports</span>
                    <i class="bi bi-chevron-down"></i>
                </a>

                <ul id="report" class="accordion-collapse collapse" data-bs-parent="#dropdown-menu">
                    <li>
                        <x-link route="reports.overall-report" :checkActive="['reports.overall-report']" name="Overall Report" />
                    </li>
                </ul>
            </li>
        @endcanany

    </ul>
    <!-- Sidebar Footer -->
    <div class="sidebar-footer">
        <div class="sidebar-version">Dada Dhaka Ltd</div>
    </div>
</aside>
<!-- End aside =================================== -->

<div id="aside-layer" onclick="toggleSidebar()"></div>
