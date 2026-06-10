@section('title', 'Department Details')
<x-app-layout>
    <!-- Start header widget -->
    <div class="widget mb-3">
        <div class="widget-body d-flex">
            <div class="ms-auto">
                <button type="button" class="btn icon lg rounded" title="Go back" onclick="history.back()">
                    <i class="bi bi-arrow-left"></i>
                </button>
            </div>
        </div>
    </div>
    <!-- End header widget -->

    <div class="row">
        <div class="col-md-8">
            <div class="widget">
                <div class="widget-head mb-3">
                    <h5>Department Details</h5>
                </div>
                <div class="widget-body">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th width="30%">Department Name</th>
                                <td>{{ $department->name }}</td>
                            </tr>
                            <tr>
                                <th>Code</th>
                                <td><span class="badge bg-secondary">{{ $department->code }}</span></td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    <span class="badge {{ $department->status ? 'bg-success' : 'bg-danger' }}">
                                        {{ $department->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Total Sections</th>
                                <td>{{ $department->sections_count ?? 0 }}</td>
                            </tr>
                            {{-- <tr>
                                <th>Total Users</th>
                                <td>{{ $department->users_count ?? 0 }}</td>
                            </tr> --}}
                            <tr>
                                <th>Note</th>
                                <td>{{ $department->note ?? 'N/A' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="widget">
                <div class="widget-head mb-3">
                    <h5>Sections</h5>
                </div>
                <div class="widget-body">
                    @if($department->sections->count() > 0)
                        <div class="list-group">
                            @foreach($department->sections as $section)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0">{{ $section->name }}</h6>
                                        <small class="text-muted">{{ $section->code }}</small>
                                    </div>
                                    <span class="badge bg-info rounded-pill">
                                        {{ $section->users_count ?? 0 }} users
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-info">
                            No sections found for this department.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
