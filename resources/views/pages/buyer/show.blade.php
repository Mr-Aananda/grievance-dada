@section('title', 'Buyer Details')
<x-app-layout>
    <!-- Start header widget -->
    <div class="widget mb-3">
        <div class="widget-body d-flex">
            <!-- Start left menu -->
            @include('pages.buyer.menu')
            <!-- End left menu -->
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
                    <h5>Buyer Details</h5>
                </div>
                <div class="widget-body">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th width="30%">Company Name</th>
                                <td>{{ $buyer->company_name }}</td>
                            </tr>
                            <tr>
                                <th>Code</th>
                                <td>{{ $buyer->code }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $buyer->email ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Phone</th>
                                <td>{{ $buyer->phone ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Country</th>
                                <td>{{ $buyer->country }}</td>
                            </tr>
                            <tr>
                                <th>Address</th>
                                <td>{{ $buyer->address ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    <span class="badge {{ $buyer->status ? 'bg-success' : 'bg-danger' }}">
                                        {{ $buyer->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Note</th>
                                <td>{{ $buyer->note ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Created By</th>
                                <td>{{ $buyer->creator->name ?? 'System' }}</td>
                            </tr>
                            <tr>
                                <th>Created At</th>
                                <td>{{ $buyer->created_at->format('d M, Y h:i A') }}</td>
                            </tr>
                            <tr>
                                <th>Last Updated By</th>
                                <td>{{ $buyer->updater->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Last Updated At</th>
                                <td>{{ $buyer->updated_at->format('d M, Y h:i A') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="widget">
                <div class="widget-head mb-3">
                    <h5>Quick Actions</h5>
                </div>
                <div class="widget-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('buyer.edit', $buyer->id) }}" class="btn btn-success">
                            <i class="bi bi-pencil-square me-2"></i> Edit Buyer
                        </a>

                        <form action="{{ route('buyer.toggleStatus', $buyer->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn {{ $buyer->status ? 'btn-warning' : 'btn-info' }} w-100">
                                <i class="bi bi-power me-2"></i>
                                {{ $buyer->status ? 'Deactivate' : 'Activate' }} Buyer
                            </button>
                        </form>

                        <a href="{{ route('buyer.index') }}" class="btn btn-primary">
                            <i class="bi bi-list me-2"></i> Back to List
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
