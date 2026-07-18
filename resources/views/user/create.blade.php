@section('title', 'Create user')
<x-app-layout>
    <!-- Start header widget -->
    <div class="card mb-3">
        <div class="card-body py-2 d-flex align-items-center">
            <!-- Start left menu -->
            @include('user.menu')
            <!-- End left menu -->
            <!-- Start right buttons -->
            <div class="ms-auto">
                <button type="button" class="btn btn-sm btn-outline-secondary" title="Go back" onclick="history.back()">
                    <i class="bi bi-arrow-left"></i>
                </button>
            </div>
            <!-- End right buttons -->
        </div>
    </div>
    <!-- End header widget -->

    <!-- Start body widget -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-transparent border-0 py-3">
                    <h5 class="mb-0 fw-bold">Create New User</h5>
                    <p class="mb-0 text-muted small">Must fill star (<span class="text-danger fw-bold">*</span>) pointed boxes</p>
                </div>
                <div class="card-body">
                    <form action="{{ route('user.store') }}" method="POST">
                        @csrf
                        <div class="row g-3 mb-2">
                            <div class="col-md-6">
                                <x-form.label name="Name" for="name" required />
                                <x-form.input type="text" id="name" name="name" placeholder="Enter user name"
                                    value="{{ old('name') }}" required autofocus />
                            </div>
                            <div class="col-md-6">
                                <x-form.label name="Employee ID" for="emp_id" required />
                                <x-form.input type="text" id="emp_id" name="emp_id"
                                    placeholder="Enter employee identity number" value="{{ old('emp_id') }}" required />
                            </div>
                        </div>

                        <div class="row g-3 mb-2">
                            <div class="col-md-6">
                                <x-form.label name="Department" required />
                                <select id="department_id"
                                    class="form-select @error('department_id') is-invalid @enderror"
                                    name="department_id" required onchange="loadSections(this.value)">
                                    <option value="">Select Department</option>
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->id }}"
                                            {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                            {{ $department->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('department_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong class="text-danger">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <x-form.label name="Section" required />
                                <select id="section_id" class="form-select @error('section_id') is-invalid @enderror"
                                    name="section_id" required>
                                    <option value="">Select Section</option>
                                </select>
                                @error('section_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong class="text-danger">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-3 mb-2">
                            <div class="col-md-4">
                                <x-form.label name="Email" for="email" />
                                <x-form.input type="email" id="email" name="email" placeholder="user@gmail.com"
                                    value="{{ old('email') }}" />
                            </div>
                            <div class="col-md-4">
                                <x-form.label name="Phone" for="phone" />
                                <x-form.input type="text" id="phone" name="phone"
                                    placeholder="Enter user mobile no" value="{{ old('phone') }}" />
                            </div>
                            <div class="col-md-4">
                                <x-form.label name="Designation" for="designation" />
                                <x-form.input type="text" id="designation" name="designation"
                                    placeholder="Enter designation title" value="{{ old('designation') }}" />
                            </div>
                        </div>

                        <!-- Multiple Roles Selection with Checkboxes - Compact version -->
                        <div class="row g-3 mb-2">
                            <div class="col-md-12">
                                <x-form.label name="User Roles" required />
                                <div class="card p-3 bg-light-subtle border @error('roles') border-danger @enderror" style="border-radius: 8px;">
                                    <div class="row">
                                        @forelse($roles as $role)
                                            <div class="col-md-3 col-sm-6 mb-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="roles[]" 
                                                        value="{{ $role->name }}" id="role-{{ $role->id }}"
                                                        @checked(is_array(old('roles')) && in_array($role->name, old('roles')))>
                                                    <label class="form-check-label" for="role-{{ $role->id }}">
                                                        {{ $role->name }}
                                                    </label>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="col-12 text-muted small">No roles available</div>
                                        @endforelse
                                    </div>
                                </div>
                                @error('roles')
                                    <span class="text-danger small d-block mt-1">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <x-form.label name="Password" for="password" required />
                                <x-form.input type="password" id="password" name="password" placeholder="Enter secure password" required />
                            </div>
                            <div class="col-md-6">
                                <x-form.label name="Confirm Password" for="password_confirmation" required />
                                <x-form.input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm your password" required />
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button class="btn btn-success sm px-4" type="submit">
                                <i class="bi bi-check-circle me-1"></i> Save
                            </button>
                            <a href="{{ route('user.index') }}" class="btn btn-secondary sm px-4">
                                <i class="bi bi-x-circle me-1"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End body widget -->

    <script>
        function loadSections(departmentId) {
            const sectionSelect = document.getElementById('section_id');

            if (!departmentId) {
                sectionSelect.innerHTML = '<option value="">Select Department First</option>';
                sectionSelect.disabled = false;
                return;
            }

            // Show loading
            sectionSelect.innerHTML = '<option value="">Loading sections...</option>';
            sectionSelect.disabled = true;

            // Use Laravel's route helper
            const url = `{{ route('user.sections', ':id') }}`.replace(':id', departmentId);

            // Fetch sections
            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(sections => {
                    sectionSelect.disabled = false;

                    if (!sections || sections.length === 0) {
                        sectionSelect.innerHTML = '<option value="">No sections found</option>';
                        return;
                    }

                    let options = '<option value="">Select Section</option>';
                    sections.forEach(section => {
                        // Check if this section was previously selected (for form re-population)
                        const isSelected = {{ old('section_id', 0) }} == section.id ? 'selected' : '';
                        options += `<option value="${section.id}" ${isSelected}>${section.name}</option>`;
                    });

                    sectionSelect.innerHTML = options;
                })
                .catch(error => {
                    console.error('Error loading sections:', error);
                    sectionSelect.innerHTML = '<option value="">Error loading sections</option>';
                    sectionSelect.disabled = false;
                });
        }

        // Load sections if department is already selected (for validation errors)
        document.addEventListener('DOMContentLoaded', function() {
            const departmentId = document.getElementById('department_id').value;
            if (departmentId) {
                loadSections(departmentId);
            }
        });
    </script>
</x-app-layout>
