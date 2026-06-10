@section('title', 'Create user')
<x-app-layout>
    <!-- Start main-bar -->
    <!-- Start header widget -->
    <div class="widget mb-3">
        <div class="widget-body d-flex">
            <!-- Start left menu -->
            @include('user.menu')
            <!-- End left menu -->
            <!-- Start right buttons -->
            <div class="ms-auto">
                <button type="button" class="btn icon lg rounded" title="Go back" onclick="history.back()">
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
            <div class="widget">
                <div class="widget-head mb-3">
                    <h5>Create New User</h5>
                    <p><small>Must fill star (<span class="text-danger fw-bold">*</span>) pointed boxes</small></p>
                </div>
                <div class="widget-body">
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

                                <!-- Compact Bootstrap checkboxes for roles -->
                                <div class="border rounded p-2 @error('role') border-danger @enderror"
                                     style="max-height: 150px; overflow-y: auto; background-color: #f8f9fa;">
                                    <div class="d-flex flex-wrap" style="gap: 4px 8px;">
                                        @foreach ($roles as $role)
                                            <div class="form-check form-check-inline mb-1" style="margin-right: 4px;">
                                                <input class="form-check-input role-checkbox" type="checkbox"
                                                    name="role[]"
                                                    value="{{ $role->name }}"
                                                    id="role_{{ $loop->index }}"
                                                    {{ is_array(old('role')) && in_array($role->name, old('role')) ? 'checked' : '' }}>
                                                <label class="form-check-label small fw-bold" for="role_{{ $loop->index }}">
                                                    {{ $role->name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <small class="text-muted d-block mt-1">Select one or more roles</small>

                                @error('role')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong class="text-danger">{{ $message }}</strong>
                                    </span>
                                @enderror
                                @error('role.*')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong class="text-danger">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-3 mb-2">
                            <div class="col-md-6">
                                <x-form.label name="Password" required />
                                <x-form.input type="password" name="password" placeholder="********" required />
                            </div>
                            <div class="col-md-6">
                                <x-form.label name="Confirm Password" required />
                                <x-form.input type="password" name="password_confirmation" placeholder="********"
                                    required />
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12 text-end">
                                <x-form.reset />
                                <x-form.save name="Add User" />
                            </div>
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
