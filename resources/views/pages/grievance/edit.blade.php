@section('title', __('Edit Ticket') . ' #' . $grievance->ticket_number)

<x-app-layout>
    <!-- Include Quill Editor stylesheet -->
    @push('style')
        <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />
        <style>
            .ql-container {
                font-family: 'Source Sans 3', 'Noto Sans Bengali', sans-serif;
                font-size: 1rem;
            }
            .ql-editor {
                min-height: 200px;
                line-height: 1.6;
            }
        </style>
    @endpush

    <!-- Start header widget -->
    <div class="card mb-3 shadow-sm border-0">
        <div class="card-body py-2 d-flex align-items-center">
            <h5 class="mb-0 fw-bold">
                <i class="bi bi-pencil-square text-primary me-2"></i> {{ __('Edit Ticket') }} #{{ $grievance->ticket_number }}
            </h5>
            <div class="ms-auto">
                <a href="{{ route('admin.grievance.show', $grievance->id) }}" class="btn btn-sm btn-outline-secondary" title="{{ __('Go back') }}">
                    <i class="bi bi-arrow-left"></i> {{ __('Cancel') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Edit Form Card -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form action="{{ route('admin.grievance.update', $grievance->id) }}" method="POST" id="editGrievanceForm">
                @csrf
                @method('PUT')

                <div class="row g-3 mb-4">
                    <!-- Category Selection -->
                    <div class="col-md-4">
                        <label for="category_id" class="form-label small fw-semibold">{{ __('Category') }} <span class="text-danger">*</span></label>
                        <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                            <option value="">{{ __('— Select Category —') }}</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $grievance->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Department Selection -->
                    <div class="col-md-4">
                        <label for="department_id" class="form-label small fw-semibold">{{ __('Department') }}</label>
                        <select name="department_id" id="department_id" class="form-select @error('department_id') is-invalid @enderror">
                            <option value="">{{ __('— Select Department (Optional) —') }}</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ old('department_id', $grievance->department_id) == $dept->id ? 'selected' : '' }}>
                                    {{ $dept->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('department_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Employee ID -->
                    <div class="col-md-4">
                        <label for="employee_id" class="form-label small fw-semibold">{{ __('Employee ID') }}</label>
                        <input type="text" name="employee_id" id="employee_id" class="form-control @error('employee_id') is-invalid @enderror" 
                               value="{{ old('employee_id', $grievance->employee_id) }}" placeholder="{{ __('e.g., EMP-1023 (Optional)') }}">
                        @error('employee_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Description (Quill Editor) -->
                <div class="mb-4">
                    <label class="form-label small fw-semibold">{{ __('Issue Description') }} <span class="text-danger">*</span></label>
                    <input type="hidden" name="description" id="descriptionInput" value="{{ old('description', $grievance->description) }}">
                    <div id="quillEditor" class="border rounded bg-white">
                        {!! old('description', $grievance->description) !!}
                    </div>
                    @error('description')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <hr class="my-4">

                <div class="row g-3 mb-4">
                    <!-- Status -->
                    <div class="col-md-4">
                        <label for="status" class="form-label small fw-semibold">{{ __('Status') }} <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="submitted" {{ old('status', $grievance->status) === 'submitted' ? 'selected' : '' }}>{{ __('Submitted') }}</option>
                            <option value="under_review" {{ old('status', $grievance->status) === 'under_review' ? 'selected' : '' }}>{{ __('Under Review') }}</option>
                            <option value="in_resolution" {{ old('status', $grievance->status) === 'in_resolution' ? 'selected' : '' }}>{{ __('In Resolution') }}</option>
                            <option value="resolved" {{ old('status', $grievance->status) === 'resolved' ? 'selected' : '' }}>{{ __('Resolved') }}</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Admin Remarks -->
                    <div class="col-md-8">
                        <label for="admin_remarks" class="form-label small fw-semibold">{{ __('Admin Remarks / Resolution Details') }}</label>
                        <textarea name="admin_remarks" id="admin_remarks" class="form-control @error('admin_remarks') is-invalid @enderror" 
                                  rows="3" placeholder="{{ __('Type resolution comments or administrative updates here...') }}" 
                                  maxlength="5000">{{ old('admin_remarks', $grievance->admin_remarks) }}</textarea>
                        @error('admin_remarks')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Submit and Cancel buttons -->
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-4 fw-semibold">
                        <i class="bi bi-save me-1"></i> {{ __('Save Changes') }}
                    </button>
                    <a href="{{ route('admin.grievance.show', $grievance->id) }}" class="btn btn-secondary px-4 fw-semibold">
                        <i class="bi bi-x-circle me-1"></i> {{ __('Cancel') }}
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Include the Quill library and initialize -->
    @push('script')
        <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const quill = new Quill('#quillEditor', {
                    theme: 'snow',
                    modules: {
                        toolbar: [
                            ['bold', 'italic', 'underline'],
                            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                            ['clean']
                        ]
                    }
                });

                const form = document.getElementById('editGrievanceForm');
                const descriptionInput = document.getElementById('descriptionInput');

                form.addEventListener('submit', function (event) {
                    // Extract HTML content from editor
                    const html = quill.root.innerHTML;
                    
                    // If editor is empty, set empty string so validation handles it
                    const text = quill.getText().trim();
                    if (text === '') {
                        descriptionInput.value = '';
                    } else {
                        descriptionInput.value = html;
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>
