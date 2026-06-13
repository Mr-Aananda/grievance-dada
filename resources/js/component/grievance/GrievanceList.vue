<template>
    <div>
        <!-- Statistics Cards -->
        <div class="gms-stats-grid">
            <div v-for="(stat, key) in statDefs" :key="key"
                :class="['gms-stat-card', stat.cls, filterStatus === key && 'active']"
                @click="$emit('filter-change', filterStatus === key ? '' : key)"
                role="button" tabindex="0"
                @keyup.enter="$emit('filter-change', filterStatus === key ? '' : key)">
                <div class="gms-stat-icon">
                    <i :class="['bi', stat.icon]"></i>
                </div>
                <div class="gms-stat-number">{{ statusCounts[key] ?? 0 }}</div>
                <div class="gms-stat-label">{{ stat.label }}</div>
                <div class="gms-stat-bangla">{{ stat.bn }}</div>
            </div>
        </div>

        <!-- Grievances Table -->
        <div class="gms-card">
            <div class="gms-card-header">
                <div>
                    <div class="gms-card-title">
                        <i class="bi bi-ticket-perforated"></i> All Grievances
                    </div>
                    <div class="gms-card-sub">{{ totalRecords }} total records</div>
                </div>
                <div class="gms-filters">
                    <div class="gms-search">
                        <i class="bi bi-search"></i>
                        <input type="text" class="gms-search-input" placeholder="Search ticket..."
                            :value="search" @input="$emit('search-change', $event.target.value)" />
                    </div>
                    <select class="gms-select" :value="filterStatus" @change="$emit('filter-change', $event.target.value)">
                        <option value="">All Status</option>
                        <option v-for="(stat, key) in statDefs" :key="key" :value="key">
                            {{ stat.label }}
                        </option>
                    </select>
                    <button v-if="search || filterStatus" class="gms-btn-clear" @click="$emit('clear-filters')">
                        <i class="bi bi-x-lg"></i> Clear
                    </button>
                </div>
            </div>

            <div class="gms-table-container">
                <table class="gms-table">
                    <thead>
                        <tr>
                            <th>Ticket #</th>
                            <th>Category</th>
                            <th>Department</th>
                            <th>Status</th>
                            <th>Submitted</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="tableLoading">
                            <td colspan="6">
                                <div v-for="n in 5" :key="n" class="gms-skeleton"></div>
                            </td>
                        </tr>
                        <template v-else-if="grievances.length">
                            <tr v-for="grievance in grievances" :key="grievance.id"
                                @click="$emit('view-detail', grievance.id)">
                                <td class="gms-ticket-number">{{ grievance.ticket_number }}</td>
                                <td>{{ grievance.category?.name || '—' }}</td>
                                <td class="gms-dept-cell">{{ grievance.department?.name || '—' }}</td>
                                <td>
                                    <span :class="['gms-badge', getStatusClass(grievance.status)]">
                                        {{ grievance.status_label }}
                                    </span>
                                </td>
                                <td class="gms-date-cell">{{ formatDate(grievance.created_at) }}</td>
                                <td class="gms-action-cell">
                                    <i class="bi bi-chevron-right"></i>
                                </td>
                            </tr>
                        </template>
                        <tr v-else>
                            <td colspan="6">
                                <div class="gms-empty">
                                    <i class="bi bi-inbox"></i>
                                    <p>No grievances found.</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div v-if="pagination.last_page > 1" class="gms-pagination">
                <button class="gms-page-btn" :disabled="pagination.current_page === 1"
                    @click="$emit('page-change', pagination.current_page - 1)" aria-label="Previous page">
                    <i class="bi bi-chevron-left"></i>
                </button>
                <button v-for="(page, idx) in pageNumbers" :key="idx"
                    :class="['gms-page-btn', page === pagination.current_page && 'active']"
                    :disabled="page === '...'"
                    @click="page !== '...' && $emit('page-change', page)">
                    {{ page }}
                </button>
                <button class="gms-page-btn" :disabled="pagination.current_page === pagination.last_page"
                    @click="$emit('page-change', pagination.current_page + 1)" aria-label="Next page">
                    <i class="bi bi-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    grievances: Array,
    statusCounts: Object,
    totalRecords: Number,
    pagination: Object,
    tableLoading: Boolean,
    search: String,
    filterStatus: String,
    statDefs: Object,
});

defineEmits(['view-detail', 'filter-change', 'search-change', 'page-change', 'clear-filters']);

function formatDate(iso) {
    if (!iso) return '—';
    return new Date(iso).toLocaleDateString('en-GB', {
        day: '2-digit',
        month: 'short',
        year: 'numeric'
    });
}

function getStatusClass(status) {
    const classes = {
        submitted: 'badge-primary',
        under_review: 'badge-warning',
        in_resolution: 'badge-danger',
        resolved: 'badge-success'
    };
    return classes[status] || 'badge-secondary';
}

const pageNumbers = computed(() => {
    const current = props.pagination.current_page;
    const last = props.pagination.last_page;

    if (last <= 7) {
        return Array.from({ length: last }, (_, i) => i + 1);
    }

    const pages = new Set([1, last, current, current - 1, current + 1]);
    const sorted = [...pages].filter(p => p >= 1 && p <= last).sort((a, b) => a - b);

    const result = [];
    for (let i = 0; i < sorted.length; i++) {
        if (i > 0 && sorted[i] - sorted[i - 1] > 1) {
            result.push('...');
        }
        result.push(sorted[i]);
    }
    return result;
});
</script>
