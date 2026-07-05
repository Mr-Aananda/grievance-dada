@section('title', 'Roles')

<x-app-layout>
    <!-- Start header widget -->
    <div class="widget mb-3 border-top print-none">
        <div class="widget-body d-flex">
            <!-- Start menu -->
            @include('role.menu')
            <!-- End menu -->
            
            <!-- Start right buttons -->
            <div class="ms-auto">
                <button type="button" class="btn icon lg rounded" title="Print" onclick="printable('print-widget')">
                    <i class="bi bi-printer"></i>
                </button>
                <button type="button" class="btn icon lg rounded" title="Reload" onclick="location.reload()">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
                <button type="button" class="btn icon lg rounded" title="Go back" onclick="history.back()">
                    <i class="bi bi-arrow-left"></i>
                </button>
            </div>
            <!-- End right buttons -->
        </div>
    </div>
    <!-- End header widget -->

    <div id="print-widget">
        <!-- Start print header -->
        <x-print.header/>
        <!-- End print header -->

        <!-- Start table card -->
        <div class="widget">
            <div class="widget-head mb-3">
                <h5>All Roles</h5>
                <p><small>Total Result found {{ $roles->total() }} </small></p>
            </div>
            <div class="widget-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th style="width: 70px;" class="ps-3">SL</th>
                                <th>Name</th>
                                <th class="text-center">Total Assigned</th>
                                <th class="text-end pe-3 print-none">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($roles as $role)
                                <tr>
                                    <td class="ps-3">{{ $roles->firstItem() + $loop->index }}</td>
                                    <td>
                                        <span class="fw-bold">{{ $role->name }}</span>
                                        @if ($role->is_permanent)
                                            <span data-bs-toggle="tooltip" title="This role is permanent" class="ms-1 text-muted">
                                                <i class="bi bi-shield-lock"></i>
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $role->users_count }}</td>
                                    <td class="text-end pe-3 print-none">
                                        @can('role.show')
                                            <a href="{{ route('role.show', $role->id) }}"
                                               class="btn btn-info sm" title="View Details">
                                                <i class="bi bi-eye-fill"></i>
                                            </a>
                                        @endcan
                                        @can('role.edit')
                                            @unless(\Database\Seeders\RoleSeeder::ADMINISTRATOR_RULE_NAME == $role->name)
                                                <a href="{{ route('role.edit', $role->id) }}"
                                                   class="btn btn-success sm" title="Edit">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                            @endunless
                                        @endcan

                                        @can('role.destroy')
                                            @unless($role->is_permanent)
                                                <form action="{{ route('role.destroy', $role->id) }}" method="POST"
                                                      class="d-inline"
                                                      onsubmit="return confirm('Are you sure want to delete?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" {{ $role->users_count > 0 ? 'disabled' : '' }} title="Delete" class="btn btn-danger sm">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            @endunless
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">
                                        <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                        No roles found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- End table card -->
    </div>

    <!-- Start pagination -->
    <x-pagination :items="$roles" />
    <!-- End pagination -->
</x-app-layout>
