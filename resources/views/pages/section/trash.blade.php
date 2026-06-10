@section('title', 'Section Trash')

<x-app-layout>
    <!-- Start header widget -->
    <div class="widget mb-3 border-top print-none">
        <div class="widget-body d-flex">
            <!-- Start left menu -->
            @include('pages.section.menu')
            <!-- End left menu -->

            <!-- Start right buttons -->
            <div class="ms-auto">
                <button type="button" class="btn icon lg rounded" title="Print" onclick="window.print()">
                    <i class="bi bi-printer"></i>
                </button>
                <a href="{{ route('section.trash') }}" class="btn icon lg rounded" title="Reload">
                    <i class="bi bi-bootstrap-reboot"></i>
                </a>
                <a href="{{ route('section.index') }}" class="btn icon lg rounded" title="Back to List">
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
                <h5>Section Trash List</h5>
                <p><small>{{ $sections->total() }} results found</small></p>
            </div>

            <div class="widget-body">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 60px;">SL</th>
                                <th>Section Name</th>
                                <th>Code</th>
                                <th>Department</th>
                                <th>Total Users</th>
                                <th>Status</th>
                                <th>Note</th>
                                <th>Deleted At</th>
                                <th class="text-end print-none" style="width: 140px;">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($sections as $section)
                                @php
                                    $userCount = $section->users_count ?? 0;
                                    $statusBadge = $section->status ? 'bg-success' : 'bg-danger';
                                    $statusText = $section->status ? 'Active' : 'Inactive';
                                @endphp
                                <tr>
                                    <th scope="row">{{ $sections->firstItem() + $loop->index }}</th>
                                    <td>
                                        <strong>{{ $section->name ?? '--' }}</strong>
                                    </td>
                                    <td>
                                        @if ($section->code)
                                            <span class="badge bg-secondary">{{ $section->code }}</span>
                                        @else
                                            <span class="text-muted small">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($section->department)
                                            <span class="badge bg-info">{{ $section->department->name }}</span>
                                        @else
                                            <span class="text-muted small">No department</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($userCount > 0)
                                            <span class="badge bg-primary">{{ $userCount }} user(s)</span>
                                        @else
                                            <span class="text-muted small">No users</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge {{ $statusBadge }}">{{ $statusText }}</span>
                                    </td>
                                    <td>{{ $section->note ? Str::limit($section->note, 30) : '--' }}</td>
                                    <td>{{ $section->deleted_at->format('d M, Y h:i A') }}</td>

                                    <td class="text-end print-none">
                                        <a href="{{ route('section.restore', $section->id) }}"
                                            class="btn btn-warning sm">
                                            <i class="bi bi-arrow-clockwise"></i>
                                        </a>

                                        <form action="{{ route('section.permanentDelete', $section->id) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn sm btn-danger" title="Delete Permanently"
                                                onclick="return confirm('Are you sure you want to PERMANENTLY delete this section? This will also permanently remove user assignments. This action cannot be undone.')">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">
                                        <i class="bi bi-inbox"></i> No Sections Found in Trash
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
    @if ($sections->hasPages())
        <div class="widget">
            <div class="widget-body">
                {{ $sections->links() }}
            </div>
        </div>
    @endif
    <!-- End pagination -->

    <style>
        .badge {
            font-size: 0.85rem;
        }
    </style>
</x-app-layout>
