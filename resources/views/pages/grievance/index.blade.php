@section('title', __('Grievances & Tickets'))

<x-app-layout>
    <!-- Start header widget -->
    <div class="card mb-3 shadow-sm">
        <div class="card-body py-2 d-flex align-items-center">
            <h5 class="mb-0 fw-bold text-primary">
                <i class="bi bi-ticket-perforated me-2"></i> {{ __('Track Grievances') }}
            </h5>
            <!-- Start right buttons -->
            <div class="ms-auto">
                <button type="button" class="btn btn-sm btn-outline-secondary collapsed" title="{{ __('Search Filters') }}"
                        data-bs-toggle="collapse" data-bs-target="#tableSearch" aria-controls="tableSearch"
                        aria-expanded="false">
                    <i class="bi bi-funnel-fill"></i> {{ __('Filter') }}
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary" title="{{ __('Reload') }}" onclick="location.reload()">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary" title="{{ __('Go back') }}" onclick="history.back()">
                    <i class="bi bi-arrow-left"></i>
                </button>
            </div>
            <!-- End right buttons -->
        </div>

        <!-- Start Filter Fill -->
        <div class="card-footer collapse {{ request()->search == '1' ? 'show' : '' }} bg-light" id="tableSearch">
            <form action="{{ route('admin.grievance.index') }}" method="get">
                <input hidden type="text" name="search" value="1">
                <div class="row g-3 py-2">
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
                        <a href="{{ route('admin.grievance.index') }}" class="btn btn-sm btn-secondary me-1">{{ __('Reset') }}</a>
                        <button class="btn btn-sm btn-primary" type="submit">
                            <i class="bi bi-search me-1"></i> {{ __('Search') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <!-- End Filter Fill -->
    </div>
    <!-- End header widget -->

    <div class="card shadow-sm border-0">
        <div class="card-header bg-transparent border-0 d-flex align-items-center">
            <h6 class="mb-0 fw-bold">{{ __('All Submissions') }}</h6>
            <span class="badge bg-secondary ms-2">{{ $grievances->total() }} {{ __('results found') }}</span>
        </div>
        <div class="card-body p-0 table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th scope="col" style="width: 60px;" class="ps-3">{{ __('SL') }}</th>
                        <th scope="col">{{ __('Ticket Number') }}</th>
                        <th scope="col">{{ __('Category') }}</th>
                        <th scope="col">{{ __('Department') }}</th>
                        <th scope="col">{{ __('Employee ID') }}</th>
                        <th scope="col">{{ __('Status') }}</th>
                        <th scope="col">{{ __('Submitted At') }}</th>
                        <th scope="col" class="text-end pe-3">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($grievances as $grievance)
                        <tr>
                            <td class="ps-3">{{ $grievances->firstItem() + $loop->index }}</td>
                            <td>
                                <span class="fw-bold">{{ $grievance->ticket_number }}</span>
                            </td>
                            <td>
                                <span class="badge bg-secondary-subtle text-secondary">{{ $grievance->category->name ?? '—' }}</span>
                            </td>
                            <td>{{ $grievance->department->name ?? '—' }}</td>
                            <td>
                                <code>{{ $grievance->employee_id ?? __('Anonymous') }}</code>
                            </td>
                            <td>
                                <span class="badge bg-{{ $grievance->status_badge }}">
                                    {{ $grievance->status_label }}
                                </span>
                            </td>
                            <td>
                                <small class="text-muted">{{ $grievance->created_at->format('d M Y, h:i A') }}</small>
                            </td>
                            <td class="text-end pe-3">
                                <a href="{{ route('admin.grievance.show', $grievance->id) }}"
                                   class="btn btn-sm btn-info text-white" title="{{ __('Process') }}">
                                    <i class="bi bi-eye-fill me-1"></i> {{ __('Process') }}
                                </a>
                                
                                <form action="{{ route('admin.grievance.destroy', $grievance->id) }}" 
                                      method="POST" class="d-inline ms-1"
                                      onsubmit="return confirm('{{ __('Are you sure you want to delete this ticket?') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="{{ __('Delete') }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                {{ __('No grievance records found.') }}
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
</x-app-layout>
