@section('title', 'Buyer Trash')
<x-app-layout>
    <!-- Start header widget -->
    <div class="widget mb-3 border-top print-none">
        <div class="widget-body d-flex">
            <!-- Start left menu -->
            @include('pages.buyer.menu')
            <!-- End left menu -->

            <!-- Start right buttons -->
            <div class="ms-auto">
                <button type="button" class="btn icon lg rounded" title="Print" onclick="window.print()">
                    <i class="bi bi-printer"></i>
                </button>
                <a href="{{ route('buyer.trash') }}" class="btn icon lg rounded" title="Reload">
                    <i class="bi bi-bootstrap-reboot"></i>
                </a>
                <a href="{{ route('buyer.index') }}" class="btn icon lg rounded" title="Back to List">
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
                <h5>Buyer Trash List</h5>
                <p><small>{{ $buyers->total() }} results found</small></p>
            </div>

            <div class="widget-body">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 60px;">SL</th>
                                <th>Company Name</th>
                                <th>Code</th>
                                <th>Country</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Status</th>
                                <th>Deleted At</th>
                                <th class="text-end print-none" style="width: 140px;">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($buyers as $buyer)
                                <tr>
                                    <th scope="row">{{ $buyers->firstItem() + $loop->index }}</th>
                                    <td>
                                        <strong>{{ $buyer->company_name }}</strong>
                                    </td>
                                    <td>{{ $buyer->code }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $buyer->country }}</span>
                                    </td>
                                    <td>{{ $buyer->email ?? 'N/A' }}</td>
                                    <td>{{ $buyer->phone ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge {{ $buyer->status ? 'bg-success' : 'bg-danger' }}">
                                            {{ $buyer->status ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>{{ $buyer->deleted_at->format('d M, Y h:i A') }}</td>

                                    <td class="text-end print-none">
                                        <a href="{{ route('buyer.restore', $buyer->id) }}" class="btn btn-warning sm">
                                            <i class="bi bi-arrow-clockwise"></i>
                                        </a>

                                        <form action="{{ route('buyer.permanentDelete', $buyer->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn sm btn-danger" title="Delete Permanently"
                                                onclick="return confirm('Are you sure you want to PERMANENTLY delete this buyer? This action cannot be undone.')">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">
                                        <i class="bi bi-inbox"></i> No Buyers Found in Trash
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
    @if ($buyers->hasPages())
        <div class="widget">
            <div class="widget-body">
                {{ $buyers->links() }}
            </div>
        </div>
    @endif
    <!-- End pagination -->
</x-app-layout>
