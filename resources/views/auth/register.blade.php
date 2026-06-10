@section('title', 'Register')

<x-guest-layout>
    <main class="authentication signup register-page" style="background-image: url('{{ asset('assets/images/background.jpeg') }}')">
        <div class="widget">

            <!-- Widget Head with Logo -->
            <div class="widget-head">
                <div class="logo">
                    <img src="{{ Vite::asset('resources/assets/images/logo/dada_bg.png') }}"
                         alt="Dada (Dhaka) Ltd">
                </div>
            </div>

            <!-- Widget Body -->
            <div class="widget-body">
                <x-alert />

                <form method="POST"
                      action="{{ route('register') }}"
                      x-data="registerForm()"
                      x-init="init()">
                    @csrf

                    <div class="row g-2">
                        <!-- Employee ID -->
                        <div class="col-12">
                            <label class="form-label required">Employee ID</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-person-badge"></i>
                                </span>
                                <input type="text"
                                       name="emp_id"
                                       class="form-control  @error('emp_id') is-invalid @enderror"
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
                            <label class="form-label required">Full Name</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-person"></i>
                                </span>
                                <input type="text"
                                       name="name"
                                       class="form-control @error('name') is-invalid @enderror"
                                       placeholder="Full name"
                                       value="{{ old('name') }}"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Department -->
                        <div class="col-md-12">
                            <label class="form-label required">Department</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-building"></i>
                                </span>
                                <select class="form-control @error('department_id') is-invalid @enderror"
                                        name="department_id"
                                        x-model="selectedDepartment"
                                        @change="loadSections()"
                                        required
                                        style="color-scheme: dark;">
                                    <option value="">Select Department</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}"
                                            @selected(old('department_id') == $department->id)
                                            style="background-color: #2d3748; color: white;">
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
                        <div class="col-md-12">
                            <label class="form-label required">Section</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-diagram-2"></i>
                                </span>
                                <select class="form-control @error('section_id') is-invalid @enderror"
                                        name="section_id"
                                        x-model="selectedSection"
                                        :disabled="!selectedDepartment || isLoading"
                                        required
                                        style="color-scheme: dark;">
                                    <option value="">Select Section</option>
                                    <template x-for="section in sections" :key="section.id">
                                        <option :value="section.id"
                                                x-text="section.name"
                                                style="background-color: #2d3748; color: white;"></option>
                                    </template>
                                </select>
                                @error('section_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="col-12">
                            <label class="form-label required">Password</label>
                            <div class="input-group toggle-password-fill">
                                <span class="input-group-text">
                                    <i class="bi bi-lock"></i>
                                </span>
                                <input type="password"
                                       name="password"
                                       id="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       placeholder="Password"
                                       required>
                                <button type="button"
                                        class="pass-eye"
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
                            <label class="form-label required">Confirm Password</label>
                            <div class="input-group toggle-password-fill">
                                <span class="input-group-text">
                                    <i class="bi bi-lock-fill"></i>
                                </span>
                                <input type="password"
                                       name="password_confirmation"
                                       id="password_confirmation"
                                       class="form-control"
                                       placeholder="Confirm password"
                                       required>
                                <button type="button"
                                        class="pass-eye"
                                        onclick="show(event, 'password_confirmation')">
                                    <i class="bi bi-eye-fill"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="col-12 mt-4">
                            <button type="submit"
                                    class="btn btn-primary w-100"
                                    :disabled="isLoading">
                                <i class="bi bi-person-plus"></i>
                                <span x-show="!isLoading">Register</span>
                                <span x-show="isLoading">
                                    <span class="spinner-border spinner-border-sm me-1"></span>
                                    Loading...
                                </span>
                            </button>
                        </div>

                        <!-- Login Link -->
                        <div class="col-12 text-center mt-3">
                            <small>
                                <a href="{{ route('login') }}">Login</a>
                            </small>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>

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
                        const res = await fetch(`{{ url('departments') }}/${this.selectedDepartment}/sections`);
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
