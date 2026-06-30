@section('title', 'Edit Category')
<x-app-layout>
    <!-- Start header widget -->
    <div class="card mb-3">
        <div class="card-body py-2 d-flex align-items-center">
            <!-- Start left menu -->
            @include('pages.category.menu')
            <!-- End left menu -->
            
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

    <!-- Start body card -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-transparent border-0 pt-3 px-4">
            <h6 class="mb-0 fw-bold"><i class="bi bi-pencil-square me-1"></i> Edit Category</h6>
        </div>
        <div class="card-body px-4 pb-4">
            <form action="{{ route('category.update', $category->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <x-form.label name="Category Name" for="name" required />
                        <x-form.input type="text" id="name" name="name"
                            value="{{ old('name', $category->name) }}" placeholder="Enter category name" required autofocus />
                    </div>
                    <div class="col-md-4">
                        <x-form.label name="Category Code" for="code" />
                        <x-form.input type="text" id="code" name="code"
                            value="{{ old('code', $category->code) }}" placeholder="Enter unique code" />
                    </div>
                    <div class="col-md-4">
                        <x-form.label name="Status" required />
                        <select id="status" class="form-select" name="status">
                            <option value="1" {{ old('status', $category->status) == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('status', $category->status) == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-12">
                        <x-form.label name="Note" for="note"/>
                        <x-form.input type="textarea" id="note" name="note"
                            placeholder="Optional notes..." old_text="{{ old('note', $category->note) }}"/>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-primary px-4" type="submit">
                        <i class="bi bi-check-circle me-1"></i> Update
                    </button>
                    <a href="{{ route('category.index') }}" class="btn btn-sm btn-secondary px-4">
                        <i class="bi bi-x-circle me-1"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
