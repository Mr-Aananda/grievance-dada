<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('layouts.partials.head')

<body class="gms-portal-body-bg">
    <div id="vueRoot">
        {{-- ── Admin-matched Top Navbar ── --}}
        <nav :class="['gms-portal-nav', activeTab === 'submit' ? 'nav-submit' : 'nav-track']">
            <div class="gms-portal-nav-inner">
                <!-- Left: Branding -->
                <div class="gms-portal-nav-brand d-flex align-items-center gap-2 gap-md-3">
                    <div class="gms-portal-nav-logo">
                        <img src="{{ Vite::asset('resources/assets/images/logo/dada_bg.png') }}" alt="DADA Logo">
                    </div>
                    <div>
                        <div class="gms-portal-nav-title">DADA (Dhaka) Ltd.</div>
                        <div class="gms-portal-nav-sub d-none d-sm-flex align-items-center gap-1">
                            <span class="gms-portal-dot"></span> {{ __('Grievance Redressal Portal') }}
                        </div>
                    </div>
                </div>

                <!-- Middle: Tab Switcher (Vue root template) -->
                <div class="gms-portal-tabs-container">
                    <div class="gms-portal-tabs">
                        <button 
                            :class="['gms-tab-btn', activeTab === 'submit' ? 'active' : '']"
                            @click="activeTab = 'submit'"
                        >
                            <i class="bi bi-pencil-square"></i>
                            <span>Submit Grievance</span>
                        </button>
                        <button 
                            :class="['gms-tab-btn', activeTab === 'track' ? 'active' : '']"
                            @click="activeTab = 'track'"
                        >
                            <i class="bi bi-search"></i>
                            <span>Track Status</span>
                            <span v-if="totalRecords" class="gms-tab-badge">@{{ totalRecords }}</span>
                        </button>
                    </div>
                </div>

                <!-- Right: Secure & Confidential Badge & Language Switcher -->
                <div class="gms-portal-nav-right d-flex align-items-center gap-2 gap-sm-3">
                    <!-- Theme Toggle Button -->
                    <button id="gms-theme-toggle" type="button" class="btn btn-sm btn-outline-secondary d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; border-radius: 50%; padding: 0;" title="Toggle Theme">
                        <i class="bi bi-sun"></i>
                        <i class="bi bi-moon"></i>
                    </button>

                    <div class="dropdown gms-lang-dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle d-flex align-items-center" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-translate me-1"></i>
                            <span class="small fw-semibold ms-1">
                                @if(app()->getLocale() == 'bn')
                                    <span class="d-none d-md-inline">বাংলা</span>
                                    <span class="d-inline d-md-none">BN</span>
                                @elseif(app()->getLocale() == 'ko')
                                    <span class="d-none d-md-inline">한국어</span>
                                    <span class="d-inline d-md-none">KO</span>
                                @else
                                    <span class="d-none d-md-inline">English</span>
                                    <span class="d-inline d-md-none">EN</span>
                                @endif
                            </span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow">
                            <li>
                                <a class="dropdown-item d-flex align-items-center {{ app()->getLocale() == 'en' ? 'active' : '' }}" href="{{ route('lang.switch', 'en') }}">
                                    <span class="me-2">🇺🇸</span> English
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center {{ app()->getLocale() == 'bn' ? 'active' : '' }}" href="{{ route('lang.switch', 'bn') }}">
                                    <span class="me-2">🇧🇩</span> বাংলা (Bangla)
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center {{ app()->getLocale() == 'ko' ? 'active' : '' }}" href="{{ route('lang.switch', 'ko') }}">
                                    <span class="me-2">🇰🇷</span> 한국어 (Korean)
                                </a>
                            </li>
                        </ul>
                    </div>
                    <span class="gms-portal-badge d-none d-sm-inline-flex">
                        <i class="bi bi-shield-check-fill me-1"></i>@lang('Secure & Confidential')
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

    <script>
        document.addEventListener('click', function(event) {
            const toggleButton = event.target.closest('#gms-theme-toggle');
            if (toggleButton) {
                const currentTheme = document.documentElement.getAttribute('data-bs-theme') || 'light';
                const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                document.documentElement.setAttribute('data-bs-theme', newTheme);
                localStorage.setItem('theme', newTheme);
            }
        });
    </script>
</body>
</html>
