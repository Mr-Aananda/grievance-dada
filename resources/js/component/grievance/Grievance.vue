<template>
    <div class="gms-scope">
        <!-- Toast -->
        <Teleport to="body">
            <div :class="['gms-toast', toast.show && 'show']">
                <i class="bi bi-check2-circle"></i> {{ toast.message }}
            </div>
        </Teleport>

        <!-- Topbar -->
        <header class="gms-topbar">
            <div class="gms-topbar-brand">
                <div class="gms-brand-icon">
                    <i class="bi bi-shield-check"></i>
                </div>
                <div>
                    <div class="gms-brand-name">DADA (Dhaka) Ltd.</div>
                    <div class="gms-brand-sub">Grievance Management System</div>
                </div>
            </div>
            <div class="gms-topbar-badge">
                <i class="bi bi-person-badge me-1"></i> GMS Portal
            </div>
        </header>

        <!-- Main Content -->
        <div class="gms-page-wrap">
            <!-- Page Header -->
            <div class="gms-page-header">
                <div class="gms-page-title">Grievance Redressal Portal</div>
                <div class="gms-page-sub">
                    <i class="bi bi-chat-square-dots me-1"></i>
                    অভিযোগ প্রতিকার মাধ্যম — Submit and track your grievances
                </div>
            </div>

            <!-- Success Alert -->
            <Transition name="gms-fade">
                <div v-if="newTicket" class="gms-alert-success">
                    <i class="bi bi-check-circle-fill gms-alert-icon"></i>
                    <div class="gms-alert-content">
                        <strong>Grievance submitted successfully!</strong>
                        <div class="gms-alert-text">Save your ticket number to track your grievance status.</div>
                        <div class="gms-ticket-display" @click="copyTicket(newTicket)">
                            {{ newTicket }}
                            <i class="bi bi-copy"></i>
                        </div>
                    </div>
                    <button class="gms-close-btn" @click="newTicket = null" aria-label="Dismiss">✕</button>
                </div>
            </Transition>

            <!-- Main Grid -->
            <div class="gms-main-grid">
                <!-- Left: Submit Form -->
                <GrievanceForm
                    :categories="categories"
                    :departments="departments"
                    :store-url="storeUrl"
                    :is-submitting="isSubmitting"
                    @submit="handleSubmit"
                />

                <!-- Right: List & Stats -->
                <GrievanceList
                    :grievances="grievances"
                    :status-counts="statusCounts"
                    :total-records="totalRecords"
                    :pagination="pagination"
                    :table-loading="tableLoading"
                    :search="search"
                    :filter-status="filterStatus"
                    :stat-defs="statDefs"
                    @view-detail="showDetail"
                    @filter-change="handleFilterChange"
                    @search-change="handleSearchChange"
                    @page-change="fetchGrievances"
                    @clear-filters="clearFilters"
                />
            </div>
        </div>

        <!-- Modals -->
        <GrievanceModal
            :open="modal.open"
            :loading="modal.loading"
            :error="modal.error"
            :data="modal.data"
            @close="modal.open = false"
            @copy-ticket="copyTicket"
            @open-lightbox="openLightbox"
        />

        <Lightbox
            :open="lightbox.open"
            :index="lightbox.index"
            :images="mediaImages"
            @close="lightbox.open = false"
            @prev="lightboxPrev"
            @next="lightboxNext"
            @go-to="lightboxGoTo"
        />
    </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue';
import GrievanceForm from './GrievanceForm.vue';
import GrievanceList from './GrievanceList.vue';
import GrievanceModal from './GrievanceModal.vue';
import Lightbox from './Lightbox.vue';

const props = defineProps({
    categories: { type: Array, default: () => [] },
    departments: { type: Array, default: () => [] },
    storeUrl: { type: String, required: true },
    listUrl: { type: String, required: true },
    showUrl: { type: String, required: true },
    initialCounts: { type: Object, default: () => ({}) },
    initialTicket: { type: String, default: null },
});

const statDefs = {
    submitted: { label: 'Submitted', bn: 'জমা দেওয়া', icon: 'bi-inbox', cls: 's-primary' },
    under_review: { label: 'Under Review', bn: 'পর্যালোচনায়', icon: 'bi-eye', cls: 's-amber' },
    in_resolution: { label: 'In Resolution', bn: 'সমাধানের পথে', icon: 'bi-tools', cls: 's-danger' },
    resolved: { label: 'Resolved', bn: 'সমাধান করা', icon: 'bi-check-circle', cls: 's-success' },
};

const newTicket = ref(props.initialTicket);
const isSubmitting = ref(false);
const search = ref('');
const filterStatus = ref('');
const grievances = ref([]);
const statusCounts = ref(Object.assign({}, props.initialCounts));
const totalRecords = ref(0);
const tableLoading = ref(false);
const pagination = ref({ current_page: 1, last_page: 1 });
const toast = reactive({ show: false, message: '' });
const modal = reactive({ open: false, loading: false, error: false, data: null });
const lightbox = reactive({ open: false, index: 0 });

const mediaImages = computed(() => {
    if (!modal.data?.media) return [];
    return modal.data.media.filter(m => m.type === 'image');
});

let toastTimer = null;
function showToast(msg) {
    toast.message = msg;
    toast.show = true;
    if (toastTimer) clearTimeout(toastTimer);
    toastTimer = setTimeout(() => { toast.show = false; }, 2500);
}

async function copyTicket(ticket) {
    if (!ticket) return;
    try {
        await navigator.clipboard.writeText(ticket);
        showToast('✓ Ticket number copied!');
    } catch {
        showToast('Copy failed — please copy manually');
    }
}

async function handleSubmit(formData, files) {
    isSubmitting.value = true;
    try {
        const fd = new FormData();
        fd.append('category_id', formData.category_id);
        if (formData.department_id) fd.append('department_id', formData.department_id);
        if (formData.employee_id) fd.append('employee_id', formData.employee_id);
        fd.append('description', formData.description);
        files.forEach((f, i) => fd.append(`files[${i}]`, f));

        const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (csrf) fd.append('_token', csrf);

        const res = await axios.post(props.storeUrl, fd, {
            headers: { 'Content-Type': 'multipart/form-data' },
        });

        if (res.data.success) {
            newTicket.value = res.data.ticket_number;
            await fetchGrievances(1);
            return { success: true, ticket: res.data.ticket_number };
        }
        return { success: false, message: res.data.message };
    } catch (err) {
        if (err.response?.status === 422) {
            return { success: false, errors: err.response.data.errors };
        }
        return { success: false, message: err.message };
    } finally {
        isSubmitting.value = false;
    }
}

async function fetchGrievances(page = 1) {
    tableLoading.value = true;
    try {
        const params = new URLSearchParams({ page });
        if (search.value) params.set('search', search.value);
        if (filterStatus.value) params.set('status', filterStatus.value);

        const res = await axios.get(`${props.listUrl}?${params}`, {
            headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        });

        grievances.value = res.data.data || [];
        pagination.value = {
            current_page: res.data.current_page ?? 1,
            last_page: res.data.last_page ?? 1,
        };
        totalRecords.value = res.data.total ?? 0;
        if (res.data.status_counts) statusCounts.value = res.data.status_counts;
    } catch (e) {
        console.error('Fetch error:', e);
    } finally {
        tableLoading.value = false;
    }
}

function handleFilterChange(status) {
    filterStatus.value = status;
    fetchGrievances(1);
}

function handleSearchChange(value) {
    search.value = value;
    fetchGrievances(1);
}

function clearFilters() {
    search.value = '';
    filterStatus.value = '';
    fetchGrievances(1);
}

async function showDetail(id) {
    modal.open = true;
    modal.loading = true;
    modal.error = false;
    modal.data = null;

    try {
        const url = props.showUrl.replace('{id}', id).replace(':id', id);
        const res = await axios.get(url, {
            headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        });
        modal.data = res.data;
    } catch {
        modal.error = true;
    } finally {
        modal.loading = false;
    }
}

function openLightbox({ index }) {
    lightbox.index = index;
    lightbox.open = true;
}

function lightboxPrev() {
    if (!mediaImages.value.length) return;
    lightbox.index = (lightbox.index - 1 + mediaImages.value.length) % mediaImages.value.length;
}

function lightboxNext() {
    if (!mediaImages.value.length) return;
    lightbox.index = (lightbox.index + 1) % mediaImages.value.length;
}

function lightboxGoTo(index) {
    lightbox.index = index;
}

onMounted(() => fetchGrievances(1));
</script>
