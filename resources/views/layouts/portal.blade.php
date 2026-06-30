<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('layouts.partials.head')

<body style="background-color: #f4f6f9 !important;">

    {{-- ── Admin-matched Top Navbar ── --}}
    <nav class="gms-portal-nav">
        <div class="gms-portal-nav-inner">
            <div class="d-flex align-items-center gap-3">
                <div class="gms-portal-nav-logo">
                    <img src="{{ Vite::asset('resources/assets/images/logo/dada_bg.png') }}" alt="DADA Logo">
                </div>
                <div>
                    <div class="gms-portal-nav-title">DADA (Dhaka) Ltd.</div>
                    <div class="gms-portal-nav-sub">
                        <span class="gms-portal-dot"></span> Grievance Redressal Portal
                    </div>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="gms-portal-badge">
                    <i class="bi bi-shield-check-fill me-1"></i>Secure & Confidential
                </span>
            </div>
        </div>
    </nav>

    {{-- ── Page Content ── --}}
    <div class="pb-5" style="padding-top: 72px;">
        <div class="container-fluid px-3 px-md-4">
            <x-alert />
            {{ $slot }}
            @if (view()->exists('components.spinner'))
                @include('components.spinner')
            @endif
        </div>
    </div>

    @include('layouts.partials.script')
    @stack('script')
</body>
</html>
