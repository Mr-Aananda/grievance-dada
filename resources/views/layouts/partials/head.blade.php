<head>
    <!-- Title -->
    <title>@yield('title') | {{ config('app.name') }}</title>

    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="base-url" content="{{ url('/') }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ Vite::asset('resources/assets/icons/favicon.ico')}}" type="image/x-icon">

    <!-- Bootstrap icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Assistant:wght@200;300;400;500;600;700;800&family=Noto+Sans+Bengali:wght@100;200;300;400;500;600;700;800;900&display=swap">

    <!-- Main JS & CSS -->
    @vite(['resources/js/app.js'])

    @stack('style')
</head>
