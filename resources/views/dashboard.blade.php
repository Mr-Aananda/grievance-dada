@section('title', 'Dashboard - Complain Management')

<x-app-layout>
    <div class="container-fluid py-1 px-2 px-lg-3" x-data="dashboard()" x-init="init()">
        <!-- Header with Date/Time on Right -->
        <div
            class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-2 pb-1 border-bottom">
            <!-- Left Section: Welcome -->
            <div>
                <h2 class="fw-bold text-dark mb-0">QMS Dashboard</h2>
                <p class="text-muted mb-0">Welcome back, <span
                        class="text-primary fw-bold">{{ auth()->user()->name }}</span></p>
            </div>

            <!-- Right Section: Date & Time -->
            <div class="mt-2 mt-md-0">
                <div class="d-flex align-items-center bg-light rounded-3 px-3 py-1">
                    <i class="bi bi-calendar3 text-primary me-2 fs-5"></i>
                    <div class="text-end">
                        <small class="text-muted d-block lh-1">{{ \Carbon\Carbon::now()->format('l') }}</small>
                        <span class="fw-bold">{{ \Carbon\Carbon::now()->format('d M, Y - h:i A') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Dashboard Content - Monthly & Yearly Side by Side -->
        <div class="row g-3">
            <!-- LEFT SIDE - MONTHLY STATISTICS -->
            <div class="col-lg-6">
                <div class="d-flex align-items-center justify-content-between mb-1">
                    <h4 class="fw-bold text-primary mb-0 fs-4"><i class="bi bi-calendar-month me-2"></i>Monthly
                        Statistics</h4>
                    <div>
                        <input type="month" class="form-control form-control-sm bg-light border-0"
                            style="width: 160px;" x-model="monthFilter" @change="applyMonthlyFilter()">
                    </div>
                </div>

                <!-- Monthly Complain -->
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-white border-0 py-2">
                        <h5 class="fw-bold mb-0 fs-5"><i class="bi bi-chat-dots-fill text-primary me-2"></i>Complain -
                            Monthly</h5>
                    </div>
                    <div class="card-body pt-0 pb-2">
                        <div class="bg-light bg-opacity-50 rounded-3 p-2">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold text-primary fs-5">Total: <span class="fs-4"
                                        x-text="monthlyComplainTotal">0</span></span>
                                <small class="text-muted fs-6" x-text="monthFilterDisplay"></small>
                            </div>

                            <div class="row g-1">
                                @foreach ($complainTypeItems as $index => $type)
                                    @php
                                        $typeName = strtolower(str_replace(' ', '_', $type->name));
                                        $colors = [
                                            'primary',
                                            'warning',
                                            'info',
                                            'success',
                                            'danger',
                                            'secondary',
                                            'dark',
                                        ];
                                        $color = $colors[$index % count($colors)];
                                        $isClaimType = $type->name == 'Claim';
                                    @endphp
                                    <div class="col-xl-3 col-lg-4 col-md-6 col-6">
                                        <div class="border rounded-3 p-2 bg-white text-center shadow-sm">
                                            <div class="display-4 fw-bolder text-{{ $color }} lh-1 mb-1"
                                                x-text="getMonthlyComplainCount('{{ $typeName }}')">0</div>
                                            <div class="fw-bold text-dark fs-6 mb-1">{{ $type->name }}</div>

                                            <!-- Qty and TA Section - Only shows for Claim type and only if data exists -->
                                            @if ($isClaimType)
                                                <template
                                                    x-if="getMonthlyComplainQty('{{ $typeName }}') > 0 || getMonthlyComplainAmount('{{ $typeName }}') > 0">
                                                    <div
                                                        class="mt-1 pt-1 border-top d-flex justify-content-center gap-2 small fw-medium">
                                                        <template
                                                            x-if="getMonthlyComplainQty('{{ $typeName }}') > 0">
                                                            <span class="text-secondary"><span class="fw-bold">Q:</span>
                                                                <span class="fw-bold"
                                                                    x-text="getMonthlyComplainQty('{{ $typeName }}')"></span></span>
                                                        </template>
                                                        <template
                                                            x-if="getMonthlyComplainAmount('{{ $typeName }}') > 0">
                                                            <span class="text-success"><span class="fw-bold">TA:</span>
                                                                <span class="fw-bold"
                                                                    x-text="formatNumber(getMonthlyComplainAmount('{{ $typeName }}'))"></span></span>
                                                        </template>
                                                    </div>
                                                </template>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Monthly Manual -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-2">
                        <h5 class="fw-bold mb-0 fs-5"><i
                                class="bi bi-file-earmark-text-fill text-primary me-2"></i>Manual - Monthly</h5>
                    </div>
                    <div class="card-body pt-0 pb-2">
                        <div class="bg-light bg-opacity-50 rounded-3 p-2">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold text-primary fs-5">Total: <span class="fs-4"
                                        x-text="monthlyManualTotal">0</span></span>
                                <small class="text-muted fs-6" x-text="monthFilterDisplay"></small>
                            </div>

                            <div class="row g-1">
                                @foreach ($manualTypeItems as $index => $type)
                                    @php
                                        $typeName = strtolower(str_replace(' ', '_', $type->name));
                                        $colors = [
                                            'danger',
                                            'success',
                                            'primary',
                                            'warning',
                                            'secondary',
                                            'info',
                                            'dark',
                                        ];
                                        $color = $colors[$index % count($colors)];
                                        $isClaimType = $type->name == 'Claim';
                                    @endphp
                                    <div class="col-xl-3 col-lg-4 col-md-6 col-6">
                                        <div class="border rounded-3 p-2 bg-white text-center shadow-sm">
                                            <div class="display-4 fw-bolder text-{{ $color }} lh-1 mb-1"
                                                x-text="getMonthlyManualCount('{{ $typeName }}')">0</div>
                                            <div class="fw-bold text-dark fs-6 mb-1">{{ $type->name }}</div>

                                            <!-- Qty and TA Section - Only shows for Claim type and only if data exists -->
                                            @if ($isClaimType)
                                                <template
                                                    x-if="getMonthlyManualQty('{{ $typeName }}') > 0 || getMonthlyManualAmount('{{ $typeName }}') > 0">
                                                    <div
                                                        class="mt-1 pt-1 border-top d-flex justify-content-center gap-2 small fw-medium">
                                                        <template
                                                            x-if="getMonthlyManualQty('{{ $typeName }}') > 0">
                                                            <span class="text-secondary"><span class="fw-bold">Q:</span>
                                                                <span class="fw-bold"
                                                                    x-text="getMonthlyManualQty('{{ $typeName }}')"></span></span>
                                                        </template>
                                                        <template
                                                            x-if="getMonthlyManualAmount('{{ $typeName }}') > 0">
                                                            <span class="text-success"><span class="fw-bold">TA:</span>
                                                                <span class="fw-bold"
                                                                    x-text="formatNumber(getMonthlyManualAmount('{{ $typeName }}'))"></span></span>
                                                        </template>
                                                    </div>
                                                </template>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT SIDE - YEARLY STATISTICS -->
            <div class="col-lg-6">
                <div class="d-flex align-items-center justify-content-between mb-1">
                    <h4 class="fw-bold text-success mb-0 fs-4"><i class="bi bi-calendar4 me-2"></i>Yearly Statistics
                    </h4>
                    <div class="d-flex gap-1">
                        <input type="number" class="form-control form-control-sm bg-light border-0"
                            style="width: 80px;" x-model="yearFilter" min="2000" max="2100" placeholder="YYYY"
                            @keyup.enter="applyYearlyFilter()">
                        <button class="btn btn-sm btn-success" type="button" @click="applyYearlyFilter()">
                            <i class="bi bi-search"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-secondary" type="button" @click="resetYearlyFilter()">
                            <i class="bi bi-arrow-clockwise"></i>
                        </button>
                    </div>
                </div>

                <!-- Yearly Complain -->
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-white border-0 py-2">
                        <h5 class="fw-bold mb-0 fs-5"><i class="bi bi-chat-dots-fill text-success me-2"></i>Complain -
                            Yearly</h5>
                    </div>
                    <div class="card-body pt-0 pb-2">
                        <div class="bg-light bg-opacity-50 rounded-3 p-2">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold text-success fs-5">Total: <span class="fs-4"
                                        x-text="yearlyComplainTotal">0</span></span>
                                <small class="text-muted fs-6" x-text="yearFilterDisplay"></small>
                            </div>

                            <div class="row g-1">
                                @foreach ($complainTypeItems as $index => $type)
                                    @php
                                        $typeName = strtolower(str_replace(' ', '_', $type->name));
                                        $colors = [
                                            'primary',
                                            'warning',
                                            'info',
                                            'success',
                                            'danger',
                                            'secondary',
                                            'dark',
                                        ];
                                        $color = $colors[$index % count($colors)];
                                        $isClaimType = $type->name == 'Claim';
                                    @endphp
                                    <div class="col-xl-3 col-lg-4 col-md-6 col-6">
                                        <div class="border rounded-3 p-2 bg-white text-center shadow-sm">
                                            <div class="display-4 fw-bolder text-{{ $color }} lh-1 mb-1"
                                                x-text="getYearlyComplainCount('{{ $typeName }}')">0</div>
                                            <div class="fw-bold text-dark fs-6 mb-1">{{ $type->name }}</div>

                                            <!-- Qty and TA Section - Only shows for Claim type and only if data exists -->
                                            @if ($isClaimType)
                                                <template
                                                    x-if="getYearlyComplainQty('{{ $typeName }}') > 0 || getYearlyComplainAmount('{{ $typeName }}') > 0">
                                                    <div
                                                        class="mt-1 pt-1 border-top d-flex justify-content-center gap-2 small fw-medium">
                                                        <template
                                                            x-if="getYearlyComplainQty('{{ $typeName }}') > 0">
                                                            <span class="text-secondary"><span
                                                                    class="fw-bold">Q:</span> <span class="fw-bold"
                                                                    x-text="getYearlyComplainQty('{{ $typeName }}')"></span></span>
                                                        </template>
                                                        <template
                                                            x-if="getYearlyComplainAmount('{{ $typeName }}') > 0">
                                                            <span class="text-success"><span
                                                                    class="fw-bold">TA:</span> <span class="fw-bold"
                                                                    x-text="formatNumber(getYearlyComplainAmount('{{ $typeName }}'))"></span></span>
                                                        </template>
                                                    </div>
                                                </template>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Yearly Manual -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-2">
                        <h5 class="fw-bold mb-0 fs-5"><i
                                class="bi bi-file-earmark-text-fill text-success me-2"></i>Manual - Yearly</h5>
                    </div>
                    <div class="card-body pt-0 pb-2">
                        <div class="bg-light bg-opacity-50 rounded-3 p-2">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold text-success fs-5">Total: <span class="fs-4"
                                        x-text="yearlyManualTotal">0</span></span>
                                <small class="text-muted fs-6" x-text="yearFilterDisplay"></small>
                            </div>

                            <div class="row g-1">
                                @foreach ($manualTypeItems as $index => $type)
                                    @php
                                        $typeName = strtolower(str_replace(' ', '_', $type->name));
                                        $colors = [
                                            'danger',
                                            'success',
                                            'primary',
                                            'warning',
                                            'secondary',
                                            'info',
                                            'dark',
                                        ];
                                        $color = $colors[$index % count($colors)];
                                        $isClaimType = $type->name == 'Claim';
                                    @endphp
                                    <div class="col-xl-3 col-lg-4 col-md-6 col-6">
                                        <div class="border rounded-3 p-2 bg-white text-center shadow-sm">
                                            <div class="display-4 fw-bolder text-{{ $color }} lh-1 mb-1"
                                                x-text="getYearlyManualCount('{{ $typeName }}')">0</div>
                                            <div class="fw-bold text-dark fs-6 mb-1">{{ $type->name }}</div>

                                            <!-- Qty and TA Section - Only shows for Claim type and only if data exists -->
                                            @if ($isClaimType)
                                                <template
                                                    x-if="getYearlyManualQty('{{ $typeName }}') > 0 || getYearlyManualAmount('{{ $typeName }}') > 0">
                                                    <div
                                                        class="mt-1 pt-1 border-top d-flex justify-content-center gap-2 small fw-medium">
                                                        <template
                                                            x-if="getYearlyManualQty('{{ $typeName }}') > 0">
                                                            <span class="text-secondary"><span
                                                                    class="fw-bold">Q:</span> <span class="fw-bold"
                                                                    x-text="getYearlyManualQty('{{ $typeName }}')"></span></span>
                                                        </template>
                                                        <template
                                                            x-if="getYearlyManualAmount('{{ $typeName }}') > 0">
                                                            <span class="text-success"><span
                                                                    class="fw-bold">TA:</span> <span class="fw-bold"
                                                                    x-text="formatNumber(getYearlyManualAmount('{{ $typeName }}'))"></span></span>
                                                        </template>
                                                    </div>
                                                </template>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row g-2 mt-2">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-2">
                        <h5 class="fw-bold mb-0 fs-5"><i
                                class="bi bi-lightning-charge-fill text-warning me-2"></i>Quick Actions</h5>
                    </div>
                    <div class="card-body pt-0 py-2">
                        <div class="row g-2">
                            <div class="col">
                                <a href="{{ route('complain.create') }}?type=complain" class="btn btn-primary w-100">
                                    <i class="bi bi-chat-dots-fill fs-5 d-block mb-1"></i>
                                    <span class="fw-semibold">Add Complain</span>
                                </a>
                            </div>
                            <div class="col">
                                <a href="{{ route('complain.manual') }}?type=manual" class="btn btn-warning w-100">
                                    <i class="bi bi-file-earmark-text-fill fs-5 d-block mb-1"></i>
                                    <span class="fw-semibold">Add Manual</span>
                                </a>
                            </div>
                            <div class="col">
                                <a href="{{ route('complain.index') }}" class="btn btn-success w-100">
                                    <i class="bi bi-list-ul fs-5 d-block mb-1"></i>
                                    <span class="fw-semibold">View All</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .display-4 {
            font-size: 2.5rem !important;
            font-weight: 700 !important;
            line-height: 1;
        }

        .container-fluid {
            max-width: 100%;
        }
    </style>

    <script>
        function dashboard() {
            return {
                loading: false,
                // Monthly filter
                monthFilter: new Date().toISOString().slice(0, 7),
                monthFilterDisplay: '{{ $currentMonthName }} {{ $currentYear }}',

                // Yearly filter
                yearFilter: new Date().getFullYear().toString(),
                yearFilterDisplay: '{{ $currentYear }}',

                // Monthly stats data
                monthlyComplainData: @json($monthlyComplainStats),
                monthlyManualData: @json($monthlyManualStats),
                monthlyComplainTotal: {{ $monthlyComplainTotal }},
                monthlyManualTotal: {{ $monthlyManualTotal }},

                // Yearly stats data
                yearlyComplainData: @json($yearlyComplainStats),
                yearlyManualData: @json($yearlyManualStats),
                yearlyComplainTotal: {{ $yearlyComplainTotal }},
                yearlyManualTotal: {{ $yearlyManualTotal }},

                init() {},

                // Format number with 2 decimal places
                formatNumber(value) {
                    return Number(value).toFixed(2);
                },

                // Monthly filter methods
                async applyMonthlyFilter() {
                    try {
                        this.loading = true;

                        if (!this.monthFilter) {
                            alert('Please select a month');
                            this.loading = false;
                            return;
                        }

                        const response = await fetch('{{ route('dashboard.monthly-stats') }}?month=' + this
                            .monthFilter, {
                                headers: {
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            });

                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }

                        const data = await response.json();

                        if (data.success && data.filter_type === 'monthly') {
                            this.monthlyComplainData = data.data.complain;
                            this.monthlyManualData = data.data.manual;
                            this.monthlyComplainTotal = data.data.totals.complain;
                            this.monthlyManualTotal = data.data.totals.manual;
                            this.monthFilterDisplay = data.filter_display;
                        } else {
                            throw new Error(data.message || 'Failed to load data');
                        }
                    } catch (error) {
                        console.error('Error fetching monthly statistics:', error);
                        alert('Failed to load monthly statistics: ' + error.message);
                    } finally {
                        this.loading = false;
                    }
                },

                resetMonthlyFilter() {
                    this.monthFilter = new Date().toISOString().slice(0, 7);
                    this.monthlyComplainData = @json($monthlyComplainStats);
                    this.monthlyManualData = @json($monthlyManualStats);
                    this.monthlyComplainTotal = {{ $monthlyComplainTotal }};
                    this.monthlyManualTotal = {{ $monthlyManualTotal }};
                    this.monthFilterDisplay = '{{ $currentMonthName }} {{ $currentYear }}';
                },

                // Yearly filter methods
                async applyYearlyFilter() {
                    try {
                        this.loading = true;

                        if (!this.yearFilter) {
                            alert('Please enter a year');
                            this.loading = false;
                            return;
                        }

                        const response = await fetch('{{ route('dashboard.monthly-stats') }}?year=' + this
                        .yearFilter, {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });

                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }

                        const data = await response.json();

                        if (data.success && data.filter_type === 'yearly') {
                            this.yearlyComplainData = data.data.complain;
                            this.yearlyManualData = data.data.manual;
                            this.yearlyComplainTotal = data.data.totals.complain;
                            this.yearlyManualTotal = data.data.totals.manual;
                            this.yearFilterDisplay = data.filter_display;
                        } else {
                            throw new Error(data.message || 'Failed to load data');
                        }
                    } catch (error) {
                        console.error('Error fetching yearly statistics:', error);
                        alert('Failed to load yearly statistics: ' + error.message);
                    } finally {
                        this.loading = false;
                    }
                },

                resetYearlyFilter() {
                    this.yearFilter = new Date().getFullYear().toString();
                    this.yearlyComplainData = @json($yearlyComplainStats);
                    this.yearlyManualData = @json($yearlyManualStats);
                    this.yearlyComplainTotal = {{ $yearlyComplainTotal }};
                    this.yearlyManualTotal = {{ $yearlyManualTotal }};
                    this.yearFilterDisplay = '{{ $currentYear }}';
                },

                // Getters
                getMonthlyComplainCount(typeName) {
                    return this.monthlyComplainData[typeName]?.count || 0;
                },

                getMonthlyManualCount(typeName) {
                    return this.monthlyManualData[typeName]?.count || 0;
                },

                getYearlyComplainCount(typeName) {
                    return this.yearlyComplainData[typeName]?.count || 0;
                },

                getYearlyManualCount(typeName) {
                    return this.yearlyManualData[typeName]?.count || 0;
                },

                getMonthlyComplainQty(typeName) {
                    return this.monthlyComplainData[typeName]?.total_quantity || 0;
                },

                getMonthlyComplainAmount(typeName) {
                    return this.monthlyComplainData[typeName]?.total_amount || 0;
                },

                getMonthlyManualQty(typeName) {
                    return this.monthlyManualData[typeName]?.total_quantity || 0;
                },

                getMonthlyManualAmount(typeName) {
                    return this.monthlyManualData[typeName]?.total_amount || 0;
                },

                getYearlyComplainQty(typeName) {
                    return this.yearlyComplainData[typeName]?.total_quantity || 0;
                },

                getYearlyComplainAmount(typeName) {
                    return this.yearlyComplainData[typeName]?.total_amount || 0;
                },

                getYearlyManualQty(typeName) {
                    return this.yearlyManualData[typeName]?.total_quantity || 0;
                },

                getYearlyManualAmount(typeName) {
                    return this.yearlyManualData[typeName]?.total_amount || 0;
                }
            }
        }
    </script>
</x-app-layout>
