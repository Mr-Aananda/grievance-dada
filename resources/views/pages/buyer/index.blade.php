@section('title', 'Buyers')
<x-app-layout>
    <!-- Start main-bar -->
    <!-- Start header widget -->
    <div class="widget mb-3">
        <div class="widget-body d-flex">
            <!-- Start menu -->
            @include('pages.buyer.menu')
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
            <form action="{{ route('buyer.index') }}" method="get">
                <div class="row py-3 g-3">
                    <input hidden type="text" name="search" value="1">
                    <div class="col-md-3">
                        <label for="company_name" class="form-label">Company Name</label>
                        <input class="form-control" name="company_name" id="company_name"
                               placeholder="Type company name" value="{{ request()->company_name }}">
                    </div>
                    <div class="col-md-3">
                        <label for="code" class="form-label">Code</label>
                        <input class="form-control" name="code" id="code"
                               placeholder="Type code" value="{{ request()->code }}">
                    </div>
                    <div class="col-md-2">
                        <label for="country" class="form-label">Country</label>
                        <input class="form-control" name="country" id="country"
                               placeholder="Type country" value="{{ request()->country }}">
                    </div>
                    <div class="col-md-2">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" name="status" id="status">
                            <option value="">All</option>
                            <option value="1" {{ request()->status == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ request()->status == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <button class="btn btn-success d-block w-100" type="submit"><i
                                class="bi bi-search"></i>
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
        <x-print.header/>
        <!-- End print header -->

        <!-- Start body widget -->
        <div class="widget">
            <div class="widget-head mb-3">
                <h5>All Buyers</h5>
                <p><small>{{ $buyers->total() }} results found </small></p>
            </div>
            <div class="widget-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th scope="col">SL</th>
                            <th scope="col">Company Name</th>
                            <th scope="col">Code</th>
                            <th scope="col">Country</th>
                            <th scope="col">Email</th>
                            <th scope="col">Phone</th>
                            <th scope="col">Status</th>
                            <th scope="col" class="text-end print-none">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($buyers as $buyer)
                            <tr>
                                <th scope="row">{{ $buyers->firstItem() + $loop->index }}</th>
                                <td>
                                    <strong>{{ $buyer->company_name }}</strong>
                                    @if($buyer->note)
                                        <br><small class="text-muted">{{ Str::limit($buyer->note, 50) }}</small>
                                    @endif
                                </td>
                                <td>{{ $buyer->code }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $buyer->country }}</span>
                                </td>
                                <td>{{ $buyer->email ?? '--' }}</td>
                                <td>{{ $buyer->phone ?? '--' }}</td>
                                <td>
                                    <span class="badge {{ $buyer->status ? 'bg-success' : 'bg-danger' }}">
                                        {{ $buyer->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="text-end print-none">
                                    @can('buyer.show')
                                        <a href="{{ route('buyer.show', $buyer->id) }}"
                                           class="btn sm btn-info" title="View Details">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    @endcan

                                    @can('buyer.edit')
                                        <a href="{{ route('buyer.edit', $buyer->id) }}"
                                            class="btn sm btn-success" title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                    @endcan

                                    @can('buyer.destroy')
                                        <form action="{{ route('buyer.destroy', $buyer->id) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Are you sure want to delete?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Delete"
                                                    class="btn sm btn-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No Data found</td>
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
    <x-pagination :items="$buyers" />
    <!-- End pagination -->
</x-app-layout>
