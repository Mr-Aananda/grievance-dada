@if (count($complain->files_data) > 0)
    <div class="modal fade" id="filesModal{{ $complain->id }}" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title">
                        <i class="bi bi-files me-2"></i>
                        Complaint Files & Images ({{ count($complain->files_data) }})
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    {{-- Tabs --}}
                    <ul class="nav nav-tabs mb-3" id="filesTab{{ $complain->id }}" role="tablist">
                        @php
                            $images = array_filter(
                                $complain->files_data,
                                fn($file) => App\Helpers\FileHelper::isImage($file['mime_type'] ?? ''),
                            );

                            $documents = array_filter(
                                $complain->files_data,
                                fn($file) => !App\Helpers\FileHelper::isImage($file['mime_type'] ?? ''),
                            );
                        @endphp

                        <li class="nav-item">
                            <button class="nav-link active" data-bs-toggle="tab"
                                data-bs-target="#images-{{ $complain->id }}">
                                <i class="bi bi-images me-1"></i>
                                Images ({{ count($images) }})
                            </button>
                        </li>

                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab"
                                data-bs-target="#documents-{{ $complain->id }}">
                                <i class="bi bi-file-earmark me-1"></i>
                                Documents ({{ count($documents) }})
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content">
                        {{-- IMAGES --}}
                        <div class="tab-pane fade show active" id="images-{{ $complain->id }}">
                            @if (count($images))
                                <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3">
                                    @foreach ($images as $index => $file)
                                        <div class="col">
                                            <div class="image-card">
                                                <div class="image-thumbnail border rounded overflow-hidden"
                                                    style="height:150px;cursor:pointer"
                                                    onclick="openImageLightbox({{ $complain->id }}, {{ json_encode(array_values($images)) }}, {{ $index }})">
                                                    <img src="{{ route('complain.image.stream', ['complain' => $complain->id, 'image' => $file['id']]) }}"
                                                        class="img-fluid w-100 h-100 object-fit-cover"
                                                        alt="{{ $file['file_name'] }}">
                                                </div>

                                                <div class="mt-2 d-flex justify-content-between align-items-center">
                                                    <small class="text-muted text-truncate" style="max-width: 100px;">
                                                        {{ Str::limit($file['file_name'], 15) }}
                                                    </small>

                                                    <div>
                                                        {{-- View/Stream Link --}}
                                                        <a href="{{ route('complain.image.stream', ['complain' => $complain->id, 'image' => $file['id']]) }}"
                                                            target="_blank" class="btn btn-outline-info btn-sm me-1"
                                                            title="View">
                                                            <i class="bi bi-eye"></i>
                                                        </a>

                                                        {{-- Download Link --}}
                                                        <a href="{{ route('complain.file.download', ['complain' => $complain->id, 'file' => $file['id']]) }}"
                                                            class="btn btn-outline-secondary btn-sm" title="Download">
                                                            <i class="bi bi-download"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-center text-muted py-4">No images found</p>
                            @endif
                        </div>

                        {{-- DOCUMENTS --}}
                        <div class="tab-pane fade" id="documents-{{ $complain->id }}">
                            @if (count($documents))
                                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
                                    @foreach ($documents as $file)
                                        @php
                                            $fileName = $file['original_name'] ?? $file['file_name'];
                                            $fileSize =
                                                $file['formatted_size'] ??
                                                App\Helpers\FileHelper::formatFileSize($file['size']);

                                            // শুধু প্রয়োজনীয় আইকন (অডিও, ডাটাবেস, কোড বাদ)
                                            $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
                                            $mimeType = $file['mime_type'] ?? '';

                                            // আইকন ডিটেকশন
                                            if (str_contains($mimeType, 'pdf') || $fileExt == 'pdf') {
                                                $icon = 'file-pdf';
                                                $color = 'text-danger';
                                            } elseif (
                                                str_contains($mimeType, 'word') ||
                                                in_array($fileExt, ['doc', 'docx'])
                                            ) {
                                                $icon = 'file-word';
                                                $color = 'text-primary';
                                            } elseif (
                                                str_contains($mimeType, 'excel') ||
                                                in_array($fileExt, ['xls', 'xlsx', 'csv'])
                                            ) {
                                                $icon = 'file-excel';
                                                $color = 'text-success';
                                            } elseif (
                                                str_contains($mimeType, 'presentation') ||
                                                in_array($fileExt, ['ppt', 'pptx'])
                                            ) {
                                                $icon = 'file-ppt';
                                                $color = 'text-danger';
                                            } elseif (
                                                str_contains($mimeType, 'zip') ||
                                                str_contains($mimeType, 'rar') ||
                                                in_array($fileExt, ['zip', 'rar', '7z', 'tar', 'gz'])
                                            ) {
                                                $icon = 'file-zip';
                                                $color = 'text-warning';
                                            } elseif (
                                                str_contains($mimeType, 'text') ||
                                                in_array($fileExt, ['txt', 'rtf', 'log'])
                                            ) {
                                                $icon = 'file-text';
                                                $color = 'text-secondary';
                                            } elseif (in_array($fileExt, ['msg', 'eml'])) {
                                                $icon = 'envelope-paper';
                                                $color = 'text-info';
                                            } elseif (in_array($fileExt, ['ttf', 'otf', 'woff'])) {
                                                $icon = 'fonts';
                                                $color = 'text-warning';
                                            } else {
                                                $icon = 'file-earmark';
                                                $color = 'text-muted';
                                            }
                                        @endphp
                                        <div class="col">
                                            <div class="card document-card h-100">
                                                <div class="card-body d-flex align-items-center">
                                                    <div class="me-3">
                                                        <i
                                                            class="bi bi-{{ $icon }} fs-2 {{ $color }}"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="text-truncate mb-1" title="{{ $fileName }}">
                                                            {{ Str::limit($fileName, 25) }}
                                                        </h6>
                                                        <small class="text-muted">{{ $fileSize }}</small>
                                                    </div>
                                                </div>
                                                <div class="card-footer bg-white d-flex justify-content-between gap-2">
                                                    {{-- Download using file.download route --}}
                                                    <a href="{{ route('complain.file.download', ['complain' => $complain->id, 'file' => $file['id']]) }}"
                                                        class="btn btn-outline-primary btn-sm flex-grow-1">
                                                        <i class="bi bi-download me-1"></i>
                                                        Download
                                                    </a>

                                                    {{-- Optional: Preview link for PDFs --}}
                                                    @if ($file['mime_type'] === 'application/pdf')
                                                        <a href="{{ route('complain.file.download', ['complain' => $complain->id, 'file' => $file['id']]) }}"
                                                            target="_blank" class="btn btn-outline-info btn-sm"
                                                            title="Preview">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-center text-muted py-4">No documents found</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="modal-footer d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted me-3">
                            {{ count($images) }} images,
                            {{ count($documents) }} documents
                        </small>

                        {{-- Download All Attachments Button --}}
                        <a href="{{ route('complain.download.all', ['complain' => $complain->id]) }}"
                            class="btn btn-success btn-sm" target="_blank">
                            <i class="bi bi-cloud-download me-1"></i>
                            Download All
                        </a>
                    </div>
                    <button class="btn btn-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .document-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .document-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, .1);
        }

        .image-card {
            transition: transform 0.2s ease;
        }

        .image-card:hover {
            transform: translateY(-2px);
        }

        .object-fit-cover {
            object-fit: cover;
        }
    </style>
@endif
