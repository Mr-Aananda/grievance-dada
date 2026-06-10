@section('title', 'Complain Type Details')
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
                    <h5>Complain Type Details</h5>
                </div>
                <div class="widget-body">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th width="30%">Complain Type Name</th>
                                <td>{{ $complainType->name }}</td>
                            </tr>
                            <tr>
                                <th>Code</th>
                                <td><span class="badge bg-secondary">{{ $complainType->code ?? 'N/A' }}</span></td>
                            </tr>
                            <tr>
                                <th>Type</th>
                                <td>
                                    <span class="badge bg-{{ $complainType->type == 'complain' ? 'primary' : 'info' }}">
                                        {{ ucfirst($complainType->type) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    <span class="badge {{ $complainType->status ? 'bg-success' : 'bg-danger' }}">
                                        {{ $complainType->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Note</th>
                                <td>{{ $complainType->note ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Total Complains</th>
                                <td>
                                    <span class="badge bg-primary">{{ $complainType->complains->count() }}</span>
                                </td>
                            </tr>
                            <tr>
                                <th>Created At</th>
                                <td>{{ $complainType->created_at->format('d M, Y h:i A') }}</td>
                            </tr>
                            <tr>
                                <th>Last Updated</th>
                                <td>{{ $complainType->updated_at->format('d M, Y h:i A') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
