<template>
    <div class="gms-scope gms-fade-in-up">
        <!-- Full Page Loading Overlay -->
        <Transition name="gms-fade">
            <div v-if="isSubmitting" class="gms-submit-loading-overlay">
                <div class="gms-submit-loading-card">
                    <div class="gms-spinner-large"></div>
                    <h5 class="gms-submit-loading-title">{{ $t('Submitting...') }}</h5>
                    <p class="gms-submit-loading-text">{{ $t('Your grievance is being submitted, please wait.') }}</p>
                    <span class="gms-submit-loading-subtext">{{ $t('Processing... Please do not close or refresh this page.') }}</span>
                </div>
            </div>
        </Transition>

        <!-- Centered Dynamic Workspace Container -->
        <div :class="['gms-workspace-container mx-auto', activeTab === 'submit' ? 'view-submit' : 'view-track']">
            <Transition name="gms-tab-fade" mode="out-in">
                <!-- Submit Form View -->
                <div v-if="activeTab === 'submit'" key="submit-view">
                    <div class="row g-4">
                        <!-- Left: Form -->
                        <div class="col-12 col-lg-7 col-xl-8 d-flex flex-column">
                            <GrievanceForm
                                ref="formRef"
                                class="flex-grow-1"
                                :categories="categories"
                                :departments="departments"
                                :store-url="storeUrl"
                                :is-submitting="isSubmitting"
                                :upload-progress="uploadProgress"
                                @submit="handleSubmit"
                            />
                        </div>

                        <!-- Right: Advisory Sidebar -->
                        <div class="col-12 col-lg-5 col-xl-4">
                            <div class="card border-0 shadow-sm gms-advisory-card h-100">
                                <div class="card-body p-4 d-flex flex-column justify-content-between">
                                    <div>
                                        <!-- Branding Header -->
                                        <div class="text-center mb-4">
                                            <div class="gms-advisory-logo-wrap mx-auto mb-2">
                                                <img :src="logoUrl" alt="DADA Logo" class="gms-advisory-logo">
                                            </div>
                                            <h6 class="fw-bold mb-1 text-dark">{{ $t('DADA (Dhaka) Ltd.') }}</h6>
                                            <span class="badge bg-light text-secondary border px-2 py-1" style="font-size: 10px;">{{ $t('Redressal Committee') }}</span>
                                        </div>

                                        <!-- Warning Box (সতর্কীকরণ) -->
                                        <div class="gms-warning-box mb-4">
                                            <div class="d-flex gap-2">
                                                <i class="bi bi-exclamation-triangle-fill text-warning fs-5"></i>
                                                <div>
                                                    <h6 class="fw-bold text-dark mb-1 small-title">{{ $t('Notice') }}</h6>
                                                    <p class="mb-0 text-muted extra-small">
                                                        {{ $t('Please do not share any confidential passwords or personal financial transaction information in the grievance description.') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Guidelines List -->
                                        <div class="mb-4">
                                            <h6 class="fw-bold text-dark mb-3 small-title">
                                                <i class="bi bi-info-circle text-primary me-2"></i>{{ $t('Guidelines') }}
                                            </h6>
                                            <ul class="gms-guide-list ps-0 mb-0">
                                                <li class="gms-guide-item">
                                                    <span class="gms-guide-num">1</span>
                                                    <div class="gms-guide-text" v-html="$t('Select the correct category and describe your grievance clearly and in detail.')"></div>
                                                </li>
                                                <li class="gms-guide-item">
                                                    <span class="gms-guide-num">2</span>
                                                    <div class="gms-guide-text" v-html="$t('Attach any supporting evidence (images, videos, documents) if available.')"></div>
                                                </li>
                                                <li class="gms-guide-item">
                                                    <span class="gms-guide-num">3</span>
                                                    <div class="gms-guide-text" v-html="$t('Copy and save the Ticket Number after submission for tracking.')"></div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    <!-- Security shield info -->
                                    <div class="gms-security-shield text-center p-3 border rounded-3 bg-light-subtle">
                                        <i class="bi bi-shield-lock-fill text-success fs-4 mb-2 d-block"></i>
                                        <span class="fw-bold text-dark d-block small">{{ $t('100% Encrypted & Confidential') }}</span>
                                        <span class="text-muted extra-small">{{ $t('Your inputs are protected by secure industry standards.') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tracker List View -->
                <div v-else key="track-view">
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
            </Transition>
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
import { showToast, showGrievanceSuccess } from '../../utils/alert.js';
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
    activeTab:     { type: String, default: 'submit' },
    totalRecords:  { type: Number, default: 0 }
});

const emit = defineEmits(['update:activeTab', 'update:totalRecords']);

const statDefs = {
    submitted:     { label: 'New Submitted',  icon: 'bi-inbox-fill'        },
    under_review:  { label: 'Under Review',   icon: 'bi-eye-fill'          },
    in_resolution: { label: 'In Resolution',  icon: 'bi-tools'             },
    resolved:      { label: 'Resolved',       icon: 'bi-check-circle-fill' },
};

const activeTab = computed({
    get: () => props.activeTab,
    set: (val) => emit('update:activeTab', val)
});

const newTicket    = ref(props.initialTicket);
const isSubmitting = ref(false);
const uploadProgress = ref(0);
const formRef      = ref(null);
const search       = ref('');
const filterStatus = ref('');
const grievances   = ref([]);
const statusCounts = ref(Object.assign({}, props.initialCounts));

const totalRecords = computed({
    get: () => props.totalRecords,
    set: (val) => emit('update:totalRecords', val)
});

const tableLoading = ref(false);
const pagination   = ref({ current_page: 1, last_page: 1 });
const modal        = reactive({ open: false, loading: false, error: false, data: null });
const lightbox     = reactive({ open: false, index: 0 });

const mediaImages = computed(() => {
    if (!modal.data?.media) return [];
    return modal.data.media.filter(m => m.type === 'image');
});

async function copyTicket(ticket) {
    if (!ticket) return;
    const successMsg = window.translations['✓ Ticket number copied!'] || '✓ Ticket number copied!';
    const failMsg    = window.translations['Copy failed — please copy manually'] || 'Copy failed — please copy manually';
    try {
        if (navigator.clipboard?.writeText) {
            await navigator.clipboard.writeText(ticket);
            showToast(successMsg, 'success');
        } else {
            const ta = document.createElement('textarea');
            ta.value = ticket;
            ta.style.cssText = 'position:fixed;opacity:0';
            document.body.appendChild(ta);
            ta.focus(); ta.select();
            const ok = document.execCommand('copy');
            document.body.removeChild(ta);
            showToast(ok ? successMsg : failMsg, ok ? 'success' : 'error');
        }
    } catch {
        showToast(failMsg, 'error');
    }
}



async function handleSubmit(formData, files) {
    isSubmitting.value = true;
    uploadProgress.value = 0;
    try {
        const fd = new FormData();
        fd.append('category_id', formData.category_id);
        if (formData.department_id) fd.append('department_id', formData.department_id);
        if (formData.employee_id)   fd.append('employee_id',   formData.employee_id);
        fd.append('description', formData.description);
        files.forEach((f, i) => fd.append(`files[${i}]`, f));
        const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (csrf) fd.append('_token', csrf);
        const res = await axios.post(props.storeUrl, fd, { 
            headers: { 'Content-Type': 'multipart/form-data' },
            onUploadProgress: (progressEvent) => {
                if (progressEvent.total) {
                    uploadProgress.value = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                }
            }
        });
        if (res.data.success) {
            formRef.value?.reset();
            isSubmitting.value = false;
            fetchGrievances(1);
            activeTab.value = 'track';
            showGrievanceSuccess(res.data.ticket_number);
        }
        return { success: res.data.success, message: res.data.message };
    } catch (err) {
        if (err.response?.status === 422) {
            formRef.value?.setErrors(err.response.data.errors);
            return { success: false, errors: err.response.data.errors };
        }
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
