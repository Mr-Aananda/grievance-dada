@section('title', 'Complain Types')

<x-app-layout>
    <!-- Start header widget -->
    <div class="widget mb-3">
        <div class="widget-body d-flex">
            <!-- Start menu -->
            @include('pages.complain-type.menu')
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
            <form action="{{ route('complain-type.index') }}" method="get">
                <div class="row py-3 g-3">
                    <input hidden type="text" name="search" value="1">
                    <div class="col-md-4">
                        <label for="name" class="form-label">Complain Type Name</label>
                        <input class="form-control" name="name" id="name"
                               placeholder="Type a complain type name" value="{{ request()->name }}">
                    </div>
                    <div class="col-md-3">
                        <label for="type" class="form-label">Type</label>
                        <select class="form-control" name="type" id="type">
                            <option value="">All Types</option>
                            <option value="complain" {{ request()->type == 'complain' ? 'selected' : '' }}>Complain</option>
                            <option value="manual" {{ request()->type == 'manual' ? 'selected' : '' }}>Manual</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <button class="btn btn-success d-block w-100" type="submit">
                            <i class="bi bi-search"></i> Search
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
                <h5>All Complain Types</h5>
                <p><small>{{ $complainTypes->total() }} results found </small></p>
            </div>
            <div class="widget-body">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th scope="col">SL</th>
                        <th scope="col">Complain Type Name</th>
                        <th scope="col">Code</th>
                        <th scope="col">Type</th>
                        <th scope="col">Status</th>
                        <th scope="col">Note</th>
                        <th scope="col" class="text-end print-none">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($complainTypes as $type)
                        <tr>
                            <th scope="row">{{ $complainTypes->firstItem() + $loop->index }}</th>
                            <td>
                                {{ $type->name }}
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $type->code ?? 'N/A' }}</span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $type->type == 'complain' ? 'primary' : 'info' }}">
                                    {{ ucfirst($type->type) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $type->status ? 'success' : 'danger' }}">
                                    {{ $type->status ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>{{ $type->note ?? "--" }}</td>
                            <td class="text-end print-none">
                                @can('complain-type.show')
                                    <a href="{{ route('complain-type.show', $type->id) }}"
                                       class="btn sm btn-info" title="View Details">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                @endcan

                                @can('complain-type.edit')
                                    <a href="{{ route('complain-type.edit', $type->id) }}"
                                        class="btn sm btn-success" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                @endcan

                                @can('complain-type.destroy')
                                    <form action="{{ route('complain-type.destroy', $type->id) }}" method="POST"
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
    <x-pagination :items="$complainTypes" />
    <!-- End pagination -->
</x-app-layout>
