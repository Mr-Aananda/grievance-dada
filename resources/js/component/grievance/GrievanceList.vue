<template>
    <div class="d-flex flex-column h-100">

        <!-- Gradient Stat Cards (matching admin dashboard) -->
        <div class="row g-3 mb-4">
            <div v-for="(stat, key) in statDefs" :key="key" class="col-12 col-sm-6 col-xxl-3">
                <div
                    :class="['gms-stat-card', `gms-stat-${key}`, filterStatus === key && 'gms-stat-active']"
                    @click="$emit('filter-change', filterStatus === key ? '' : key)"
                    role="button" tabindex="0"
                    @keyup.enter="$emit('filter-change', filterStatus === key ? '' : key)"
                >
                    <div class="gms-stat-inner">
                        <h3 class="gms-stat-count">{{ statusCounts[key] ?? 0 }}</h3>
                        <p class="gms-stat-label">{{ stat.label }}</p>
                    </div>
                    <i :class="['gms-stat-bg-icon bi', stat.icon]"></i>
                    <div class="gms-stat-footer-link">
                        {{ filterStatus === key ? 'Clear filter' : 'Filter by this' }}
                        <i :class="['bi ms-1', filterStatus === key ? 'bi-x-circle' : 'bi-arrow-right-short']"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grievance Tracker Table -->
        <div class="card border-0 shadow-sm flex-grow-1 gms-tracker-card">
            <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center gap-2">
                    <span class="gms-tracker-icon"><i class="bi bi-ticket-perforated-fill"></i></span>
                    <div>
                        <h6 class="mb-0 fw-bold">Grievance Tracker</h6>
                        <small class="text-muted">{{ totalRecords }} total records</small>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <div class="gms-search-wrap">
                        <i class="bi bi-search gms-search-icon"></i>
                        <input
                            type="text"
                            class="gms-search-input"
                            placeholder="Search ticket number…"
                            :value="search"
                            @input="$emit('search-change', $event.target.value)"
                        />
                    </div>
                    <button v-if="search || filterStatus" class="gms-btn-clear" @click="$emit('clear-filters')">
                        <i class="bi bi-x-lg me-1"></i> Clear
                    </button>
                </div>
            </div>

            <div class="card-body p-0 table-responsive d-flex flex-column flex-grow-1">
                <table v-if="tableLoading || grievances.length" class="table table-hover align-middle mb-0">
                    <thead class="gms-thead">
                        <tr>
                            <th class="ps-4">Ticket #</th>
                            <th>Category</th>
                            <th>Department</th>
                            <th>Status</th>
                            <th>Submitted</th>
                            <th class="pe-4"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="tableLoading">
                            <td colspan="6" class="py-4">
                                <div v-for="n in 5" :key="n" class="gms-skeleton my-2 mx-4"></div>
                            </td>
                        </tr>
                        <template v-else>
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
                        </template>
                    </tbody>
                </table>

                <div v-else class="gms-empty-state flex-grow-1">
                    <div class="gms-empty-icon"><i class="bi bi-inbox-fill"></i></div>
                    <h5 class="gms-empty-title">No grievances found</h5>
                    <p class="gms-empty-sub">Submit a new grievance using the form,<br>or adjust your search filters.</p>
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
