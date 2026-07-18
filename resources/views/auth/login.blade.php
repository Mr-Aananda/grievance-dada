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
    background: linear-gradient(135deg, rgba(0,0,0,0.65) 0%, rgba(0,0,0,0.45) 100%);
    backdrop-filter: blur(3px);
    z-index: 0;
}

/* ─── Premium Dark Glassmorphism card ─── */
.gms-login-card {
    position: relative;
    z-index: 1;
    width: 100%;
    max-width: 370px;
    background: #212529; /* Standard dark mode component background */
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 20px;
    padding: 16px 30px 32px; /* Reduced top padding to move elements up */
    box-shadow: 0 20px 50px rgba(0,0,0,0.5), inset 0 1px 0 rgba(255,255,255,0.06);
    text-align: center;
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}

.gms-login-card:hover {
    border-color: rgba(255, 255, 255, 0.12);
    box-shadow: 0 25px 60px rgba(0,0,0,0.6), inset 0 1px 0 rgba(255,255,255,0.08);
}

/* ─── Logo (Moved up slightly) ─── */
.gms-login-logo {
    margin-top: -12px; /* Pulls logo up to cancel empty margins */
    margin-bottom: 8px;
}

.gms-login-logo-img {
    max-width: 135px;
    filter: brightness(0) invert(1);
    opacity: 0.95;
    transition: transform 0.3s ease;
}

.gms-login-logo:hover .gms-login-logo-img {
    transform: scale(1.03);
}

.gms-login-bg-hidden { display: none; }

/* ─── Heading ─── */
.gms-login-title {
    font-size: 21px;
    font-weight: 700;
    color: #ffffff;
    margin: 0 0 2px 0;
    letter-spacing: -0.3px;
}

.gms-login-subtitle {
    font-size: 11.5px;
    color: rgba(255,255,255,0.55);
    margin: 0 0 18px 0;
    letter-spacing: 0.3px;
}

/* ─── Form ─── */
.gms-login-form {
    text-align: left;
}

.gms-login-field {
    margin-bottom: 12px;
}

.gms-login-label {
    display: block;
    font-size: 11.5px;
    font-weight: 600;
    color: rgba(255,255,255,0.7);
    margin-bottom: 5px;
    letter-spacing: 0.5px;
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
    color: rgba(255,255,255,0.45);
    font-size: 14px;
    z-index: 2;
    pointer-events: none;
}

.gms-login-input {
    width: 100%;
    height: 42px;
    background: rgba(15, 19, 26, 0.55); /* Match dark theme input field style #151922 */
    border: 1px solid rgba(255, 255, 255, 0.12);
    border-radius: 8px;
    padding: 0 42px 0 40px;
    font-size: 13.5px;
    color: #f8fafc;
    outline: none;
    transition: all 0.2s ease-in-out;
}

.gms-login-input::placeholder {
    color: rgba(255, 255, 255, 0.3);
}

.gms-login-input:focus {
    border-color: rgba(245, 123, 32, 0.85); /* Accent brand color */
    background: rgba(15, 19, 26, 0.75);
    box-shadow: 0 0 0 3px rgba(245, 123, 32, 0.22);
}

.gms-login-input.is-invalid {
    border-color: rgba(239, 68, 68, 0.8) !important;
    box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.18) !important;
}

.gms-login-eye {
    position: absolute;
    right: 12px;
    background: none;
    border: none;
    color: rgba(255,255,255,0.45);
    font-size: 14px;
    cursor: pointer;
    padding: 0;
    z-index: 2;
    transition: color 0.2s;
}

.gms-login-eye:hover { color: rgba(255,255,255,0.8); }

.gms-login-error {
    margin-top: 4px;
    font-size: 11.5px;
    color: #f87171;
}

.gms-login-caps {
    font-size: 11px;
    color: #fbbf24;
    margin-top: 4px;
    display: block;
}

/* ─── Submit button ─── */
.gms-login-btn {
    width: 100%;
    height: 44px;
    margin-top: 8px;
    background: linear-gradient(135deg, #F57B20 0%, #d85f0b 100%);
    border: none;
    border-radius: 8px;
    color: #ffffff;
    font-size: 14.5px;
    font-weight: 600;
    letter-spacing: 0.3px;
    cursor: pointer;
    transition: all 0.2s ease-in-out;
    box-shadow: 0 4px 15px rgba(245, 123, 32, 0.3);
}

.gms-login-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(245, 123, 32, 0.45);
    background: linear-gradient(135deg, #ff8933 0%, #e06510 100%);
}

.gms-login-btn:active {
    transform: translateY(0);
}

/* ─── Responsive ─── */
@media (max-width: 480px) {
    .gms-login-card {
        padding: 16px 22px 28px;
    }
}
</style>
@endpush
</x-guest-layout>
