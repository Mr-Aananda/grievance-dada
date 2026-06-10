<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('layouts.partials.head')

<body>
    <!-- Start navigation  -->
    @include('layouts.partials.navbar')
    <!-- End navigation  -->

    <!-- start aside-bar -->
    @include('layouts.partials.asidebar')
    <!-- end aside-bar -->

    <!-- Main Content Area -->
    <main class="main-bar" id="main-bar">
        <div class="container-fluid px-5">
            {{-- show error or success message --}}
            <x-alert />

            {{-- main body start --}}
            {{ $slot }}

            @if (view()->exists('components.spinner'))
                @include('components.spinner')
            @endif
        </div>
    </main>
    <!-- Javascript -->
    @include('layouts.partials.script')
    <!-- Custom Scripts -->
    @stack('script')
</body>

</html>
