@section('title', 'Admin Login')

<x-guest-layout>
<div class="gms-login-wrapper">

    {{-- Background overlay --}}
    <div class="gms-login-overlay"></div>

    {{-- Login Card --}}
    <div class="gms-login-card">

        {{-- Logo --}}
        <div class="gms-login-logo">
            <img src="{{ asset('assets/images/background.jpeg') }}" alt="" class="gms-login-bg-hidden" aria-hidden="true">
            <img src="{{ Vite::asset('resources/assets/images/logo/dada_bg.png') }}" alt="Dada (Dhaka) Ltd" class="gms-login-logo-img">
        </div>

        {{-- Heading --}}
        <h1 class="gms-login-title">Sign In</h1>
        <p class="gms-login-subtitle">Grievance Management System</p>

        <x-alert />

        <form method="POST" action="{{ route('login') }}" class="gms-login-form">
            @csrf

            {{-- Employee ID --}}
            <div class="gms-login-field">
                <label for="login" class="gms-login-label">Employee ID</label>
                <div class="gms-login-input-wrap">
                    <span class="gms-login-icon"><i class="bi bi-person-badge"></i></span>
                    <input
                        type="text"
                        name="login"
                        id="login"
                        value="{{ old('login') }}"
                        class="gms-login-input @error('login') is-invalid @enderror"
                        placeholder="Enter your Employee ID"
                        required
                        autofocus
                    >
                </div>
                @error('login')
                    <div class="gms-login-error">{{ $message }}</div>
                @enderror
            </div>

            {{-- Password --}}
            <div class="gms-login-field">
                <label for="password" class="gms-login-label">Password</label>
                <div class="gms-login-input-wrap">
                    <span class="gms-login-icon"><i class="bi bi-lock"></i></span>
                    <input
                        type="password"
                        name="password"
                        id="password"
                        class="gms-login-input @error('password') is-invalid @enderror"
                        placeholder="Enter your password"
                        required
                        onkeydown="capsLock(event)"
                    >
                    <button type="button" class="gms-login-eye" onclick="show(event, password)" tabindex="-1">
                        <i class="bi bi-eye-fill"></i>
                    </button>
                </div>
                <small id="capsLockText" class="d-none gms-login-caps">⚠ Caps Lock is on</small>
                @error('password')
                    <div class="gms-login-error">{{ $message }}</div>
                @enderror
            </div>

            {{-- Submit --}}
            <button type="submit" class="gms-login-btn">
                <i class="bi bi-box-arrow-in-right me-2"></i> Sign In
            </button>

        </form>
    </div>
</div>

@push('style')
<style>
/* ─── Full-screen background ─── */
.gms-login-page {
    margin: 0;
    padding: 0;
    min-height: 100vh;
    background: url('{{ asset('assets/images/background.jpeg') }}') center center / cover no-repeat fixed;
    display: flex;
    align-items: center;
    justify-content: center;
}

.gms-login-wrapper {
    position: relative;
    width: 100%;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 24px 16px;
}

.gms-login-overlay {
    position: fixed;
    inset: 0;
    background: linear-gradient(135deg, rgba(0,0,0,0.55) 0%, rgba(0,0,0,0.35) 100%);
    backdrop-filter: blur(2px);
    z-index: 0;
}

/* ─── Glassmorphism card ─── */
.gms-login-card {
    position: relative;
    z-index: 1;
    width: 100%;
    max-width: 360px;
    background: rgba(255, 255, 255, 0.10);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.22);
    border-radius: 18px;
    padding: 28px 28px 32px;
    box-shadow: 0 24px 60px rgba(0,0,0,0.35), inset 0 1px 0 rgba(255,255,255,0.15);
    text-align: center;
}

/* ─── Logo ─── */
.gms-login-logo {
    margin-bottom: 12px;
}

.gms-login-logo-img {
    max-width: 140px;
    filter: brightness(0) invert(1);
    opacity: 0.92;
}

.gms-login-bg-hidden { display: none; }

/* ─── Heading ─── */
.gms-login-title {
    font-size: 22px;
    font-weight: 700;
    color: #ffffff;
    margin: 0 0 3px 0;
    letter-spacing: -0.3px;
}

.gms-login-subtitle {
    font-size: 12px;
    color: rgba(255,255,255,0.65);
    margin: 0 0 20px 0;
    letter-spacing: 0.3px;
}

/* ─── Form ─── */
.gms-login-form {
    text-align: left;
}

.gms-login-field {
    margin-bottom: 13px;
}

.gms-login-label {
    display: block;
    font-size: 12.5px;
    font-weight: 600;
    color: rgba(255,255,255,0.80);
    margin-bottom: 7px;
    letter-spacing: 0.3px;
    text-transform: uppercase;
}

.gms-login-input-wrap {
    position: relative;
    display: flex;
    align-items: center;
}

.gms-login-icon {
    position: absolute;
    left: 14px;
    color: rgba(255,255,255,0.55);
    font-size: 15px;
    z-index: 2;
    pointer-events: none;
}

.gms-login-input {
    width: 100%;
    height: 42px;
    background: rgba(255,255,255,0.10);
    border: 1px solid rgba(255,255,255,0.22);
    border-radius: 10px;
    padding: 0 44px 0 42px;
    font-size: 14px;
    color: #ffffff;
    outline: none;
    transition: border-color 0.2s, background 0.2s;
}

.gms-login-input::placeholder {
    color: rgba(255,255,255,0.38);
}

.gms-login-input:focus {
    border-color: rgba(245, 123, 32, 0.8);
    background: rgba(255,255,255,0.14);
    box-shadow: 0 0 0 3px rgba(245, 123, 32, 0.18);
}

.gms-login-input.is-invalid {
    border-color: rgba(220,53,69,0.7);
}

.gms-login-eye {
    position: absolute;
    right: 12px;
    background: none;
    border: none;
    color: rgba(255,255,255,0.50);
    font-size: 15px;
    cursor: pointer;
    padding: 0;
    z-index: 2;
    transition: color 0.2s;
}

.gms-login-eye:hover { color: rgba(255,255,255,0.85); }

.gms-login-error {
    margin-top: 5px;
    font-size: 12px;
    color: #ff7c8a;
}

.gms-login-caps {
    font-size: 11.5px;
    color: #ffc107;
    margin-top: 4px;
    display: block;
}

/* ─── Submit button ─── */
.gms-login-btn {
    width: 100%;
    height: 48px;
    margin-top: 8px;
    background: linear-gradient(135deg, #F57B20 0%, #e06510 100%);
    border: none;
    border-radius: 10px;
    color: #ffffff;
    font-size: 15px;
    font-weight: 700;
    letter-spacing: 0.3px;
    cursor: pointer;
    transition: transform 0.15s, box-shadow 0.15s, opacity 0.15s;
    box-shadow: 0 6px 20px rgba(245, 123, 32, 0.42);
}

.gms-login-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 10px 28px rgba(245, 123, 32, 0.55);
    opacity: 0.95;
}

.gms-login-btn:active {
    transform: translateY(0);
}

/* ─── Responsive ─── */
@media (max-width: 480px) {
    .gms-login-card {
        padding: 32px 22px;
    }
}
</style>
@endpush
</x-guest-layout>
