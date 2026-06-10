@section('title', 'Overall Complain Report')

<x-app-layout>
    <!-- Header with Menu (Left) and Icons (Right) -->
    <div class="widget mb-3">
        <div class="widget-body d-flex justify-content-between align-items-center">
            <!-- Left Side - Reports Menu -->
            <div class="d-flex align-items-center">
                @include('pages.reports.menu')
            </div>

            <!-- Right Side - Icons -->
            <div class="d-flex gap-2">
                <!-- Excel Export Icon -->
                @if (request()->has('search') && $complains->count() > 0)
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

                <!-- Search Icon -->
                <button type="button" class="btn icon lg rounded collapsed" title="Search" data-bs-toggle="collapse"
                    data-bs-target="#tableSearch" aria-expanded="false">
                    <i class="bi bi-search"></i>
                </button>

                <!-- Print Icon -->
                <button type="button" class="btn icon lg rounded" title="Print" onclick="printable('print-widget')">
                    <i class="bi bi-printer"></i>
                </button>

                <!-- Reload Icon -->
                <button type="button" class="btn icon lg rounded" title="Reload" onclick="location.reload()">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>

                <!-- Back Icon -->
                <button type="button" class="btn icon lg rounded" title="Go back" onclick="history.back()">
                    <i class="bi bi-arrow-left"></i>
                </button>
            </div>
        </div>

        <!-- Filter Area - Initially Closed, Opens on Search Icon Click -->
        <div class="widget-body collapse p-1" id="tableSearch">
            <form method="GET" action="{{ route('reports.overall-report') }}">
                <div class="row g-1">
                    <input type="hidden" name="search" value="1">

                    <!-- Type Filter -->
                    <div class="col-md-2">
                        <label class="form-label mb-0 small">Type</label>
                        <select name="type" class="form-select">
                            <option value="">All Types</option>
                            <option value="complain" {{ request('type') == 'complain' ? 'selected' : '' }}>Complaint
                            </option>
                            <option value="manual" {{ request('type') == 'manual' ? 'selected' : '' }}>Manual</option>
                        </select>
                    </div>

                    <!-- Date From -->
                    <div class="col-md-2">
                        <label class="form-label mb-0 small">Date From</label>
                        <input type="date" name="date_from" class="form-control" value="{{ request()->date_from }}">
                    </div>

                    <!-- Date To -->
                    <div class="col-md-2">
                        <label class="form-label mb-0 small">Date To</label>
                        <input type="date" name="date_to" class="form-control" value="{{ request()->date_to }}">
                    </div>

                    <!-- Year Filter -->
                    <div class="col-md-2">
                        <label class="form-label mb-0 small">Year</label>
                        <select class="form-select" name="year">
                            <option value="">All Years</option>
                            @for ($year = date('Y'); $year >= 2020; $year--)
                                <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <!-- Month Filter -->
                    <div class="col-md-2">
                        <label class="form-label mb-0 small">Month</label>
                        <select class="form-select" name="month">
                            <option value="">All Months</option>
                            @for ($month = 1; $month <= 12; $month++)
                                <option value="{{ $month }}"
                                    {{ request('month') == $month ? 'selected' : '' }}>
                                    {{ date('F', mktime(0, 0, 0, $month, 1)) }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <!-- Buyer -->
                    <div class="col-md-2">
                        <label class="form-label mb-0 small">Buyer</label>
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

                    <!-- PS -->
                    <div class="col-md-2">
                        <label class="form-label mb-0 small">PS</label>
                        <input name="ps" class="form-control" placeholder="Type PS" value="{{ request()->ps }}">
                    </div>

                    <!-- PO -->
                    <div class="col-md-2">
                        <label class="form-label mb-0 small">PO</label>
                        <input name="po" class="form-control" placeholder="Type PO" value="{{ request()->po }}">
                    </div>

                    <!-- CAP -->
                    <div class="col-md-2">
                        <label class="form-label mb-0 small">CAP</label>
                        <input name="cap" class="form-control" placeholder="Type CAP"
                            value="{{ request()->cap }}">
                    </div>

                    <!-- Complain Type -->
                    <div class="col-md-2">
                        <label class="form-label mb-0 small">Complain Type</label>
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

                    <!-- Category -->
                    <div class="col-md-2">
                        <label class="form-label mb-0 small">Category</label>
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

                    <!-- Status -->
                    <div class="col-md-2">
                        <label class="form-label mb-0 small">Status</label>
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

                    <!-- Search Button -->
                    <div class="col-md-2">
                        <label class="form-label mb-0 small">&nbsp;</label>
                        <button type="submit" class="btn btn-success d-block w-100">
                            <i class="bi bi-search"></i> Search
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Printable Section -->
    <div id="print-widget">
        <x-print.header />

        @if (request()->has('search'))
            @if ($complains->count() > 0)
                <!-- Report Summary Cards -->
                <div class="widget mb-3">
                    <div class="widget-head mb-3">
                        <h5 class="mb-0">
                            <i class="bi bi-bar-chart-line text-primary me-2"></i>
                            Report Summary
                        </h5>
                    </div>

                    <div class="widget-body">
                        <div class="row g-2">
                            <!-- Total Records -->
                            <div class="col-md-3 col-sm-6">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body p-2">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary text-white rounded-circle p-2 me-2">
                                                <i class="bi bi-exclamation-triangle"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-bold">{{ $summaryStats['total'] }}</h6>
                                                <small class="text-muted">Total Records</small>
                                                <div>
                                                    <small>
                                                        <span class="badge bg-danger">C:
                                                            {{ $summaryStats['complains_count'] }}</span>
                                                        <span class="badge bg-info">M:
                                                            {{ $summaryStats['manuals_count'] }}</span>
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Pending -->
                            <div class="col-md-3 col-sm-6">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body p-2">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-warning text-white rounded-circle p-2 me-2">
                                                <i class="bi bi-clock-history"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-bold">{{ $summaryStats['pending'] }}</h6>
                                                <small class="text-muted">Pending</small>
                                                <div>
                                                    <small
                                                        class="text-muted">{{ $summaryStats['pending_percentage'] }}%</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- In Progress -->
                            <div class="col-md-3 col-sm-6">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body p-2">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-info text-white rounded-circle p-2 me-2">
                                                <i class="bi bi-gear-wide-connected"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-bold">{{ $summaryStats['in_progress'] }}</h6>
                                                <small class="text-muted">In Progress</small>
                                                <div>
                                                    <small
                                                        class="text-muted">{{ $summaryStats['in_progress_percentage'] }}%</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Resolved -->
                            <div class="col-md-3 col-sm-6">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body p-2">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-success text-white rounded-circle p-2 me-2">
                                                <i class="bi bi-check2-circle"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-bold">{{ $summaryStats['resolved'] }}</h6>
                                                <small class="text-muted">Resolved</small>
                                                <div>
                                                    <small
                                                        class="text-muted">{{ $summaryStats['resolved_percentage'] }}%</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detailed Report Table - Index Style (without Actions and Attachments) -->
                <div class="widget">
                    <div class="widget-head mb-3">
                        <h5 class="mb-0">
                            <i class="bi bi-table text-dark me-2"></i>
                            Detailed Complain Report
                        </h5>
                        <p class="mb-0 text-muted small">
                            Showing {{ $complains->count() }} record(s)
                        </p>
                    </div>

                    <div class="widget-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th width="50" class="p-1">SL</th>
                                        <th class="p-1">Date</th>
                                        <th class="p-1">Complain/Manual Type</th>
                                        <th class="p-1">Category</th>
                                        <th class="p-1">Buyer Name</th>
                                        <th class="p-1">PS/PO/CAP</th>
                                        <th class="p-1">Qty/Amount/Total</th>
                                        <th class="p-1 print-none">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($complains as $index => $complain)
                                        @php
                                            $qty = (float) ($complain->quantity ?? 0);
                                            $amount = (float) ($complain->amount ?? 0);
                                            $total = $qty * $amount;
                                        @endphp

                                        <tr class="clickable-row" data-href="{{ route('complain.show', $complain->id) }}">
                                            <td class="text-center fw-bold p-1">
                                                {{ $index + 1 }}
                                            </td>

                                            <td class="p-1">
                                                {{ $complain->date ? \Carbon\Carbon::parse($complain->date)->format('d M Y') : 'N/A' }}
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

                                            <td class="p-1">
                                                @if ($complain->category)
                                                    <span class="badge bg-light text-dark border fw-normal">
                                                        {{ $complain->category->name }}
                                                    </span>
                                                @elseif ($complain->manual_category)
                                                    <span class="badge bg-light text-dark border fw-normal">
                                                        {{ $complain->manual_category }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>

                                            <td class="p-1">
                                                <div class="fw-semibold">{{ $complain->buyer?->company_name ?? '--' }} -
                                                    <strong class="text-info">{{ $complain->buyer?->country ?? '--' }}</strong>
                                                </div>
                                            </td>

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

                                            <td class="p-1">
                                                @if ($qty > 0 || $amount > 0)
                                                    <div class="d-flex flex-wrap gap-2 align-items-center">
                                                        @if ($qty > 0)
                                                            <span><span class="text-secondary">Q:</span> <strong>{{ number_format($qty) }}</strong></span>
                                                        @endif
                                                        @if ($amount > 0)
                                                            <span><span class="text-secondary">A:</span> <strong>{{ number_format($amount, 2) }}</strong></span>
                                                        @endif
                                                        @if ($total > 0)
                                                            <span><span class="text-secondary">T:</span> <strong>{{ number_format($total, 2) }}</strong></span>
                                                        @endif
                                                    </div>
                                                @else
                                                    <span class="text-muted small">—</span>
                                                @endif
                                            </td>

                                            <td class="text-center p-1 print-none">
                                                @if ($complain->type === 'complain')
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
                                                    @endphp

                                                    <span class="badge bg-{{ $statusColors[$complain->status] ?? 'secondary' }}">
                                                        <i class="bi {{ $statusIcon[$complain->status] ?? 'bi-question-circle' }} me-1"></i>
                                                        {{ ucfirst($complain->status) }}
                                                    </span>
                                                @else
                                                    --
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                {{-- @if ($complains->count() > 0)
                                    <tfoot class="table-light">
                                        <tr>
                                            <td colspan="6" class="text-end fw-bold p-1">GRAND TOTAL:</td>
                                            <td class="p-1 fw-bold">
                                                Q: {{ number_format($summaryStats['total_quantity'], 0) }}<br>
                                                T: {{ number_format($summaryStats['total_calculated_amount'], 2) }}
                                            </td>
                                        </tr>
                                    </tfoot>
                                @endif --}}
                            </table>
                        </div>
                    </div>
                </div>
            @else
                <!-- No Data Message -->
                <div class="widget">
                    <div class="widget-body text-center py-5">
                        <i class="bi bi-clipboard-data display-1 text-muted mb-3"></i>
                        <h5 class="text-muted">No Data Available</h5>
                        <p class="mb-2">No complains found for the selected criteria.</p>
                        <button class="btn btn-primary btn-sm" data-bs-toggle="collapse"
                            data-bs-target="#tableSearch">
                            <i class="bi bi-search me-1"></i> Apply Filters
                        </button>
                    </div>
                </div>
            @endif
        @else
            <!-- No Search Performed -->
            <div class="widget">
                <div class="widget-body text-center py-5">
                    <i class="bi bi-search display-1 text-muted mb-3"></i>
                    <h5 class="text-muted">Click Search Icon to Filter Report</h5>
                    <p class="mb-2">Use the search icon above to filter and generate report.</p>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="collapse" data-bs-target="#tableSearch">
                        <i class="bi bi-funnel me-1"></i> Show Filters
                    </button>
                </div>
            </div>
        @endif
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

        .card {
            border-radius: 8px;
        }

        .bg-primary {
            background-color: #0d6efd !important;
        }

        .bg-warning {
            background-color: #ffc107 !important;
        }

        .bg-info {
            background-color: #0dcaf0 !important;
        }

        .bg-success {
            background-color: #198754 !important;
        }

        .clickable-row {
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .clickable-row:hover {
            background-color: rgba(0, 0, 0, 0.03);
        }

        @media print {
            .btn,
            .dropdown,
            .collapse,
            .no-print {
                display: none !important;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const rows = document.querySelectorAll('.clickable-row');

            rows.forEach(row => {
                row.addEventListener('click', function(e) {
                    const href = this.dataset.href;
                    if (href) {
                        window.location.href = href;
                    }
                });
            });
        });
    </script>
</x-app-layout>
