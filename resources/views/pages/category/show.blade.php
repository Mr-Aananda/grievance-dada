@section('title', 'Category Details')
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
                    <h5>Category Details</h5>
                </div>
                <div class="widget-body">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th width="30%">Category Name</th>
                                <td>{{ $category->name }}</td>
                            </tr>
                            <tr>
                                <th>Code</th>
                                <td><span class="badge bg-secondary">{{ $category->code ?? 'N/A' }}</span></td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    <span class="badge {{ $category->status ? 'bg-success' : 'bg-danger' }}">
                                        {{ $category->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                            </tr>

                            <tr>
                                <th>Note</th>
                                <td>{{ $category->note ?? 'N/A' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
