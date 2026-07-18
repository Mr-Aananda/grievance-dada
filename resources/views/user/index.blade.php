@section('title', 'Users')

<x-app-layout>
    <!-- Start header widget -->
    <div class="card mb-3">
        <div class="card-body py-2 d-flex align-items-center">
            <!-- Start menu -->
            @include('user.menu')
            <!-- End  menu -->
            <!-- Start right button -->
            <div class="ms-auto">
                <button type="button" class="btn btn-sm btn-outline-secondary me-1" title="Search" data-bs-toggle="collapse"
                    data-bs-target="#tableSearch" aria-controls="tableSearch" aria-expanded="false">
                    <i class="bi bi-search"></i> Filter
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary me-1" title="Print" onclick="printable('print-widget')">
                    <i class="bi bi-printer"></i>
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary me-1" title="Reload" onclick="location.reload()">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary" title="Go back" onclick="history.back()">
                    <i class="bi bi-arrow-left"></i>
                </button>
            </div>
            <!-- End right button -->
        </div>

        <!-- Start Filter Fill -->
        <div class="card-footer collapse {{ request()->search == '1' ? 'show' : '' }} bg-light border-0" id="tableSearch">
            <form action="{{ route('user.index') }}">
                <input hidden name="search" value="1">
                <div class="row py-2 g-3">
                    <div class="col-md-3">
                        <label for="account" class="form-label small fw-semibold">User name</label>
                        <input type="text" class="form-control form-control-sm" value="{{ request()->name }}"
                            placeholder="Search users" id="account" list="search-user" name="name">
                    </div>

                    <div class="col-md-3">
                        <label for="phone" class="form-label small fw-semibold">Phone</label>
                        <input type="text" class="form-control form-control-sm" value="{{ request()->phone }}"
                            placeholder="Ex: 01xxx" name="phone" id="phone">
                    </div>

                    <div class="col-md-3">
                        <label for="Status" class="form-label small fw-semibold">Status</label>
                        <select class="form-select form-select-sm" name="status" id="Status">
                            <option selected disabled value="">--Choose one--</option>
                            <option value="1" {{ request()->status == '1' ? 'selected' : '' }}>Active User</option>
                            <option value="0" {{ request()->status == '0' ? 'selected' : '' }}>Inactive User</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <button class="btn btn-sm btn-primary d-block w-100" type="submit"><i class="bi bi-search"></i>
                            Search
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <!-- End Search body -->
    </div>
    <!-- End header widget -->

    <div id="print-widget">
        <!-- Start print header -->
        <x-print.header />
        <!-- End print header -->

        <div class="card shadow-sm border-0">
            <div class="card-header bg-transparent border-0 d-flex align-items-center py-3">
                <h5 class="mb-0 fw-bold">All Users</h5>
                <span class="badge bg-light text-dark ms-2 fw-semibold">Total: {{ $users->total() }}</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th style="width: 70px;" class="ps-3">SL</th>
                                <th>Name</th>
                                <th>Employee ID</th>
                                <th>Department</th>
                                <th>Section</th>
                                <th class="text-center">Status</th>
                                <th class="text-end pe-3 print-none">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr>
                                    <td class="ps-3">{{ $users->firstItem() + $loop->index }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->emp_id }}</td>
                                    <td>{{ $user->department?->name }}</td>
                                    <td>{{ $user->section?->name ?? 'N/A' }}</td>
                                    <td class="text-center">
                                        <span class="badge {{ $user->status == '1' ? 'bg-primary' : 'bg-danger' }}">
                                            {{ $user->status == '1' ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="text-end pe-3 print-none">
                                        @can('user.show')
                                            <a href="{{ route('user.show', $user->id) }}" class="btn btn-sm btn-info text-white">
                                                <i class="bi bi-eye-fill"></i>
                                            </a>
                                        @endcan

                                        @can('user.edit')
                                            <a href="{{ route('user.edit', $user->id) }}" class="btn btn-sm btn-success text-white">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                        @endcan

                                        @can('user.destroy')
                                            @php
                                                $isAdmin = $user->hasRole('Administrator') && $user->email === 'admin@dadadhaka.com';
                                            @endphp

                                            @if (!$isAdmin)
                                                <form action="{{ route('user.destroy', $user->id) }}" method="POST"
                                                    class="d-inline" id="sm-delete-{{ $user->id }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-sm btn-danger text-white"
                                                        onclick="if(confirm('Are you sure want to delete?')) { document.getElementById('sm-delete-{{ $user->id }}').submit() } return false">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <button class="btn btn-sm btn-danger text-white" disabled>
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            @endif
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        <i class="bi bi-inbox display-4 d-block mb-2"></i>
                                        <span>No users found</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Start pagination -->
    <x-pagination :items="$users" />
    <!-- End pagination -->
</x-app-layout>
