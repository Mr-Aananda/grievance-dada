@section('title', 'Departments')

<x-app-layout>
    <!-- Start main-bar -->
    <!-- Start header widget -->
    <div class="widget mb-3">
        <div class="widget-body d-flex">
            <!-- Start menu -->
            @include('pages.department.menu')
            <!-- End menu -->
            <!-- Start right buttons -->
            <div class="ms-auto">
                <button type="button" class="btn icon lg rounded collapsed" title="Search" data-bs-toggle="collapse"
                    data-bs-target="#tableSearch" aria-controls="tableSearch" aria-expanded="false">
                    <i class="bi bi-search"></i>
                </button>
                <button type="button" class="btn icon lg rounded" title="Print" onclick="printable('print-widget')">
                    <i class="bi bi-printer"></i>
                </button>
                <button type="button" class="btn icon lg rounded" title="Reloar" onclick="location.reload()">
                    <i class="bi bi-bootstrap-reboot"></i>
                </button>
                <button type="button" class="btn icon lg rounded" title="Go back" onclick="history.back()">
                    <i class="bi bi-arrow-left"></i>
                </button>
            </div>
            <!-- End right buttons -->
        </div>

        <!-- Start Filter Fill -->
        <div class="widget-body collapse {{ request()->search == '1' ? 'show' : '' }}" id="tableSearch">
            <form action="{{ route('department.index') }}" method="get">
                <div class="row py-3 g-3">
                    <input hidden type="text" name="search" value="1">
                    <div class="col-md-4">
                        <label for="department" class="form-label">Department name</label>
                        <input class="form-control" list="departmentList" name="name" id="department"
                            placeholder="Type a department name" value="{{ request()->name }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <button class="btn btn-success d-block w-100" type="submit"><i class="bi bi-search"></i>
                            Search
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

        <!-- Start body widget -->
        <div class="widget">
            <div class="widget-head mb-3">
                <h5>All Departments</h5>
                <p><small>{{ count($departments) }} results found </small></p>
            </div>
            <div class="widget-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">SL</th>
                            <th scope="col">Department Name</th>
                            {{-- <th scope="col">Code</th> --}}
                            <th scope="col">Sections</th>
                            {{-- <th scope="col">Total Users</th> --}}
                            <th scope="col">Note</th>
                            <th scope="col" class="text-end print-none">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($departments as $department)
                            <tr>
                                <th scope="row">{{ $departments->firstItem() + $loop->index }}</th>
                                <td>
                                    {{ $department->name }}
                                </td>
                                {{-- <td>
                                <span class="badge bg-secondary">{{ $department->code ?? 'N/A' }}</span>
                            </td> --}}
                                <td>
                                    @if ($department->sections->count() > 0)
                                        <div class="section-list">
                                            @foreach ($department->sections->take(3) as $section)
                                                <span class="badge bg-primary me-1 mb-1">
                                                    {{ $section->name }}
                                                </span>
                                            @endforeach
                                            @if ($department->sections->count() > 3)
                                                <span class="badge bg-light text-dark">
                                                    +{{ $department->sections->count() - 3 }} more
                                                </span>
                                            @endif
                                            <div class="text-muted small mt-1">
                                                Total: {{ $department->sections->count() }} sections
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted">No sections</span>
                                    @endif
                                </td>
                                {{-- <td class="text-center">
                                <span class="badge bg-primary">{{ $department->users_count ?? 0 }}</span>
                            </td> --}}
                                <td>{{ $department->note ?? '--' }}</td>
                                <td class="text-end print-none">
                                    @can('department.show')
                                        <a href="{{ route('department.show', $department->id) }}" class="btn sm btn-info"
                                            title="View Details">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    @endcan

                                    @can('department.edit')
                                        <a href="{{ route('department.edit', $department->id) }}"
                                            class="btn sm btn-success" title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                    @endcan

                                    @can('department.destroy')
                                        <form action="{{ route('department.destroy', $department->id) }}" method="POST"
                                            class="d-inline" onsubmit="return confirm('Are you sure want to delete?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Delete"
                                                {{ ($department->sections_count ?? 0) > 0 || ($department->sections_count ?? 0) > 0 ? 'disabled' : '' }}
                                                class="btn sm btn-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No Data found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <!-- End body widget -->
    </div>

    <!-- Start pagination -->
    <x-pagination :items="$departments" />
    <!-- End pagination -->

    <style>
        .section-list {
            max-width: 300px;
        }

        .section-list .badge {
            font-size: 0.75rem;
        }
    </style>
</x-app-layout>
