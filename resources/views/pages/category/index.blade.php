@section('title', 'Categories')

<x-app-layout>
    <!-- Start header widget -->
    <div class="card mb-3">
        <div class="card-body py-2 d-flex align-items-center">
            <!-- Start menu -->
            @include('pages.category.menu')
            <!-- End menu -->
            
            <!-- Start right buttons -->
            <div class="ms-auto">
                <button type="button" class="btn btn-sm btn-outline-secondary collapsed me-1" title="Search Filters"
                        data-bs-toggle="collapse" data-bs-target="#tableSearch" aria-controls="tableSearch"
                        aria-expanded="false">
                    <i class="bi bi-funnel-fill"></i> Filter
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary me-1" title="Print" onclick="printable('print-widget')">
                    <i class="bi bi-printer"></i>
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary me-1" title="Reload" onclick="location.reload()">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary" title="Go back" onclick="history.back()">
                    <i class="bi bi-arrow-left"></i>
                </button>
            </div>
            <!-- End right buttons -->
        </div>

        <!-- Start Filter Fill -->
        <div class="card-footer collapse {{ request()->search == '1' ? 'show' : '' }} bg-light border-0" id="tableSearch">
            <form action="{{ route('category.index') }}" method="get">
                <input hidden type="text" name="search" value="1">
                <div class="row py-2 g-3 align-items-end">
                    <div class="col-md-4">
                        <label for="name" class="form-label small fw-semibold">Category name</label>
                        <input class="form-control form-control-sm" name="name" id="name"
                               placeholder="Type a category name" value="{{ request()->name }}">
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-sm btn-primary w-100" type="submit">
                            <i class="bi bi-search me-1"></i> Search
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <!-- End Filter Fill -->
    </div>
    <!-- End header widget -->

    <div id="print-widget">
        <!-- Start print header -->
        <x-print.header/>
        <!-- End print header -->

        <!-- Start table card -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-transparent border-0 d-flex align-items-center">
                <h6 class="mb-0 fw-bold">All Categories</h6>
                <span class="badge bg-secondary ms-2">{{ $categories->total() }} results found</span>
            </div>
            <div class="card-body p-0 table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" style="width: 60px;" class="ps-3">SL</th>
                            <th scope="col">Category Name</th>
                            <th scope="col">Note</th>
                            <th scope="col" class="text-end pe-3 print-none">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            <tr>
                                <td class="ps-3">{{ $categories->firstItem() + $loop->index }}</td>
                                <td>
                                    <span class="fw-bold">{{ $category->name }}</span>
                                </td>
                                <td>
                                    <small class="text-muted">{{ $category->note ?? '—' }}</small>
                                </td>
                                <td class="text-end pe-3 print-none">
                                    @can('category.show')
                                        <a href="{{ route('category.show', $category->id) }}"
                                           class="btn btn-sm btn-info text-white" title="View Details">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                    @endcan
                                    @can('category.edit')
                                        <a href="{{ route('category.edit', $category->id) }}"
                                           class="btn btn-sm btn-success text-white" title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                    @endcan

                                    @can('category.destroy')
                                        <form action="{{ route('category.destroy', $category->id) }}" method="POST"
                                              class="d-inline ms-1" onsubmit="return confirm('Are you sure want to delete?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Delete" class="btn btn-sm btn-danger text-white">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                    No categories found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($categories->hasPages())
                <div class="card-footer bg-transparent border-0 d-flex justify-content-end">
                    {{ $categories->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
        <!-- End table card -->
    </div>
</x-app-layout>
