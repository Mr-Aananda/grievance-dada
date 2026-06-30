@section('title', 'Category Trash')

<x-app-layout>
    <!-- Start header widget -->
    <div class="card mb-3 print-none">
        <div class="card-body py-2 d-flex align-items-center">
            <!-- Start left menu -->
            @include('pages.category.menu')
            <!-- End left menu -->

            <!-- Start right buttons -->
            <div class="ms-auto">
                <button type="button" class="btn btn-sm btn-outline-secondary me-1" title="Print" onclick="window.print()">
                    <i class="bi bi-printer"></i>
                </button>
                <a href="{{ route('category.trash') }}" class="btn btn-sm btn-outline-secondary me-1" title="Reload">
                    <i class="bi bi-arrow-clockwise"></i>
                </a>
                <a href="{{ route('category.index') }}" class="btn btn-sm btn-outline-secondary" title="Back to List">
                    <i class="bi bi-arrow-left"></i>
                </a>
            </div>
            <!-- End right buttons -->
        </div>
    </div>
    <!-- End header widget -->

    <!-- Start body widget -->
    <div id="print-widget">
        <!-- Start print header -->
        <x-print.header />
        <!-- End print header -->

        <div class="card shadow-sm border-0">
            <div class="card-header bg-transparent border-0 d-flex align-items-center">
                <h6 class="mb-0 fw-bold">Category Trash List</h6>
                <span class="badge bg-secondary ms-2">{{ $categories->total() }} results found</span>
            </div>

            <div class="card-body p-0 table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" style="width: 60px;" class="ps-3">SL</th>
                            <th scope="col">Category Name</th>
                            <th scope="col">Code</th>
                            <th scope="col">Note</th>
                            <th scope="col">Deleted At</th>
                            <th scope="col" class="text-end pe-3 print-none" style="width: 140px;">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($categories as $category)
                            <tr>
                                <td class="ps-3">{{ $categories->firstItem() + $loop->index }}</td>
                                <td>
                                    <span class="fw-bold">{{ $category->name ?? '--' }}</span>
                                </td>
                                <td>
                                    @if ($category->code)
                                        <span class="badge bg-secondary-subtle text-secondary">{{ $category->code }}</span>
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">{{ $category->note ? Str::limit($category->note, 30) : '—' }}</small>
                                </td>
                                <td>
                                    <small class="text-muted">{{ $category->deleted_at->format('d M Y, h:i A') }}</small>
                                </td>

                                <td class="text-end pe-3 print-none">
                                    <a href="{{ route('category.restore', $category->id) }}" class="btn btn-sm btn-warning text-dark" title="Restore">
                                        <i class="bi bi-arrow-clockwise"></i> Restore
                                    </a>

                                    <form action="{{ route('category.permanentDelete', $category->id) }}"
                                        method="POST" class="d-inline ms-1">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger text-white" title="Delete Permanently"
                                            onclick="return confirm('Are you sure you want to PERMANENTLY delete this category? This action cannot be undone.')">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                    No categories found in trash.
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
    </div>
</x-app-layout>
