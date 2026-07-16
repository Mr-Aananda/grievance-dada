<template>
    <div class="d-flex flex-column h-100">

        <!-- Grievance Tracker Card -->
        <div class="card border-0 shadow-sm flex-grow-1 gms-tracker-card">
            <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center gap-2">
                    <span class="gms-tracker-icon"><i class="bi bi-ticket-perforated-fill"></i></span>
                    <div>
                        <h6 class="mb-0 fw-bold text-dark">{{ $t('Grievance Tracker') }}</h6>
                        <small v-if="search" class="text-muted">{{ totalRecords }} {{ $t('results found') }}</small>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2 flex-wrap w-100-mobile">
                    <div class="gms-search-wrap flex-grow-1-mobile">
                        <i class="bi bi-search gms-search-icon"></i>
                        <input
                            type="text"
                            class="gms-search-input"
                            :placeholder="$t('Search ticket number…')"
                            :value="search"
                            @input="$emit('search-change', $event.target.value)"
                        />
                    </div>
                    <button v-if="search || filterStatus" class="gms-btn-clear" @click="$emit('clear-filters')">
                        <i class="bi bi-x-lg me-1"></i> {{ $t('Clear') }}
                    </button>
                </div>
            </div>

            <div class="card-body p-0 table-responsive d-flex flex-column flex-grow-1">
                <!-- Desktop Table View -->
                <table v-if="(tableLoading || grievances.length) && !tableLoading" class="table table-hover align-middle mb-0 d-none d-md-table">
                    <thead class="gms-thead">
                        <tr>
                            <th class="ps-4">{{ $t('Ticket #') }}</th>
                            <th>{{ $t('Category') }}</th>
                            <th>{{ $t('Department') }}</th>
                            <th>{{ $t('Status') }}</th>
                            <th>{{ $t('Submitted') }}</th>
                            <th class="pe-4"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="g in grievances" :key="g.id"
                            @click="$emit('view-detail', g.id)" class="gms-table-row">
                            <td class="ps-4">
                                <span class="gms-ticket-number">{{ g.ticket_number }}</span>
                            </td>
                            <td><span class="gms-category-badge">{{ g.category?.name || '—' }}</span></td>
                            <td class="text-muted small">{{ g.department?.name || '—' }}</td>
                            <td>
                                <span :class="['gms-status-pill', `status-${g.status}`]">{{ g.status_label }}</span>
                            </td>
                            <td><small class="text-muted">{{ formatDate(g.created_at) }}</small></td>
                            <td class="pe-4 text-end">
                                <i class="bi bi-chevron-right text-muted gms-row-arrow"></i>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!-- Mobile Card List View -->
                <div v-if="!tableLoading && grievances.length" class="gms-mobile-list d-md-none">
                    <div v-for="g in grievances" :key="g.id"
                        @click="$emit('view-detail', g.id)" class="gms-mobile-card p-3 mb-2 mx-3 mt-2 shadow-sm">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="gms-mobile-ticket-num">{{ g.ticket_number }}</span>
                            <span :class="['gms-status-pill m-0', `status-${g.status}`]">{{ g.status_label }}</span>
                        </div>
                        <div class="row g-2 mb-2">
                            <div class="col-6">
                                <div class="gms-mobile-meta-label">{{ $t('Category') }}</div>
                                <div class="gms-mobile-meta-val text-truncate">{{ g.category?.name || '—' }}</div>
                            </div>
                            <div class="col-6">
                                <div class="gms-mobile-meta-label">{{ $t('Department') }}</div>
                                <div class="gms-mobile-meta-val text-truncate">{{ g.department?.name || '—' }}</div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between pt-2 border-top border-light">
                            <span class="gms-mobile-date"><i class="bi bi-calendar3 me-1"></i>{{ formatDate(g.created_at) }}</span>
                            <span class="gms-mobile-action">{{ $t('View') }} <i class="bi bi-arrow-right ms-1"></i></span>
                        </div>
                    </div>
                </div>

                <!-- Skeleton Loaders -->
                <div v-if="tableLoading" class="w-100">
                    <!-- Desktop Skeleton -->
                    <div class="d-none d-md-block p-4">
                        <div v-for="n in 5" :key="'desk-sk-'+n" class="gms-skeleton my-2 mx-4"></div>
                    </div>
                    <!-- Mobile Skeleton -->
                    <div class="d-md-none p-3">
                        <div v-for="n in 3" :key="'mob-sk-'+n" class="gms-mobile-skeleton-card mb-3 p-3 shadow-sm">
                            <div class="d-flex justify-content-between mb-3">
                                <div class="gms-skeleton-line w-50"></div>
                                <div class="gms-skeleton-line w-25"></div>
                            </div>
                            <div class="gms-skeleton-line w-75 mb-2"></div>
                            <div class="gms-skeleton-line w-60"></div>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-else-if="!grievances.length" class="gms-empty-state flex-grow-1">
                    <div class="gms-empty-icon">
                        <i :class="['bi', search ? 'bi-search-heart' : 'bi-ticket-perforated']"></i>
                    </div>
                    <h5 class="gms-empty-title">
                        {{ search ? $t('No grievance found') : $t('Track Your Grievance') }}
                    </h5>
                    <p class="gms-empty-sub">
                        {{ search 
                            ? $t('We could not find any grievance matching that ticket number. Please check the spelling and try again.') 
                            : $t('Enter your exact Ticket Number in the search box above to track its current status.') 
                        }}
                    </p>
                </div>
            </div>

            <div v-if="pagination.last_page > 1" class="card-footer bg-transparent border-top d-flex justify-content-end p-3">
                <nav><ul class="pagination pagination-sm mb-0">
                    <li :class="['page-item', pagination.current_page === 1 && 'disabled']">
                        <button class="page-link" @click="$emit('page-change', pagination.current_page - 1)">&laquo;</button>
                    </li>
                    <li v-for="(page, idx) in pageNumbers" :key="idx"
                        :class="['page-item', page === pagination.current_page && 'active', page === '...' && 'disabled']">
                        <button class="page-link" @click="page !== '...' && $emit('page-change', page)">{{ page }}</button>
                    </li>
                    <li :class="['page-item', pagination.current_page === pagination.last_page && 'disabled']">
                        <button class="page-link" @click="$emit('page-change', pagination.current_page + 1)">&raquo;</button>
                    </li>
                </ul></nav>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    grievances: Array, statusCounts: Object, totalRecords: Number,
    pagination: Object, tableLoading: Boolean, search: String,
    filterStatus: String, statDefs: Object,
});
defineEmits(['view-detail','filter-change','search-change','page-change','clear-filters']);

function formatDate(iso) {
    if (!iso) return '—';
    return new Date(iso).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
}

const pageNumbers = computed(() => {
    const { current_page: c, last_page: l } = props.pagination;
    if (l <= 7) return Array.from({ length: l }, (_, i) => i + 1);
    const pages = new Set([1, l, c, c-1, c+1]);
    const sorted = [...pages].filter(p => p >= 1 && p <= l).sort((a, b) => a - b);
    const result = [];
    for (let i = 0; i < sorted.length; i++) {
        if (i > 0 && sorted[i] - sorted[i-1] > 1) result.push('...');
        result.push(sorted[i]);
    }
    return result;
});
</script>
