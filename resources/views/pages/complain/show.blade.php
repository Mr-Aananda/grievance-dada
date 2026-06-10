@section('title', $complain->type == 'complain' ? 'Complaint Details' : 'Manual Entry Details')

<x-app-layout>
    <!-- Header Widget - Exactly like index -->
    <div class="widget mb-3">
        <div class="widget-body d-flex p-1">
            @include('pages.complain.menu')

            <div class="ms-auto">
                <button type="button" class="btn icon lg rounded" title="Print" onclick="printable('print-widget')">
                    <i class="bi bi-printer"></i>
                </button>
                <button type="button" class="btn icon lg rounded" title="Reload" onclick="location.reload()">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
                @can('complain.edit')
                    <a href="{{ route('complain.edit', $complain->id) }}" class="btn icon lg rounded" title="Edit">
                        <i class="bi bi-pencil-square"></i>
                    </a>
                @endcan
                <button type="button" class="btn icon lg rounded" title="Go Back" onclick="history.back()">
                    <i class="bi bi-arrow-left"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Printable Section -->
    <div id="print-widget">
        <x-print.header />

        <!-- Detailed View Table -->
        <div class="widget m-0">
            <div class="widget-head mb-1 p-1">
                <h5 class="mb-0">
                    <i class="bi bi-table text-dark me-2"></i>
                    @if ($complain->type == 'complain')
                        Complaint Details
                    @else
                        Manual Entry Details
                    @endif
                    <span class="badge bg-light text-dark border ms-2">ID: #{{ $complain->id }}</span>
                </h5>
            </div>

            <div class="widget-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="p-1" width="200">Field</th>
                                <th class="p-1">Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Date -->
                            <tr>
                                <td class="p-1 fw-bold">Date</td>
                                <td class="p-1">
                                    {{ $complain->date ? \Carbon\Carbon::parse($complain->date)->format('d M Y') : 'N/A' }}
                                </td>
                            </tr>

                            <!-- Type - With two badges -->
                            <tr>
                                <td class="p-1 fw-bold">Type</td>
                                <td class="p-1">
                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                        <!-- Type Badge -->
                                        @if ($complain->type == 'complain')
                                            <span class="badge bg-primary fw-bold px-2 py-1">
                                                <i class="bi bi-chat-left-text me-1"></i>COMPLAIN
                                            </span>
                                        @else
                                            <span class="badge bg-success fw-bold px-2 py-1">
                                                <i class="bi bi-journal-text me-1"></i>MANUAL
                                            </span>
                                        @endif

                                        <!-- Complain Type -->
                                        @if ($complain->complain_type_name)
                                            <span class="badge bg-light text-dark border fw-normal px-2 py-1">
                                                {{ $complain->complain_type_name }}
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>

                            <!-- Category -->
                            <tr>
                                <td class="p-1 fw-bold">Category</td>
                                <td class="p-1">
                                    @if ($complain->category_name)
                                        <span class="badge bg-light text-dark border fw-normal">
                                            {{ $complain->category_name }}
                                        </span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                            </tr>
                            <!-- Subject -->
                            <tr>
                                <td class="p-1 fw-bold">Subject</td>
                                <td class="p-1">{{ $complain->subject ?? '—' }}</td>
                            </tr>

                            <!-- Buyer Information -->
                            <tr>
                                <td class="p-1 fw-bold">Buyer Name</td>
                                <td class="p-1">
                                    @if ($complain->buyer)
                                        <div class="fw-semibold">{{ $complain->buyer->company_name }} -
                                            <strong class="text-info">{{ $complain->buyer->country }}</strong>
                                        </div>
                                        @if ($complain->buyer->code)
                                            <small class="text-muted">Code: {{ $complain->buyer->code }}</small>
                                        @endif
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                            </tr>

                            <!-- PS/PO/CAP -->
                            <tr>
                                <td class="p-1 fw-bold">PS/PO/CAP</td>
                                <td class="p-1">
                                    <small class="text-muted">
                                        @if ($complain->ps)
                                            <span class="me-2"><strong>PS:</strong> {{ $complain->ps }}</span>
                                        @endif
                                        @if ($complain->po)
                                            <span class="me-2"><strong>PO:</strong> {{ $complain->po }}</span>
                                        @endif
                                        @if ($complain->cap)
                                            <span><strong>CAP:</strong> {{ $complain->cap }}</span>
                                        @endif
                                        @if (!$complain->ps && !$complain->po && !$complain->cap)
                                            —
                                        @endif
                                    </small>
                                </td>
                            </tr>

                            <!-- Location/Style -->
                            <tr>
                                <td class="p-1 fw-bold">Line/Floor</td>
                                <td class="p-1">{{ $complain->line_floor ?? '—' }}</td>
                            </tr>

                            <tr>
                                <td class="p-1 fw-bold">Style/Order</td>
                                <td class="p-1">{{ $complain->style_order ?? '—' }}</td>
                            </tr>

                            <!-- Quantity/Amount/Total -->
                            @php
                                $qty = (float) ($complain->quantity ?? 0);
                                $amount = (float) ($complain->amount ?? 0);
                                $total = $complain->total_amount ?? $qty * $amount;
                            @endphp
                            <tr>
                                <td class="p-1 fw-bold">Quantity & Amount</td>
                                <td class="p-1">
                                    @if ($qty > 0 || $amount > 0)
                                        <small class="d-flex flex-wrap gap-1">
                                            @if ($qty > 0)
                                                <span class="badge bg-light text-dark border">Qty:
                                                    {{ number_format($qty, 2) }}</span>
                                            @endif
                                            @if ($amount > 0)
                                                <span class="badge bg-light text-dark border">Amount:
                                                    ${{ number_format($amount, 2) }}</span>
                                            @endif
                                            @if ($total > 0)
                                                <span class="badge bg-light text-dark border fw-semibold">
                                                    Total: ${{ number_format($total, 2) }}
                                                </span>
                                            @endif
                                        </small>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                            </tr>

                            <!-- Status (only for complaints) -->
                            @if ($complain->type === 'complain')
                                <tr>
                                    <td class="p-1 fw-bold">Status</td>
                                    <td class="p-1">
                                        @php
                                            $statusColors = [
                                                'pending' => 'warning',
                                                'in_progress' => 'info',
                                                'resolved' => 'success',
                                                'closed' => 'secondary',
                                            ];
                                            $statusIcon = [
                                                'pending' => 'bi-clock',
                                                'in_progress' => 'bi-gear',
                                                'resolved' => 'bi-check-circle',
                                                'closed' => 'bi-lock',
                                            ];
                                            $currentStatus = strtolower(
                                                $complain->status_name ?? ($complain->status ?? 'pending'),
                                            );
                                        @endphp

                                        <span
                                            class="badge bg-{{ $statusColors[$currentStatus] ?? 'secondary' }} px-3 py-1">
                                            <i
                                                class="bi {{ $statusIcon[$currentStatus] ?? 'bi-question-circle' }} me-1"></i>
                                            {{ $complain->status_name ?? ucfirst($complain->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @endif

                            <!-- Description -->
                            <tr>
                                <td class="p-1 fw-bold">
                                    @if ($complain->type == 'complain')
                                        Complaint Description
                                    @else
                                        Entry Description
                                    @endif
                                </td>
                                <td class="p-1">
                                    @if ($complain->complain)
                                        <div class="quill-content p-2 bg-white border rounded">
                                            {!! $complain->complain !!}
                                        </div>
                                    @else
                                        <span class="text-muted">No description provided</span>
                                    @endif
                                </td>
                            </tr>

                            <!-- Note -->
                            @if ($complain->note)
                                <tr>
                                    <td class="p-1 fw-bold">Note</td>
                                    <td class="p-1">
                                        <div class="p-2 bg-light border rounded">
                                            {{ $complain->note }}
                                        </div>
                                    </td>
                                </tr>
                            @endif

                            <!-- Status Note -->
                            @if ($complain->status_note)
                                <tr>
                                    <td class="p-1 fw-bold">Status Note</td>
                                    <td class="p-1">
                                        <div class="p-2 bg-light border rounded">
                                            {{ $complain->status_note }}
                                        </div>
                                    </td>
                                </tr>
                            @endif

                            <!-- Created By -->
                            <tr>
                                <td class="p-1 fw-bold">Created By</td>
                                <td class="p-1">
                                    @if ($complain->user)
                                        {{ $complain->user->name }}
                                        @if ($complain->user->designation)
                                            <small class="text-muted">({{ $complain->user->designation }})</small>
                                        @endif
                                    @else
                                        N/A
                                    @endif
                                </td>
                            </tr>

                            <!-- Created At -->
                            <tr>
                                <td class="p-1 fw-bold">Created At</td>
                                <td class="p-1">
                                    {{ \Carbon\Carbon::parse($complain->created_at)->format('d M Y h:i A') }}
                                </td>
                            </tr>

                            <!-- Updated Info -->
                            @if ($complain->updated_at && $complain->updated_at != $complain->created_at)
                                <tr>
                                    <td class="p-1 fw-bold">Last Updated</td>
                                    <td class="p-1">
                                        {{ \Carbon\Carbon::parse($complain->updated_at)->format('d M Y h:i A') }}
                                        @if ($complain->updated_by)
                                            <small class="text-muted">by
                                                {{ $complain->updated_by->name ?? '' }}</small>
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <td class="p-1 fw-bold">Updated By</td>
                                    <td class="p-1">
                                        @if ($complain->updatedBy)
                                            {{ $complain->updatedBy->name }}
                                            @if ($complain->updatedBy->designation)
                                                <small
                                                    class="text-muted">({{ $complain->updatedBy->designation }})</small>
                                            @endif
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <!-- Attachments Section - At the bottom exactly like before -->
                @if (
                    ($complain->files_data && count($complain->files_data) > 0) ||
                        ($complain->media && count($complain->media) > 0) ||
                        $complain->videos_count > 0)
                    <div class="mt-2 p-1">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="p-1" colspan="2">
                                            <i class="bi bi-paperclip me-1"></i>
                                            Attachments
                                            ({{ ($complain->files_data ? count($complain->files_data) : 0) + ($complain->videos_count ?? 0) }})
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="p-1" colspan="2">
                                            @include('pages.complain.partials.attachments')
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Include Modals -->
    @include('pages.complain.modals.status-modal', ['complain' => $complain])
    @include('pages.complain.modals.files', ['complain' => $complain])
    @include('pages.complain.modals.videos', ['complain' => $complain])

    <!-- Global Lightbox Modal -->
    <div class="modal fade" id="globalLightboxModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content bg-dark">
                <div class="modal-header border-0 p-1">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <div class="text-white">
                            <h6 class="mb-0" id="lightboxTitle"></h6>
                            <small class="text-white-50" id="lightboxInfo"></small>
                        </div>
                        <div class="btn-group">
                            <button type="button" class="btn btn-dark btn-sm" id="lightboxDownloadBtn">
                                <i class="bi bi-download text-white"></i>
                            </button>
                            <button type="button" class="btn btn-dark btn-sm" data-bs-dismiss="modal">
                                <i class="bi bi-x-lg text-white"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="modal-body d-flex align-items-center justify-content-center p-0">
                    <div id="lightboxCarousel" class="carousel slide w-100 h-100" data-bs-interval="false">
                        <div class="carousel-inner h-100" id="lightboxSlides">
                            <!-- Slides will be inserted dynamically -->
                        </div>

                        <button class="carousel-control-prev" type="button" data-bs-target="#lightboxCarousel"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon bg-dark rounded-circle p-2"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#lightboxCarousel"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon bg-dark rounded-circle p-2"></span>
                        </button>
                    </div>
                </div>

                <div class="modal-footer border-0 p-1">
                    <div class="d-flex justify-content-center w-100">
                        <div class="d-flex flex-wrap gap-1 justify-content-center" id="lightboxThumbs">
                            <!-- Thumbnails will be inserted dynamically -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .table td,
        .table th {
            padding: 0.3rem 0.4rem !important;
            vertical-align: middle;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        .badge {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        .dropdown-menu {
            min-width: auto;
        }

        .quill-content {
            font-family: inherit;
            line-height: 1.5;
            max-height: 300px;
            overflow-y: auto;
        }

        .quill-content p {
            margin-bottom: 0.5rem;
        }

        .quill-content p:last-child {
            margin-bottom: 0;
        }

        @media print {

            .table td,
            .table th {
                padding: 0.15rem 0.25rem !important;
                font-size: 0.8rem;
            }

            .btn,
            .modal,
            .dropdown {
                display: none !important;
            }
        }
    </style>

    <script>
        // Format file size helper function
        function formatFileSizeJS(bytes) {
            if (bytes == 0) return '0 B';
            const units = ['B', 'KB', 'MB', 'GB', 'TB'];
            const i = Math.floor(Math.log(bytes) / Math.log(1024));
            return (bytes / Math.pow(1024, i)).toFixed(i > 0 ? 2 : 0) + ' ' + units[i];
        }

        // Open lightbox with specific image
        function openImageLightbox(complainId, images, imageIndex) {
            if (images.length === 0) return;

            let slidesHtml = '';
            let thumbsHtml = '';

            images.forEach((image, index) => {
                const isActive = index === imageIndex ? 'active' : '';

                slidesHtml += `
                    <div class="carousel-item h-100 ${isActive}">
                        <div class="d-flex align-items-center justify-content-center h-100">
                            <img src="${image.url || image.original_url}"
                                 class="img-fluid"
                                 alt="${image.file_name || image.name}"
                                 style="max-height: 80vh; object-fit: contain;"
                                 onerror="this.onerror=null; this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iODAwIiBoZWlnaHQ9IjYwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iODAwIiBoZWlnaHQ9IjYwMCIgZmlsbD0iIzIyMiIvPjx0ZXh0IHg9IjUwJSIgeT0iNTAlIiBmb250LWZhbWlseT0iQXJpYWwiIGZvbnQtc2l6ZT0iMTYiIGZpbGw9IiNmZmYiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIj5JbWFnZSBOb3QgRm91bmQ8L3RleHQ+PC9zdmc+'">
                        </div>
                    </div>
                `;

                thumbsHtml += `
                    <img src="${image.url || image.original_url}"
                         class="rounded border ${isActive ? 'border-primary border-2' : 'border-secondary'}"
                         style="width: 50px; height: 50px; object-fit: cover; cursor: pointer;"
                         onclick="jumpToLightboxSlide(${index})"
                         alt="Thumb ${index + 1}">
                `;
            });

            document.getElementById('lightboxSlides').innerHTML = slidesHtml;
            document.getElementById('lightboxThumbs').innerHTML = thumbsHtml;

            const activeImage = images[imageIndex];
            document.getElementById('lightboxTitle').textContent = activeImage.file_name || activeImage.name;
            document.getElementById('lightboxInfo').textContent =
                `${activeImage.formatted_size || formatFileSizeJS(activeImage.size)} • ${imageIndex + 1} of ${images.length}`;

            const downloadBtn = document.getElementById('lightboxDownloadBtn');
            downloadBtn.onclick = function() {
                window.location.href = activeImage.download_url || activeImage.url || activeImage.original_url;
            };

            const lightboxModal = new bootstrap.Modal(document.getElementById('globalLightboxModal'));
            lightboxModal.show();

            const carousel = document.getElementById('lightboxCarousel');
            const bsCarousel = new bootstrap.Carousel(carousel, {
                interval: false
            });

            window.currentLightboxImages = images;
            window.currentComplainId = complainId;

            carousel.addEventListener('slid.bs.carousel', function(event) {
                const newIndex = event.to;
                const newImage = images[newIndex];
                document.getElementById('lightboxTitle').textContent = newImage.file_name || newImage.name;
                document.getElementById('lightboxInfo').textContent =
                    `${newImage.formatted_size || formatFileSizeJS(newImage.size)} • ${newIndex + 1} of ${images.length}`;

                downloadBtn.onclick = function() {
                    window.location.href = newImage.download_url || newImage.url || newImage.original_url;
                };

                const thumbs = document.querySelectorAll('#lightboxThumbs img');
                thumbs.forEach((thumb, index) => {
                    thumb.classList.remove('border-primary', 'border-2');
                    thumb.classList.add('border-secondary');
                    if (index === newIndex) {
                        thumb.classList.remove('border-secondary');
                        thumb.classList.add('border-primary', 'border-2');
                    }
                });
            });
        }

        function jumpToLightboxSlide(index) {
            const carousel = document.getElementById('lightboxCarousel');
            const bsCarousel = bootstrap.Carousel.getInstance(carousel);
            if (bsCarousel) {
                bsCarousel.to(index);
            }
        }

        document.addEventListener('keydown', function(e) {
            const lightboxModal = document.querySelector('#globalLightboxModal.show');
            if (!lightboxModal) return;

            const carousel = lightboxModal.querySelector('#lightboxCarousel');
            if (!carousel) return;

            const bsCarousel = bootstrap.Carousel.getInstance(carousel);

            if (e.key === 'ArrowLeft') {
                bsCarousel.prev();
            } else if (e.key === 'ArrowRight') {
                bsCarousel.next();
            } else if (e.key === 'Escape') {
                bootstrap.Modal.getInstance(lightboxModal).hide();
            }
        });

        document.addEventListener('hidden.bs.modal', function(event) {
            if (event.target.id && event.target.id.startsWith('videosModal')) {
                const videos = event.target.querySelectorAll('video');
                videos.forEach(video => {
                    if (!video.paused) {
                        video.pause();
                        video.currentTime = 0;
                    }
                });
            }
        });
    </script>
</x-app-layout>
