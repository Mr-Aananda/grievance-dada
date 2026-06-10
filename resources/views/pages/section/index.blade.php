@section('title', 'Sections')
<x-app-layout>
    <!-- Start main-bar -->
    <!-- Start header widget -->
    <div class="widget mb-3">
        <div class="widget-body d-flex">
            <!-- Start menu -->
            @include('pages.section.menu')
            <!-- End menu -->
            <!-- Start right buttons -->
            <div class="ms-auto">
                <button type="button" class="btn icon lg rounded collapsed" title="Search"
                        data-bs-toggle="collapse" data-bs-target="#tableSearch" aria-controls="tableSearch"
                        aria-expanded="false">
                    <i class="bi bi-search"></i>
                </button>
                <button type="button" class="btn icon lg rounded" title="Print"
                        onclick="printable('print-widget')">
                    <i class="bi bi-printer"></i>
                </button>
                <button type="button" class="btn icon lg rounded" title="Reload" onclick="location.reload()">
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
            <form action="{{ route('section.index') }}" method="get">
                <div class="row py-3 g-3">
                    <input hidden type="text" name="search" value="1">
                    <div class="col-md-3">
                        <label for="name" class="form-label">Section Name</label>
                        <input type="text" class="form-control" id="name" name="name"
                               placeholder="Search sections" value="{{ request()->name }}">
                    </div>
                    <div class="col-md-3">
                        <label for="department" class="form-label">Department</label>
                        <select class="form-select" id="department" name="department_id">
                            <option value="">All Departments</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}"
                                        {{ request()->department_id == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    {{-- <div class="col-md-2">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">All Status</option>
                            <option value="1" {{ request()->status == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ request()->status == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div> --}}
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <button class="btn btn-success d-block w-100" type="submit">
                            <i class="bi bi-search"></i> Search
                        </button>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <a href="{{ route('section.index') }}" class="btn btn-danger d-block w-100">
                            <i class="bi bi-x-circle"></i> Clear
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

        <!-- Start body widget -->
        <div class="widget">
            <div class="widget-head mb-3">
                <h5>All Sections</h5>
                <p><small>{{ $sections->total() }} results found</small></p>
            </div>
            <div class="widget-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Section Name</th>
                                {{-- <th>Code</th> --}}
                                <th>Department</th>
                                {{-- <th>Total Users</th>
                                <th>Status</th> --}}
                                <th>Note</th>
                                <th class="text-end print-none">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sections as $section)
                                <tr>
                                    <th>{{ $sections->firstItem() + $loop->index }}</th>
                                    <td>{{ $section->name }}</td>
                                    {{-- <td>
                                        <span class="badge bg-secondary">{{ $section->code }}</span>
                                    </td> --}}
                                    <td>
                                        <span class="badge bg-primary">{{ $section->department->name ?? 'N/A' }}</span>
                                    </td>
                                    {{-- <td class="text-center">
                                        <span class="badge bg-primary">{{ $section->users_count ?? 0 }}</span>
                                    </td> --}}
                                    {{-- <td class="text-center">
                                        <span class="badge {{ $section->status ? 'bg-success' : 'bg-danger' }}">
                                            {{ $section->status ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td> --}}
                                    <td>{{ $section->note ?? '--' }}</td>
                                    <td class="text-end print-none">
                                        @can('section.show')
                                            <a href="{{ route('section.show', $section->id) }}"
                                               class="btn sm btn-info" title="View">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        @endcan

                                        @can('section.edit')
                                            <a href="{{ route('section.edit', $section->id) }}"
                                               class="btn sm btn-success" title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                        @endcan

                                        @can('section.destroy')
                                            <form action="{{ route('section.destroy', $section->id) }}" method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('Are you sure want to delete this section?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" title="Delete"
                                                        {{ $section->users_count > 0 ? 'disabled' : '' }}
                                                        class="btn sm btn-danger">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">No sections found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- End body widget -->
    </div>

    <!-- Start pagination -->
    <x-pagination :items="$sections" />
    <!-- End pagination -->

    <style>
        .badge {
            font-size: 0.85rem;
        }
    </style>
</x-app-layout>
