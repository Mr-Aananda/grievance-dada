@section('title', 'Complain Trash')

<x-app-layout>
    <!-- Start header widget -->
    <div class="widget mb-3 border-top print-none">
        <div class="widget-body d-flex">
            <!-- Start left menu -->
            @include('pages.complain.menu')
            <!-- End left menu -->

            <!-- Start right buttons -->
            <div class="ms-auto">
                <button type="button" class="btn icon lg rounded" title="Print" onclick="window.print()">
                    <i class="bi bi-printer"></i>
                </button>
                <a href="{{ route('complain.trash') }}" class="btn icon lg rounded" title="Reload">
                    <i class="bi bi-bootstrap-reboot"></i>
                </a>
                <a href="{{ route('complain.index') }}" class="btn icon lg rounded" title="Back to List">
                    <i class="bi bi-arrow-left"></i>
                </a>
            </div>
            <!-- End right buttons -->
        </div>
    </div>
    <!-- End header widget -->

    <!-- Start body widget -->
    <div id="print-widget">
        <!-- Start print header -->
        <x-print.header />
        <!-- End print header -->

        <div class="widget">
            <div class="widget-head mb-3">
                <h5>Trash List - Complaints & Manual Entries</h5>
                <p><small>{{ $complains->total() }} results found</small></p>
            </div>

            <div class="widget-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width:60px;">SL</th>
                                <th>Date</th>
                                <th>Complain/Manual Type</th>
                                <th>Category</th>
                                <th>Buyer Name</th>
                                <th>PS / PO / CAP</th>
                                <th>Deleted By</th>
                                <th>Deleted At</th>
                                <th class="text-end print-none" style="width:130px;">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($complains as $complain)
                                @php
                                    // attachments
                                    $files = $complain->files_data ?? [];
                                    $videos = $complain->videos_data ?? [];

                                    $images = array_filter($files, fn($f) => $f['is_image'] ?? false);
                                    $documents = array_filter($files, fn($f) => !($f['is_image'] ?? false));

                                    $imageCount = count($images);
                                    $documentCount = count($documents);
                                    $videoCount = count($videos);
                                    $totalAttachments = $imageCount + $documentCount + $videoCount;

                                    // type color (auto rotating)
                                    $palette = ['primary', 'success', 'info', 'warning', 'danger'];
                                    $type = $complain->complainType;
                                    $typeColor = $type ? $palette[$type->id % count($palette)] : 'secondary';

                                    // PS / PO / CAP
                                    $details = [];
                                    if ($complain->ps) {
                                        $details[] = "PS: {$complain->ps}";
                                    }
                                    if ($complain->po) {
                                        $details[] = "PO: {$complain->po}";
                                    }
                                    if ($complain->cap) {
                                        $details[] = "CAP: {$complain->cap}";
                                    }

                                    $shortComplain = Str::limit(strip_tags($complain->complain ?? ''), 50);
                                @endphp

                                <tr>
                                    <td class="fw-bold text-center">
                                        {{ $complains->firstItem() + $loop->index }}
                                    </td>

                                    <td>
                                        {{ $complain->date ? \Carbon\Carbon::parse($complain->date)->format('d M Y') : '—' }}
                                    </td>

                                    <td class="p-1">
                                        <div class="d-flex align-items-center gap-2">
                                            <!-- Type Badge - Fixed Colors, Bold -->
                                            @if ($complain->type == 'complain')
                                                <span class="badge bg-primary fw-bold px-2 py-1">
                                                    <i class="bi bi-chat-left-text me-1"></i>COMPLAIN
                                                </span>
                                            @else
                                                <span class="badge bg-success fw-bold px-2 py-1">
                                                    <i class="bi bi-journal-text me-1"></i>MANUAL
                                                </span>
                                            @endif

                                            <!-- C/M Type - Light -->
                                            @if ($complain->complainType)
                                                <span
                                                    class="badge bg-light text-dark border fw-normal px-2 py-1 fw-bold">
                                                    {{ $complain->complainType->name }}
                                                </span>
                                            @else
                                                <span
                                                    class="badge bg-light text-muted border fw-normal px-2 py-1 fw-bold">
                                                    —
                                                </span>
                                            @endif
                                        </div>
                                    </td>

                                    {{-- CATEGORY --}}
                                    <td>
                                        @if ($complain->category)
                                            <span class="badge bg-light text-dark border fw-normal">
                                                {{ $complain->category->name }}
                                            </span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>

                                    {{-- NAME --}}
                                    <td>{{ $complain->buyer?->company_name ?? '--' }} <strong>-
                                            {{ $complain->buyer?->country ?? '--' }}</strong></td>

                                    {{-- PS / PO / CAP --}}
                                    <td>
                                        <small class="text-muted">
                                            {{ implode(' | ', $details) ?: '—' }}
                                        </small>
                                    </td>

                                    {{-- DETAILS --}}
                                    {{-- <td>
                                        <small>{{ $shortComplain ?: '—' }}</small>
                                    </td> --}}

                                    {{-- DELETED AT --}}
                                    <td>
                                        {{$complain->deletedBy?->name ?? '—'}}
                                    </td>
                                    {{-- DELETED AT --}}
                                    <td>
                                        {{ $complain->deleted_at ? $complain->deleted_at->format('d M Y h:i A') : '—' }}
                                    </td>

                                    {{-- ACTIONS --}}
                                    <td class="text-end print-none">
                                        <form action="{{ route('complain.restore', $complain->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn sm btn-primary" title="Restore"
                                                onclick="return confirm('Restore this entry?')">
                                                <i class="bi bi-arrow-clockwise"></i>
                                            </button>
                                        </form>

                                        <form action="{{ route('complain.permanentDelete', $complain->id) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn sm btn-danger" title="Delete Permanently"
                                                onclick="return confirm('PERMANENTLY delete? This cannot be undone.')">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                            @empty
                                <tr>
                                    <td colspan="11" class="text-center text-muted py-4">
                                        <i class="bi bi-inbox me-1"></i> No Entries Found in Trash
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    <!-- Start pagination -->
    @if ($complains->hasPages())
        <div class="widget">
            <div class="widget-body">
                {{ $complains->links() }}
            </div>
        </div>
    @endif
    <!-- End pagination -->
</x-app-layout>
