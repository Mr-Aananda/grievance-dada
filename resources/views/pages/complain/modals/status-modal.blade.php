<!-- resources/views/pages/complain/modals/status-modal.blade.php -->
<div class="modal fade" id="statusModal{{ $complain->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white py-3">
                <h5 class="modal-title mb-0">
                    <i class="bi bi-pencil-square me-2"></i>
                    Update Complaint Status
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('complain.update-status', $complain->id) }}" method="POST">
                @csrf

                <div class="modal-body py-3">

                    <!-- Complaint Basic Info -->
                    <div class="mb-3 pb-3 border-bottom">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h6 class="mb-1 fw-semibold">{{ $complain->name }}</h6>
                                <div class="d-flex align-items-center gap-2">
                                    <small class="text-muted">
                                        <i class="bi bi-tag me-1"></i>
                                        {{ $complain->complainType->name ?? 'N/A' }}
                                    </small>
                                    @if($complain->po)
                                        <small class="text-muted">| PO: {{ $complain->po }}</small>
                                    @endif
                                    @if($complain->ps)
                                        <small class="text-muted">| PS: {{ $complain->ps }}</small>
                                    @endif
                                </div>
                            </div>
                            <small class="text-muted fw-medium">
                                Current: {{ ucfirst($complain->status) }}
                            </small>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="bi bi-calendar me-1"></i>
                                {{ $complain->date ? \Carbon\Carbon::parse($complain->date)->format('M d, Y') : 'N/A' }}
                            </small>
                            @if($complain->quantity > 0 || $complain->amount > 0)
                                <small class="text-muted">
                                    @if($complain->quantity > 0)
                                        Qty: {{ number_format($complain->quantity) }}
                                    @endif
                                    @if($complain->amount > 0)
                                        | Amt: ${{ number_format($complain->amount, 2) }}
                                    @endif
                                </small>
                            @endif
                        </div>
                    </div>

                    <!-- Previous Note (if exists) -->
                    @if($complain->status_note)
                        <div class="mb-3">
                            <label class="form-label fw-medium mb-1">
                                <i class="bi bi-chat-left-text text-muted me-1"></i>
                                Previous Note
                            </label>
                            <div class="bg-light rounded p-2 border">
                                <p class="mb-0 small">{{ $complain->status_note }}</p>
                                @if($complain->updated_at)
                                    <small class="text-muted d-block mt-1">
                                        {{ $complain->updated_at->format('M d, Y') }}
                                        @if($complain->updatedBy)
                                            by {{ $complain->updatedBy->name }}
                                        @endif
                                    </small>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Status Selection -->
                    <div class="mb-3">
                        <label class="form-label fw-medium mb-1">
                            <i class="bi bi-flag me-1"></i>
                            Select New Status <span class="text-danger">*</span>
                        </label>
                        <select name="status" class="form-select" required>
                            <option value="">-- Choose Status --</option>
                            <option value="pending" {{ $complain->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="in_progress" {{ $complain->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="resolved" {{ $complain->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                            <option value="closed" {{ $complain->status == 'closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                    </div>

                    <!-- New Note -->
                    <div class="mb-3">
                        <label class="form-label fw-medium mb-1">
                            <i class="bi bi-pencil me-1"></i>
                            Update Note <span class="text-danger">*</span>
                        </label>
                        <textarea name="note" class="form-control" rows="3"
                                  placeholder="Explain the reason for status change..."
                                  required>{{ old('note') }}</textarea>
                        <small class="text-muted d-block mt-1">
                            <i class="bi bi-info-circle me-1"></i>
                            This note will be saved with the status update
                        </small>
                    </div>

                </div>

                <div class="modal-footer py-2 border-top">
                    <button type="button" class="btn btn-light px-3" data-bs-dismiss="modal">
                        <i class="bi bi-x me-1"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary px-3">
                        <i class="bi bi-check-circle me-1"></i> Update Status
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
