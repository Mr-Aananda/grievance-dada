@section('title', 'Edit Complain Type')

<x-app-layout>
    <!-- Start main-bar -->
    <!-- Start header widget -->
    <div class="widget mb-3">
        <div class="widget-body d-flex">
            <!-- Start left menu -->
            @include('pages.complain-type.menu')
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
                    <form action="{{ route('complain-type.update', $complainType->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-3 mb-2">
                            <div class="col-md-3">
                                <x-form.label name="Name" for="name" required />
                                <x-form.input type="text" id="name" name="name"
                                    value="{{ old('name', $complainType->name) }}"
                                    placeholder="Enter name" required autofocus />
                            </div>

                            <div class="col-md-3">
                                <x-form.label name="Code" for="code" />
                                <x-form.input type="text" id="code" name="code"
                                              placeholder="Enter unique code"
                                              value="{{ old('code', $complainType->code) }}" />
                            </div>

                            <div class="col-md-3">
                                <x-form.label name="Type" for="type" required />
                                <select id="type" class="form-select" name="type" required>
                                    <option value="complain" {{ old('type', $complainType->type) == 'complain' ? 'selected' : '' }}>Complain</option>
                                    <option value="manual" {{ old('type', $complainType->type) == 'manual' ? 'selected' : '' }}>Manual</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <x-form.label name="Status" required />
                                <select id="status" class="form-select" name="status">
                                    <option value="1" {{ old('status', $complainType->status) == '1' ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('status', $complainType->status) == '0' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="row g-3 mb-2">
                            <div class="col-md-12">
                                <x-form.label name="Note" for="note"/>
                                <x-form.input type="textarea" id="note" name="note"
                                    placeholder="Optional" old_text="{{ old('note', $complainType->note) }}"/>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12 text-end">
                                <x-form.reset />
                                <x-form.save name="Update Complain Type" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End body widget -->
</x-app-layout>
