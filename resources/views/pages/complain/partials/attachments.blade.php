@php
    // Use files_data and videos_data from model
    $files = $complain->files_data ?? [];
    $videos = $complain->videos_data ?? [];

    // Count images and documents separately
    $images = array_filter($files, function($file) {
        return App\Helpers\FileHelper::isImage($file['mime_type'] ?? '');
    });
    $documents = array_filter($files, function($file) {
        return !App\Helpers\FileHelper::isImage($file['mime_type'] ?? '');
    });

    $imageCount = count($images);
    $documentCount = count($documents);
    $videoCount = count($videos);
    $totalAttachments = $imageCount + $documentCount + $videoCount;

    // Color array for complain types
    $complainTypeColors = [
        'bg-primary', 'bg-success', 'bg-info', 'bg-warning', 'bg-danger',
        'bg-secondary', 'bg-purple', 'bg-pink', 'bg-teal', 'bg-indigo',
        'bg-orange', 'bg-cyan', 'bg-gray', 'bg-dark'
    ];

    // Dynamic complain type color
    $complainTypeColor = $complain->complainType ?
        ($complainTypeColors[$complain->complainType->id % count($complainTypeColors)] ?? 'bg-primary') :
        'bg-primary';
@endphp

@if ($totalAttachments > 0)
    <div class="widget mb-4">
        <div class="widget-head border-bottom pb-2 mb-3">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold text-dark">
                    <i class="bi bi-paperclip me-2"></i>Attachments ({{ $totalAttachments }})
                </h6>
                @if ($totalAttachments > 0)
                    <a href="{{ route('complain.download.all', ['complain' => $complain->id]) }}"
                       class="btn btn-primary btn-sm" title="Download all attachments">
                        <i class="bi bi-download me-1"></i> Download All
                    </a>
                @endif
            </div>
        </div>

        <div class="widget-body">
            <!-- Tabs Navigation -->
            <ul class="nav nav-tabs mb-4" id="attachmentTabs" role="tablist">
                @if ($imageCount > 0)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="images-tab" data-bs-toggle="tab"
                            data-bs-target="#images-pane" type="button" role="tab">
                            <i class="bi bi-images me-1"></i> Images
                            <span class="badge {{ $complainTypeColor }} ms-1">{{ $imageCount }}</span>
                        </button>
                    </li>
                @endif

                @if ($documentCount > 0)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $imageCount == 0 ? 'active' : '' }}"
                            id="documents-tab" data-bs-toggle="tab"
                            data-bs-target="#documents-pane" type="button" role="tab">
                            <i class="bi bi-files me-1"></i> Documents
                            <span class="badge {{ $complainTypeColor }} ms-1">{{ $documentCount }}</span>
                        </button>
                    </li>
                @endif

                @if ($videoCount > 0)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $imageCount == 0 && $documentCount == 0 ? 'active' : '' }}"
                            id="videos-tab" data-bs-toggle="tab"
                            data-bs-target="#videos-pane" type="button" role="tab">
                            <i class="bi bi-camera-video me-1"></i> Videos
                            <span class="badge {{ $complainTypeColor }} ms-1">{{ $videoCount }}</span>
                        </button>
                    </li>
                @endif
            </ul>

            <!-- Tab Content -->
            <div class="tab-content" id="attachmentTabContent">
                <!-- Images Tab -->
                @if ($imageCount > 0)
                    <div class="tab-pane fade show active" id="images-pane" role="tabpanel">
                        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3">
                            @foreach($images as $index => $image)
                            <div class="col">
                                <div class="image-card position-relative">
                                    <!-- Clickable Image Thumbnail -->
                                    <div class="image-thumbnail rounded border overflow-hidden"
                                         style="height: 150px; cursor: pointer;"
                                         onclick="openImageLightbox({{ $complain->id }}, {{ json_encode(array_values($images)) }}, {{ $index }})"
                                         title="Click to view full size">
                                        <img src="{{ route('complain.image.stream', ['complain' => $complain->id, 'image' => $image['id']]) }}"
                                             class="img-fluid h-100 w-100 object-fit-cover"
                                             alt="{{ $image['file_name'] }}"
                                             loading="lazy"
                                             onerror="this.onerror=null; this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjUwIiBoZWlnaHQ9IjE1MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMjUwIiBoZWlnaHQ9IjE1MCIgZmlsbD0iI2YwZjBmMCIvPjx0ZXh0IHg9IjUwJSIgeT0iNTAlIiBmb250LWZhbWlseT0iQXJpYWwiIGZvbnQtc2l6ZT0iMTIiIGZpbGw9IiM5OTkiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIj5JbWFnZSBOb3QgRm91bmQ8L3RleHQ+PC9zdmc+'">

                                        <!-- Zoom icon overlay -->
                                        <div class="position-absolute top-50 start-50 translate-middle opacity-0 image-overlay">
                                            <div class="bg-dark bg-opacity-50 rounded-circle p-2">
                                                <i class="bi bi-zoom-in text-white fs-4"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Image Info -->
                                    <div class="mt-2">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="text-truncate me-2">
                                                <small class="text-muted d-block" title="{{ $image['file_name'] }}">
                                                    <i class="bi bi-file-earmark-image me-1"></i>
                                                    {{ Str::limit($image['file_name'], 20) }}
                                                </small>
                                                <small class="text-muted">
                                                    {{ $image['formatted_size'] ?? App\Helpers\FileHelper::formatFileSize($image['size'] ?? 0) }}
                                                </small>
                                            </div>
                                            <div class="btn-group btn-group-sm">
                                                {{-- View Image Button --}}
                                                <a href="{{ route('complain.image.stream', ['complain' => $complain->id, 'image' => $image['id']]) }}"
                                                   target="_blank"
                                                   class="btn btn-outline-info"
                                                   title="View Full Size">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                {{-- Download Image Button --}}
                                                <a href="{{ route('complain.file.download', ['complain' => $complain->id, 'file' => $image['id']]) }}"
                                                   class="btn btn-outline-secondary"
                                                   title="Download"
                                                   target="_blank">
                                                    <i class="bi bi-download"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Documents Tab -->
                @if ($documentCount > 0)
                    <div class="tab-pane fade {{ $imageCount == 0 ? 'show active' : '' }}"
                         id="documents-pane" role="tabpanel">
                        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
                            @foreach($documents as $document)
                            @php
                                $icon = App\Helpers\FileHelper::getFileIcon($document['mime_type'] ?? '', $document['original_name'] ?? $document['file_name']);
                                $color = App\Helpers\FileHelper::getFileColor($document['mime_type'] ?? '', $document['original_name'] ?? $document['file_name']);
                                $badge = App\Helpers\FileHelper::getFileBadge($document['original_name'] ?? $document['file_name']);
                                $fileName = $document['original_name'] ?? $document['file_name'];
                                $fileSize = $document['formatted_size'] ?? App\Helpers\FileHelper::formatFileSize($document['size'] ?? 0);
                            @endphp
                            <div class="col">
                                <div class="card border h-100 document-card">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <i class="bi bi-{{ $icon }} fs-1 {{ $color }}"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1 text-truncate" title="{{ $fileName }}">
                                                    {{ Str::limit($fileName, 25) }}
                                                </h6>
                                                <div class="text-muted small">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <span>
                                                            <i class="bi bi-hdd me-1"></i>
                                                            {{ $fileSize }}
                                                        </span>
                                                        <span class="badge {{ $complainTypeColor }}">
                                                            {{ $badge['text'] }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-white border-top-0">
                                        <div class="d-grid">
                                            <a href="{{ route('complain.file.download', ['complain' => $complain->id, 'file' => $document['id']]) }}"
                                               class="btn btn-outline-primary btn-sm"
                                               target="_blank"
                                               title="Download {{ $fileName }}">
                                                <i class="bi bi-download me-1"></i> Download
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Videos Tab -->
                @if ($videoCount > 0)
                    <div class="tab-pane fade {{ $imageCount == 0 && $documentCount == 0 ? 'show active' : '' }}"
                         id="videos-pane" role="tabpanel">
                        <div class="row row-cols-1 row-cols-md-2 g-3">
                            @foreach($videos as $video)
                            <div class="col">
                                <div class="card border h-100">
                                    <div class="card-body">
                                        <div class="ratio ratio-16x9 mb-3">
                                            <video controls class="rounded" style="background-color: #000;">
                                                <source src="{{ route('complain.videos.stream', ['complain' => $complain->id, 'video' => $video['id']]) }}"
                                                        type="{{ $video['mime_type'] ?? 'video/mp4' }}">
                                                Your browser does not support the video tag.
                                            </video>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1 text-truncate" title="{{ $video['original_name'] ?? $video['file_name'] }}">
                                                    <i class="bi bi-film me-1"></i>
                                                    {{ Str::limit($video['original_name'] ?? $video['file_name'], 30) }}
                                                </h6>
                                                <small class="text-muted">
                                                    <i class="bi bi-hdd me-1"></i>
                                                    {{ $video['formatted_size'] ?? App\Helpers\FileHelper::formatFileSize($video['size'] ?? 0) }}
                                                </small>
                                            </div>
                                            <div class="btn-group btn-group-sm">
                                                {{-- Open Video Button --}}
                                                <a href="{{ route('complain.videos.stream', ['complain' => $complain->id, 'video' => $video['id']]) }}"
                                                   target="_blank"
                                                   class="btn btn-outline-info"
                                                   title="Open Video">
                                                    <i class="bi bi-play-circle"></i>
                                                </a>
                                                {{-- Download Video Button --}}
                                                <a href="{{ route('complain.file.download', ['complain' => $complain->id, 'file' => $video['id']]) }}"
                                                   class="btn btn-outline-primary"
                                                   target="_blank"
                                                   title="Download video">
                                                    <i class="bi bi-download"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
    .image-card:hover .image-overlay {
        opacity: 1 !important;
        transition: opacity 0.3s ease;
    }
    .image-thumbnail {
        position: relative;
        background-color: #f8f9fa;
    }
    .image-thumbnail:hover {
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .document-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .document-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .object-fit-cover {
        object-fit: cover;
    }
    </style>
@endif
