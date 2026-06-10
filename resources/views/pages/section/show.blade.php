@section('title', 'Section Details')
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
                <button type="button" class="btn icon lg rounded" title="Print"
                        onclick="printable('print-widget')">
                    <i class="bi bi-printer"></i>
                </button>
                <button type="button" class="btn icon lg rounded" title="Go back" onclick="history.back()">
                    <i class="bi bi-arrow-left"></i>
                </button>
            </div>
            <!-- End right buttons -->
        </div>
    </div>
    <!-- End header widget -->

    <div class="row">
        <div class="col-md-4">
            <div class="widget">
                <div class="widget-head mb-3">
                    <h5>Section Information</h5>
                </div>
                <div class="widget-body">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th width="40%">Section Name</th>
                                <td>{{ $section->name }}</td>
                            </tr>
                            <tr>
                                <th>Code</th>
                                <td><span class="badge bg-secondary">{{ $section->code }}</span></td>
                            </tr>
                            <tr>
                                <th>Department</th>
                                <td>
                                    <span class="badge bg-info">
                                        {{ $section->department->name ?? 'N/A' }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    <span class="badge {{ $section->status ? 'bg-success' : 'bg-danger' }}">
                                        {{ $section->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                            </tr>
                            {{-- <tr>
                                <th>Total Users</th>
                                <td>
                                    <span class="badge bg-primary">{{ $section->users_count ?? 0 }}</span>
                                </td>
                            </tr> --}}
                            <tr>
                                <th>Note</th>
                                <td>{{ $section->note ?? 'N/A' }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="mt-3 print-none">
                        <div class="d-grid gap-2">
                            @can('section.edit')
                                <a href="{{ route('section.edit', $section->id) }}"
                                   class="btn btn-success">
                                    <i class="bi bi-pencil-square me-1"></i> Edit Section
                                </a>
                            @endcan

                            @can('section.destroy')
                                <form action="{{ route('section.destroy', $section->id) }}" method="POST"
                                      onsubmit="return confirm('Are you sure want to delete this section?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            {{ $section->users_count > 0 ? 'disabled' : '' }}
                                            class="btn btn-danger w-100">
                                        <i class="bi bi-trash me-1"></i> Delete Section
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="widget">
                <div class="widget-head mb-3">
                    <h5>Users in this Section ({{ $section->users_count ?? 0 }})</h5>
                </div>
                <div class="widget-body">
                    @if($section->users->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Name</th>
                                        <th>Employee ID</th>
                                        <th>Designation</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($section->users as $user)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->emp_id }}</td>
                                            <td>{{ $user->designation ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge {{ $user->status ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $user->status ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            No users assigned to this section.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
