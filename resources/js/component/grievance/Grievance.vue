<template>
    <div class="gms-scope">
        <!-- Toast -->
        <Teleport to="body">
            <div :class="['gms-toast', toast.show && 'show']">
                <i class="bi bi-check2-circle"></i> {{ toast.message }}
            </div>
        </Teleport>

        <!-- Success Alert -->
        <Transition name="gms-fade">
            <div v-if="newTicket" class="gms-success-alert mb-4">
                <div class="gms-success-icon"><i class="bi bi-check-circle-fill"></i></div>
                <div class="flex-grow-1">
                    <div class="gms-success-title">Grievance Submitted Successfully!</div>
                    <div class="gms-success-sub">Your ticket number is shown below. Click to copy.</div>
                    <div class="gms-ticket-chip" @click="copyTicket(newTicket)" title="Click to copy">
                        <i class="bi bi-ticket-perforated me-2"></i>{{ newTicket }}<i class="bi bi-copy ms-2 opacity-75"></i>
                    </div>
                </div>
                <button class="gms-success-close" @click="newTicket = null"><i class="bi bi-x-lg"></i></button>
            </div>
        </Transition>

        <!-- Main Grid -->
        <div class="row g-4">
            <!-- Left: Submit Form -->
            <div class="col-12 col-lg-5 col-xl-4 d-flex flex-column">
                <GrievanceForm
                    class="flex-grow-1"
                    :categories="categories"
                    :departments="departments"
                    :store-url="storeUrl"
                    :is-submitting="isSubmitting"
                    @submit="handleSubmit"
                />
            </div>

            <!-- Right: Tracker -->
            <div class="col-12 col-lg-7 col-xl-8 d-flex flex-column">
                <GrievanceList
                    class="flex-grow-1"
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
import GrievanceForm   from './GrievanceForm.vue';
import GrievanceList   from './GrievanceList.vue';
import GrievanceModal  from './GrievanceModal.vue';
import Lightbox        from './Lightbox.vue';

const props = defineProps({
    categories:    { type: Array,  default: () => [] },
    departments:   { type: Array,  default: () => [] },
    storeUrl:      { type: String, required: true },
    listUrl:       { type: String, required: true },
    showUrl:       { type: String, required: true },
    logoUrl:       { type: String, default: '' },
    initialCounts: { type: Object, default: () => ({}) },
    initialTicket: { type: String, default: null },
});

const statDefs = {
    submitted:     { label: 'New Submitted',  icon: 'bi-inbox-fill'        },
    under_review:  { label: 'Under Review',   icon: 'bi-eye-fill'          },
    in_resolution: { label: 'In Resolution',  icon: 'bi-tools'             },
    resolved:      { label: 'Resolved',       icon: 'bi-check-circle-fill' },
};

const newTicket    = ref(props.initialTicket);
const isSubmitting = ref(false);
const search       = ref('');
const filterStatus = ref('');
const grievances   = ref([]);
const statusCounts = ref(Object.assign({}, props.initialCounts));
const totalRecords = ref(0);
const tableLoading = ref(false);
const pagination   = ref({ current_page: 1, last_page: 1 });
const toast        = reactive({ show: false, message: '' });
const modal        = reactive({ open: false, loading: false, error: false, data: null });
const lightbox     = reactive({ open: false, index: 0 });

const mediaImages = computed(() => {
    if (!modal.data?.media) return [];
    return modal.data.media.filter(m => m.type === 'image');
});

let toastTimer = null;
function showToast(msg) {
    toast.message = msg; toast.show = true;
    if (toastTimer) clearTimeout(toastTimer);
    toastTimer = setTimeout(() => { toast.show = false; }, 2500);
}

async function copyTicket(ticket) {
    if (!ticket) return;
    try {
        if (navigator.clipboard && navigator.clipboard.writeText) {
            await navigator.clipboard.writeText(ticket);
            showToast('✓ Ticket number copied!');
        } else {
            const textArea = document.createElement("textarea");
            textArea.value = ticket;
            textArea.style.position = "fixed";
            textArea.style.opacity = "0";
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            const successful = document.execCommand('copy');
            document.body.removeChild(textArea);
            if (successful) {
                showToast('✓ Ticket number copied!');
            } else {
                showToast('Copy failed — please copy manually');
            }
        }
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
        if (formData.employee_id)   fd.append('employee_id',   formData.employee_id);
        fd.append('description', formData.description);
        files.forEach((f, i) => fd.append(`files[${i}]`, f));
        const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (csrf) fd.append('_token', csrf);
        const res = await axios.post(props.storeUrl, fd, { headers: { 'Content-Type': 'multipart/form-data' } });
        if (res.data.success) { newTicket.value = res.data.ticket_number; await fetchGrievances(1); return { success: true, ticket: res.data.ticket_number }; }
        return { success: false, message: res.data.message };
    } catch (err) {
        if (err.response?.status === 422) return { success: false, errors: err.response.data.errors };
        return { success: false, message: err.message };
    } finally { isSubmitting.value = false; }
}

async function fetchGrievances(page = 1) {
    tableLoading.value = true;
    try {
        const params = new URLSearchParams({ page });
        if (search.value)       params.set('search', search.value);
        if (filterStatus.value) params.set('status', filterStatus.value);
        const res = await axios.get(`${props.listUrl}?${params}`, { headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
        grievances.value  = res.data.data || [];
        pagination.value  = { current_page: res.data.current_page ?? 1, last_page: res.data.last_page ?? 1 };
        totalRecords.value = res.data.total ?? 0;
        if (res.data.status_counts) statusCounts.value = res.data.status_counts;
    } catch (e) { console.error('Fetch error:', e); }
    finally { tableLoading.value = false; }
}

function handleFilterChange(s)  { filterStatus.value = s;  fetchGrievances(1); }
function handleSearchChange(v)  { search.value = v;        fetchGrievances(1); }
function clearFilters()         { search.value = ''; filterStatus.value = ''; fetchGrievances(1); }

async function showDetail(id) {
    modal.open = true; modal.loading = true; modal.error = false; modal.data = null;
    try {
        const url = props.showUrl.replace('{id}', id).replace(':id', id);
        const res = await axios.get(url, { headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
        modal.data = res.data;
    } catch { modal.error = true; } finally { modal.loading = false; }
}

function openLightbox({ index }) { lightbox.index = index; lightbox.open = true; }
function lightboxPrev()  { if (!mediaImages.value.length) return; lightbox.index = (lightbox.index - 1 + mediaImages.value.length) % mediaImages.value.length; }
function lightboxNext()  { if (!mediaImages.value.length) return; lightbox.index = (lightbox.index + 1) % mediaImages.value.length; }
function lightboxGoTo(i) { lightbox.index = i; }

onMounted(() => fetchGrievances(1));
</script>
