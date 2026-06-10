<template>
    <div class="gms-scope">

        <!-- ── Toast ─────────────────────────────────────────────── -->
        <Teleport to="body">
            <div :class="['gms-toast', toast.show && 'show']">
                <i class="bi bi-check2-circle"></i> {{ toast.message }}
            </div>
        </Teleport>

        <!-- ── Topbar ─────────────────────────────────────────────── -->
        <header class="gms-topbar">
            <div class="gms-topbar-brand">
                <div class="gms-brand-icon"><i class="bi bi-shield-check"></i></div>
                <div>
                    <div class="gms-brand-name">DADA (Dhaka) Ltd.</div>
                    <div class="gms-brand-sub">Grievance Management System</div>
                </div>
            </div>
            <div class="gms-topbar-badge">
                <i class="bi bi-person-badge me-1"></i> GMS Portal
            </div>
        </header>

        <!-- ── Page Wrap ──────────────────────────────────────────── -->
        <div class="gms-page-wrap">

            <!-- Page Header -->
            <div class="gms-page-header">
                <div class="gms-page-title">Grievance Redressal Portal</div>
                <div class="gms-page-sub">
                    <i class="bi bi-chat-square-dots me-1"></i>
                    অভিযোগ প্রতিকার মাধ্যম — Submit and track your grievances
                </div>
            </div>

            <!-- ── Success Alert (after submit) ──────────────────── -->
            <Transition name="gms-fade">
                <div v-if="newTicket" class="gms-alert-success" style="position:relative;">
                    <i class="bi bi-check-circle-fill gms-alert-icon"></i>
                    <div style="flex:1">
                        <strong style="font-size:.9rem;">Grievance submitted successfully!</strong>
                        <div style="margin-top:.5rem;font-size:.8rem;opacity:.8;margin-bottom:.5rem;">
                            Save your ticket number to track your grievance status.
                        </div>
                        <div class="gms-ticket-display" @click="copyTicket(newTicket)">
                            {{ newTicket }}
                            <i class="bi bi-copy"></i>
                        </div>
                    </div>
                    <button class="gms-close-btn" @click="newTicket = null">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            </Transition>

            <!-- ── Error Alert ────────────────────────────────────── -->
            <Transition name="gms-fade">
                <div v-if="formErrors.length" class="gms-alert-error">
                    <div style="font-weight:700;font-size:.88rem;color:var(--danger-color);margin-bottom:.4rem;">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i> Please fix the following errors:
                    </div>
                    <ul style="margin:0;padding-left:1.2rem;">
                        <li v-for="(err, i) in formErrors" :key="i"
                            style="font-size:.82rem;margin-bottom:.15rem;">{{ err }}</li>
                    </ul>
                </div>
            </Transition>

            <!-- ── Main Grid ──────────────────────────────────────── -->
            <div class="gms-main-grid">

                <!-- ── LEFT: Submit Form ──────────────────────────── -->
                <div class="gms-form-panel">
                    <div class="gms-card" style="border-radius:var(--gms-radius);">
                        <div class="gms-form-panel-header">
                            <div class="gms-form-panel-title">
                                <i class="bi bi-pencil-square me-1"></i> Submit New Grievance
                            </div>
                            <div class="gms-form-panel-sub">অভিযোগ জমা দিন</div>
                        </div>

                        <div class="gms-form-panel-body">
                            <form @submit.prevent="confirmSubmit" novalidate>

                                <!-- Category -->
                                <div class="gms-field-group">
                                    <label class="gms-field-label" for="gms_category">
                                        <i class="bi bi-tag me-1"></i> Category
                                        <span class="req">*</span>
                                    </label>
                                    <select
                                        id="gms_category"
                                        v-model="form.category_id"
                                        class="gms-control"
                                        :class="{ 'gms-control--error': fieldErrors.category_id }"
                                        :disabled="isSubmitting"
                                        required
                                    >
                                        <option value="">— Select a category —</option>
                                        <option
                                            v-for="cat in categories"
                                            :key="cat.id"
                                            :value="cat.id"
                                        >{{ cat.name }}</option>
                                    </select>
                                    <div v-if="fieldErrors.category_id" class="gms-field-error">
                                        {{ fieldErrors.category_id[0] }}
                                    </div>
                                </div>

                                <!-- Department -->
                                <div class="gms-field-group">
                                    <label class="gms-field-label" for="gms_dept">
                                        <i class="bi bi-building me-1"></i> Department
                                        <span class="gms-badge-opt">Optional</span>
                                    </label>
                                    <select
                                        id="gms_dept"
                                        v-model="form.department_id"
                                        class="gms-control"
                                        :disabled="isSubmitting"
                                    >
                                        <option value="">— Select a department —</option>
                                        <option
                                            v-for="dept in departments"
                                            :key="dept.id"
                                            :value="dept.id"
                                        >{{ dept.name }}</option>
                                    </select>
                                </div>

                                <!-- Employee ID -->
                                <div class="gms-field-group">
                                    <label class="gms-field-label" for="gms_emp">
                                        <i class="bi bi-person-badge me-1"></i> Employee ID
                                        <span class="gms-badge-opt">Optional</span>
                                    </label>
                                    <input
                                        id="gms_emp"
                                        type="text"
                                        v-model="form.employee_id"
                                        class="gms-control"
                                        placeholder="e.g. EMP-1023"
                                        :disabled="isSubmitting"
                                    />
                                </div>

                                <div class="gms-divider"></div>

                                <!-- Description -->
                                <div class="gms-field-group">
                                    <label class="gms-field-label" for="gms_desc">
                                        <i class="bi bi-chat-text me-1"></i> Issue Description
                                        <span class="req">*</span>
                                    </label>
                                    <textarea
                                        id="gms_desc"
                                        v-model="form.description"
                                        class="gms-control"
                                        :class="{ 'gms-control--error': fieldErrors.description }"
                                        rows="6"
                                        maxlength="5000"
                                        placeholder="Describe your grievance clearly and in detail…"
                                        :disabled="isSubmitting"
                                        required
                                        @input="clearFieldError('description')"
                                    ></textarea>
                                    <div class="gms-char-bar">
                                        <span
                                            class="gms-char-count"
                                            :style="form.description.length > 4800 ? 'color:var(--danger-color)' : ''"
                                        >{{ form.description.length }} / 5000</span>
                                    </div>
                                    <div v-if="fieldErrors.description" class="gms-field-error">
                                        {{ fieldErrors.description[0] }}
                                    </div>
                                </div>

                                <!-- File Upload -->
                                <div class="gms-field-group" style="margin-bottom:0;">
                                    <label class="gms-field-label">
                                        <i class="bi bi-paperclip me-1"></i> Attachments
                                        <span class="gms-badge-opt">Optional</span>
                                    </label>

                                    <!-- Drop Zone -->
                                    <div
                                        v-if="uploadedFiles.length === 0"
                                        class="gms-drop-zone"
                                        :class="{ 'drag-active': isDragging }"
                                        @click="openFilePicker"
                                        @dragover.prevent="isDragging = true"
                                        @dragleave="isDragging = false"
                                        @drop.prevent="onDrop"
                                    >
                                        <div class="gms-drop-zone-icon"><i class="bi bi-cloud-upload"></i></div>
                                        <div class="gms-drop-zone-text">Click to browse or drag &amp; drop</div>
                                        <div class="gms-drop-zone-hint">Images · Videos · PDF · Word · Excel — max 100 MB each</div>
                                    </div>

                                    <!-- File List -->
                                    <div v-else class="gms-file-list">
                                        <div
                                            v-for="(f, i) in uploadedFiles"
                                            :key="i"
                                            class="gms-file-row"
                                        >
                                            <i :class="['bi', fileIcon(f), 'gms-file-icon']"></i>
                                            <div style="flex:1;min-width:0;">
                                                <div class="gms-file-name">{{ f.name }}</div>
                                                <div class="gms-file-size">{{ fmtSize(f.size) }}</div>
                                            </div>
                                            <button
                                                type="button"
                                                class="gms-file-remove"
                                                @click="removeFile(i)"
                                                title="Remove"
                                            ><i class="bi bi-x-lg"></i></button>
                                        </div>
                                    </div>

                                    <button
                                        v-if="uploadedFiles.length > 0"
                                        type="button"
                                        class="gms-btn-add-more"
                                        @click="openFilePicker"
                                    >
                                        <i class="bi bi-plus-circle"></i> Add more files
                                    </button>

                                    <!-- Hidden file input -->
                                    <input
                                        ref="fileInput"
                                        type="file"
                                        multiple
                                        accept="image/*,video/mp4,video/avi,video/mov,video/mkv,video/webm,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/plain,text/csv"
                                        style="display:none;"
                                        @change="onFileChange"
                                    />
                                </div>

                                <!-- Submit -->
                                <button
                                    type="submit"
                                    class="gms-btn-primary-full"
                                    :disabled="!isFormValid || isSubmitting"
                                >
                                    <template v-if="isSubmitting">
                                        <span class="gms-spin"></span>
                                        Submitting…
                                    </template>
                                    <template v-else>
                                        <i class="bi bi-send-fill"></i>
                                        Submit Grievance
                                    </template>
                                </button>

                                <div style="text-align:center;margin-top:.85rem;">
                                    <span style="font-size:.72rem;color:var(--gms-txt-sub);">
                                        <i class="bi bi-lock me-1"></i> Secure &amp; confidential
                                    </span>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- ── RIGHT: Stats + Table ───────────────────────── -->
                <div>
                    <!-- Stat Cards -->
                    <div class="gms-stat-grid">
                        <div
                            v-for="(s, key) in statDefs"
                            :key="key"
                            :class="['gms-stat-card', s.cls, filterStatus === key && 'active']"
                            @click="toggleFilter(key)"
                        >
                            <div class="gms-stat-icon"><i :class="['bi', s.icon]"></i></div>
                            <div class="gms-stat-num">{{ statusCounts[key] ?? 0 }}</div>
                            <div class="gms-stat-label">{{ s.label }}</div>
                            <div class="gms-stat-bn">{{ s.bn }}</div>
                        </div>
                    </div>

                    <!-- Table Card -->
                    <div class="gms-card">
                        <div class="gms-card-header">
                            <div>
                                <div class="gms-card-title">
                                    <i class="bi bi-ticket-perforated"></i> All Grievances
                                </div>
                                <div class="gms-card-sub">{{ totalRecords }} total records</div>
                            </div>

                            <!-- Filters -->
                            <div class="gms-toolbar-right">
                                <div class="gms-search-wrap">
                                    <i class="bi bi-search"></i>
                                    <input
                                        v-model="search"
                                        type="text"
                                        class="gms-search-input"
                                        placeholder="Search ticket…"
                                        @input="onSearchInput"
                                    />
                                </div>
                                <select
                                    v-model="filterStatus"
                                    class="gms-filter-select"
                                    @change="fetchGrievances(1)"
                                >
                                    <option value="">All Status</option>
                                    <option v-for="(s, key) in statDefs" :key="key" :value="key">
                                        {{ s.label }}
                                    </option>
                                </select>
                                <button
                                    v-if="search || filterStatus"
                                    type="button"
                                    class="gms-btn-clear"
                                    @click="clearFilters"
                                    title="Clear filters"
                                >
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Table -->
                        <div class="gms-table-wrap">
                            <table class="gms-table">
                                <thead>
                                    <tr>
                                        <th style="padding-left:1.25rem;">Ticket #</th>
                                        <th>Category</th>
                                        <th class="th-dept">Department</th>
                                        <th>Status</th>
                                        <th class="th-date">Submitted</th>
                                        <th style="width:2rem;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Loading skeleton -->
                                    <template v-if="tableLoading">
                                        <tr v-for="n in 5" :key="'sk-'+n">
                                            <td colspan="6" style="padding:0;">
                                                <div class="gms-skeleton" style="margin:.5rem 1.25rem;height:32px;border-radius:6px;"></div>
                                            </td>
                                        </tr>
                                    </template>

                                    <template v-else-if="grievances && grievances.length > 0">
                                        <tr
                                            v-for="g in grievances"
                                            :key="g.id"
                                            @click="showDetail(g.id)"
                                        >
                                            <td style="padding-left:1.25rem;" class="gms-ticket-no">{{ g.ticket_number }}</td>
                                            <td>{{ g.category ? g.category.name : '—' }}</td>
                                            <td class="td-dept" style="color:var(--gms-txt-sub);">{{ g.department ? g.department.name : '—' }}</td>
                                            <td>
                                                <span :class="['gms-badge-status', statusClass(g.status)]">
                                                    {{ g.status_label }}
                                                </span>
                                            </td>
                                            <td class="td-date" style="color:var(--gms-txt-sub);font-size:.8rem;">
                                                {{ fmtDate(g.created_at) }}
                                            </td>
                                            <td style="color:#cbd5e1;text-align:right;padding-right:1rem;">
                                                <i class="bi bi-chevron-right" style="font-size:.75rem;"></i>
                                            </td>
                                        </tr>
                                    </template>

                                    <tr v-else>
                                        <td colspan="6">
                                            <div class="gms-empty-state">
                                                <i class="bi bi-inbox"></i>
                                                <p>No grievances found.</p>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div v-if="pagination.last_page > 1" class="gms-table-footer">
                            <div class="gms-pagination">
                                <button
                                    class="gms-page-btn"
                                    :disabled="pagination.current_page === 1"
                                    @click="fetchGrievances(pagination.current_page - 1)"
                                >
                                    <i class="bi bi-chevron-left"></i>
                                </button>
                                <button
                                    v-for="p in pageNumbers"
                                    :key="p"
                                    :class="['gms-page-btn', p === pagination.current_page && 'active']"
                                    @click="p !== '…' && fetchGrievances(p)"
                                >{{ p }}</button>
                                <button
                                    class="gms-page-btn"
                                    :disabled="pagination.current_page === pagination.last_page"
                                    @click="fetchGrievances(pagination.current_page + 1)"
                                >
                                    <i class="bi bi-chevron-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ── Detail Modal ───────────────────────────────────────── -->
        <Teleport to="body">
            <Transition name="gms-modal">
                <div
                    v-if="modal.open"
                    class="gms-modal-overlay"
                    @click.self="modal.open = false"
                >
                    <div class="gms-modal-wrap">
                        <div class="modal-content">
                            <div class="gms-modal-header">
                                <div class="gms-modal-title">
                                    <i class="bi bi-ticket-perforated"></i> Grievance Details
                                </div>
                                <button class="gms-modal-close" @click="modal.open = false">
                                    <i class="bi bi-x-lg" style="font-size:.8rem;"></i>
                                </button>
                            </div>

                            <!-- Modal Body: Loading -->
                            <div v-if="modal.loading" class="gms-modal-body" style="text-align:center;padding:3rem 0;">
                                <div class="gms-spin" style="margin:0 auto 1rem;border-color:rgba(0,0,0,.1);border-top-color:var(--gms-primary);width:28px;height:28px;border-width:3px;"></div>
                                <span style="font-size:.82rem;color:var(--gms-txt-sub);">Loading details…</span>
                            </div>

                            <!-- Modal Body: Error -->
                            <div v-else-if="modal.error" class="gms-modal-body" style="text-align:center;padding:3rem 0;color:var(--danger-color);">
                                <i class="bi bi-exclamation-circle" style="font-size:2rem;display:block;margin-bottom:.75rem;"></i>
                                <span style="font-size:.85rem;">Failed to load grievance details.</span>
                            </div>

                            <!-- Modal Body: Data -->
                            <div v-else-if="modal.data" class="gms-modal-body">
                                <!-- Ticket + Status -->
                                <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;flex-wrap:wrap;margin-bottom:1.25rem;">
                                    <div>
                                        <div class="gms-detail-label">Ticket Number</div>
                                        <div class="gms-ticket-copy-btn" @click="copyTicket(modal.data.ticket_number)">
                                            <span style="font-family:'Courier New',monospace;font-size:1.3rem;font-weight:800;color:var(--gms-primary);">
                                                {{ modal.data.ticket_number }}
                                            </span>
                                            <i class="bi bi-copy gms-copy-icon"></i>
                                        </div>
                                    </div>
                                    <span :class="['gms-badge-status', statusClass(modal.data.status)]">
                                        {{ modal.data.status_label }}
                                    </span>
                                </div>

                                <!-- Meta Grid -->
                                <div style="display:grid;grid-template-columns:1fr 1fr;gap:.9rem 1.5rem;margin-bottom:1.1rem;">
                                    <div>
                                        <div class="gms-detail-label">Category</div>
                                        <div class="gms-detail-val">{{ modal.data.category ?? '—' }}</div>
                                    </div>
                                    <div>
                                        <div class="gms-detail-label">Department</div>
                                        <div class="gms-detail-val">{{ modal.data.department ?? '—' }}</div>
                                    </div>
                                    <div>
                                        <div class="gms-detail-label">Employee ID</div>
                                        <div class="gms-detail-val">{{ modal.data.employee_id ?? '—' }}</div>
                                    </div>
                                    <div>
                                        <div class="gms-detail-label">Submitted</div>
                                        <div class="gms-detail-val">{{ modal.data.submitted_at }}</div>
                                    </div>
                                    <div v-if="modal.data.resolved_at">
                                        <div class="gms-detail-label">Resolved</div>
                                        <div class="gms-detail-val">{{ modal.data.resolved_at }}</div>
                                    </div>
                                </div>

                                <!-- Description -->
                                <div>
                                    <div class="gms-detail-label" style="margin-bottom:.4rem;">Description</div>
                                    <div class="gms-detail-box">{{ modal.data.description }}</div>
                                </div>

                                <!-- Admin Remarks -->
                                <div v-if="modal.data.admin_remarks" style="margin-top:1rem;">
                                    <div class="gms-detail-label" style="margin-bottom:.4rem;">Admin Remarks</div>
                                    <div class="gms-remarks-box">{{ modal.data.admin_remarks }}</div>
                                </div>

                                <!-- Attachments -->
                                <template v-if="modalMedia.length">
                                    <div class="gms-divider"></div>
                                    <div style="font-size:.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--gms-txt-sub);margin-bottom:.6rem;">
                                        <i class="bi bi-paperclip me-1"></i> Attachments ({{ modalMedia.length }})
                                    </div>
                                    <div
                                        v-for="m in modalMedia"
                                        :key="m.url"
                                        class="gms-attach-row"
                                    >
                                        <i :class="['bi', m.type === 'image' ? 'bi-file-earmark-image text-success' : m.type === 'video' ? 'bi-file-earmark-play text-danger' : 'bi-file-earmark-text text-secondary']" style="font-size:1.1rem;"></i>
                                        <div style="flex:1;min-width:0;">
                                            <div style="font-weight:600;font-size:.82rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                                {{ m.original_name || m.file_name }}
                                            </div>
                                            <div style="font-size:.7rem;color:var(--gms-txt-sub);">{{ m.size }}</div>
                                        </div>
                                        <a
                                            v-if="m.url"
                                            :href="m.url"
                                            target="_blank"
                                            class="gms-btn-outline"
                                            style="font-size:.75rem;padding:.3rem .7rem;text-decoration:none;"
                                        >
                                            <i class="bi bi-eye me-1"></i>View
                                        </a>
                                    </div>
                                </template>
                            </div>

                            <div class="gms-modal-footer">
                                <button class="gms-btn-outline" @click="modal.open = false">Close</button>
                                <button
                                    class="gms-btn-primary"
                                    :disabled="!modal.data"
                                    @click="modal.data && copyTicket(modal.data.ticket_number)"
                                >
                                    <i class="bi bi-copy"></i> Copy Ticket
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </Transition>
        </Teleport>
    </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted, watch } from 'vue';

// ── Props ────────────────────────────────────────────────────────
const props = defineProps({
    categories:   { type: Array,  default: () => [] },
    departments:  { type: Array,  default: () => [] },
    storeUrl:     { type: String, required: true },
    listUrl:      { type: String, required: true },
    showUrl:      { type: String, required: true }, // e.g. '/grievance/{id}' — we replace {id}
    initialCounts:{ type: Object, default: () => ({}) },
    initialTicket: { type: String, default: null },
});

// ── Static config ────────────────────────────────────────────────
const statDefs = {
    submitted:     { label: 'Submitted',    bn: 'জমা দেওয়া',     icon: 'bi-inbox',        cls: 's-primary' },
    under_review:  { label: 'Under Review', bn: 'পর্যালোচনায়',  icon: 'bi-eye',          cls: 's-amber'   },
    in_resolution: { label: 'In Resolution',bn: 'সমাধানের পথে', icon: 'bi-tools',        cls: 's-danger'  },
    resolved:      { label: 'Resolved',     bn: 'সমাধান করা',    icon: 'bi-check-circle', cls: 's-success' },
};

// ── Reactive state ───────────────────────────────────────────────
const newTicket      = ref(props.initialTicket);
const formErrors     = ref([]);
const fieldErrors    = ref({});
const isSubmitting   = ref(false);
const isDragging     = ref(false);
const uploadedFiles  = ref([]);
const fileInput      = ref(null);
const search         = ref('');
const filterStatus   = ref('');
const grievances     = ref([]);
const statusCounts   = ref(Object.assign({ submitted: 0, under_review: 0, in_resolution: 0, resolved: 0 }, props.initialCounts));
const totalRecords   = ref(0);
const tableLoading   = ref(false);
const pagination     = ref({ current_page: 1, last_page: 1 });
const toast          = reactive({ show: false, message: '' });
let   searchTimer    = null;

const form = reactive({
    category_id:   '',
    department_id: '',
    employee_id:   '',
    description:   '',
});

const modal = reactive({
    open:    false,
    loading: false,
    error:   false,
    data:    null,
});

// ── Computed ─────────────────────────────────────────────────────
const isFormValid = computed(() =>
    form.category_id && form.description.trim().length > 0
);

// Safely derive media list from modal data — avoids ?. in template
const modalMedia = computed(() => {
    if (!modal.data) return [];
    return Array.isArray(modal.data.media) ? modal.data.media : [];
});

const pageNumbers = computed(() => {
    const cur  = pagination.value.current_page;
    const last = pagination.value.last_page;
    if (last <= 7) return Array.from({ length: last }, (_, i) => i + 1);
    const pages = new Set([1, last, cur, cur - 1, cur + 1].filter(p => p >= 1 && p <= last));
    const arr = [...pages].sort((a, b) => a - b);
    const result = [];
    arr.forEach((p, i) => {
        if (i > 0 && p - arr[i - 1] > 1) result.push('…');
        result.push(p);
    });
    return result;
});

// ── Helpers ───────────────────────────────────────────────────────
function fmtSize(b) {
    if (!b) return '';
    const s = ['B', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(b) / Math.log(1024));
    return (b / Math.pow(1024, i)).toFixed(1) + ' ' + s[i];
}

function fmtDate(iso) {
    if (!iso) return '—';
    return new Date(iso).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
}

function fileIcon(f) {
    const m = f.type || '';
    if (m.startsWith('image/'))  return 'bi-file-earmark-image text-success';
    if (m.startsWith('video/'))  return 'bi-file-earmark-play text-danger';
    if (m.includes('pdf'))       return 'bi-file-earmark-pdf text-danger';
    if (m.includes('word'))      return 'bi-file-earmark-word text-primary';
    if (m.includes('excel') || m.includes('spreadsheet')) return 'bi-file-earmark-excel text-success';
    return 'bi-file-earmark text-secondary';
}

function statusClass(status) {
    return {
        submitted:     'bs-submitted',
        under_review:  'bs-under_review',
        in_resolution: 'bs-in_resolution',
        resolved:      'bs-resolved',
    }[status] ?? 'bs-default';
}

function clearFieldError(field) {
    delete fieldErrors.value[field];
}

// ── Toast ─────────────────────────────────────────────────────────
function showToast(msg) {
    toast.message = msg;
    toast.show    = true;
    setTimeout(() => { toast.show = false; }, 2200);
}

function copyTicket(ticket) {
    navigator.clipboard.writeText(ticket).then(() => showToast('Copied to clipboard'));
}

// ── File handling ─────────────────────────────────────────────────
function openFilePicker() { fileInput.value.value = ''; fileInput.value.click(); }

function addFiles(newFiles) {
    for (const f of newFiles) {
        if (f.size > 100 * 1024 * 1024) { alert(`"${f.name}" exceeds 100 MB.`); continue; }
        if (!uploadedFiles.value.some(x => x.name === f.name && x.size === f.size)) {
            uploadedFiles.value.push(f);
        }
    }
}

function onFileChange(e)         { addFiles([...e.target.files]); }
function onDrop(e)               { isDragging.value = false; addFiles([...e.dataTransfer.files]); }
function removeFile(i)           { uploadedFiles.value.splice(i, 1); }

// ── Form validation ───────────────────────────────────────────────
function validate() {
    fieldErrors.value = {};
    formErrors.value  = [];

    if (!form.category_id) {
        fieldErrors.value.category_id = ['Category is required'];
        formErrors.value.push('Category is required');
    }
    if (!form.description.trim()) {
        fieldErrors.value.description = ['Issue description is required'];
        formErrors.value.push('Issue description is required');
    }
    return formErrors.value.length === 0;
}

// ── Submit ────────────────────────────────────────────────────────
async function confirmSubmit() {
    if (!validate()) return;
    await submitGrievance();
}

async function submitGrievance() {
    isSubmitting.value = true;
    formErrors.value   = [];

    try {
        const fd = new FormData();
        fd.append('category_id', form.category_id);
        if (form.department_id) fd.append('department_id', form.department_id);
        if (form.employee_id)   fd.append('employee_id',   form.employee_id);
        fd.append('description', form.description);

        uploadedFiles.value.forEach((f, i) => fd.append(`files[${i}]`, f));

        const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (csrf) fd.append('_token', csrf);

        const res = await axios.post(props.storeUrl, fd, {
            headers: { 'Content-Type': 'multipart/form-data' },
        });

        if (res.data.success) {
            newTicket.value = res.data.ticket_number;
            // Reset form
            form.category_id   = '';
            form.department_id = '';
            form.employee_id   = '';
            form.description   = '';
            uploadedFiles.value = [];
            fieldErrors.value   = {};
            // Refresh table
            await fetchGrievances(1);
        } else {
            formErrors.value = [res.data.message || 'Failed to submit grievance'];
        }
    } catch (err) {
        if (err.response?.status === 422) {
            const errs = err.response.data.errors || {};
            fieldErrors.value = errs;
            formErrors.value  = Object.values(errs).flat();
        } else {
            formErrors.value = [err.message || 'Something went wrong'];
        }
    } finally {
        isSubmitting.value = false;
    }
}

// ── Table / Fetch ─────────────────────────────────────────────────
async function fetchGrievances(page = 1) {
    tableLoading.value = true;
    try {
        const params = new URLSearchParams();
        params.set('page', page);
        if (search.value)       params.set('search', search.value);
        if (filterStatus.value) params.set('status', filterStatus.value);

        const res = await axios.get(`${props.listUrl}?${params}`, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        });

        grievances.value   = Array.isArray(res.data.data) ? res.data.data : [];
        pagination.value   = {
            current_page: res.data.current_page ?? 1,
            last_page:    res.data.last_page    ?? 1,
        };
        totalRecords.value = res.data.total ?? 0;
        if (res.data.status_counts && typeof res.data.status_counts === 'object') {
            statusCounts.value = res.data.status_counts;
        }
    } catch (e) {
        console.error('Fetch error:', e);
    } finally {
        tableLoading.value = false;
    }
}

function toggleFilter(key) {
    filterStatus.value = filterStatus.value === key ? '' : key;
    fetchGrievances(1);
}

function clearFilters() {
    search.value       = '';
    filterStatus.value = '';
    fetchGrievances(1);
}

function onSearchInput() {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(() => fetchGrievances(1), 400);
}

// ── Detail Modal ──────────────────────────────────────────────────
async function showDetail(id) {
    modal.open    = true;
    modal.loading = true;
    modal.error   = false;
    modal.data    = null;

    try {
        const url = props.showUrl.replace('{id}', id).replace(':id', id);
        const res = await axios.get(url, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        });
        modal.data = res.data;
    } catch (e) {
        modal.error = true;
    } finally {
        modal.loading = false;
    }
}

// ── Init ──────────────────────────────────────────────────────────
onMounted(() => {
    fetchGrievances(1);
});
</script>

<style scoped>
/* ── Transitions ─────────────────────────────────────────────── */
.gms-fade-enter-active, .gms-fade-leave-active { transition: opacity .3s, transform .3s; }
.gms-fade-enter-from, .gms-fade-leave-to       { opacity: 0; transform: translateY(-6px); }

.gms-modal-enter-active, .gms-modal-leave-active { transition: opacity .25s; }
.gms-modal-enter-from, .gms-modal-leave-to       { opacity: 0; }
.gms-modal-enter-active .gms-modal-wrap,
.gms-modal-leave-active .gms-modal-wrap          { transition: transform .25s, opacity .25s; }
.gms-modal-enter-from .gms-modal-wrap,
.gms-modal-leave-to .gms-modal-wrap              { transform: translateY(16px); opacity: 0; }

/* ── Modal overlay ───────────────────────────────────────────── */
.gms-modal-overlay {
    position:        fixed;
    inset:           0;
    background:      rgba(0, 0, 0, 0.45);
    backdrop-filter: blur(3px);
    z-index:         9999;
    display:         flex;
    align-items:     center;
    justify-content: center;
    padding:         1rem;
}

.gms-modal-wrap {
    width:     100%;
    max-width: 760px;
}

/* ── Skeleton ────────────────────────────────────────────────── */
.gms-skeleton {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: shimmer 1.4s infinite;
}

@keyframes shimmer {
    0%   { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}

/* ── Pagination ──────────────────────────────────────────────── */
.gms-pagination {
    display:     flex;
    align-items: center;
    gap:         .35rem;
    flex-wrap:   wrap;
}

.gms-page-btn {
    min-width:    32px;
    height:       32px;
    padding:      0 .5rem;
    border:       1.5px solid var(--gms-border);
    border-radius: 7px;
    background:   var(--gms-card);
    color:        var(--gms-txt-sub);
    font-size:    .82rem;
    font-weight:  600;
    cursor:       pointer;
    display:      inline-flex;
    align-items:  center;
    justify-content: center;
    transition:   all .15s;
}

.gms-page-btn:hover:not(:disabled):not(.active) {
    border-color: var(--gms-primary);
    color:        var(--gms-primary);
}

.gms-page-btn.active {
    background:   var(--gms-primary);
    border-color: var(--gms-primary);
    color:        #fff;
}

.gms-page-btn:disabled {
    opacity: .45;
    cursor:  not-allowed;
}

/* ── Error input state ───────────────────────────────────────── */
.gms-control--error {
    border-color: var(--danger-color) !important;
}

.gms-field-error {
    font-size:  .75rem;
    color:      var(--danger-color);
    margin-top: .3rem;
}

/* ── Responsive hide ─────────────────────────────────────────── */
@media (max-width: 768px) {
    .th-dept, :deep(.td-dept) { display: none; }
}
@media (max-width: 576px) {
    .th-date, :deep(.td-date) { display: none; }
}
</style>
