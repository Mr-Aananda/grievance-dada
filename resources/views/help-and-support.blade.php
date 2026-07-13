<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Help & Support') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f9f9f9;
            font-family: 'Arial', sans-serif;
        }
        .card {
            border-radius: 15px;
            background-color: #ffffff;
        }
        .button {
            background-color: #f47b22;
            border-color: #f47b22;
            color:#ffff;
            transition: all 0.3s ease;
        }
        .button:hover {
            /* background-color: #ffffff; */
            color: #f47b22;
            border-color: #f47b22;
        }
        .text {
            color: #f47b22 !important;
        }
        .card-title {
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container my-5">
        <!-- Header Section -->
        <header class="text-center mb-5">
            <h1 class="display-5 text fw-bold">{{ __('Help & Support') }}</h1>
            <p class="lead">Dada (Dhaka) Ltd - {{ __('IT Department') }}</p>
        </header>

        <!-- Information Section -->
        <div class="card shadow-lg border-0">
            <div class="card-body text-center">
                <h2 class="card-title text mb-4">{{ __('We Are Here to Help!') }}</h2>
                <p class="fs-5">
                    {{ __('This project is an') }} <span class="fw-bold">{{ __('In-House Project') }}</span> {{ __('developed by the') }}
                    <span class="fw-bold">{{ __('IT Department') }}</span> {{ __('of Dada (Dhaka) Ltd.') }}
                </p>
                <p class="fs-5">
                    {{ __('If you encounter any issues or need assistance, please reach out to our IT support team.') }}
                </p>
                <hr class="my-4">
                <div class="contact-info">
                    <h5 class="fw-bold">{{ __('Contact Information') }}</h5>
                    <p>{{ __('Email:') }} <a href="mailto:dka_it@dadadhaka.com" class="text-decoration-none text">dka_it@dadadhaka.com</a></p>
                    <p>{{ __('Office Hours:') }} <span class="fw-bold">{{ __('Saturday - Thursday, 8 AM to 5.30 PM') }}</span></p>
                </div>
                <!-- Return Back Button -->
                <a href="{{ route('dashboard') }}" class="btn button mt-4">{{ __('Return Back') }}</a>
            </div>
        </div>

        <!-- Footer Section -->
        <footer class="text-center mt-5">
            <p class="text-muted">&copy; {{ date('Y') }} Dada (Dhaka) Ltd. {{ __('All Rights Reserved.') }} {{ __('Developed by the IT Department.') }}</p>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
