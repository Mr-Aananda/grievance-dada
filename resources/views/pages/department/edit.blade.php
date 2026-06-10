@section('title', 'Edit Department')

<x-app-layout>
    <!-- Start main-bar -->
    <!-- Start header widget -->
    <div class="widget mb-3">
        <div class="widget-body d-flex">
            <!-- Start left menu -->
            @include('pages.department.menu')
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
                <div class="widget-body">
                    <form action="{{ route('department.update', $department->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-3 mb-2">
                            <div class="col-md-4">
                                <x-form.label name="Department Name" for="name" required />
                                <x-form.input type="text" id="name" name="name"
                                    value="{{ old('name', $department->name) }}"
                                    placeholder="Enter department name" required autofocus />
                            </div>

                             <div class="col-md-4">
                                <x-form.label name="Department Code" for="code" required />
                                <x-form.input type="text" id="code" name="code"
                                              placeholder="Enter unique code"
                                              value="{{ old('code', $department->code) }}" required />
                            </div>
                            <div class="col-md-4">
                                <x-form.label name="Status" required />
                                <select id="status" class="form-select" name="status">
                                    <option value="1" {{ old('status', $department->status) == '1' ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('status', $department->status) == '0' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="row g-3 mb-2">
                            <div class="col-md-12">
                                <x-form.label name="Note" for="note"/>
                                <x-form.input type="textarea" id="note" name="note"
                                    placeholder="Optional" old_text="{{ old('note', $department->note) }}"/>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12 text-end">
                                <x-form.reset />
                                <x-form.save name="Update Department" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End body widget -->
</x-app-layout>
