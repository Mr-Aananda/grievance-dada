@section('title', 'Login')

<x-guest-layout>
    <main class="authentication" style="background-image: url('{{ asset('assets/images/background.jpeg') }}')" >
        <div class="widget">

            <!-- Logo -->
            <div class="widget-head">
                <div class="logo">
                    <img
                        src="{{ Vite::asset('resources/assets/images/logo/dada_bg.png') }}"
                        alt="Dada (Dhaka) Ltd">
                </div>
            </div>

            <!-- Body -->
            <div class="widget-body">
                <x-alert />

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="row g-3">

                        <!-- Email / Employee ID -->
                        <div class="col-12">
                            <label for="login" class="form-label required">
                                Employee ID
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-person-badge"></i>
                                </span>
                                <input
                                    type="text"
                                    name="login"
                                    id="login"
                                    value="{{ old('login') }}"
                                    class="form-control @error('login') is-invalid @enderror"
                                    placeholder="Employee ID"
                                    required
                                    autofocus
                                >
                                @error('login')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="col-12">
                            <label for="password" class="form-label required">
                                Password
                            </label>
                            <div class="input-group toggle-password-fill">
                                <span class="input-group-text">
                                    <i class="bi bi-unlock"></i>
                                </span>
                                <input
                                    type="password"
                                    name="password"
                                    id="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Password"
                                    required
                                    onkeydown="capsLock(event)"
                                >
                                <button
                                    type="button"
                                    class="pass-eye"
                                    onclick="show(event, password)">
                                    <i class="bi bi-eye-fill"></i>
                                </button>
                                @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <small id="capsLockText" class="d-none text-danger">
                                Caps lock is on
                            </small>
                        </div>

                        <!-- Login Button -->
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-box-arrow-in-right"></i>
                                Login
                            </button>
                        </div>

                        <!-- Register link -->
                        <div class="col-12 text-center">
                            <a href="{{ route('register') }}">
                                Register
                            </a>
                        </div>

                    </div>
                </form>
            </div>

        </div>
    </main>
</x-guest-layout>
