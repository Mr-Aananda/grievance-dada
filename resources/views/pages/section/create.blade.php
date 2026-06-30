@section('title', 'Create Section')
<x-app-layout>
    <!-- Start header widget -->
    <div class="card mb-3">
        <div class="card-body py-2 d-flex align-items-center">
            <!-- Start menu -->
            @include('pages.section.menu')
            <!-- End menu -->

            <!-- Start right buttons -->
            <div class="ms-auto">
                <button type="button" class="btn btn-sm btn-outline-secondary" title="Go back" onclick="history.back()">
                    <i class="bi bi-arrow-left"></i> Back
                </button>
            </div>
            <!-- End right buttons -->
        </div>
    </div>
    <!-- End header widget -->

    <!-- Start body widget -->
    <div class="row">
        <div class="col-md-12 mx-auto">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-transparent border-0 pt-3 px-4">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-plus-circle me-1"></i> Create New Section</h6>
                </div>
                <div class="card-body px-4 pb-4">
                    <form action="{{ route('section.store') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <x-form.label name="Section Name" for="name" required />
                                <x-form.input type="text" id="name" name="name"
                                    placeholder="Enter section name" value="{{ old('name') }}" required autofocus />
                            </div>

                            <div class="col-md-6">
                                <x-form.label name="Section Code" for="code" required />
                                <x-form.input type="text" id="code" name="code"
                                    placeholder="Enter unique code" value="{{ old('code') }}" required />
                                <small class="text-muted">Must be unique (e.g., SEC-1)</small>
                            </div>

                            <div class="col-md-6">
                                <x-form.label name="Department" required />
                                <select id="department_id"
                                    class="form-select @error('department_id') is-invalid @enderror"
                                    name="department_id" required>
                                    <option value="">Select Department</option>
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->id }}"
                                            {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                            {{ $department->name }} ({{ $department->code }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('department_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <x-form.label name="Status" required />
                                <select id="status" class="form-select" name="status">
                                    <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive
                                    </option>
                                </select>
                            </div>

                            <div class="col-12">
                                <x-form.label name="Note/Description" for="note" />
                                <textarea id="note" name="note" class="form-control" rows="3" placeholder="Optional description">{{ old('note') }}</textarea>
                            </div>

                            <div class="col-12 mt-4">
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-primary px-4" type="submit">
                                        <i class="bi bi-check-circle me-1"></i> Save
                                    </button>
                                    <a href="{{ route('section.index') }}" class="btn btn-sm btn-secondary px-4">
                                        <i class="bi bi-x-circle me-1"></i> Cancel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
