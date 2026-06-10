<!-- resources/views/pages/complain/modals/videos.blade.php -->
@if(count($complain->videos_data) > 0)
<div class="modal fade" id="videosModal{{ $complain->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title">
                    <i class="bi bi-camera-video me-2"></i>
                    Complaint Videos ({{ count($complain->videos_data) }})
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="row g-3">
                    @foreach($complain->videos_data as $video)
                    <div class="col-md-6">
                        <div class="card border h-100">
                            <div class="card-header bg-light py-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 text-truncate" style="max-width: 70%;" title="{{ $video['original_name'] ?? $video['file_name'] }}">
                                        <i class="bi bi-file-play text-danger me-2"></i>
                                        {{ Str::limit($video['original_name'] ?? $video['file_name'], 25) }}
                                    </h6>
                                    <span class="badge bg-secondary">
                                        {{ $video['formatted_size'] ?? 'N/A' }}
                                    </span>
                                </div>
                            </div>

                            <div class="card-body p-0">
                                <video class="w-100 bg-dark" controls style="height: 200px; max-height: 40vh;">
                                    <source src="{{ route('complain.videos.stream', ['complain' => $complain->id, 'video' => $video['id']]) }}"
                                            type="{{ $video['mime_type'] ?: 'video/mp4' }}">
                                    Your browser does not support video playback.
                                </video>
                            </div>

                            <div class="card-footer bg-white">
                                <div class="d-grid gap-2">
                                    {{-- Download using file.download route --}}
                                    <a href="{{ route('complain.file.download', ['complain' => $complain->id, 'file' => $video['id']]) }}"
                                       class="btn btn-primary btn-sm"
                                       target="_blank">
                                        <i class="bi bi-download me-1"></i> Download Video
                                    </a>

                                    {{-- Optional: Open in new tab button --}}
                                    <a href="{{ route('complain.videos.stream', ['complain' => $complain->id, 'video' => $video['id']]) }}"
                                       class="btn btn-outline-secondary btn-sm"
                                       target="_blank"
                                       title="Open in new tab">
                                        <i class="bi bi-box-arrow-up-right me-1"></i> Open Video
                                    </a>
                                </div>
                                <div class="text-muted small mt-2 text-center">
                                    @if(isset($video['created_at']))
                                        Uploaded: {{ \Carbon\Carbon::parse($video['created_at'])->format('M d, Y h:i A') }}
                                    @else
                                        Uploaded: N/A
                                    @endif
                                </div>

                                {{-- Show file info --}}
                                <div class="text-muted small mt-1 text-center">
                                    <span class="badge bg-light text-dark">
                                        {{ $video['mime_type'] ?? 'Video file' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="modal-footer d-flex justify-content-between align-items-center">
                <div>
                    {{-- Download All Videos Button (if you have a route for downloading all videos) --}}
                    @if(count($complain->videos_data) > 1)
                    <a href="{{ route('complain.download.all', ['complain' => $complain->id]) }}"
                       class="btn btn-success btn-sm"
                       target="_blank">
                        <i class="bi bi-cloud-download me-1"></i>
                        Download All Attachments
                    </a>
                    @endif
                </div>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>
@endif

{{-- Optional: Add this JavaScript for better video handling --}}
@push('scripts')
<script>
// Handle video playback issues
document.addEventListener('DOMContentLoaded', function() {
    // When modal is hidden, pause all videos
    const videoModals = document.querySelectorAll('[id^="videosModal"]');
    videoModals.forEach(modal => {
        modal.addEventListener('hidden.bs.modal', function () {
            const videos = this.querySelectorAll('video');
            videos.forEach(video => {
                video.pause();
            });
        });
    });
});
</script>
@endpush
