<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('layouts.partials.head')

<body class="gms-portal-body-bg">
    <div id="vueRoot">
        {{-- ── Admin-matched Top Navbar ── --}}
        <nav :class="['gms-portal-nav', activeTab === 'submit' ? 'nav-submit' : 'nav-track']">
            <div class="gms-portal-nav-inner">
                <!-- Left: Branding -->
                <div class="gms-portal-nav-brand d-flex align-items-center gap-3">
                    <div class="gms-portal-nav-logo">
                        <img src="{{ Vite::asset('resources/assets/images/logo/dada_bg.png') }}" alt="DADA Logo">
                    </div>
                    <div class="d-none d-md-block">
                        <div class="gms-portal-nav-title">DADA (Dhaka) Ltd.</div>
                        <div class="gms-portal-nav-sub">
                            <span class="gms-portal-dot"></span> Grievance Redressal Portal
                        </div>
                    </div>
                </div>

                <!-- Middle: Tab Switcher (Vue root template) -->
                <div class="gms-portal-tabs-container">
                    <div class="gms-portal-tabs">
                        <button 
                            :class="['gms-tab-btn', activeTab === 'submit' && 'active']"
                            @click="activeTab = 'submit'"
                        >
                            <i class="bi bi-pencil-square"></i>
                            <span>Submit Grievance</span>
                        </button>
                        <button 
                            :class="['gms-tab-btn', activeTab === 'track' && 'active']"
                            @click="activeTab = 'track'"
                        >
                            <i class="bi bi-search"></i>
                            <span>Track Status</span>
                            <span v-if="totalRecords" class="gms-tab-badge">@{{ totalRecords }}</span>
                        </button>
                    </div>
                </div>

                <!-- Right: Secure & Confidential Badge -->
                <div class="gms-portal-nav-right">
                    <span class="gms-portal-badge">
                        <i class="bi bi-shield-check-fill me-1"></i>Secure &amp; Confidential
                    </span>
                </div>
            </div>
        </nav>

        {{-- ── Page Content ── --}}
        <div class="pb-5 gms-portal-content">
            <div class="container-fluid px-3 px-md-4">
                <x-alert />
                {{ $slot }}
                @if (view()->exists('components.spinner'))
                    @include('components.spinner')
                @endif
            </div>
        </div>
    </div>

    @include('layouts.partials.script')
    @stack('script')
</body>
</html>
