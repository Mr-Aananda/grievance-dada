@section('title', 'QMS - Quality Management System')

<x-guest-layout>
    <!-- Centered Section -->
    <div class="d-flex justify-content-center align-items-center min-vh-100 bg-light position-relative overflow-hidden">
        <!-- Soft Gradient Background -->
        <div class="position-absolute top-0 start-0 w-100 h-100"
             style="background: linear-gradient(135deg, #e3f2fd 0%, #f1f8e9 100%); z-index:-1;">
        </div>

        <!-- Card -->
        <div class="card text-center shadow-lg border-0 animate__animated animate__fadeInUp"
             style="max-width: 600px; width: 100%; border-radius: 24px;">

            <!-- Logo Section -->
            <div class="card-header py-4 bg-white border-0"
                 style="border-top-left-radius: 24px; border-top-right-radius: 24px;">
                <img src="{{ Vite::asset('resources/assets/images/logo/dada_bg.png') }}"
                     alt="QMS Logo"
                     class="img-fluid"
                     style="max-height: 90px;">
            </div>

            <!-- Welcome Section -->
            <div class="card-body px-4 py-5" style="background-color: #f9fafc;">
                <h1 class="fw-bold mb-3" style="font-size: 2rem; color: #1b4332;">
                    Quality Management System
                </h1>
                <p class="text-muted mb-4" style="font-size: 1.1rem;">
                    Streamline inspection, monitor quality data, and ensure garment excellence — all in one smart platform.
                </p>

                <!-- Button Group -->
                <div class="d-flex flex-column flex-sm-row justify-content-center gap-3">
                    <a href="{{ route('login') }}" class="btn btn-success btn-lg px-5">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Login
                    </a>
                    <a href="{{ route('help-and-support') }}" class="btn btn-outline-dark btn-lg px-5">
                        <i class="bi bi-question-circle me-2"></i>Help & Support
                    </a>
                </div>
            </div>

            <!-- Footer Section -->
            <div class="card-footer py-3 small"
                 style="border-bottom-left-radius: 24px; border-bottom-right-radius: 24px; background-color: #eef3f7;">
                &copy; {{ date('Y') }} Dada (Dhaka) Ltd. — Quality Management System <br>
                <span class="text-secondary">Developed by the IT Department.</span>
            </div>
        </div>
    </div>

    <style>
        body {
            background-color: #f4f6f8;
            font-family: 'Nunito', sans-serif;
            margin: 0;
        }

        .card {
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-6px);
        }

        h1 {
            letter-spacing: 0.5px;
        }

        .btn {
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 40px;
            transition: all 0.3s ease;
        }

        .btn-success {
            background-color: #2e7d32;
            border: 2px solid #2e7d32;
        }

        .btn-success:hover {
            background-color: #1b5e20;
            border-color: #1b5e20;
        }

        .btn-outline-dark {
            color: #333;
            border: 2px solid #333;
        }

        .btn-outline-dark:hover {
            background-color: #333;
            color: #fff;
        }

        @media (max-width: 576px) {
            h1 {
                font-size: 1.6rem;
            }

            .btn {
                font-size: 1rem;
                width: 100%;
            }

            .card {
                margin: 0 15px;
            }
        }
    </style>
</x-guest-layout>
