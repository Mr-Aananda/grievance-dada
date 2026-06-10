@section('title', 'Edit Section')
<x-app-layout>
    <!-- Start main-bar -->
    <!-- Start header widget -->
    <div class="widget mb-3">
        <div class="widget-body d-flex">
            <!-- Start menu -->
            @include('pages.section.menu')
            <!-- End menu -->
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
        <div class="col-md-8 mx-auto">
            <div class="widget">
                <div class="widget-head mb-3">
                    <h5>Edit Section</h5>
                    <p><small>Update section information</small></p>
                </div>
                <div class="widget-body">
                    <form action="{{ route('section.update', $section->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row g-3">
                            <div class="col-md-6">
                                <x-form.label name="Section Name" for="name" required />
                                <x-form.input type="text" id="name" name="name"
                                              placeholder="Enter section name"
                                              value="{{ old('name', $section->name) }}" required autofocus />
                            </div>

                            <div class="col-md-6">
                                <x-form.label name="Section Code" for="code" required />
                                <x-form.input type="text" id="code" name="code"
                                              placeholder="Enter unique code"
                                              value="{{ old('code', $section->code) }}" required />
                            </div>

                            <div class="col-md-6">
                                <x-form.label name="Department" required />
                                <select id="department_id" class="form-select @error('department_id') is-invalid @enderror"
                                        name="department_id" required>
                                    <option value="">Select Department</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}"
                                            {{ old('department_id', $section->department_id) == $department->id ? 'selected' : '' }}>
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
                                    <option value="1" {{ old('status', $section->status) == '1' ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('status', $section->status) == '0' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <x-form.label name="Note/Description" for="note" />
                                <textarea id="note" name="note" class="form-control"
                                          rows="3" placeholder="Optional description">{{ old('note', $section->note) }}</textarea>
                            </div>

                            <div class="col-12 mt-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('section.index') }}" class="btn btn-secondary">Cancel</a>
                                    <x-form.save name="Update Section" />
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End body widget -->
</x-app-layout>
