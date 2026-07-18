@section('title', __('Process') . ' ' . __('Grievance') . ' #' . $grievance->ticket_number)

@push('style')
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />
    <style>
        .ql-container {
            font-family: inherit;
        }
        .ql-editor {
            padding: 0;
            height: auto;
            min-height: unset;
        }
    </style>
@endpush

<x-app-layout>
    <!-- Start header widget -->
    <div class="card mb-3 shadow-sm">
        <div class="card-body py-2 d-flex align-items-center">
            <h5 class="mb-0 fw-bold">
                <i class="bi bi-gear-wide-connected text-primary me-2"></i> {{ __('Process Ticket') }}
            </h5>
            <div class="ms-auto">
                <a href="{{ route('admin.grievance.edit', $grievance->id) }}" class="btn btn-sm btn-outline-primary me-2" title="{{ __('Edit') }}">
                    <i class="bi bi-pencil-square"></i> {{ __('Edit Ticket') }}
                </a>
                <a href="{{ route('admin.grievance.index') }}" class="btn btn-sm btn-outline-secondary" title="{{ __('Go back') }}">
                    <i class="bi bi-arrow-left"></i> {{ __('Back to List') }}
                </a>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <!-- Left: Ticket Details and Attachments -->
        <div class="col-lg-8">
            <!-- Ticket Info Card -->
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-transparent border-bottom py-3">
                    <h6 class="mb-0 fw-bold">
                        <i class="bi bi-info-circle me-1 text-primary"></i> {{ __('Grievance Information') }}
                    </h6>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped table-hover mb-0">
                        <tbody>
                            <tr>
                                <th style="width: 30%;" class="ps-3 text-muted small">{{ __('Ticket Number') }}</th>
                                <td class="fw-bold">{{ $grievance->ticket_number }}</td>
                            </tr>
                            <tr>
                                <th class="ps-3 text-muted small">{{ __('Category') }}</th>
                                <td>
                                    <span class="badge bg-secondary-subtle text-secondary">{{ $grievance->category->name ?? '—' }}</span>
                                </td>
                            </tr>
                            <tr>
                                <th class="ps-3 text-muted small">{{ __('Department') }}</th>
                                <td>{{ $grievance->department->name ?? '—' }}</td>
                            </tr>
                            <tr>
                                <th class="ps-3 text-muted small">{{ __('Employee ID') }}</th>
                                <td><code>{{ $grievance->employee_id ?? __('Anonymous') }}</code></td>
                            </tr>
                            <tr>
                                <th class="ps-3 text-muted small">{{ __('Submission Date') }}</th>
                                <td>{{ $grievance->created_at->format('d M Y, h:i A') }} ({{ $grievance->created_at->diffForHumans() }})</td>
                            </tr>
                            <tr>
                                <th class="ps-3 text-muted small">{{ __('Submitted By') }}</th>
                                <td>
                                    @if($grievance->user)
                                        <span class="fw-semibold">{{ $grievance->user->name }}</span>
                                    @else
                                        <span class="text-muted italic">{{ __('Anonymous') }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="ps-3 text-muted small">{{ __('Current Status') }}</th>
                                <td>
                                    <span class="badge bg-{{ $grievance->status_badge }}">
                                        {{ $grievance->status_label }}
                                    </span>
                                </td>
                            </tr>
                            @if($grievance->statusChangedBy)
                                <tr>
                                    <th class="ps-3 text-muted small">{{ __('Status Changed By') }}</th>
                                    <td>
                                        <span class="fw-semibold text-dark">{{ $grievance->statusChangedBy->name }}</span>
                                    </td>
                                </tr>
                            @endif
                            @if($grievance->updatedBy)
                                <tr>
                                    <th class="ps-3 text-muted small">{{ __('Last Updated By') }}</th>
                                    <td>
                                        <span class="fw-semibold text-dark">{{ $grievance->updatedBy->name }}</span>
                                    </td>
                                </tr>
                            @endif
                            @if($grievance->resolved_at)
                                <tr>
                                    <th class="ps-3 text-muted small">{{ __('Resolved At') }}</th>
                                    <td>
                                        <span class="text-success fw-bold">{{ $grievance->resolved_at->format('d M Y, h:i A') }}</span>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Description Card -->
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-transparent border-bottom py-3">
                    <h6 class="mb-0 fw-bold">
                        <i class="bi bi-card-text me-1 text-primary"></i> {{ __('Issue Description') }}
                    </h6>
                </div>
                <div class="card-body">
                    <div class="p-3 bg-body-secondary rounded fs-6 ql-editor" style="line-height: 1.6;">{!! $grievance->description !!}</div>
                </div>
            </div>

            <!-- Attachments Card -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-transparent border-bottom py-3">
                    <h6 class="mb-0 fw-bold">
                        <i class="bi bi-paperclip me-1 text-primary"></i> {{ __('Attached Files') }}
                    </h6>
                </div>
                <div class="card-body">
                    @php
                        $images = $grievance->getMedia('grievance_images');
                        $docs = $grievance->getMedia('grievance_documents');
                        $videos = $grievance->getMedia('grievance_videos');
                    @endphp

                    @if($images->isEmpty() && $docs->isEmpty() && $videos->isEmpty())
                        <div class="text-center py-3 text-muted">
                            <i class="bi bi-folder-symlink fs-4 d-block mb-1"></i>
                            {{ __('No files attached to this grievance.') }}
                        </div>
                    @else
                        <div class="row g-3">
                            <!-- Image Attachments -->
                            @if($images->isNotEmpty())
                                <div class="col-12">
                                    <h6 class="fw-bold border-bottom pb-1 mb-2"><i class="bi bi-image me-1"></i> {{ __('Images') }}</h6>
                                    <div class="row g-2">
                                        @foreach($images as $media)
                                            <div class="col-md-3 col-6">
                                                <div class="card border h-100 shadow-none">
                                                    <a href="{{ route('grievance.media.stream', $media->id) }}" target="_blank">
                                                        <img src="{{ route('grievance.media.stream', $media->id) }}" class="card-img-top" style="height: 100px; object-fit: cover;" alt="{{ $media->name }}">
                                                    </a>
                                                    <div class="card-body p-2 text-center">
                                                        <span class="d-block text-truncate small" title="{{ $media->file_name }}">{{ $media->file_name }}</span>
                                                        <a href="{{ route('grievance.media.stream', $media->id) }}" target="_blank" class="btn btn-xs btn-outline-primary mt-1">
                                                            <i class="bi bi-eye"></i> {{ __('View') }}
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Video Attachments -->
                            @if($videos->isNotEmpty())
                                <div class="col-12 mt-3">
                                    <h6 class="fw-bold border-bottom pb-1 mb-2"><i class="bi bi-play-btn me-1"></i> {{ __('Videos') }}</h6>
                                    <div class="row g-2">
                                        @foreach($videos as $media)
                                            <div class="col-md-4 col-12">
                                                <div class="card border shadow-none">
                                                    <video controls class="card-img-top" style="height: 140px; background: #000;">
                                                        <source src="{{ route('grievance.media.stream', $media->id) }}" type="{{ $media->mime_type }}">
                                                    </video>
                                                    <div class="card-body p-2 text-center">
                                                        <span class="d-block text-truncate small" title="{{ $media->file_name }}">{{ $media->file_name }}</span>
                                                        <a href="{{ route('grievance.media.stream', $media->id) }}" target="_blank" class="btn btn-xs btn-outline-primary mt-1">
                                                            <i class="bi bi-download"></i> {{ __('Download') }}
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Document Attachments -->
                            @if($docs->isNotEmpty())
                                <div class="col-12 mt-3">
                                    <h6 class="fw-bold border-bottom pb-1 mb-2"><i class="bi bi-file-earmark-text me-1"></i> {{ __('Documents') }}</h6>
                                    <div class="list-group">
                                        @foreach($docs as $media)
                                            <a href="{{ route('grievance.media.stream', $media->id) }}" target="_blank" class="list-group-item list-group-item-action d-flex align-items-center py-2 px-3">
                                                <i class="bi bi-file-earmark-pdf-fill text-danger fs-5 me-3"></i>
                                                <div class="flex-grow-1 min-w-0">
                                                    <div class="text-truncate small fw-semibold">{{ $media->file_name }}</div>
                                                    <small class="text-muted">{{ round($media->size / 1024 / 1024, 2) }} MB</small>
                                                </div>
                                                <span class="btn btn-xs btn-outline-primary"><i class="bi bi-download"></i></span>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right: Process and Action -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 card-outline card-primary">
                <div class="card-header bg-transparent border-bottom py-3">
                    <h6 class="mb-0 fw-bold">
                        <i class="bi bi-pencil-square me-1 text-primary"></i> {{ __('Process Action') }}
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.grievance.update-status', $grievance->id) }}" method="POST">
                        @csrf

                        <!-- Status Selection -->
                        <div class="mb-3">
                            <label for="status" class="form-label small fw-semibold">{{ __('Status') }} <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="submitted" {{ $grievance->status === 'submitted' ? 'selected' : '' }}>{{ __('Submitted') }}</option>
                                <option value="under_review" {{ $grievance->status === 'under_review' ? 'selected' : '' }}>{{ __('Under Review') }}</option>
                                <option value="in_resolution" {{ $grievance->status === 'in_resolution' ? 'selected' : '' }}>{{ __('In Resolution') }}</option>
                                <option value="resolved" {{ $grievance->status === 'resolved' ? 'selected' : '' }}>{{ __('Resolved') }}</option>
                            </select>
                        </div>

                        <!-- Admin Remarks -->
                        <div class="mb-3">
                            <label for="admin_remarks" class="form-label small fw-semibold">{{ __('Admin Remarks / Resolution Details') }}</label>
                            <textarea name="admin_remarks" id="admin_remarks" class="form-control" rows="8" placeholder="{{ __('Type resolution details or comments here...') }}" maxlength="5000">{{ old('admin_remarks', $grievance->admin_remarks) }}</textarea>
                            <div class="form-text text-muted small">{{ __('These remarks are visible to the ticket submitter for status tracking.') }}</div>
                        </div>

                        <!-- Action Button -->
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary py-2 fw-semibold">
                                <i class="bi bi-save me-1"></i> {{ __('Update Ticket Status') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
