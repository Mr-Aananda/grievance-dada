@section('title', 'Users')

<x-app-layout>
    <!-- Start header widget -->
    <div class="widget mb-3 border-top print-none">
        <div class="widget-body d-flex">
            <!-- Start menu -->
            @include('user.menu')
            <!-- End  menu -->
            <!-- Start right button -->
            <div class="ms-auto">
                <button type="button" class="btn icon lg rounded" title="Search" data-bs-toggle="collapse"
                    data-bs-target="#tableSearch" aria-controls="tableSearch" aria-expanded="true">
                    <i class="bi bi-search"></i>
                </button>
                <button type="button" class="btn icon lg rounded" title="Print" onclick="printable('print-widget')">
                    <i class="bi bi-printer"></i>
                </button>

                <button type="button" class="btn icon lg rounded" title="Reloar" onclick="location.reload()">
                    <i class="bi bi-bootstrap-reboot"></i>
                </button>
                <button type="button" class="btn icon lg rounded" title="Go back" onclick="history.back()">
                    <i class="bi bi-arrow-left"></i>
                </button>
            </div>
            <!-- End right button -->
        </div>

        <!-- Start Search body -->
        <div class="widget-body collapse {{ request()->search == '1' ? 'show' : '' }}" id="tableSearch">
            <form action="{{ route('user.index') }}">
                <div class="row py-3 g-3">

                    <input hidden name="search" value="1">
                    <div class="col-md-3">
                        <label for="account" class="form-label">User name</label>
                        <input type="text" class="form-control" value="{{ request()->name }}"
                            placeholder="Search users" id="account" list="search-user" name="name">

                    </div>

                    <div class="col-md-3">
                        <label for="phone" class="form-label"> Phone</label>
                        <input type="text" class="form-control" value="{{ request()->phone }}"
                            placeholder="Ex: 01xxx" name="phone" id="phone">
                    </div>

                    <div class="col-md-3">
                        <label for="Status" class="form-label"> Status</label>
                        <select class="form-select" name="status" id="Status">
                            <option selected disabled value>--Choose one--</option>
                            <option value="1" {{ request()->status == '1' ? 'selected' : '' }}>Active User</option>
                            <option value="0" {{ request()->status == '0' ? 'selected' : '' }}>Inactive User</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <button class="btn btn-success d-block w-100" type="submit"><i class="bi bi-search"></i>
                            Search
                        </button>
                    </div>
                    <div class="col-md-1">
                        <label class="form-label">&nbsp;</label>
                        <button class="btn btn-danger d-block w-100" type="reset">
                            <i class="bi bi-stars"></i>
                            Reset
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <!-- End Search body -->
    </div>
    <!-- End header widget -->


    <!-- Start body widget -->
    <div id="print-widget">
        <!-- Start print header -->
        <x-print.header />
        <!-- End print header -->

        <div class="widget">
            <div class="widget-head mb-3">
                <h5>All Users</h5>
                <p><small>Total Result found {{ count($users) }} </small></p>
            </div>
            <div class="widget-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 70px;">
                                    SL
                                </th>
                                <th>Name</th>
                                <th>Employee ID</th>
                                <th>Department</th>
                                <th>Section</th>
                                <th class="text-center">Status</th>
                                <th class="text-end print-none">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr>
                                    <th>
                                        {{ $users->firstItem() + $loop->index }}
                                    </th>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->emp_id  }}</td>
                                    <td>{{ $user->department?->name }}</td>
                                    <td>{{ $user->section?->name ?? 'N/A' }}</td>
                                    <td class="text-center">
                                        <span class="badge {{ $user->status == '1' ? 'bg-primary' : 'bg-danger' }}">
                                            {{ $user->status == '1' ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="text-end print-none">
                                        @can('user.show')
                                            <a href="{{ route('user.show', $user->id) }}" class="btn btn-info sm">
                                                <i class="bi bi-eye-fill"></i>
                                            </a>
                                        @endcan

                                        @can('user.edit')
                                            <a href="{{ route('user.edit', $user->id) }}" class="btn btn-success sm">
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
                                                    <button type="button" class="btn btn-danger sm"
                                                        onclick="if(confirm('Are you sure want to delete?')) { document.getElementById('sm-delete-{{ $user->id }}').submit() } return false">
                                                        <i class="bi bi-trash-fill"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <button class="btn btn-danger sm" disabled>
                                                    <i class="bi bi-trash-fill"></i>
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
    <!-- End Body widget -->
</x-app-layout>
