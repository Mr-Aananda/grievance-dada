<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('layouts.partials.head')

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        <!-- Start navigation  -->
        @include('layouts.partials.navbar')
        <!-- End navigation  -->

        <!-- start aside-bar -->
        @include('layouts.partials.asidebar')
        <!-- end aside-bar -->

        <!-- Main Content Area -->
        <main class="app-main">
            <!-- App Content Header -->
            <div class="app-content-header py-2 mb-2">
                <div class="container-fluid px-4">
                    <div class="row align-items-center">
                        <div class="col-sm-6">
                            <h3 class="mb-0 fw-bold">@yield('title')</h3>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-end mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">@yield('title')</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="app-content py-2">
                <div class="container-fluid px-4">
                    {{-- show error or success message --}}
                    <x-alert />

                    {{-- main body start --}}
                    {{ $slot }}

                    @if (view()->exists('components.spinner'))
                        @include('components.spinner')
                    @endif
                </div>
            </div>
        </main>
    </div>
    <!-- Javascript -->
    @include('layouts.partials.script')
    <!-- Custom Scripts -->
    @stack('script')
</body>

</html>
