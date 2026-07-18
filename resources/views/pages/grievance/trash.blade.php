@section('title', __('Grievance Trash'))

<x-app-layout>
    <!-- Start menu -->
    @include('pages.grievance.menu')
    <!-- End menu -->

    <!-- Start Filter Fill -->
    <div class="card collapse {{ request()->search == '1' ? 'show' : '' }} bg-body-secondary border-0 mb-3 shadow-sm" id="tableSearch" style="border-radius: 12px;">
        <div class="card-body py-3">
            <form action="{{ route('admin.grievance.trash') }}" method="get">
                <input hidden type="text" name="search" value="1">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="search_input" class="form-label small fw-semibold">{{ __('Search keyword') }}</label>
                        <input class="form-control form-control-sm" name="search" id="search_input"
                               placeholder="{{ __('Ticket, description, Employee ID...') }}" value="{{ request()->search }}">
                    </div>
                    <div class="col-md-3">
                        <label for="status" class="form-label small fw-semibold">{{ __('Status') }}</label>
                        <select name="status" id="status" class="form-select form-select-sm">
                            <option value="">{{ __('All Statuses') }}</option>
                            <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>{{ __('Submitted') }}</option>
                            <option value="under_review" {{ request('status') === 'under_review' ? 'selected' : '' }}>{{ __('Under Review') }}</option>
                            <option value="in_resolution" {{ request('status') === 'in_resolution' ? 'selected' : '' }}>{{ __('In Resolution') }}</option>
                            <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>{{ __('Resolved') }}</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="category_id" class="form-label small fw-semibold">{{ __('Category') }}</label>
                        <select name="category_id" id="category_id" class="form-select form-select-sm">
                            <option value="">{{ __('All Categories') }}</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="department_id" class="form-label small fw-semibold">{{ __('Department') }}</label>
                        <select name="department_id" id="department_id" class="form-select form-select-sm">
                            <option value="">{{ __('All Departments') }}</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                                    {{ $dept->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 text-end">
                        <a href="{{ route('admin.grievance.trash') }}" class="btn btn-sm btn-secondary me-1">{{ __('Reset') }}</a>
                        <button class="btn btn-sm btn-primary" type="submit">
                            <i class="bi bi-search me-1"></i> {{ __('Search') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- End header widget -->

    <!-- Form for Bulk actions -->
    <form id="bulkForm" method="POST" action="">
        @csrf
        
        <div class="card shadow-sm border-0">
            <div class="card-header bg-transparent border-0 d-flex align-items-center">
                <h6 class="mb-0 fw-bold">{{ __('Trash List') }}</h6>
                <span class="badge bg-secondary ms-2 me-3">{{ $grievances->total() }} {{ __('results found') }}</span>
                
                <!-- Bulk buttons container -->
                <div class="ms-auto d-none gap-2" id="bulkActions">
                    <button type="submit" onclick="submitBulk('restore')" class="btn btn-sm btn-success">
                        <i class="bi bi-arrow-clockwise"></i> {{ __('Bulk Restore') }}
                    </button>
                    <button type="submit" onclick="submitBulk('force-delete')" class="btn btn-sm btn-danger">
                        <i class="bi bi-trash-fill"></i> {{ __('Bulk Permanent Delete') }}
                    </button>
                </div>
            </div>
            
            <div class="card-body p-0 table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" style="width: 40px;" class="ps-3">
                                <input type="checkbox" id="selectAll" class="form-check-input">
                            </th>
                            <th scope="col" style="width: 50px;">{{ __('SL') }}</th>
                            <th scope="col">{{ __('Ticket Number') }}</th>
                            <th scope="col">{{ __('Category') }}</th>
                            <th scope="col">{{ __('Department') }}</th>
                            <th scope="col">{{ __('Status') }}</th>
                            <th scope="col">{{ __('Deleted By') }}</th>
                            <th scope="col">{{ __('Deleted At') }}</th>
                            <th scope="col" class="text-end pe-3" style="width: 180px;">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($grievances as $grievance)
                            <tr>
                                <td class="ps-3">
                                    <input type="checkbox" name="ids[]" value="{{ $grievance->id }}" class="form-check-input select-item">
                                </td>
                                <td>{{ $grievances->firstItem() + $loop->index }}</td>
                                <td>
                                    <span class="fw-bold">{{ $grievance->ticket_number }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary-subtle text-secondary">{{ $grievance->category->name ?? '—' }}</span>
                                </td>
                                <td>{{ $grievance->department->name ?? '—' }}</td>
                                <td>
                                    <span class="badge bg-{{ $grievance->status_badge }}">
                                        {{ $grievance->status_label }}
                                    </span>
                                </td>
                                <td>
                                    <span class="fw-semibold text-dark">{{ $grievance->deletedBy->name ?? __('Unknown') }}</span>
                                </td>
                                <td>
                                    <small class="text-muted">{{ $grievance->deleted_at->format('d M Y, h:i A') }}</small>
                                </td>
                                <td class="text-end pe-3">
                                    <a href="{{ route('admin.grievance.restore', $grievance->id) }}"
                                       class="btn btn-sm btn-warning text-dark" title="{{ __('Restore') }}">
                                        <i class="bi bi-arrow-clockwise me-1"></i>{{ __('Restore') }}
                                    </a>
                                    
                                    <form action="{{ route('admin.grievance.permanentDelete', $grievance->id) }}" 
                                          method="POST" class="d-inline ms-1"
                                          onsubmit="return confirm('{{ __('Are you sure you want to PERMANENTLY delete this ticket? This action cannot be undone.') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger text-white" title="{{ __('Delete Permanently') }}">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4 text-muted">
                                    <i class="bi bi-trash3 fs-3 d-block mb-2 text-danger"></i>
                                    {{ __('Trash is empty.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($grievances->hasPages())
                <div class="card-footer bg-transparent border-0 d-flex justify-content-end">
                    {{ $grievances->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </form>

    @push('script')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const selectAll = document.getElementById('selectAll');
                const selectItems = document.querySelectorAll('.select-item');
                const bulkActions = document.getElementById('bulkActions');
                const bulkForm = document.getElementById('bulkForm');

                if (selectAll) {
                    selectAll.addEventListener('change', function () {
                        selectItems.forEach(item => {
                            item.checked = selectAll.checked;
                        });
                        toggleBulkActions();
                    });
                }

                selectItems.forEach(item => {
                    item.addEventListener('change', function () {
                        if (selectAll && !this.checked) {
                            selectAll.checked = false;
                        }
                        toggleBulkActions();
                    });
                });

                function toggleBulkActions() {
                    const checkedCount = document.querySelectorAll('.select-item:checked').length;
                    if (checkedCount > 0) {
                        bulkActions.classList.remove('d-none');
                        bulkActions.classList.add('d-flex');
                    } else {
                        bulkActions.classList.remove('d-flex');
                        bulkActions.classList.add('d-none');
                    }
                }
            });

            function submitBulk(action) {
                event.preventDefault();
                const bulkForm = document.getElementById('bulkForm');
                const checkedCount = document.querySelectorAll('.select-item:checked').length;
                
                if (checkedCount === 0) {
                    alert("{{ __('Please select at least one ticket.') }}");
                    return;
                }

                let message = "";
                if (action === 'restore') {
                    message = "{{ __('Are you sure you want to restore the selected tickets?') }}";
                    bulkForm.action = "{{ route('admin.grievance.bulk-restore') }}";
                } else if (action === 'force-delete') {
                    message = "{{ __('Are you sure you want to PERMANENTLY delete the selected tickets? This action cannot be undone.') }}";
                    bulkForm.action = "{{ route('admin.grievance.bulk-permanent-delete') }}";
                }

                if (confirm(message)) {
                    bulkForm.submit();
                }
            }
        </script>
    @endpush
</x-app-layout>
