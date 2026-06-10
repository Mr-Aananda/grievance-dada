@section('title', 'Roles')

<x-app-layout>
    <!-- Start main-bar  -->
            <!-- Start header widget -->
            <div class="widget mb-3">
                <div class="widget-body d-flex">
                    <!-- Start left menu -->
                    @include('role.menu')
                    <!-- End left menu -->
                    <!-- Start right buttons -->
                    <div class="ms-auto">
                        <button type="button" class="btn icon lg rounded" title="Print"
                            onclick="printable('print-widget')">
                            <i class="bi bi-printer"></i>
                        </button>
                        <button type="button" class="btn icon lg rounded" title="Reloar" onclick="location.reload()">
                            <i class="bi bi-bootstrap-reboot"></i>
                        </button>
                        <button type="button" class="btn icon lg rounded" title="Go back" onclick="history.back()">
                            <i class="bi bi-arrow-left"></i>
                        </button>
                    </div>
                    <!-- End right buttons -->

                </div>
                <!-- Start Filter Fill -->
                <div class="widget-body collapse" id="tableSearch">
                    <form action="#">
                        <div class="row py-3 gx-3">

                            <div class="col-md-5">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                                    <select class="form-select">
                                        <option selected="">Select user roles</option>
                                        <option value="1">All</option>
                                        <option value="2">Admin</option>
                                        <option value="3">Operator</option>
                                        <option value="4">Salesman</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-1">
                                <button class="btn btn-success d-block w-100" type="submit"> <i
                                        class="bi bi-search"></i>
                                    Search</button>
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
                        <h5>All roles</h5>
                        <p><small>{{ count($roles) }} results found </small></p>
                    </div>
                    <div class="widget-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">sl</th>
                                    <th scope="col">Name</th>
                                    <th scope="col" class="text-center">Total Assigned</th>
                                    <th scope="col" class="text-end print-none">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($roles as $role)
                                    <tr>
                                        <th scope="row">{{ $roles->firstItem() + $loop->index }}</th>
                                        <td>
                                            {{ $role->name }}
                                            @if ($role->is_permanent)
                                                <span data-bs-toggle="tooltip" title="This role is permanent">
                                                    <i class="bi bi-shield-lock"></i>
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">{{ $role->users_count }}</td>
                                        <td class="text-end print-none">
                                            @can('role.show')
                                                <a href="{{ route('role.show', $role->id) }}" class="btn sm btn-info">
                                                    <i class="bi bi-eye-fill"></i>
                                                </a>
                                            @endcan
                                            @can('role.edit')
                                                @unless(\Database\Seeders\RoleSeeder::ADMINISTRATOR_RULE_NAME == $role->name)
                                                    <a href="{{ route('role.edit', $role->id) }}" class="btn sm btn-success">
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
                                                        <button type="submit" {{ $role->users_count> 0 ? 'disabled' : '' }} class="btn sm btn-danger">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                @endunless
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">No roles found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- End body widget -->
            </div>
        <!-- Start pagination -->
            <x-pagination :items="$roles" />
        <!-- End pagination -->
    <!-- End main-bar ================================================ -->
</x-app-layout>
