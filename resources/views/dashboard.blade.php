@section('title', __('Dashboard'))

<x-app-layout>

{{-- ═══ STAT CARDS ═══ --}}
<div class="row g-3 mb-4">

    {{-- Submitted --}}
    <div class="col-lg-3 col-6">
        <div class="small-box" style="background: linear-gradient(135deg,#4361ee,#3a0ca3); color:#fff; border-radius:14px; overflow:hidden; box-shadow:0 6px 20px rgba(67,97,238,.35);">
            <div class="inner" style="color:#fff !important;">
                <h3 style="color:#fff !important; font-size:2.2rem; font-weight:800;">{{ $stats['submitted'] }}</h3>
                <p style="color:rgba(255,255,255,.85) !important; font-size:.9rem; font-weight:600;">{{ __('New Submitted') }}</p>
            </div>
            <i class="small-box-icon bi bi-inbox-fill" style="color:rgba(255,255,255,.18);"></i>
            <a href="{{ route('admin.grievance.index', ['status' => 'submitted']) }}"
               style="background:rgba(0,0,0,.15); color:#fff; display:block; padding:6px 14px; font-size:.8rem; text-decoration:none;">
                {{ __('View all') }} &nbsp;<i class="bi bi-arrow-right-short"></i>
            </a>
        </div>
    </div>

    {{-- Under Review --}}
    <div class="col-lg-3 col-6">
        <div class="small-box" style="background: linear-gradient(135deg,#f77f00,#d62828); color:#fff; border-radius:14px; overflow:hidden; box-shadow:0 6px 20px rgba(247,127,0,.35);">
            <div class="inner" style="color:#fff !important;">
                <h3 style="color:#fff !important; font-size:2.2rem; font-weight:800;">{{ $stats['under_review'] }}</h3>
                <p style="color:rgba(255,255,255,.85) !important; font-size:.9rem; font-weight:600;">{{ __('Under Review') }}</p>
            </div>
            <i class="small-box-icon bi bi-eye-fill" style="color:rgba(255,255,255,.18);"></i>
            <a href="{{ route('admin.grievance.index', ['status' => 'under_review']) }}"
               style="background:rgba(0,0,0,.15); color:#fff; display:block; padding:6px 14px; font-size:.8rem; text-decoration:none;">
                {{ __('View all') }} &nbsp;<i class="bi bi-arrow-right-short"></i>
            </a>
        </div>
    </div>

    {{-- In Resolution --}}
    <div class="col-lg-3 col-6">
        <div class="small-box" style="background: linear-gradient(135deg,#e040fb,#7c4dff); color:#fff; border-radius:14px; overflow:hidden; box-shadow:0 6px 20px rgba(224,64,251,.30);">
            <div class="inner" style="color:#fff !important;">
                <h3 style="color:#fff !important; font-size:2.2rem; font-weight:800;">{{ $stats['in_resolution'] }}</h3>
                <p style="color:rgba(255,255,255,.85) !important; font-size:.9rem; font-weight:600;">{{ __('In Resolution') }}</p>
            </div>
            <i class="small-box-icon bi bi-tools" style="color:rgba(255,255,255,.18);"></i>
            <a href="{{ route('admin.grievance.index', ['status' => 'in_resolution']) }}"
               style="background:rgba(0,0,0,.15); color:#fff; display:block; padding:6px 14px; font-size:.8rem; text-decoration:none;">
                {{ __('View all') }} &nbsp;<i class="bi bi-arrow-right-short"></i>
            </a>
        </div>
    </div>

    {{-- Resolved --}}
    <div class="col-lg-3 col-6">
        <div class="small-box" style="background: linear-gradient(135deg,#06d6a0,#118ab2); color:#fff; border-radius:14px; overflow:hidden; box-shadow:0 6px 20px rgba(6,214,160,.35);">
            <div class="inner" style="color:#fff !important;">
                <h3 style="color:#fff !important; font-size:2.2rem; font-weight:800;">{{ $stats['resolved'] }}</h3>
                <p style="color:rgba(255,255,255,.85) !important; font-size:.9rem; font-weight:600;">{{ __('Resolved') }}</p>
            </div>
            <i class="small-box-icon bi bi-check-circle-fill" style="color:rgba(255,255,255,.18);"></i>
            <a href="{{ route('admin.grievance.index', ['status' => 'resolved']) }}"
               style="background:rgba(0,0,0,.15); color:#fff; display:block; padding:6px 14px; font-size:.8rem; text-decoration:none;">
                {{ __('View all') }} &nbsp;<i class="bi bi-arrow-right-short"></i>
            </a>
        </div>
    </div>

</div>

{{-- ═══ CHARTS + TABLE ROW ═══ --}}
<div class="row g-3 mb-4">

    {{-- Category Doughnut Chart --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100" style="border-radius:14px;">
            <div class="card-header bg-transparent border-bottom d-flex align-items-center py-3">
                <i class="bi bi-tags-fill text-primary me-2"></i>
                <h6 class="mb-0 fw-bold">{{ __('Category Distribution') }}</h6>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center" style="min-height:270px;">
                @if(count($categoryStats) > 0 && $stats['total'] > 0)
                    <canvas id="categoryChart" style="max-height:240px;"></canvas>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-pie-chart" style="font-size:2rem; opacity:.3;"></i>
                        <p class="mt-2 small">{{ __('No data yet') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Department Horizontal Bar Chart --}}
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm h-100" style="border-radius:14px;">
            <div class="card-header bg-transparent border-bottom d-flex align-items-center py-3">
                <i class="bi bi-building-fill text-success me-2"></i>
                <h6 class="mb-0 fw-bold">{{ __('Department Distribution') }}</h6>
            </div>
            <div class="card-body" style="min-height:270px;">
                @if(count($departmentStats) > 0 && $stats['total'] > 0)
                    <canvas id="departmentChart"></canvas>
                @else
                    <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                        <div class="text-center">
                            <i class="bi bi-bar-chart" style="font-size:2rem; opacity:.3;"></i>
                            <p class="mt-2 small">{{ __('No data yet') }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Summary Totals --}}
    <div class="col-lg-3">
        <div class="card border-0 shadow-sm h-100" style="border-radius:14px;">
            <div class="card-header bg-transparent border-bottom d-flex align-items-center py-3">
                <i class="bi bi-bar-chart-line-fill text-warning me-2"></i>
                <h6 class="mb-0 fw-bold">{{ __('Summary') }}</h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <span class="small fw-semibold text-muted">{{ __('Total Grievances') }}</span>
                        <span class="badge rounded-pill" style="background:#4361ee; color:#fff; font-size:.85rem; padding:5px 12px;">{{ $stats['total'] }}</span>
                    </li>
                    <li class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <span class="small fw-semibold text-muted">{{ __("Today's") }}</span>
                        <span class="badge rounded-pill bg-secondary" style="font-size:.85rem; padding:5px 12px;">{{ $stats['today_total'] }}</span>
                    </li>
                    <li class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <span class="small fw-semibold text-muted">{{ __('Today Resolved') }}</span>
                        <span class="badge rounded-pill" style="background:#06d6a0; color:#fff; font-size:.85rem; padding:5px 12px;">{{ $todayResolved }}</span>
                    </li>
                    <li class="d-flex justify-content-between align-items-center py-2">
                        <span class="small fw-semibold text-muted">{{ __("Today's Growth") }}</span>
                        <span class="badge rounded-pill {{ $todayGrowth >= 0 ? 'bg-success' : 'bg-danger' }}" style="font-size:.85rem; padding:5px 12px;">
                            {{ $todayGrowth >= 0 ? '+' : '' }}{{ $todayGrowth }}%
                        </span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

</div>

{{-- ═══ RECENT GRIEVANCES TABLE ═══ --}}
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm" style="border-radius:14px;">
            <div class="card-header bg-transparent border-bottom d-flex align-items-center justify-content-between py-3">
                <div class="d-flex align-items-center">
                    <i class="bi bi-clock-history text-primary me-2"></i>
                    <h6 class="mb-0 fw-bold">{{ __('Recent Grievances') }}</h6>
                </div>
                <a href="{{ route('admin.grievance.index') }}" class="btn btn-sm btn-outline-primary" style="border-radius:8px; font-size:.8rem;">
                    {{ __('View All') }} <i class="bi bi-arrow-right-short"></i>
                </a>
            </div>
            <div class="card-body p-0 table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">{{ __('Ticket #') }}</th>
                            <th>{{ __('Category') }}</th>
                            <th>{{ __('Department') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Date') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentGrievances as $g)
                            <tr>
                                <td class="ps-4">
                                    <a href="{{ route('admin.grievance.show', $g->id) }}" class="fw-bold text-decoration-none text-primary">{{ $g->ticket_number }}</a>
                                </td>
                                <td class="small">{{ $g->category->name ?? '—' }}</td>
                                <td class="small text-muted">{{ $g->department->name ?? '—' }}</td>
                                <td>
                                    <span class="badge rounded-pill bg-{{ $g->status_badge }}" style="font-size:.75rem; padding:4px 10px;">{{ $g->status_label }}</span>
                                </td>
                                <td>
                                    <small class="text-muted">{{ $g->created_at->format('d M Y') }}</small>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">{{ __('No grievances recorded yet.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ─── Category Doughnut ───
    @if(count($categoryStats) > 0 && $stats['total'] > 0)
    const catLabels = @json(array_column($categoryStats, 'name'));
    const catData   = @json(array_column($categoryStats, 'count'));
    const catColors = [
        '#4361ee','#f77f00','#06d6a0','#7209b7','#d62828',
        '#118ab2','#e9c46a','#2a9d8f','#e76f51','#457b9d'
    ];
    new Chart(document.getElementById('categoryChart'), {
        type: 'doughnut',
        data: {
            labels: catLabels,
            datasets: [{
                data: catData,
                backgroundColor: catColors,
                borderWidth: 2,
                borderColor: '#fff',
                hoverOffset: 8,
            }]
        },
        options: {
            cutout: '62%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        font: { size: 11 },
                        boxWidth: 12,
                        padding: 12,
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(ctx) {
                            const total = ctx.dataset.data.reduce((a,b) => a+b, 0);
                            const pct   = total > 0 ? Math.round(ctx.raw / total * 100) : 0;
                            return ` ${ctx.label}: ${ctx.raw} (${pct}%)`;
                        }
                    }
                }
            }
        }
    });
    @endif

    // ─── Department Horizontal Bar ───
    @if(count($departmentStats) > 0 && $stats['total'] > 0)
    const deptLabels = @json(array_column($departmentStats, 'name'));
    const deptData   = @json(array_column($departmentStats, 'count'));
    const barColors  = [
        'rgba(6,214,160,.8)','rgba(17,138,178,.8)','rgba(67,97,238,.8)',
        'rgba(247,127,0,.8)','rgba(114,9,183,.8)','rgba(214,40,40,.8)',
        'rgba(42,157,143,.8)','rgba(233,196,106,.8)','rgba(231,111,81,.8)',
    ];
    new Chart(document.getElementById('departmentChart'), {
        type: 'bar',
        data: {
            labels: deptLabels,
            datasets: [{
                label: 'Grievances',
                data: deptData,
                backgroundColor: barColors,
                borderRadius: 6,
                borderSkipped: false,
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(ctx) {
                            return ` ${ctx.raw} ticket${ctx.raw !== 1 ? 's' : ''}`;
                        }
                    }
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: { precision: 0, font: { size: 11 } },
                    grid: { color: 'rgba(0,0,0,.06)' }
                },
                y: {
                    ticks: { font: { size: 11 } },
                    grid: { display: false }
                }
            }
        }
    });
    @endif

});
</script>
@endpush

</x-app-layout>
