@section('title', 'Create Category')
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
                    <form action="{{ route('category.store') }}" method="POST">
                        @csrf
                        <div class="row g-3 mb-2">
                            <div class="col-md-6">
                                <x-form.label name="Category Name" for="name" required />
                                <x-form.input type="text" id="name" name="name" placeholder="Enter category name" required />
                            </div>
                            <div class="col-md-6">
                                <x-form.label name="Category Code" for="code" />
                                <x-form.input type="text" id="code" name="code" placeholder="Enter unique code" />
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
                                <x-form.save name="Add Category" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
