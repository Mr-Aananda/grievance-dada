@section('title', 'Users details')

<x-app-layout>
    <!-- Start main-bar -->

    <div class="row g-3">

        <div class="col-lg-3">
            <div class="widget">
                <div class="widget-head border-bottom pb-3 text-center mb-2">
                    <button type="button" class="btn icon lg rounded" title="Print Product Details"
                        onclick="printable('print-widget')">
                        <i class="bi bi-printer"></i>
                    </button>
                    @can('user.edit')
                        <a href="{{ route('user.edit', $user->id) }}" type="button" class="btn icon lg rounded"
                            title="Edit">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                    @endcan
                    @can('user.destroy')
                        <form action="{{ route('user.destroy', $user->id) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Are you sure want to delete?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn icon lg rounded" title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    @endcan
                </div>

                <div class="text-center">
                    <img src="https://ui-avatars.com/api/?name={{ $user->name }}&background=random&size=300"
                        class="rounded" alt="{{ $user->name }}">
                </div>

            </div>
        </div>

        <div class="col-lg-9">
            <div class="widget" id="print-widget">

                <!-- Start print header -->
                <x-print.header />
                <!-- End print header  -->

                <!-- Start body -->
                <div class="widget-body mt-3">
                    <h5 class="mt-3 mb-2">User Details</h5>
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td style="width: 200px;">Name</td>
                                <td>{{ $user->name }}</td>
                            </tr>
                            <tr>
                                <td>Employee ID</td>
                                <td>{{ $user->emp_id }}</td>
                            </tr>
                            <tr>
                                <td>Department</td>
                                <td>{{ $user->department?->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td>Section</td>
                                <td>{{ $user->section?->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td>Designation</td>
                                <td>{{ $user->designation ?? '--' }}</td>
                            </tr>
                            <tr>
                                <td>Email</td>
                                <td>{{ $user->email ?? '--' }}</td>
                            </tr>
                            <tr>
                                <td>Phone</td>
                                <td>{{ $user->phone ?? '--' }}</td>
                            </tr>
                            <tr>
                                <td>Status</td>
                                <td>
                                    @if($user->status == 1)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Roles Section - Updated for multiple roles -->
                    <h5 class="mt-4 mb-2">Roles</h5>
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td style="width: 200px;">User Roles</td>
                                <td>
                                    @forelse($user->roles as $role)
                                        <span class="badge bg-primary me-1">{{ $role->name }}</span>
                                    @empty
                                        <span class="text-muted">No roles assigned</span>
                                    @endforelse
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Permissions Section - Only show if user has permissions -->
                    @if(!$user->roles->isEmpty())
                        <h5 class="mt-4 mb-2">Permissions</h5>

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
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width: 200px;">Permission Area</th>
                                                <th>Permissions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($assigned_permission_area_groups as $group => $permission_areas)
                                                <tr>
                                                    <td class="fw-bold">{{ ucwords(str_replace('_', ' ', $group)) }}</td>
                                                    <td>
                                                        @forelse($permission_areas as $permission_area)
                                                            <span class="badge bg-info text-dark me-1 mb-1">
                                                                {{ $permission_area['key'] ?? $permission_area }}
                                                            </span>
                                                        @empty
                                                            <span class="text-muted">No permissions</span>
                                                        @endforelse
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="2" class="text-center text-muted">No permission areas assigned</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            @endif

                            <!-- Partial Permissions -->
                            @if(!empty($assigned_partial_permission_groups))
                                <h6 class="mt-3 mb-2">Partial Permissions</h6>
                                <div class="row">
                                    @forelse($assigned_partial_permission_groups as $group => $partial_permissions)
                                        <div class="col-md-6 mb-3">
                                            <div class="card h-100">
                                                <div class="card-header bg-light">
                                                    <strong>{{ ucfirst(str_replace('_', ' ', $group)) }}</strong>
                                                </div>
                                                <div class="card-body">
                                                    <ul class="list-unstyled mb-0">
                                                        @forelse($partial_permissions as $permission)
                                                            <li class="mb-1">
                                                                <i class="bi bi-check-circle-fill text-success me-1 small"></i>
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
                                            <p class="text-muted text-center">No partial permissions assigned</p>
                                        </div>
                                    @endforelse
                                </div>
                            @endif

                            <!-- If no permissions at all -->
                            @if(empty($assigned_permission_area_groups) && empty($assigned_partial_permission_groups))
                                <div class="alert alert-warning">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    No specific permissions assigned to this user.
                                </div>
                            @endif
                        @endunless
                    @endif
                </div>
                <!-- End body  -->
            </div>
        </div>
    </div>

    <!-- End main-bar -->
</x-app-layout>
