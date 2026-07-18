@section('title', 'Users details')

<x-app-layout>
    <!-- Start header widget -->
    <div class="card mb-3">
        <div class="card-body py-2 d-flex align-items-center">
            <!-- Start left menu -->
            @include('user.menu')
            <!-- End left menu -->
            
            <!-- Start right buttons -->
            <div class="ms-auto d-flex gap-1">
                <button type="button" class="btn btn-sm btn-outline-secondary" title="Print Details"
                    onclick="printable('print-widget')">
                    <i class="bi bi-printer"></i>
                </button>
                @can('user.edit')
                    <a href="{{ route('user.edit', $user->id) }}" class="btn btn-sm btn-outline-success" title="Edit">
                        <i class="bi bi-pencil-square"></i>
                    </a>
                @endcan
                @can('user.destroy')
                    @php
                        $isAdmin = $user->hasRole('Administrator') && $user->email === 'admin@dadadhaka.com';
                    @endphp
                    @if (!$isAdmin)
                        <form action="{{ route('user.destroy', $user->id) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Are you sure want to delete?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                    <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    @endif
                @endcan
                <button type="button" class="btn btn-sm btn-outline-secondary" title="Go back" onclick="history.back()">
                    <i class="bi bi-arrow-left"></i>
                </button>
            </div>
            <!-- End right buttons -->
        </div>
    </div>
    <!-- End header widget -->

    <div class="row g-3">
        <div class="col-lg-3">
            <div class="card shadow-sm border-0 text-center p-4">
                <div class="mb-3">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random&size=300"
                        class="rounded img-fluid" alt="{{ $user->name }}" style="max-height: 150px;">
                </div>
                <h5 class="fw-bold mb-1">{{ $user->name }}</h5>
                <span class="badge bg-secondary mb-3">{{ $user->emp_id }}</span>
            </div>
        </div>

        <div class="col-lg-9">
            <div id="print-widget">
                <!-- Start print header -->
                <x-print.header />
                <!-- End print header  -->

                <!-- Start details card -->
                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-header bg-transparent border-0 py-3">
                        <h5 class="mb-0 fw-bold">User Details</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-hover align-middle">
                            <tbody>
                                <tr>
                                    <th scope="row" width="30%">Name</th>
                                    <td>{{ $user->name }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Employee ID</th>
                                    <td>{{ $user->emp_id }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Department</th>
                                    <td>{{ $user->department?->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Section</th>
                                    <td>{{ $user->section?->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Designation</th>
                                    <td>{{ $user->designation ?? '--' }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Email</th>
                                    <td>{{ $user->email ?? '--' }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Phone</th>
                                    <td>{{ $user->phone ?? '--' }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Status</th>
                                    <td>
                                        <span class="badge {{ $user->status == 1 ? 'bg-success' : 'bg-danger' }}">
                                            {{ $user->status == 1 ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Roles card -->
                <div class="widget mb-3">
                    <div class="widget-head mb-3">
                        <h5>Assigned Roles</h5>
                    </div>
                    <div class="widget-body">
                        <div class="d-flex flex-wrap gap-2">
                            @forelse($user->roles as $role)
                                <span class="badge bg-primary fw-bold px-3 py-2" style="font-size: 0.85rem;">{{ $role->name }}</span>
                            @empty
                                <span class="text-muted small">No roles assigned</span>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Permissions card -->
                @if(!$user->roles->isEmpty())
                    <div class="widget mb-3">
                        <div class="widget-head mb-3">
                            <h5>Permissions Details</h5>
                        </div>
                        <div class="widget-body">
                            @php
                                $isAdministrator = $user->roles->contains('name', 'Administrator');
                            @endphp

                            @if($isAdministrator)
                                <div class="alert alert-info">
                                    <strong>Administrator:</strong> This user has all permissions.
                                </div>
                            @else
                                <!-- Permission Areas -->
                                @if(!empty($assigned_permission_area_groups))
                                    <div class="table-responsive mb-4">
                                        <table class="table table-hover align-middle">
                                            <thead>
                                                <tr>
                                                    <th scope="col" width="30%">Permission Area</th>
                                                    <th scope="col">Permissions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($assigned_permission_area_groups as $group => $permission_areas)
                                                    <tr>
                                                        <td class="fw-bold">{{ ucwords(str_replace('_', ' ', $group)) }}</td>
                                                        <td>
                                                            <div class="d-flex flex-wrap gap-1">
                                                                @forelse($permission_areas as $permission_area)
                                                                    <span class="badge bg-info text-dark">
                                                                        {{ $permission_area['key'] ?? $permission_area }}
                                                                    </span>
                                                                @empty
                                                                    <span class="text-muted small">No permissions</span>
                                                                @endforelse
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="2" class="text-center text-muted py-3">No permission areas assigned</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                @endif

                                <!-- Partial Permissions -->
                                @if(!empty($assigned_partial_permission_groups))
                                    <h6 class="fw-bold mb-3">Partial Permissions</h6>
                                    <div class="row">
                                        @forelse($assigned_partial_permission_groups as $group => $partial_permissions)
                                            <div class="col-md-6 mb-3">
                                                <div class="widget h-100">
                                                    <div class="widget-head py-2">
                                                        <strong>{{ ucfirst(str_replace('_', ' ', $group)) }}</strong>
                                                    </div>
                                                    <div class="widget-body py-2">
                                                        <ul class="list-unstyled mb-0 small">
                                                            @forelse($partial_permissions as $permission)
                                                                <li class="mb-2">
                                                                    <i class="bi bi-check-circle-fill text-success me-1"></i>
                                                                    {{ ucfirst(str_replace(['_', '-'], ' ', $permission['name'] ?? $permission)) }}
                                                                    @if(isset($permission['description']))
                                                                        <small class="text-muted d-block ms-3">{{ $permission['description'] }}</small>
                                                                    @endif
                                                                </li>
                                                            @empty
                                                                <li class="text-muted">No permissions</li>
                                                            @endforelse
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="col-12">
                                                <p class="text-muted text-center py-2">No partial permissions assigned</p>
                                            </div>
                                        @endforelse
                                    </div>
                                @endif

                                <!-- If no permissions at all -->
                                @if(empty($assigned_permission_area_groups) && empty($assigned_partial_permission_groups))
                                    <div class="alert alert-warning">
                                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                        No specific permissions assigned to this user.
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
