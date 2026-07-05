@section('title', 'Edit User')
<x-app-layout>
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
    <div class="widget mb-5">
        <div class="widget-head mb-3">
            <h5>Update User</h5>
            <p><small>Must fill star (<span class="text-danger fw-bold">*</span>) pointed boxes</small></p>
        </div>

        <div class="widget-body" id="root">
            <form action="{{ route('user.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="row g-3 mb-2">
                    <div class="col-md-6">
                        <x-form.label name="Name" for="name" required />
                        <x-form.input type="text" id="name" name="name"
                            value="{{ old('name', $user->name) }}" placeholder="Enter user name" required />
                    </div>
                    <div class="col-md-6">
                        <x-form.label name="Employee ID" for="emp_id" required />
                        <x-form.input type="text" id="emp_id" name="emp_id"
                            value="{{ old('emp_id', $user->emp_id) }}" placeholder="Enter employee identity number"
                            required />
                    </div>
                </div>

                <div class="row g-3 mb-2">
                    <div class="col-md-6">
                        <x-form.label name="Department" required />
                        <select id="department_id" class="form-select @error('department_id') is-invalid @enderror"
                            name="department_id" required onchange="loadSections(this.value)">
                            <option value="">Select Department</option>
                            @foreach ($departments as $dep)
                                <option value="{{ $dep->id }}"
                                    {{ old('department_id', $user->department_id) == $dep->id ? 'selected' : '' }}>
                                    {{ $dep->name }}
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
                        <x-form.input type="email" id="email" name="email"
                            value="{{ old('email', $user->email) }}" placeholder="user@gmail.com" />
                    </div>
                    <div class="col-md-4">
                        <x-form.label name="Phone" for="phone" />
                        <x-form.input type="text" id="phone" name="phone"
                            value="{{ old('phone', $user->phone) }}" placeholder="Enter user mobile no" />
                    </div>
                    <div class="col-md-4">
                        <x-form.label name="Designation" for="designation" />
                        <x-form.input type="text" id="designation" name="designation"
                            value="{{ old('designation', $user->designation) }}"
                            placeholder="Enter designation title" />
                    </div>
                </div>

                <!-- Multiple Roles Selection with Checkboxes -->
                <div class="row g-3 mb-2">
                    <div class="col-md-12">
                        <x-form.label name="User Roles" required />

                        <!-- Get user's current roles -->
                        @php
                            $userRoles = $user->roles->pluck('name')->toArray();
                        @endphp

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
                                            {{ in_array($role->name, old('role', $userRoles)) ? 'checked' : '' }}>
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
                    <div class="col-md-12">
                        <x-form.label name="Status" required />
                        <select id="status" class="form-select" name="status">
                            <option value="1" {{ old('status', $user->status) == 1 ? 'selected' : '' }}>
                                Active
                            </option>
                            <option value="0" {{ old('status', $user->status) == 0 ? 'selected' : '' }}>
                                Inactive
                            </option>
                        </select>
                    </div>
                </div>

                <div class="row g-3 mb-2">
                    <div class="col-md-6">
                        <x-form.label name="Password" />
                        <x-form.input type="password" name="password" placeholder="Leave blank to keep current password" />
                        <small class="text-muted">Leave blank to keep current password</small>
                    </div>
                    <div class="col-md-6">
                        <x-form.label name="Confirm Password" />
                        <x-form.input type="password" name="password_confirmation" placeholder="Confirm new password" />
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
    <!-- End body widget -->

    <script>
        function loadSections(departmentId) {
            const sectionSelect = document.getElementById('section_id');

            if (!departmentId) {
                sectionSelect.innerHTML = '<option value="">Select Department First</option>';
                return;
            }

            // Show loading
            sectionSelect.innerHTML = '<option value="">Loading sections...</option>';
            sectionSelect.disabled = true;

            // Use the correct URL
            const url = `{{ url('admin/user/sections') }}/${departmentId}`;

            // Fetch sections
            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
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
                        // Check if this is the current section
                        const currentSectionId = {{ $user->section_id ?? 0 }};
                        const selected = (currentSectionId && section.id == currentSectionId) ? 'selected' : '';
                        options += `<option value="${section.id}" ${selected}>${section.name}</option>`;
                    });

                    sectionSelect.innerHTML = options;
                })
                .catch(error => {
                    console.error('Error loading sections:', error);
                    sectionSelect.innerHTML = '<option value="">Error loading sections</option>';
                    sectionSelect.disabled = false;
                });
        }

        // Load sections on page load if department is selected
        document.addEventListener('DOMContentLoaded', function() {
            const departmentId = document.getElementById('department_id').value;
            if (departmentId) {
                loadSections(departmentId);
            }
        });
    </script>
</x-app-layout>
