@section('title', 'Create Complain Type')
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
                    <form action="{{ route('complain-type.store') }}" method="POST">
                        @csrf
                        <div class="row g-3 mb-2">
                            <div class="col-md-4">
                                <x-form.label name="Name" for="name" required />
                                <x-form.input type="text" id="name" name="name" placeholder="Enter name"
                                    required autofocus />
                            </div>

                            <div class="col-md-4">
                                <x-form.label name="Code" for="code" />
                                <x-form.input type="text" id="code" name="code"
                                              placeholder="Enter unique code"
                                              value="{{ old('code') }}" />
                                <small class="text-muted">Must be unique (e.g., CT001)</small>
                            </div>

                            <div class="col-md-4">
                                <x-form.label name="Type" for="type" required />
                                <select id="type" class="form-select" name="type" required>
                                    <option value="">Select Type</option>
                                    <option value="complain" {{ old('type') == 'complain' ? 'selected' : '' }}>Complain</option>
                                    <option value="manual" {{ old('type') == 'manual' ? 'selected' : '' }}>Manual</option>
                                </select>
                            </div>
                        </div>

                        <div class="row g-3 mb-2">
                            <div class="col-md-12">
                                <x-form.label name="Note" for="note"/>
                                <x-form.input type="textarea" id="note" name="note" placeholder="Optional"/>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12 text-end">
                                <x-form.reset />
                                <x-form.save name="Add Complain Type" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End body widget -->
    <!-- End main-bar -->
</x-app-layout>
