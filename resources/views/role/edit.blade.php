@section('title', 'Edit Role')

<x-app-layout>
    <!-- Start header widget -->
    <div class="card mb-3">
        <div class="card-body py-2 d-flex align-items-center">
            <!-- Start left menu -->
            @include('role.menu')
            <!-- End left menu -->
            <!-- Start right buttons -->
            <div class="ms-auto">
                <button type="button" class="btn btn-sm btn-outline-secondary" title="Go back" onclick="history.back()">
                    <i class="bi bi-arrow-left"></i>
                </button>
            </div>
            <!-- End right buttons -->
        </div>
    </div>
    <!-- End header widget -->

    <!-- Start body card -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-transparent border-0 py-3">
            <h5 class="mb-0 fw-bold">Update Role</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('role.update', $role->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="name" class="form-label">Role Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                           id="name" name="name" value="{{ old('name', $role->name) }}"
                           placeholder="Enter role name" @if ($role->is_permanent) readonly @endif
                           @disabled(\Database\Seeders\RoleSeeder::ADMINISTRATOR_RULE_NAME == $role->name) required>
                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <h5 class="fw-bold mb-3"><i class="bi bi-key me-1"></i> Permissions</h5>

                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th scope="col" style="width: 250px;" class="ps-3">Permission Areas</th>
                                <th scope="col">Permissions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($permission_area_groups as $group => $permission_areas)
                                <tr x-data="parentChildrenCheckboxData">
                                    <td class="ps-3 fw-semibold text-secondary">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox"
                                                   id="permission-area-checkbox-parent-{{ $loop->index }}"
                                                   x-model="allChecked" @change="onAllCheckedChange"
                                                   x-ref="parentCheckbox"
                                                   @disabled(\Database\Seeders\RoleSeeder::ADMINISTRATOR_RULE_NAME == $role->name)>
                                            <label class="form-check-label"
                                                   for="permission-area-checkbox-parent-{{ $loop->index }}">
                                                {{ ucwords(str_replace('_', ' ', $group)) }}
                                            </label>
                                        </div>
                                    </td>
                                    <td x-ref="checkboxItemContainer">
                                        <div class="d-flex flex-wrap gap-2 py-1">
                                            @forelse($permission_areas as $permission_area)
                                                <div class="form-check form-check-inline m-0 me-3">
                                                    <input class="form-check-input" type="checkbox"
                                                           value="{{ $permission_area['value'] }}"
                                                           id="{{ $permission_area['value'] }}"
                                                           name="permissions[]"
                                                           @change="onCheckboxItemChange"
                                                           @disabled(\Database\Seeders\RoleSeeder::ADMINISTRATOR_RULE_NAME == $role->name)
                                                           @checked(in_array($permission_area['value'], old('permissions', $assigned_permissions)))>
                                                    <label class="form-check-label small"
                                                           for="{{ $permission_area['value'] }}">
                                                        {{ $permission_area['key'] }}
                                                    </label>
                                                </div>
                                            @empty
                                                <span class="text-muted small">—</span>
                                            @endforelse
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center text-muted py-3">No permissions available</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex gap-2">
                    <button class="btn btn-success sm px-4" type="submit" 
                        @disabled(\Database\Seeders\RoleSeeder::ADMINISTRATOR_RULE_NAME == $role->name)>
                        <i class="bi bi-check-circle me-1"></i> Update
                    </button>
                    <a href="{{ route('role.index') }}" class="btn btn-secondary sm px-4">
                        <i class="bi bi-x-circle me-1"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
    <!-- End body card -->

    @push('script')
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('parentChildrenCheckboxData', () => ({
                    allChecked: false,
                    onCheckboxItemChange() {
                        let checked = false;
                        let unchecked = false;
                        const checkboxes = this.$refs.checkboxItemContainer.querySelectorAll(
                            'input[type=checkbox]');
                        for (let i = 0; i < checkboxes.length; i++) {
                            const checkbox = checkboxes[i]
                            if (checkbox.checked) {
                                checked = true
                            } else {
                                unchecked = true
                            }

                            if (checked && unchecked) {
                                break
                            }
                        }

                        this.$refs.parentCheckbox.indeterminate = checked && unchecked;
                        this.allChecked = checked && !unchecked
                    },
                    onAllCheckedChange(event) {
                        const checkboxes = this.$refs.checkboxItemContainer.querySelectorAll(
                            'input[type=checkbox]');
                        checkboxes.forEach(checkbox => {
                            checkbox.checked = event.target.checked
                        })
                    },
                    init() {
                        this.onCheckboxItemChange()
                    }
                }))
            })
        </script>
    @endpush
</x-app-layout>
