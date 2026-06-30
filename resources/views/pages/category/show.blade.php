@section('title', 'Category Details')
<x-app-layout>
    <!-- Start header widget -->
    <div class="card mb-3">
        <div class="card-body py-2 d-flex align-items-center">
            <!-- Start left menu -->
            @include('pages.category.menu')
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
                    <h6 class="mb-0 fw-bold"><i class="bi bi-info-circle me-1"></i> Category Details</h6>
                </div>
                <div class="card-body px-4 pb-4">
                    <table class="table table-hover align-middle mb-0">
                        <tbody>
                            <tr>
                                <th scope="row" width="30%">Category Name</th>
                                <td>{{ $category->name }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Code</th>
                                <td><span class="badge bg-secondary-subtle text-secondary">{{ $category->code ?? '—' }}</span></td>
                            </tr>
                            <tr>
                                <th scope="row">Status</th>
                                <td>
                                    <span class="badge bg-{{ $category->status ? 'success-subtle text-success' : 'danger-subtle text-danger' }}">
                                        {{ $category->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">Note</th>
                                <td><small class="text-muted">{{ $category->note ?? '—' }}</small></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
