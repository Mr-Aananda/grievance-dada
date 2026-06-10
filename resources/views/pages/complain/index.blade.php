@section('title', 'Complains & Manuals')

<x-app-layout>
    <!-- Header + Buttons -->
    <div class="widget mb-3">
        <div class="widget-body d-flex">
            @include('pages.complain.menu')

            <div class="ms-auto">

                <!-- Excel Export Icon -->
                @if ($complains->count() > 0)
                    <form action="{{ route('reports.overall.export') }}" method="POST" class="d-inline">
                        @csrf
                        <input type="hidden" name="export_format" value="excel">
                        @foreach (request()->all() as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        <button type="submit" class="btn icon lg rounded" title="Export to Excel">
                            <i class="bi bi-filetype-xlsx"></i>
                        </button>
                    </form>
                @endif

                <button type="button" class="btn icon lg rounded collapsed" title="Search" data-bs-toggle="collapse"
                    data-bs-target="#tableSearch" aria-expanded="false">
                    <i class="bi bi-search"></i>
                </button>
                <button type="button" class="btn icon lg rounded" title="Print" onclick="printable('print-widget')">
                    <i class="bi bi-printer"></i>
                </button>
                <button type="button" class="btn icon lg rounded" title="Reload" onclick="location.reload()">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
                <a href="{{ route('complain.create') }}" class="btn icon lg rounded" title="Add New">
                    <i class="bi bi-plus"></i>
                </a>
            </div>
        </div>

        <!-- Search Filter -->
        <div class="widget-body collapse {{ request()->search == '1' ? 'show' : '' }} p-3" id="tableSearch">
            <form action="{{ route('complain.index') }}" method="get">
                <input type="hidden" name="search" value="1">

                <!-- ================= Row 1 ================= -->
                <div class="row g-2 mb-2">

                    <div class="col-md-2">
                        <label class="form-label small mb-0">Type</label>
                        <select name="type" class="form-select">
                            <option value="">All Types</option>
                            <option value="complain" {{ request('type') == 'complain' ? 'selected' : '' }}>Complaint
                            </option>
                            <option value="manual" {{ request('type') == 'manual' ? 'selected' : '' }}>Manual</option>
                        </select>
                    </div>



                    <div class="col-md-2">
                        <label class="form-label small mb-0">Date From</label>
                        <input type="date" name="date_from" class="form-control" value="{{ request()->date_from }}">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label small mb-0">Date To</label>
                        <input type="date" name="date_to" class="form-control" value="{{ request()->date_to }}">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label small mb-0">PS</label>
                        <input name="ps" class="form-control" placeholder="Type PS" value="{{ request()->ps }}">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label small mb-0">PO</label>
                        <input name="po" class="form-control" placeholder="Type PO" value="{{ request()->po }}">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label small mb-0">CAP</label>
                        <input name="cap" class="form-control" placeholder="Type CAP" value="{{ request()->cap }}">
                    </div>

                </div>

                <!-- ================= Row 2 ================= -->
                <div class="row g-2 mb-2">

                    <div class="col-md-3">
                        <label class="form-label small mb-0">Complain/Manual Type</label>
                        <select name="complain_type_id" class="form-select select2">
                            <option value="">All Types</option>
                            @foreach ($complainTypes as $type)
                                <option value="{{ $type->id }}"
                                    {{ request()->complain_type_id == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label small mb-0">Category</label>
                        <select name="category_id" class="form-select select2">
                            <option value="">All Categories</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ request()->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label small mb-0">Manual Category</label>
                        <input type="text" name="manual_category" class="form-control"
                            placeholder="Type manual category" value="{{ request()->manual_category }}">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label small mb-0">Subject</label>
                        <input name="subject" class="form-control" placeholder="Type Subject"
                            value="{{ request()->subject }}">
                    </div>


                    <div class="col-md-2">
                        <label class="form-label small mb-0">Status</label>
                        <select class="form-select select2" name="status">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending
                            </option>
                            <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In
                                Progress</option>
                            <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved
                            </option>
                            <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed
                            </option>
                        </select>
                    </div>

                </div>

                <!-- ================= Final Row ================= -->
                <div class="row g-2 mb-3 align-items-end">

                    <div class="col-md-3">
                        <label class="form-label small mb-0">Buyer</label>
                        <select name="buyer_id" class="form-select select2">
                            <option value="">All Buyers</option>
                            @foreach ($buyers as $buyer)
                                <option value="{{ $buyer->id }}"
                                    {{ request('buyer_id') == $buyer->id ? 'selected' : '' }}>
                                    {{ $buyer->company_name }} ({{ $buyer->country ?? 'N/A' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Complain Description -->
                    <div class="col-md-6">
                        <label class="form-label small mb-0">Complain Description</label>
                        <input type="text" name="complain_text" class="form-control"
                            placeholder="Type any word..." value="{{ request()->complain_text }}">
                    </div>

                    <!-- Buttons -->
                    <div class="col-md-3">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success w-50">
                                <i class="bi bi-search"></i> Search
                            </button>

                            <a href="{{ route('complain.index') }}" class="btn btn-secondary w-50">
                                <i class="bi bi-arrow-clockwise"></i> Reset
                            </a>
                        </div>
                    </div>

                </div>

            </form>
        </div>
    </div>

    <!-- Printable Section -->
    <div id="print-widget">
        <x-print.header />

        <!-- Detailed Report Table -->
        <div class="widget">
            <div class="widget-head mb-3">
                <h5 class="mb-0">
                    <i class="bi bi-table text-dark me-2"></i>
                    All Complaints & Manual Entries
                </h5>
                <p class="mb-0 text-muted small">
                    Showing {{ $complains->count() }} of {{ $complains->total() }} record(s)
                </p>
            </div>

            <div class="widget-body p-0">
                @if ($complains->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-0 complain-table">
                            <thead class="table-light">
                                <tr>
                                    <th class="col-sl">SL</th>
                                    <th class="col-date">Date</th>
                                    <th class="col-type">Type</th>
                                    <th class="col-category">Category</th>
                                    <th class="col-subject">Subject</th>
                                    <th class="col-buyer">Buyer Name</th>
                                    <th class="col-pspo">PS/PO/CAP</th>
                                    <th class="col-qty">Qty/Amount/Total</th>
                                    <th class="col-status print-none">Status</th>
                                    <th class="col-files print-none">Files</th>
                                    <th class="col-actions print-none">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($complains as $index => $complain)
                                    @php
                                        $files = $complain->files_data ?? [];
                                        $images = array_filter($files, function ($file) {
                                            return $file['is_image'] ?? false;
                                        });
                                        $documents = array_filter($files, function ($file) {
                                            return !($file['is_image'] ?? false);
                                        });
                                        $imageCount = count($images);
                                        $documentCount = count($documents);
                                        $videoCount = $complain->videos_count ?? 0;
                                        $totalAttachments = $imageCount + $documentCount + $videoCount;

                                        $qty = (float) ($complain->quantity ?? 0);
                                        $amount = (float) ($complain->amount ?? 0);
                                        $total = $qty * $amount;
                                    @endphp

                                    <tr class="clickable-row"
                                        data-href="{{ route('complain.show', $complain->id) }}">
                                        <td class="text-center fw-bold">
                                            {{ $complains->firstItem() + $loop->index }}.
                                        </td>

                                        <td>
                                            {{ $complain->date ? \Carbon\Carbon::parse($complain->date)->format('d M Y') : 'N/A' }}
                                        </td>

                                        <td>
                                            <div class="type-wrapper">
                                                @if ($complain->type == 'complain')
                                                    <span class="type-badge type-complain">COMPLAIN</span>
                                                @else
                                                    <span class="type-badge type-manual">MANUAL</span>
                                                @endif

                                                @if ($complain->complainType)
                                                    <span
                                                        class="type-badge type-sub fw-bold">{{ $complain->complainType->name }}</span>
                                                @else
                                                    <span class="type-badge type-sub">—</span>
                                                @endif
                                            </div>
                                        </td>

                                        <td>
                                            @if ($complain->category)
                                                <span class="category-text fw-bold" title="{{ $complain->category->name }}">
                                                    {{ $complain->category->name }}
                                                </span>
                                            @elseif ($complain->manual_category)
                                                <span class="category-text fw-bold" title="{{ $complain->manual_category }}">
                                                    {{ $complain->manual_category }}
                                                </span>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>

                                        <td>
                                            @if ($complain->subject)
                                                <div class="subject-text" title="{{ $complain->subject }}">
                                                    {{ $complain->subject }}
                                                </div>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>

                                        <td>
                                            @if ($complain->buyer)
                                                <div class="buyer-text"
                                                    title="{{ $complain->buyer->company_name }} - {{ $complain->buyer->country }}">
                                                    {{ $complain->buyer->company_name ?? '--' }}
                                                    @if ($complain->buyer->country)
                                                        <span class="country">({{ $complain->buyer->country }})</span>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-muted">--</span>
                                            @endif
                                        </td>

                                        <td>
                                            <div class="pspo-wrapper">
                                                @if ($complain->ps)
                                                    <span class="pspo-badge" title="PS: {{ $complain->ps }}">
                                                        <strong>PS:</strong> {{ $complain->ps }}
                                                    </span>
                                                @endif
                                                @if ($complain->po)
                                                    <span class="pspo-badge" title="PO: {{ $complain->po }}">
                                                        <strong>PO:</strong> {{ $complain->po }}
                                                    </span>
                                                @endif
                                                @if ($complain->cap)
                                                    <span class="pspo-badge" title="CAP: {{ $complain->cap }}">
                                                        <strong>CAP:</strong> {{ $complain->cap }}
                                                    </span>
                                                @endif

                                                @if (!$complain->ps && !$complain->po && !$complain->cap)
                                                    <span class="text-muted small">—</span>
                                                @endif
                                            </div>
                                        </td>

                                        <td>
                                            @if ($qty > 0 || $amount > 0)
                                                <div class="qty-amount-wrapper">
                                                    @if ($qty > 0)
                                                        <span class="qty-item"><span class="text-secondary">Q:</span>
                                                            <strong>{{ number_format($qty) }}</strong></span>
                                                    @endif
                                                    @if ($amount > 0)
                                                        <span class="amount-item"><span
                                                                class="text-secondary">A:</span>
                                                            <strong>{{ number_format($amount, 2) }}</strong></span>
                                                    @endif
                                                    @if ($total > 0)
                                                        <span class="total-item"><span
                                                                class="text-secondary">T:</span>
                                                            <strong>{{ number_format($total, 2) }}</strong></span>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-muted small">—</span>
                                            @endif
                                        </td>

                                        <td class="no-row-click print-none">
                                            @if ($complain->type === 'complain')
                                                @php
                                                    $statusColors = [
                                                        'pending' => 'status-pending',
                                                        'in_progress' => 'status-in_progress',
                                                        'resolved' => 'status-resolved',
                                                        'closed' => 'status-closed',
                                                    ];
                                                    $statusIcon = [
                                                        'pending' => 'bi-clock',
                                                        'in_progress' => 'bi-gear',
                                                        'resolved' => 'bi-check-circle',
                                                        'closed' => 'bi-lock',
                                                    ];
                                                @endphp

                                                @can('complain.update-status')
                                                    <span
                                                        class="status-badge {{ $statusColors[$complain->status] ?? 'status-closed' }}"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#statusModal{{ $complain->id }}"
                                                        title="Click to change status ({{ ucfirst($complain->status) }})">
                                                        <i
                                                            class="bi {{ $statusIcon[$complain->status] ?? 'bi-question-circle' }}"></i>
                                                        <span>{{ ucfirst($complain->status) }}</span>
                                                    </span>
                                                @else
                                                    <span
                                                        class="status-badge {{ $statusColors[$complain->status] ?? 'status-closed' }}"
                                                        title="Status: {{ ucfirst($complain->status) }}">
                                                        <i
                                                            class="bi {{ $statusIcon[$complain->status] ?? 'bi-question-circle' }}"></i>
                                                        <span>{{ ucfirst($complain->status) }}</span>
                                                    </span>
                                                @endcan
                                            @else
                                                <span class="text-muted">--</span>
                                            @endif
                                        </td>

                                        <td class="no-row-click print-none text-center">
                                            @if ($totalAttachments > 0)
                                                <div class="dropdown">
                                                    <button class="btn-file" type="button" data-bs-toggle="dropdown"
                                                        title="{{ $totalAttachments }} attachment(s)">
                                                        <i class="bi bi-paperclip"></i>
                                                        <span>{{ $totalAttachments }}</span>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        @if ($imageCount > 0 || $documentCount > 0)
                                                            <li>
                                                                <a class="dropdown-item" href="#"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#filesModal{{ $complain->id }}">
                                                                    <i class="bi bi-files text-info"></i> Files
                                                                    ({{ $imageCount + $documentCount }})
                                                                </a>
                                                            </li>
                                                        @endif
                                                        @if ($videoCount > 0)
                                                            <li>
                                                                <a class="dropdown-item" href="#"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#videosModal{{ $complain->id }}">
                                                                    <i class="bi bi-camera-video text-danger"></i>
                                                                    Videos ({{ $videoCount }})
                                                                </a>
                                                            </li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            @else
                                                <span class="text-muted small">—</span>
                                            @endif
                                        </td>

                                        <td class="no-row-click print-none text-end">
                                            <div class="dropdown">
                                                <button class="btn-action" type="button" data-bs-toggle="dropdown"
                                                    title="Actions">
                                                    <i class="bi bi-three-dots-vertical"></i>
                                                </button>

                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    @can('complain.show')
                                                        <li>
                                                            <a class="dropdown-item"
                                                                href="{{ route('complain.show', $complain->id) }}">
                                                                <i class="bi bi-eye text-info"></i> Details
                                                            </a>
                                                        </li>
                                                    @endcan

                                                    @can('complain.edit')
                                                        <li>
                                                            <a class="dropdown-item"
                                                                href="{{ route('complain.edit', $complain->id) }}">
                                                                <i class="bi bi-pencil-square text-success"></i> Edit
                                                            </a>
                                                        </li>
                                                    @endcan

                                                    @can('complain.destroy')
                                                        <li>
                                                            <form action="{{ route('complain.destroy', $complain->id) }}"
                                                                method="POST" style="display: inline;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item"
                                                                    onclick="return confirm('Are you sure want to delete?')">
                                                                    <i class="bi bi-trash text-danger"></i> Delete
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @endcan
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Include Modals -->
                    @foreach ($complains as $complain)
                        @include('pages.complain.modals.status-modal', ['complain' => $complain])
                        @include('pages.complain.modals.files', ['complain' => $complain])
                        @include('pages.complain.modals.videos', ['complain' => $complain])
                    @endforeach

                    <!-- Pagination -->
                    <x-pagination :items="$complains" class="m-0 p-1" />
                @else
                    <div class="text-center py-3 text-muted">
                        <i class="bi bi-clipboard-data display-1 mb-2"></i>
                        <h5>No Data Available</h5>
                        <p class="mb-1">No entries found for the selected criteria.</p>
                        <button class="btn btn-primary btn-sm mt-1" data-bs-toggle="collapse"
                            data-bs-target="#tableSearch">
                            <i class="bi bi-search me-1"></i> Apply Filters
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Global Lightbox Modal (Single for all complaints) -->
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
        /* টেবিল কাস্টমাইজেশন */
        .complain-table {
            min-width: 1200px;
        }

        .complain-table th {
            padding: 0.3rem 0.4rem !important;
            white-space: nowrap;
            background-color: #f8f9fa;
            font-size: 0.85rem;
        }

        .complain-table td {
            padding: 0.25rem 0.4rem !important;
            vertical-align: middle;
        }

        /* কলাম প্রস্থ */
        .col-sl {
            width: 40px;
            text-align: center;
        }

        .col-date {
            width: 90px;
        }

        .col-type {
            width: 200px;
        }

        .col-category {
            width: 130px;
        }

        .col-subject {
            width: 250px;
        }

        .col-buyer {
            width: 180px;
        }

        .col-pspo {
            width: 200px;
        }

        .col-qty {
            width: 120px;
        }

        .col-status {
            width: 70px;
            text-align: center;
        }

        .col-files {
            width: 65px;
            text-align: center;
        }

        .col-actions {
            width: 65px;
            text-align: center;
        }

        /* টাইপ - এক লাইনে, আইকন ছাড়া */
        .type-wrapper {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            white-space: nowrap;
        }

        .type-badge {
            display: inline-block;
            font-weight: bold;
            padding: 0.2rem 0.4rem;
            border-radius: 0.25rem;
            font-size: 0.7rem;
            line-height: 1.2;
            white-space: nowrap;
        }

        .type-complain {
            background-color: #0d6efd;
            color: white;
        }

        .type-manual {
            background-color: #28C76F;
            color: white;
        }

        .type-sub {
            background-color: #f8f9fa;
            color: #212529;
            border: 1px solid #dee2e6;
            font-weight: normal;
            white-space: nowrap;
        }

        /* ক্যাটাগরি - এক লাইনে */
        .category-text {
            font-size: 0.7rem;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 0.2rem 0.4rem;
            border-radius: 0.25rem;
            display: inline-block;
            white-space: nowrap;
            max-width: 100%;
        }

        /* সাবজেক্ট */
        .subject-text {
            font-weight: bold;
            font-size: 0.8rem;
            white-space: normal;
            word-wrap: break-word;
            line-height: 1.3;
            max-height: 2.6rem;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        /* বায়ার */
        .buyer-text {
            font-weight: 600;
            font-size: 0.8rem;
            white-space: normal;
            word-wrap: break-word;
            line-height: 1.3;
            max-height: 2.6rem;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        .buyer-text .country {
            color: #0dcaf0;
            font-weight: bold;
        }

        /* পিএস/পিও/সিএপি - এক লাইনে */
        .pspo-wrapper {
            display: flex;
            flex-wrap: wrap;
            gap: 0.2rem;
            align-items: center;
        }

        .pspo-badge {
            font-size: 0.7rem;
            padding: 0.2rem 0.3rem;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            color: #212529;
            border-radius: 0.25rem;
            display: inline-flex;
            align-items: center;
            white-space: nowrap;
        }

        .pspo-badge strong {
            font-weight: 600;
            margin-right: 0.1rem;
        }

        /* Quantity/Amount/Total - এক লাইনে */
        .qty-amount-wrapper {
            display: flex;
            align-items: center;
            gap: 0.3rem;
            flex-wrap: wrap;
            font-size: 0.75rem;
            white-space: nowrap;
        }

        .qty-item,
        .amount-item,
        .total-item {
            display: inline-flex;
            align-items: center;
            gap: 0.1rem;
        }

        .qty-item .text-secondary,
        .amount-item .text-secondary,
        .total-item .text-secondary {
            color: #6c757d;
            font-size: 0.65rem;
        }

        .total-item {
            font-weight: 600;
        }

        .total-item strong {
            color: #198754;
        }

        /* স্ট্যাটাস ব্যাজ - ছোট */
        .status-badge {
            font-size: 0.65rem;
            padding: 0.2rem 0.3rem;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.15rem;
            border-radius: 0.25rem;
            font-weight: 500;
            white-space: nowrap;
        }

        .status-pending {
            background-color: #ffc107;
            color: #000;
        }

        .status-in_progress {
            background-color: #0dcaf0;
            color: #000;
        }

        .status-resolved {
            background-color: #198754;
            color: #fff;
        }

        .status-closed {
            background-color: #6c757d;
            color: #fff;
        }

        /* ফাইল বাটন - ছোট */
        .btn-file {
            padding: 0.4rem 0.5rem;
            font-size: 0.80rem;
            background-color: #F47B22;
            color: white;
            border: none;
            border-radius: 0.2rem;
            display: inline-flex;
            align-items: center;
            gap: 0.15rem;
            cursor: pointer;
            line-height: 1;
        }

        .btn-file i {
            font-size: 0.8rem;
        }

        /* অ্যাকশন বাটন - ছোট */
        .btn-action {
            padding: 0.3rem 0.4rem;
            font-size: 0.80rem;
            background-color: transparent;
            border: 1px solid #6c757d;
            color: #6c757d;
            border-radius: 0.2rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            line-height: 1;
        }

        .btn-action i {
            font-size: 0.8rem;
        }

        /* ড্রপডাউন মেনু */
        .dropdown-menu {
            min-width: 120px;
            padding: 0.2rem 0;
        }

        .dropdown-item {
            padding: 0.5rem 0.7rem;
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        .dropdown-item i {
            font-size: 1rem;
        }

        /* মোবাইল রেসপন্সিভ */
        @media (max-width: 992px) {
            .col-subject {
                width: 200px;
            }

            .col-buyer {
                width: 150px;
            }

            .col-pspo {
                width: 180px;
            }

            .col-type {
                width: 180px;
            }
        }

        @media (max-width: 768px) {
            .status-badge span {
                display: none;
            }

            .btn-file span {
                display: none;
            }

            .btn-file i {
                font-size: 0.8rem;
            }

            .btn-action i {
                font-size: 0.8rem;
            }
        }

        /* প্রিন্ট মিডিয়া */
        @media print {
            .print-none {
                display: none !important;
            }
        }

        /* হোভার ইফেক্ট */
        .clickable-row {
            cursor: pointer;
        }

        .clickable-row:hover {
            background-color: rgba(0, 0, 0, 0.03);
        }

        .no-row-click {
            cursor: default !important;
        }
    </style>

    <script>
        // Pause videos when modal closes
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

            // Build slides HTML
            let slidesHtml = '';
            let thumbsHtml = '';

            images.forEach((image, index) => {
                const isActive = index === imageIndex ? 'active' : '';

                // Slide
                slidesHtml += `
                    <div class="carousel-item h-100 ${isActive}">
                        <div class="d-flex align-items-center justify-content-center h-100">
                            <img src="${image.url}"
                                 class="img-fluid"
                                 alt="${image.file_name}"
                                 style="max-height: 80vh; object-fit: contain;"
                                 onerror="this.onerror=null; this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iODAwIiBoZWlnaHQ9IjYwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iODAwIiBoZWlnaHQ9IjYwMCIgZmlsbD0iIzIyMiIvPjx0ZXh0IHg9IjUwJSIgeT0iNTAlIiBmb250LWZhbWlseT0iQXJpYWwiIGZvbnQtc2l6ZT0iMTYiIGZpbGw9IiNmZmYiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIj5JbWFnZSBOb3QgRm91bmQ8L3RleHQ+PC9zdmc+'">
                        </div>
                    </div>
                `;

                // Thumbnail
                thumbsHtml += `
                    <img src="${image.url}"
                         class="rounded border ${isActive ? 'border-primary border-2' : 'border-secondary'}"
                         style="width: 50px; height: 50px; object-fit: cover; cursor: pointer;"
                         onclick="jumpToLightboxSlide(${index})"
                         alt="Thumb ${index + 1}">
                `;
            });

            // Update lightbox content
            document.getElementById('lightboxSlides').innerHTML = slidesHtml;
            document.getElementById('lightboxThumbs').innerHTML = thumbsHtml;

            // Update title and info
            const activeImage = images[imageIndex];
            document.getElementById('lightboxTitle').textContent = activeImage.file_name;
            document.getElementById('lightboxInfo').textContent =
                `${activeImage.formatted_size || formatFileSizeJS(activeImage.size)} • ${imageIndex + 1} of ${images.length}`;

            // Update download button
            const downloadBtn = document.getElementById('lightboxDownloadBtn');
            downloadBtn.onclick = function() {
                window.location.href = activeImage.download_url ||
                    `/admin/complain/${complainId}/file/${activeImage.id}/download`;
            };

            // Show lightbox modal
            const lightboxModal = new bootstrap.Modal(document.getElementById('globalLightboxModal'));
            lightboxModal.show();

            // Initialize carousel
            const carousel = document.getElementById('lightboxCarousel');
            const bsCarousel = new bootstrap.Carousel(carousel, {
                interval: false
            });

            // Store current images for keyboard navigation
            window.currentLightboxImages = images;
            window.currentComplainId = complainId;

            // Update info on slide change
            carousel.addEventListener('slid.bs.carousel', function(event) {
                const newIndex = event.to;
                const newImage = images[newIndex];
                document.getElementById('lightboxTitle').textContent = newImage.file_name;
                document.getElementById('lightboxInfo').textContent =
                    `${newImage.formatted_size || formatFileSizeJS(newImage.size)} • ${newIndex + 1} of ${images.length}`;

                // Update download button
                downloadBtn.onclick = function() {
                    window.location.href = newImage.download_url ||
                        `/admin/complain/${complainId}/file/${newImage.id}/download`;
                };

                // Update active thumbnail
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

        // Jump to specific slide in lightbox
        function jumpToLightboxSlide(index) {
            const carousel = document.getElementById('lightboxCarousel');
            const bsCarousel = bootstrap.Carousel.getInstance(carousel);
            if (bsCarousel) {
                bsCarousel.to(index);
            }
        }

        // Keyboard navigation for lightbox
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

        document.addEventListener('DOMContentLoaded', function() {
            const rows = document.querySelectorAll('.clickable-row');

            rows.forEach(row => {
                row.addEventListener('click', function(e) {
                    // Ignore clicks on elements with class "no-row-click"
                    if (e.target.closest('.no-row-click')) return;

                    const href = this.dataset.href;
                    if (href) {
                        window.location.href = href;
                    }
                });
            });
        });
    </script>
</x-app-layout>
