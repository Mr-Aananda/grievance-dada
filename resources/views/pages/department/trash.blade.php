@section('title', 'Department Trash')

<x-app-layout>
    <!-- Start header widget -->
    <div class="widget mb-3 border-top print-none">
        <div class="widget-body d-flex">
            <!-- Start left menu -->
            @include('pages.department.menu')
            <!-- End left menu -->

            <!-- Start right buttons -->
            <div class="ms-auto">
                <button type="button" class="btn icon lg rounded" title="Print" onclick="window.print()">
                    <i class="bi bi-printer"></i>
                </button>
                <a href="{{ route('department.trash') }}" class="btn icon lg rounded" title="Reload">
                    <i class="bi bi-bootstrap-reboot"></i>
                </a>
                <a href="{{ route('department.index') }}" class="btn icon lg rounded" title="Back to List">
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
                <h5>Department Trash List</h5>
                <p><small>{{ $departments->total() }} results found</small></p>
            </div>

            <div class="widget-body">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 60px;">SL</th>
                                <th>Department Name</th>
                                <th>Code</th>
                                <th>Sections</th>
                                <th>Total Users</th>
                                <th>Note</th>
                                <th>Deleted At</th>
                                <th class="text-end print-none" style="width: 140px;">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($departments as $department)
                                @php
                                    $sectionCount = $department->sections_count ?? 0;
                                    $userCount = $department->users_count ?? 0;
                                @endphp
                                <tr>
                                    <th scope="row">{{ $departments->firstItem() + $loop->index }}</th>
                                    <td>
                                        <strong>{{ $department->name ?? '--' }}</strong>
                                    </td>
                                    <td>
                                        @if($department->code)
                                            <span class="badge bg-secondary">{{ $department->code }}</span>
                                        @else
                                            <span class="text-muted small">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($sectionCount > 0)
                                            <span class="badge bg-info">{{ $sectionCount }} section(s)</span>
                                        @else
                                            <span class="text-muted small">No sections</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($userCount > 0)
                                            <span class="badge bg-primary">{{ $userCount }} user(s)</span>
                                        @else
                                            <span class="text-muted small">No users</span>
                                        @endif
                                    </td>
                                    <td>{{ $department->note ? Str::limit($department->note, 30) : '--' }}</td>
                                    <td>{{ $department->deleted_at->format('d M, Y h:i A') }}</td>

                                    <td class="text-end print-none">
                                        <a href="{{ route('department.restore', $department->id) }}" class="btn btn-warning sm">
                                            <i class="bi bi-arrow-clockwise"></i>
                                        </a>

                                        <form action="{{ route('department.permanentDelete', $department->id) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn sm btn-danger" title="Delete Permanently"
                                                onclick="return confirm('Are you sure you want to PERMANENTLY delete this department? This will also permanently delete all associated sections and user assignments. This action cannot be undone.')">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        <i class="bi bi-inbox"></i> No Departments Found in Trash
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
    @if($departments->hasPages())
    <div class="widget">
        <div class="widget-body">
            {{ $departments->links() }}
        </div>
    </div>
    @endif
    <!-- End pagination -->
</x-app-layout>
