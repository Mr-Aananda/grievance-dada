<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('layouts.partials.head')

<body class="gms-login-page">
    {{ $slot }}

    @stack('script')
    @include('layouts.partials.auth-script')
</body>
</html>
