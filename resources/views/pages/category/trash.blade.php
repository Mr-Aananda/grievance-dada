@section('title', 'Category Trash')

<x-app-layout>
    <!-- Start header widget -->
    <div class="widget mb-3 border-top print-none">
        <div class="widget-body d-flex">
            <!-- Start left menu -->
            @include('pages.category.menu')
            <!-- End left menu -->

            <!-- Start right buttons -->
            <div class="ms-auto">
                <button type="button" class="btn icon lg rounded" title="Print" onclick="window.print()">
                    <i class="bi bi-printer"></i>
                </button>
                <a href="{{ route('category.trash') }}" class="btn icon lg rounded" title="Reload">
                    <i class="bi bi-bootstrap-reboot"></i>
                </a>
                <a href="{{ route('category.index') }}" class="btn icon lg rounded" title="Back to List">
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

        <div class="widget">
            <div class="widget-head mb-3">
                <h5>Category Trash List</h5>
                <p><small>{{ $categories->total() }} results found</small></p>
            </div>

            <div class="widget-body">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 60px;">SL</th>
                                <th>Category Name</th>
                                <th>Code</th>
                                <th>Note</th>
                                <th>Deleted At</th>
                                <th class="text-end print-none" style="width: 140px;">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($categories as $category)
                                <tr>
                                    <th scope="row">{{ $categories->firstItem() + $loop->index }}</th>
                                    <td>
                                        <strong>{{ $category->name ?? '--' }}</strong>
                                    </td>
                                    <td>
                                        @if ($category->code)
                                            <span class="badge bg-secondary">{{ $category->code }}</span>
                                        @else
                                            <span class="text-muted small">N/A</span>
                                        @endif
                                    </td>

                                    <td>{{ $category->note ? Str::limit($category->note, 30) : '--' }}</td>
                                    <td>{{ $category->deleted_at->format('d M, Y h:i A') }}</td>

                                    <td class="text-end print-none">
                                        <a href="{{ route('category.restore', $category->id) }}"
                                            class="btn btn-warning sm">
                                            <i class="bi bi-arrow-clockwise"></i>
                                        </a>


                                        <form action="{{ route('category.permanentDelete', $category->id) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn sm btn-danger" title="Delete Permanently"
                                                onclick="return confirm('Are you sure you want to PERMANENTLY delete this category? This will also permanently delete all associated complain types. This action cannot be undone.')">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        <i class="bi bi-inbox"></i> No Categories Found in Trash
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Start pagination -->
    @if ($categories->hasPages())
        <div class="widget">
            <div class="widget-body">
                {{ $categories->links() }}
            </div>
        </div>
    @endif
    <!-- End pagination -->
</x-app-layout>
