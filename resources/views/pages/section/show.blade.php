@section('title', 'Section Details')
<x-app-layout>
    <!-- Start header widget -->
    <div class="card mb-3">
        <div class="card-body py-2 d-flex align-items-center">
            <!-- Start menu -->
            @include('pages.section.menu')
            <!-- End menu -->
            
            <!-- Start right buttons -->
            <div class="ms-auto">
                <button type="button" class="btn btn-sm btn-outline-secondary me-1" title="Print"
                        onclick="printable('print-widget')">
                    <i class="bi bi-printer"></i> Print
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary" title="Go back" onclick="history.back()">
                    <i class="bi bi-arrow-left"></i> Back
                </button>
            </div>
            <!-- End right buttons -->
        </div>
    </div>
    <!-- End header widget -->

    <div class="row" id="print-widget">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-transparent border-0 pt-3 px-4">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-info-circle me-1"></i> Section Information</h6>
                </div>
                <div class="card-body px-4 pb-4">
                    <table class="table table-hover align-middle mb-0">
                        <tbody>
                            <tr>
                                <th scope="row" width="40%">Section Name</th>
                                <td>{{ $section->name }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Code</th>
                                <td><span class="badge bg-secondary-subtle text-secondary">{{ $section->code }}</span></td>
                            </tr>
                            <tr>
                                <th scope="row">Department</th>
                                <td>
                                    <span class="badge bg-info-subtle text-info">
                                        {{ $section->department->name ?? 'N/A' }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">Status</th>
                                <td>
                                    <span class="badge bg-{{ $section->status ? 'success-subtle text-success' : 'danger-subtle text-danger' }}">
                                        {{ $section->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">Note</th>
                                <td><small class="text-muted">{{ $section->note ?? '—' }}</small></td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="mt-3 print-none d-flex gap-2">
                        @can('section.edit')
                            <a href="{{ route('section.edit', $section->id) }}"
                               class="btn btn-sm btn-success text-white flex-grow-1">
                                <i class="bi bi-pencil-square me-1"></i> Edit
                            </a>
                        @endcan

                        @can('section.destroy')
                            <form action="{{ route('section.destroy', $section->id) }}" method="POST"
                                  class="flex-grow-1"
                                  onsubmit="return confirm('Are you sure want to delete this section?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        {{ ($section->users_count ?? 0) > 0 ? 'disabled' : '' }}
                                        class="btn btn-sm btn-danger text-white w-100">
                                    <i class="bi bi-trash me-1"></i> Delete
                                </button>
                            </form>
                        @endcan
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-transparent border-0 pt-3 px-4">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-people me-1"></i> Users in this Section ({{ $section->users_count ?? 0 }})</h6>
                </div>
                <div class="card-body p-0 table-responsive">
                    @if($section->users->count() > 0)
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" class="ps-3" style="width: 60px;">SL</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Employee ID</th>
                                    <th scope="col">Designation</th>
                                    <th scope="col">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($section->users as $user)
                                    <tr>
                                        <td class="ps-3">{{ $loop->iteration }}</td>
                                        <td><span class="fw-bold">{{ $user->name }}</span></td>
                                        <td><code>{{ $user->emp_id }}</code></td>
                                        <td>{{ $user->designation ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-{{ $user->status ? 'success-subtle text-success' : 'danger-subtle text-danger' }}">
                                                {{ $user->status ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-people fs-3 d-block mb-2"></i>
                            No users assigned to this section.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
