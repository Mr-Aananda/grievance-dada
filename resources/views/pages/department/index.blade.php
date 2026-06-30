@section('title', 'Departments')

<x-app-layout>
    <!-- Start header widget -->
    <div class="card mb-3">
        <div class="card-body py-2 d-flex align-items-center">
            <!-- Start menu -->
            @include('pages.department.menu')
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
            <form action="{{ route('department.index') }}" method="get">
                <div class="row py-2 g-3 align-items-end">
                    <input hidden type="text" name="search" value="1">
                    <div class="col-md-4">
                        <label for="department" class="form-label small fw-semibold">Department name</label>
                        <input class="form-control form-control-sm" name="name" id="department"
                               placeholder="Type a department name" value="{{ request()->name }}">
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
        <x-print.header />
        <!-- End print header -->

        <!-- Start table card -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-transparent border-0 d-flex align-items-center">
                <h6 class="mb-0 fw-bold">All Departments</h6>
                <span class="badge bg-secondary ms-2">{{ $departments->total() }} results found</span>
            </div>
            <div class="card-body p-0 table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" style="width: 60px;" class="ps-3">SL</th>
                            <th scope="col">Department Name</th>
                            <th scope="col">Sections</th>
                            <th scope="col">Note</th>
                            <th scope="col" class="text-end pe-3 print-none">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($departments as $department)
                            <tr>
                                <td class="ps-3">{{ $departments->firstItem() + $loop->index }}</td>
                                <td>
                                    <span class="fw-bold">{{ $department->name }}</span>
                                </td>
                                <td>
                                    @if ($department->sections->count() > 0)
                                        <div class="section-list">
                                            @foreach ($department->sections->take(3) as $section)
                                                <span class="badge bg-primary-subtle text-primary me-1 mb-1">
                                                    {{ $section->name }}
                                                </span>
                                            @endforeach
                                            @if ($department->sections->count() > 3)
                                                <span class="badge bg-secondary-subtle text-secondary">
                                                    +{{ $department->sections->count() - 3 }} more
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted small">No sections</span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">{{ $department->note ?? '—' }}</small>
                                </td>
                                <td class="text-end pe-3 print-none">
                                    @can('department.show')
                                        <a href="{{ route('department.show', $department->id) }}" class="btn btn-sm btn-info text-white"
                                           title="View Details">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                    @endcan

                                    @can('department.edit')
                                        <a href="{{ route('department.edit', $department->id) }}"
                                           class="btn btn-sm btn-success text-white" title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                    @endcan

                                    @can('department.destroy')
                                        <form action="{{ route('department.destroy', $department->id) }}" method="POST"
                                              class="d-inline ms-1" onsubmit="return confirm('Are you sure want to delete?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Delete"
                                                    {{ ($department->sections_count ?? 0) > 0 ? 'disabled' : '' }}
                                                    class="btn btn-sm btn-danger text-white">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                    No departments found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($departments->hasPages())
                <div class="card-footer bg-transparent border-0 d-flex justify-content-end">
                    {{ $departments->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
        <!-- End table card -->
    </div>

    <style>
        .section-list {
            max-width: 300px;
        }
        .section-list .badge {
            font-size: 0.75rem;
        }
    </style>
</x-app-layout>
