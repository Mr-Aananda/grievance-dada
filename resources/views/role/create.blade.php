@section('title', 'Role')

<x-app-layout>
    <!-- Start header widget -->
    <div class="widget mb-3">
        <div class="widget-body d-flex">
            <!-- Start left menu -->
        @include('role.menu')
        <!-- End left menu -->

            <!-- Start right buttons -->
            <div class="ms-auto">
                <button type="button" class="btn icon lg rounded" title="Reloar" onclick="location.reload()">
                    <i class="bi bi-bootstrap-reboot"></i>
                </button>
                <button type="button" class="btn icon lg rounded" title="Go back" onclick="history.back()">
                    <i class="bi bi-arrow-left"></i>
                </button>
            </div>
            <!-- End right buttons -->
        </div>
    </div>
    <!-- End header widget -->
    <!-- Start body widget -->
    <div class="widget">
        <div class="widget-body">
            <form action="{{ route('role.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label required">Role Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                           id="name" name="name" value="{{ old('name') }}" placeholder="Enter name">
                    @error('name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <h3 class="text-center mb-3">Permissions</h3>

                    <table class="table table-bordered  table-striped w-100">
                        <thead>
                        <tr>
                            <th>Permission Areas</th>
                            <th>Item</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($permission_area_groups as $group => $permission_areas)
                            <tr class="" x-data="parentChildrenCheckboxData">
                                <td class=" text-start">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox"
                                               id="permission-area-checkbox-parent-{{ $loop->index }}"
                                               x-model="allChecked" @change="onAllCheckedChange"
                                               x-ref="parentCheckbox">
                                        <label class="form-check-label"
                                               for="permission-area-checkbox-parent-{{ $loop->index }}">{{ ucwords($group) }}</label>
                                    </div>
                                </td>
                                <td class="" x-ref="checkboxItemContainer">

                                    @forelse($permission_areas as $permission_area)

                                        <span class=" p-3">
                                            <input class="form-check-input" type="checkbox"
                                                   value="{{ $permission_area['value'] }}"
                                                   id="{{ $permission_area['value'] }}"
                                                   name="permissions[]" @change="onCheckboxItemChange"
                                                   @checked(in_array($permission_area['value'], old('permissions', [])))>
                                            <label class="form-check-label"
                                                   for="{{ $permission_area['value'] }}">
                                                {{ $permission_area['key'] }}
                                            </label>
                                        </span>

                                    @empty
                                    @endforelse
                                </td>
                            </tr>
                        @empty
                        @endforelse
                        </tbody>
                    </table>
                </div>
                <button type="submit" class="btn btn-success">Create</button>
            </form>
        </div>
    </div>
    <!-- End body widget -->

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
