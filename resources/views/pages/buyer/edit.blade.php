@section('title', 'Edit Buyer')
<x-app-layout>
    <!-- Start main-bar -->
    <!-- Start header widget -->
    <div class="widget mb-3">
        <div class="widget-body d-flex">
            <!-- Start left menu -->
            @include('pages.buyer.menu')
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
                    <form action="{{ route('buyer.update', $buyer->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-3 mb-2">
                            <div class="col-md-6">
                                <x-form.label name="Company Name" for="company_name" required />
                                <x-form.input type="text" id="company_name" name="company_name"
                                    value="{{ old('company_name', $buyer->company_name) }}"
                                    placeholder="Enter company name" required autofocus />
                            </div>

                            <div class="col-md-6">
                                <x-form.label name="Code" for="code" required />
                                <x-form.input type="text" id="code" name="code"
                                    value="{{ old('code', $buyer->code) }}"
                                    placeholder="Enter code" required />
                            </div>
                        </div>

                        <div class="row g-3 mb-2">
                            <div class="col-md-6">
                                <x-form.label name="Email" for="email" />
                                <x-form.input type="email" id="email" name="email"
                                    value="{{ old('email', $buyer->email) }}" placeholder="Enter email address" />
                            </div>

                            <div class="col-md-6">
                                <x-form.label name="Phone" for="phone" />
                                <x-form.input type="text" id="phone" name="phone"
                                    value="{{ old('phone', $buyer->phone) }}" placeholder="Enter phone number" />
                            </div>
                        </div>

                        <div class="row g-3 mb-2">
                            <div class="col-md-6">
                                <x-form.label name="Country" for="country" required />
                                <x-form.input type="text" id="country" name="country"
                                    value="{{ old('country', $buyer->country) }}" placeholder="Enter country name"
                                    required />
                            </div>

                            <div class="col-md-6">
                                <x-form.label name="Status" for="status" required />
                                <select id="status" name="status" class="form-select" required>
                                    <option value="1" {{ old('status', $buyer->status) == '1' ? 'selected' : '' }}>
                                        Active</option>
                                    <option value="0" {{ old('status', $buyer->status) == '0' ? 'selected' : '' }}>
                                        Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="row g-3 mb-2">
                            <div class="col-md-12">
                                <x-form.label name="Address" for="address" />
                                <x-form.input type="textarea" id="address" name="address"
                                    placeholder="Enter full address" old_text="{{ old('address', $buyer->address) }}" />
                            </div>
                        </div>

                        <div class="row g-3 mb-2">
                            <div class="col-md-12">
                                <x-form.label name="Note" for="note" />
                                <x-form.input type="textarea" id="note" name="note"
                                    placeholder="Enter any additional notes"
                                    old_text="{{ old('note', $buyer->note) }}" />
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12 text-end">
                                <x-form.reset />
                                <x-form.save name="Update Buyer" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End body widget -->
</x-app-layout>
