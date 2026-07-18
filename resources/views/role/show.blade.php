@section('title', $role->name . ' - Details')

<x-app-layout>
    <!-- Start header widget -->
    <div class="card mb-3">
        <div class="card-body py-2 d-flex align-items-center">
            <!-- Start left menu -->
            @include('role.menu')
            <!-- End left menu -->
            
            <!-- Start right buttons -->
            <div class="ms-auto d-flex gap-1">
                <button type="button" class="btn btn-sm btn-outline-secondary" title="Print Details"
                    onclick="printable('print-widget')">
                    <i class="bi bi-printer"></i>
                </button>
                @unless(\Database\Seeders\RoleSeeder::ADMINISTRATOR_RULE_NAME == $role->name)
                    @can('role.edit')
                        <a href="{{ route('role.edit', $role->id) }}" class="btn btn-sm btn-outline-success" title="Edit">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                    @endcan
                    @can('role.destroy')
                        @unless($role->is_permanent)
                            <form action="{{ route('role.destroy', $role->id) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('Are you sure want to delete?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        @endunless
                    @endcan
                @endunless
                <button type="button" class="btn btn-sm btn-outline-secondary" title="Go back" onclick="history.back()">
                    <i class="bi bi-arrow-left"></i>
                </button>
            </div>
            <!-- End right buttons -->
        </div>
    </div>
    <!-- End header widget -->

    <div id="print-widget">
        <!-- Start print header -->
        <x-print.header />
        <!-- End print header  -->

        <div class="row g-3">
            <div class="col-12">
                <!-- Start details card -->
                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-header bg-transparent border-0 py-3">
                        <h5 class="mb-0 fw-bold">Role Details</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-hover align-middle">
                            <tbody>
                                <tr>
                                    <th scope="row" width="30%">Role Name</th>
                                    <td>
                                        <span class="fw-bold">{{ $role->name }}</span>
                                        @if ($role->is_permanent)
                                            <span data-bs-toggle="tooltip" title="This role is permanent" class="ms-1 text-muted">
                                                <i class="bi bi-shield-lock"></i>
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">Created At</th>
                                    <td>{{ $role->created_at->format('d M, Y h:i A') }} ({{ $role->created_at->diffForHumans() }})</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Permissions card -->
                <div class="widget mb-3">
                    <div class="widget-head mb-3">
                        <h5>Permissions Details</h5>
                    </div>
                    <div class="widget-body">
                        @if(\Database\Seeders\RoleSeeder::ADMINISTRATOR_RULE_NAME == $role->name)
                            <div class="alert alert-info">
                                <strong>Administrator:</strong> This role has all permissions.
                            </div>
                        @else
                            <div class="row row-cols-1 row-cols-md-3 g-3">
                                @forelse($assigned_permission_area_groups as $group => $permission_areas)
                                    <div class="col">
                                        <div class="widget h-100">
                                            <div class="widget-head py-2">
                                                <strong>{{ ucwords(str_replace('_', ' ', $group)) }}</strong>
                                            </div>
                                            <div class="widget-body py-2">
                                                <ul class="list-group list-group-flush small">
                                                    @forelse($permission_areas as $permission_area)
                                                        <li class="list-group-item border-0 py-1">
                                                            <i class="bi bi-check-circle-fill text-success me-1"></i>
                                                            {{ $permission_area['key'] }}
                                                        </li>
                                                    @empty
                                                        <li class="list-group-item border-0 py-1 text-center text-muted">No permissions</li>
                                                    @endforelse
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12 text-center text-muted">
                                        <p class="mb-0">No permissions assigned.</p>
                                    </div>
                                @endforelse
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Assigned Users card -->
                <div class="widget mb-3">
                    <div class="widget-head mb-3">
                        <h5>Assigned Users</h5>
                    </div>
                    <div class="widget-body">
                        <div class="row">
                            @forelse($role->users->chunk(10) as $chunk)
                                <div class="col-md-3">
                                    <ol start="{{ $loop->index * count($chunk) + 1 }}" class="mb-0">
                                        @foreach ($chunk as $user)
                                            <li class="mb-1">
                                                <a href="{{ route('user.show', $user->id) }}"
                                                    target="_blank" class="fw-semibold text-decoration-none text-primary">{{ $user->name }}</a>
                                            </li>
                                        @endforeach
                                    </ol>
                                </div>
                            @empty
                                <div class="col-12 text-center text-muted">
                                    <p class="mb-0">No users assigned to this role yet.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
