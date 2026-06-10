<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('layouts.partials.head')

    <body>
        {{ $slot }}

        <!-- Javascript -->
        {{-- <script src="{{Vite::asset("resources/assets/js/form.js")}}"></script> --}}
        {{-- @include('layouts.partials.script') --}}
        <!-- Custom Scripts -->
        @stack('script')
                <!-- Javascript -->
        @include('layouts.partials.auth-script')
    </body>
</html>
