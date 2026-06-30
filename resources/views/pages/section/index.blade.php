@section('title', 'Sections')

<x-app-layout>
    <!-- Start header widget -->
    <div class="card mb-3">
        <div class="card-body py-2 d-flex align-items-center">
            <!-- Start menu -->
            @include('pages.section.menu')
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
            <form action="{{ route('section.index') }}" method="get">
                <input hidden type="text" name="search" value="1">
                <div class="row py-2 g-3 align-items-end">
                    <div class="col-md-3">
                        <label for="name" class="form-label small fw-semibold">Section Name</label>
                        <input type="text" class="form-control form-control-sm" id="name" name="name"
                               placeholder="Search sections" value="{{ request()->name }}">
                    </div>
                    <div class="col-md-3">
                        <label for="department" class="form-label small fw-semibold">Department</label>
                        <select class="form-select form-select-sm" id="department" name="department_id">
                            <option value="">All Departments</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}"
                                        {{ request()->department_id == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 d-flex gap-2">
                        <button class="btn btn-sm btn-primary px-3" type="submit">
                            <i class="bi bi-search me-1"></i> Search
                        </button>
                        <a href="{{ route('section.index') }}" class="btn btn-sm btn-secondary px-3">
                            <i class="bi bi-x-circle me-1"></i> Clear
                        </a>
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
                <h6 class="mb-0 fw-bold">All Sections</h6>
                <span class="badge bg-secondary ms-2">{{ $sections->total() }} results found</span>
            </div>
            <div class="card-body p-0 table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" style="width: 60px;" class="ps-3">SL</th>
                            <th scope="col">Section Name</th>
                            <th scope="col">Department</th>
                            <th scope="col">Note</th>
                            <th scope="col" class="text-end pe-3 print-none">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sections as $section)
                            <tr>
                                <td class="ps-3">{{ $sections->firstItem() + $loop->index }}</td>
                                <td>
                                    <span class="fw-bold">{{ $section->name }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-primary-subtle text-primary">{{ $section->department->name ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    <small class="text-muted">{{ $section->note ?? '—' }}</small>
                                </td>
                                <td class="text-end pe-3 print-none">
                                    @can('section.show')
                                        <a href="{{ route('section.show', $section->id) }}"
                                           class="btn btn-sm btn-info text-white" title="View Details">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                    @endcan

                                    @can('section.edit')
                                        <a href="{{ route('section.edit', $section->id) }}"
                                           class="btn btn-sm btn-success text-white" title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                    @endcan

                                    @can('section.destroy')
                                        <form action="{{ route('section.destroy', $section->id) }}" method="POST"
                                              class="d-inline ms-1"
                                              onsubmit="return confirm('Are you sure want to delete this section?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Delete"
                                                    {{ ($section->users_count ?? 0) > 0 ? 'disabled' : '' }}
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
                                    No sections found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($sections->hasPages())
                <div class="card-footer bg-transparent border-0 d-flex justify-content-end">
                    {{ $sections->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
        <!-- End table card -->
    </div>
</x-app-layout>
