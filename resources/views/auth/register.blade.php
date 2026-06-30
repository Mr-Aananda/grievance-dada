@section('title', 'Register')

<x-guest-layout>
    <div class="register-box" style="min-width: 360px; max-width: 480px; margin: 2rem auto;">
        <div class="register-logo text-center mb-4">
            <a href="{{ route('dashboard') }}">
                <img src="{{ Vite::asset('resources/assets/images/logo/dada_bg.png') }}" alt="Dada (Dhaka) Ltd" style="max-width: 220px;">
            </a>
        </div>
        <!-- /.register-logo -->
        
        <div class="card card-outline card-primary shadow border-0">
            <div class="card-body register-card-body p-4">
                <p class="login-box-msg text-center fw-bold fs-5 mb-2">Create GMS Account</p>
                <p class="text-muted text-center small mb-4">Fill in the fields to request registration</p>

                <x-alert />

                <form method="POST"
                      action="{{ route('register') }}"
                      x-data="registerForm()"
                      x-init="init()">
                    @csrf

                    <div class="row g-3">
                        <!-- Employee ID -->
                        <div class="col-12">
                            <label class="form-label fw-semibold required">Employee ID</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-person-badge"></i>
                                </span>
                                <input type="text"
                                       name="emp_id"
                                       class="form-control border-start-0 @error('emp_id') is-invalid @enderror"
                                       placeholder="Employee ID"
                                       value="{{ old('emp_id') }}"
                                       required>
                                @error('emp_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Name -->
                        <div class="col-12">
                            <label class="form-label fw-semibold required">Full Name</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-person"></i>
                                </span>
                                <input type="text"
                                       name="name"
                                       class="form-control border-start-0 @error('name') is-invalid @enderror"
                                       placeholder="Full name"
                                       value="{{ old('name') }}"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Department -->
                        <div class="col-12">
                            <label class="form-label fw-semibold required">Department</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-building"></i>
                                </span>
                                <select class="form-select border-start-0 @error('department_id') is-invalid @enderror"
                                        name="department_id"
                                        x-model="selectedDepartment"
                                        @change="loadSections()"
                                        required>
                                    <option value="">Select Department</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}" @selected(old('department_id') == $department->id)>
                                            {{ $department->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('department_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Section -->
                        <div class="col-12">
                            <label class="form-label fw-semibold required">Section</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-diagram-2"></i>
                                </span>
                                <select class="form-select border-start-0 @error('section_id') is-invalid @enderror"
                                        name="section_id"
                                        x-model="selectedSection"
                                        :disabled="!selectedDepartment || isLoading"
                                        required>
                                    <option value="">Select Section</option>
                                    <template x-for="section in sections" :key="section.id">
                                        <option :value="section.id" x-text="section.name"></option>
                                    </template>
                                </select>
                                @error('section_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="col-12">
                            <label class="form-label fw-semibold required">Password</label>
                            <div class="input-group toggle-password-fill">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-lock"></i>
                                </span>
                                <input type="password"
                                       name="password"
                                       id="password"
                                       class="form-control border-start-0 border-end-0 @error('password') is-invalid @enderror"
                                       placeholder="Password"
                                       required>
                                <button type="button"
                                        class="btn btn-outline-secondary border-start-0"
                                        style="border-top-left-radius: 0; border-bottom-left-radius: 0;"
                                        onclick="show(event, 'password')">
                                    <i class="bi bi-eye-fill"></i>
                                </button>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div class="col-12">
                            <label class="form-label fw-semibold required">Confirm Password</label>
                            <div class="input-group toggle-password-fill">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-lock-fill"></i>
                                </span>
                                <input type="password"
                                       name="password_confirmation"
                                       id="password_confirmation"
                                       class="form-control border-start-0 border-end-0"
                                       placeholder="Confirm password"
                                       required>
                                <button type="button"
                                        class="btn btn-outline-secondary border-start-0"
                                        style="border-top-left-radius: 0; border-bottom-left-radius: 0;"
                                        onclick="show(event, 'password_confirmation')">
                                    <i class="bi bi-eye-fill"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="col-12 mt-4">
                            <button type="submit"
                                    class="btn btn-primary w-100 py-2"
                                    :disabled="isLoading">
                                <i class="bi bi-person-plus me-1"></i>
                                <span x-show="!isLoading">Register</span>
                                <span x-show="isLoading" style="display: none;">
                                    <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                                    Registering...
                                </span>
                            </button>
                        </div>

                        <!-- Login Link -->
                        <div class="col-12 text-center mt-3">
                            <span class="text-muted small">Already have an account?</span>
                            <a href="{{ route('login') }}" class="small fw-semibold ms-1">Login</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function registerForm() {
            return {
                selectedDepartment: @json(old('department_id', '')),
                selectedSection: @json(old('section_id', '')),
                sections: [],
                isLoading: false,

                async loadSections() {
                    if (!this.selectedDepartment) {
                        this.sections = [];
                        this.selectedSection = '';
                        return;
                    }

                    this.isLoading = true;
                    try {
                        const res = await fetch(`{{ url('admin/user/sections') }}/${this.selectedDepartment}`);
                        if (res.ok) {
                            this.sections = await res.json();
                        }
                    } catch (error) {
                        console.error('Error loading sections:', error);
                    }
                    this.isLoading = false;
                },

                init() {
                    if (this.selectedDepartment) {
                        this.loadSections();
                    }
                }
            }
        }
    </script>
</x-guest-layout>
