@section('title', 'Complain Type Trash')

<x-app-layout>
    <!-- Start header widget -->
    <div class="widget mb-3 border-top print-none">
        <div class="widget-body d-flex">
            <!-- Start left menu -->
            @include('pages.complain-type.menu')
            <!-- End left menu -->

            <!-- Start right buttons -->
            <div class="ms-auto">
                <button type="button" class="btn icon lg rounded" title="Print" onclick="window.print()">
                    <i class="bi bi-printer"></i>
                </button>
                <a href="{{ route('complain-type.trash') }}" class="btn icon lg rounded" title="Reload">
                    <i class="bi bi-bootstrap-reboot"></i>
                </a>
                <a href="{{ route('complain-type.index') }}" class="btn icon lg rounded" title="Back to List">
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
                <h5>Complain Type Trash List</h5>
                <p><small>{{ $complainTypes->total() }} results found</small></p>
            </div>

            <div class="widget-body">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 60px;">SL</th>
                                <th>Complain Type Name</th>
                                <th>Code</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Total Complains</th>
                                <th>Note</th>
                                <th>Deleted At</th>
                                <th class="text-end print-none" style="width: 140px;">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($complainTypes as $type)
                                @php
                                    $complainCount = $type->complains()->withTrashed()->count() ?? 0;
                                @endphp
                                <tr>
                                    <th scope="row">{{ $complainTypes->firstItem() + $loop->index }}</th>
                                    <td>
                                        <strong>{{ $type->name ?? '--' }}</strong>
                                    </td>
                                    <td>
                                        @if($type->code)
                                            <span class="badge bg-secondary">{{ $type->code }}</span>
                                        @else
                                            <span class="text-muted small">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $type->type == 'complain' ? 'primary' : 'info' }}">
                                            {{ ucfirst($type->type) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $type->status ? 'success' : 'danger' }}">
                                            {{ $type->status ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($complainCount > 0)
                                            <span class="badge bg-primary">{{ $complainCount }} complain(s)</span>
                                        @else
                                            <span class="text-muted small">No complains</span>
                                        @endif
                                    </td>
                                    <td>{{ $type->note ? Str::limit($type->note, 30) : '--' }}</td>
                                    <td>{{ $type->deleted_at->format('d M, Y h:i A') }}</td>

                                    <td class="text-end print-none">
                                        <a href="{{ route('complain-type.restore', $type->id) }}" class="btn btn-warning sm" title="Restore">
                                            <i class="bi bi-arrow-clockwise"></i>
                                        </a>

                                        <form action="{{ route('complain-type.permanentDelete', $type->id) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn sm btn-danger" title="Delete Permanently"
                                                onclick="return confirm('Are you sure you want to PERMANENTLY delete this complain type? This action cannot be undone.')">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">
                                        <i class="bi bi-inbox"></i> No Complain Types Found in Trash
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
    @if($complainTypes->hasPages())
    <div class="widget">
        <div class="widget-body">
            {{ $complainTypes->links() }}
        </div>
    </div>
    @endif
    <!-- End pagination -->
</x-app-layout>
