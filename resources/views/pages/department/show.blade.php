@section('title', 'Department Details')
<x-app-layout>
    <!-- Start header widget -->
    <div class="card mb-3">
        <div class="card-body py-2 d-flex align-items-center">
            <!-- Start left menu -->
            @include('pages.department.menu')
            <!-- End left menu -->
            
            <!-- Start right buttons -->
            <div class="ms-auto">
                <button type="button" class="btn btn-sm btn-outline-secondary" title="Go back" onclick="history.back()">
                    <i class="bi bi-arrow-left"></i> Back
                </button>
            </div>
            <!-- End right buttons -->
        </div>
    </div>
    <!-- End header widget -->

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-transparent border-0 pt-3 px-4">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-info-circle me-1"></i> Department Details</h6>
                </div>
                <div class="card-body px-4 pb-4">
                    <table class="table table-hover align-middle mb-0">
                        <tbody>
                            <tr>
                                <th scope="row" width="30%">Department Name</th>
                                <td>{{ $department->name }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Code</th>
                                <td><span class="badge bg-secondary-subtle text-secondary">{{ $department->code }}</span></td>
                            </tr>
                            <tr>
                                <th scope="row">Status</th>
                                <td>
                                    <span class="badge bg-{{ $department->status ? 'success-subtle text-success' : 'danger-subtle text-danger' }}">
                                        {{ $department->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">Total Sections</th>
                                <td>{{ $department->sections_count ?? 0 }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Note</th>
                                <td><small class="text-muted">{{ $department->note ?? '—' }}</small></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-transparent border-0 pt-3 px-4">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-grid me-1"></i> Sections</h6>
                </div>
                <div class="card-body px-4 pb-4">
                    @if($department->sections->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($department->sections as $section)
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-2 border-0 border-bottom">
                                    <div>
                                        <h6 class="mb-0 fw-semibold" style="font-size: 0.875rem;">{{ $section->name }}</h6>
                                        <small class="text-muted">{{ $section->code }}</small>
                                    </div>
                                    <span class="badge bg-info-subtle text-info rounded-pill">
                                        {{ $section->users_count ?? 0 }} users
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-inbox fs-4 d-block mb-1"></i>
                            <small>No sections found for this department.</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
