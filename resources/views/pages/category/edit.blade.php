@section('title', 'Edit Category')
<x-app-layout>
    <div class="widget mb-3">
        <div class="widget-body d-flex">
            @include('pages.category.menu')
            <div class="ms-auto">
                <button type="button" class="btn icon lg rounded" onclick="history.back()">
                    <i class="bi bi-arrow-left"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="widget">
                <div class="widget-body">
                    <form action="{{ route('category.update', $category->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row g-3 mb-2">
                            <div class="col-md-4">
                                <x-form.label name="Category Name" for="name" required />
                                <x-form.input type="text" id="name" name="name"
                                    value="{{ old('name', $category->name) }}" placeholder="Enter category name" required />
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

                        <div class="row g-3 mb-2">
                            <div class="col-md-12">
                                <x-form.label name="Note" for="note"/>
                                <x-form.input type="textarea" id="note" name="note"
                                    placeholder="Optional" old_text="{{ old('note', $category->note) }}"/>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12 text-end">
                                <x-form.reset />
                                <x-form.save name="Update Category" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
