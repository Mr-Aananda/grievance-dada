<template>
    <Teleport to="body">
        <Transition name="gms-modal-fade">
            <div v-if="open" class="gms-modal-overlay" @click.self="$emit('close')">
                <div class="gms-modal" role="dialog" aria-modal="true">
                    <div class="gms-modal-header">
                        <h2 class="gms-modal-title">
                            <i class="bi bi-ticket-perforated"></i>
                            {{ $t('Grievance Details') }}
                        </h2>
                        <button class="gms-modal-close" @click="$emit('close')" aria-label="Close">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>

                    <div class="gms-modal-body">
                        <!-- Loading -->
                        <div v-if="loading" class="gms-modal-loading">
                            <div class="gms-spinner-large"></div>
                            <p>{{ $t('Loading grievance details...') }}</p>
                        </div>

                        <!-- Error -->
                        <div v-else-if="error" class="gms-modal-error">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            <h3>{{ $t('Failed to Load') }}</h3>
                            <p>{{ $t('Unable to fetch grievance details. Please try again.') }}</p>
                        </div>

                        <!-- Content -->
                        <div v-else-if="data" class="gms-details">
                            <!-- Header -->
                            <div class="gms-details-header">
                                <div>
                                    <div class="gms-details-label">{{ $t('Ticket Number') }}</div>
                                    <div class="gms-ticket-copy" @click="$emit('copy-ticket', data.ticket_number)">
                                        <span class="gms-ticket-number">{{ data.ticket_number }}</span>
                                        <i class="bi bi-copy"></i>
                                    </div>
                                </div>
                                <span :class="['gms-status-badge', getStatusClass(data.status)]">
                                    {{ data.status_label }}
                                </span>
                            </div>

                            <!-- Status Timeline Progress Steps -->
                            <div class="mb-5 text-center">
                                <div class="d-flex justify-content-between align-items-center position-relative mx-auto" style="max-width: 500px;">
                                    <!-- Progress Line Background -->
                                    <div class="position-absolute top-50 start-0 end-0 translate-middle-y bg-secondary-subtle" style="height: 4px; z-index: 1;"></div>
                                    <!-- Active Progress Line -->
                                    <div class="position-absolute top-50 start-0 translate-middle-y bg-success" :style="{ width: getStatusProgressPercent(data.status) + '%', height: '4px', zIndex: 2, transition: 'width 0.3s ease' }"></div>
                                    
                                    <!-- Step: Submitted -->
                                    <div class="position-relative" style="z-index: 3;">
                                        <div :class="['rounded-circle d-flex align-items-center justify-content-center shadow-sm', isStepActive('submitted', data.status) ? 'bg-success text-white border-0' : 'bg-white border text-muted']" style="width: 34px; height: 34px; font-size: 14px; transition: all 0.3s ease;">
                                            <i class="bi bi-inbox"></i>
                                        </div>
                                        <div class="small fw-semibold mt-1 position-absolute start-50 translate-middle-x text-nowrap text-muted" style="font-size: 10px; top: 36px;">{{ $t('Submitted') }}</div>
                                    </div>
                                    
                                    <!-- Step: Under Review -->
                                    <div class="position-relative" style="z-index: 3;">
                                        <div :class="['rounded-circle d-flex align-items-center justify-content-center shadow-sm', isStepActive('under_review', data.status) ? 'bg-success text-white border-0' : 'bg-white border text-muted']" style="width: 34px; height: 34px; font-size: 14px; transition: all 0.3s ease;">
                                            <i class="bi bi-eye"></i>
                                        </div>
                                        <div class="small fw-semibold mt-1 position-absolute start-50 translate-middle-x text-nowrap text-muted" style="font-size: 10px; top: 36px;">{{ $t('Under Review') }}</div>
                                    </div>
                                    
                                    <!-- Step: In Resolution -->
                                    <div class="position-relative" style="z-index: 3;">
                                        <div :class="['rounded-circle d-flex align-items-center justify-content-center shadow-sm', isStepActive('in_resolution', data.status) ? 'bg-success text-white border-0' : 'bg-white border text-muted']" style="width: 34px; height: 34px; font-size: 14px; transition: all 0.3s ease;">
                                            <i class="bi bi-tools"></i>
                                        </div>
                                        <div class="small fw-semibold mt-1 position-absolute start-50 translate-middle-x text-nowrap text-muted" style="font-size: 10px; top: 36px;">{{ $t('In Resolution') }}</div>
                                    </div>
                                    
                                    <!-- Step: Resolved -->
                                    <div class="position-relative" style="z-index: 3;">
                                        <div :class="['rounded-circle d-flex align-items-center justify-content-center shadow-sm', isStepActive('resolved', data.status) ? 'bg-success text-white border-0' : 'bg-white border text-muted']" style="width: 34px; height: 34px; font-size: 14px; transition: all 0.3s ease;">
                                            <i class="bi bi-check-circle"></i>
                                        </div>
                                        <div class="small fw-semibold mt-1 position-absolute start-50 translate-middle-x text-nowrap text-muted" style="font-size: 10px; top: 36px;">{{ $t('Resolved') }}</div>
                                    </div>
                                </div>
                                <div style="height: 30px;"></div> <!-- Spacer for absolute labels -->
                            </div>

                            <!-- Info Grid -->
                            <div class="gms-details-grid">
                                <div>
                                    <div class="gms-details-label">{{ $t('Category') }}</div>
                                    <div class="gms-details-value">{{ data.category || '—' }}</div>
                                </div>
                                <div>
                                    <div class="gms-details-label">{{ $t('Department') }}</div>
                                    <div class="gms-details-value">{{ data.department || '—' }}</div>
                                </div>
                                <div>
                                    <div class="gms-details-label">{{ $t('Employee ID') }}</div>
                                    <div class="gms-details-value">{{ data.employee_id || '—' }}</div>
                                </div>
                                <div>
                                    <div class="gms-details-label">{{ $t('Submitted') }}</div>
                                    <div class="gms-details-value">{{ formatDate(data.created_at) }}</div>
                                </div>
                                <div v-if="data.resolved_at">
                                    <div class="gms-details-label">{{ $t('Resolved') }}</div>
                                    <div class="gms-details-value">{{ formatDate(data.resolved_at) }}</div>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="gms-details-section">
                                <div class="gms-details-label">{{ $t('Description') }}</div>
                                <div class="gms-details-box ql-editor" v-html="data.description"></div>
                            </div>

                            <!-- Admin Remarks -->
                            <div v-if="data.admin_remarks" class="gms-details-section">
                                <div class="gms-details-label">{{ $t('Admin Remarks & Feedback') }}</div>
                                <div class="gms-remarks-box">
                                    <i class="bi bi-chat-dots-fill"></i>
                                    <p>{{ data.admin_remarks }}</p>
                                </div>
                            </div>

                            <!-- Attachments -->
                            <div v-if="attachments.length" class="gms-details-section">
                                <div class="gms-details-label">
                                    <i class="bi bi-paperclip"></i>
                                    {{ $t('Attachments') }} ({{ attachments.length }})
                                </div>
                                <div class="gms-attachments-grid">
                                    <!-- Images -->
                                    <div v-for="(att, idx) in imageAttachments" :key="'img-' + idx"
                                         class="gms-attachment-image"
                                         @click="openLightbox(idx)">
                                        <img :src="att.url" :alt="att.original_name || att.file_name" loading="lazy" />
                                        <div class="gms-image-overlay">
                                            <i class="bi bi-zoom-in"></i>
                                        </div>
                                    </div>

                                    <!-- Videos -->
                                    <div v-for="(att, idx) in videoAttachments" :key="'vid-' + idx"
                                         class="gms-attachment-video">
                                        <video :src="att.url" controls preload="metadata"></video>
                                        <div class="gms-video-info">
                                            <i class="bi bi-file-earmark-play"></i>
                                            <span>{{ att.original_name || att.file_name }}</span>
                                        </div>
                                    </div>

                                    <!-- Documents -->
                                    <div v-for="(att, idx) in documentAttachments" :key="'doc-' + idx"
                                         class="gms-attachment-doc">
                                        <i :class="['bi', getFileIcon(att.mime_type)]"></i>
                                        <div class="gms-doc-info">
                                            <div class="gms-doc-name">{{ att.original_name || att.file_name }}</div>
                                            <div class="gms-doc-meta">{{ att.size }}</div>
                                        </div>
                                        <div class="gms-doc-actions">
                                            <a :href="att.url + '?view=1'" target="_blank" rel="noopener" class="gms-btn-icon" :title="$t('View')">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a :href="att.url + '?download=1'" :download="att.file_name" class="gms-btn-icon" :title="$t('Download')">
                                                <i class="bi bi-download"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="gms-modal-footer">
                        <button class="gms-btn-secondary" @click="$emit('close')">
                            <i class="bi bi-x-lg"></i> {{ $t('Close') }}
                        </button>
                        <button v-if="data?.ticket_number" class="gms-btn-primary" @click="$emit('copy-ticket', data.ticket_number)">
                            <i class="bi bi-copy"></i> {{ $t('Copy Ticket') }}
                        </button>
                    </div>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    open: Boolean,
    loading: Boolean,
    error: Boolean,
    data: Object,
});

const emit = defineEmits(['close', 'copy-ticket', 'open-lightbox']);

const attachments = computed(() => {
    if (!props.data?.media) return [];
    return props.data.media;
});

const imageAttachments = computed(() => attachments.value.filter(att => att.type === 'image'));
const videoAttachments = computed(() => attachments.value.filter(att => att.type === 'video'));
const documentAttachments = computed(() => attachments.value.filter(att => att.type === 'document'));

function formatDate(date) {
    if (!date) return '—';
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

function formatFileSize(bytes) {
    if (!bytes) return '—';
    const units = ['B', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(1024));
    return (bytes / Math.pow(1024, i)).toFixed(1) + ' ' + units[i];
}

function getStatusClass(status) {
    const classes = {
        submitted: 'status-submitted',
        under_review: 'status-review',
        in_resolution: 'status-resolution',
        resolved: 'status-resolved'
    };
    return classes[status] || 'status-default';
}

function getStatusProgressPercent(status) {
    const percentages = {
        submitted: 0,
        under_review: 33,
        in_resolution: 66,
        resolved: 100
    };
    return percentages[status] ?? 0;
}

function isStepActive(step, currentStatus) {
    const order = ['submitted', 'under_review', 'in_resolution', 'resolved'];
    const stepIdx = order.indexOf(step);
    const currentIdx = order.indexOf(currentStatus);
    return stepIdx <= currentIdx;
}

function getFileIcon(mime) {
    if (!mime) return 'bi-file-earmark';
    if (mime.includes('pdf')) return 'bi-file-earmark-pdf';
    if (mime.includes('word')) return 'bi-file-earmark-word';
    if (mime.includes('excel') || mime.includes('sheet')) return 'bi-file-earmark-excel';
    if (mime.includes('text')) return 'bi-file-earmark-text';
    return 'bi-file-earmark';
}

// imageAttachments index matches the index used in the parent's mediaImages computed,
// since mediaImages also filters media by type === 'image' in the same order.
function openLightbox(index) {
    emit('open-lightbox', { index });
}
</script>

<style>
/* ============================================================
   Modal styles — written here (not in the shared _grievance.scss)
   because this component is rendered via <Teleport to="body">.

   WHY THE BACKGROUND WAS TRANSPARENT:
   Teleported content is moved OUTSIDE the .gms-scope element in the
   DOM, so any CSS custom properties defined only on .gms-scope (or
   on a scoped <style> block) are NOT inherited by it — the modal
   sits as a direct child of <body>. Any var(--gms-white) etc. with
   no fallback then resolves to nothing, and backgrounds/borders
   become transparent ("watery" look).

   FIX: every variable below has a hardcoded fallback
   var(--token, #fallback) so the modal looks correct even with zero
   inherited CSS variables, but still picks up your real theme colors
   whenever they ARE available (e.g. if .gms-scope wraps <body> or
   the variables are declared on :root globally).
   ============================================================ */

.gms-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(15, 23, 42, 0.6);
    -webkit-backdrop-filter: blur(3px);
    backdrop-filter: blur(3px);
    z-index: 1050;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    overflow-y: auto;
}

.gms-modal {
    background-color: var(--white-color, #ffffff);
    background-clip: padding-box;
    isolation: isolate;
    border-radius: 12px;
    width: 100%;
    max-width: 760px;
    max-height: calc(100vh - 40px);
    overflow: hidden;
    display: flex;
    flex-direction: column;
    box-shadow: 0 12px 32px rgb(34 41 47 / 18%);
    margin: auto;
}

.gms-modal-header {
    padding: 18px 24px;
    background: linear-gradient(135deg, var(--theme-color, #c98643), var(--theme-darken, #b87338));
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-shrink: 0;
}

.gms-modal-title {
    font-size: 17px;
    font-weight: 700;
    color: #ffffff;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.gms-modal-close {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: rgba(255, 255, 255, 0.15);
    border: none;
    color: #ffffff;
    cursor: pointer;
    transition: background 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.gms-modal-close:hover {
    background: rgba(255, 255, 255, 0.25);
}

.gms-modal-body {
    padding: 24px;
    overflow-y: auto;
    flex: 1;
    min-height: 0;
    background-color: var(--white-color, #ffffff);
    color: var(--black-color, #1f2d3a);
}

.gms-modal-footer {
    padding: 14px 24px;
    border-top: 1px solid var(--light-color, #e3e8ee);
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    background: var(--bg-color, #f4f5f7);
    flex-shrink: 0;
}

.gms-modal-loading,
.gms-modal-error {
    text-align: center;
    padding: 60px 20px;
}

.gms-modal-loading p,
.gms-modal-error p {
    margin-top: 16px;
    color: var(--secondary-color, #82868b);
}

.gms-modal-error h3 {
    margin: 12px 0 4px;
    font-size: 16px;
    color: var(--black-color, #1f2d3a);
}

.gms-modal-error i {
    font-size: 48px;
    color: var(--danger-color, #ea5455);
}

.gms-spinner-large {
    width: 40px;
    height: 40px;
    border: 3px solid var(--light-color, #e3e8ee);
    border-top-color: var(--theme-color, #c98643);
    border-radius: 50%;
    animation: gms-modal-spin 0.6s linear infinite;
    margin: 0 auto 16px;
}

@keyframes gms-modal-spin {
    to { transform: rotate(360deg); }
}

/* Details view */
.gms-details-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    flex-wrap: wrap;
    gap: 16px;
    margin-bottom: 24px;
    padding-bottom: 20px;
    border-bottom: 1px solid var(--light-color, #e3e8ee);
}

.gms-details-label {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--secondary-color, #82868b);
    margin-bottom: 6px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.gms-details-value {
    font-size: 15px;
    font-weight: 600;
    color: var(--black-color, #1f2d3a);
}

.gms-ticket-copy {
    display: flex;
    align-items: center;
    gap: 12px;
    cursor: pointer;
    margin-top: 6px;
}

.gms-ticket-copy .gms-ticket-number {
    font-family: 'Courier New', monospace;
    font-weight: 800;
    font-size: 18px;
    color: var(--theme-color, #c98643);
}

.gms-ticket-copy i {
    color: var(--secondary-color, #82868b);
    transition: color 0.2s ease;
}

.gms-ticket-copy:hover i {
    color: var(--theme-color, #c98643);
}

.gms-status-badge {
    display: inline-block;
    padding: 6px 16px;
    border-radius: 50px;
    font-size: 12px;
    font-weight: 700;
    white-space: nowrap;
}

.gms-status-badge.status-submitted {
    background: rgba(46, 89, 215, 0.1);
    color: var(--blue-color, #2E59D7);
}

.gms-status-badge.status-review {
    background: rgba(247, 204, 0, 0.15);
    color: #b38600;
}

.gms-status-badge.status-resolution {
    background: rgba(234, 84, 85, 0.1);
    color: var(--danger-color, #ea5455);
}

.gms-status-badge.status-resolved {
    background: rgba(40, 199, 111, 0.1);
    color: var(--success-color, #28c76f);
}

.gms-status-badge.status-default {
    background: var(--bg-color, #f4f5f7);
    color: var(--secondary-color, #82868b);
}

.gms-details-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    margin-bottom: 24px;
}

@media (max-width: 480px) {
    .gms-details-grid {
        grid-template-columns: 1fr;
    }
}

.gms-details-section {
    margin-bottom: 24px;
}

.gms-details-section:last-child {
    margin-bottom: 0;
}

.gms-details-box {
    padding: 14px 16px;
    background: var(--bg-color, #f4f5f7);
    border-radius: 8px;
    border: 1px solid var(--light-color, #e3e8ee);
    font-size: 14px;
    line-height: 1.6;
    color: var(--black-color, #1f2d3a);
    white-space: pre-wrap;
    word-break: break-word;
}

.gms-remarks-box {
    padding: 14px 16px;
    background: rgba(201, 134, 67, 0.08);
    border-radius: 8px;
    border-left: 3px solid var(--theme-color, #c98643);
    font-size: 14px;
    line-height: 1.6;
    color: var(--black-color, #1f2d3a);
    display: flex;
    gap: 10px;
}

.gms-remarks-box p {
    margin: 0;
    white-space: pre-wrap;
    word-break: break-word;
}

.gms-remarks-box i {
    color: var(--theme-color, #c98643);
    font-size: 18px;
    flex-shrink: 0;
    margin-top: 2px;
}

/* Attachments */
.gms-attachments-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
    gap: 12px;
}

@media (max-width: 480px) {
    .gms-attachments-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

.gms-attachment-image {
    position: relative;
    aspect-ratio: 1 / 1;
    border-radius: 8px;
    overflow: hidden;
    cursor: pointer;
    border: 1px solid var(--light-color, #e3e8ee);
    background: var(--bg-color, #f4f5f7);
}

.gms-attachment-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.2s ease;
}

.gms-attachment-image .gms-image-overlay {
    position: absolute;
    inset: 0;
    background: rgba(15, 23, 42, 0.4);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.2s ease;
    color: #ffffff;
    font-size: 22px;
}

.gms-attachment-image:hover .gms-image-overlay {
    opacity: 1;
}

.gms-attachment-image:hover img {
    transform: scale(1.05);
}

.gms-attachment-video {
    grid-column: 1 / -1;
    border: 1px solid var(--light-color, #e3e8ee);
    border-radius: 8px;
    overflow: hidden;
    background: var(--bg-color, #f4f5f7);
}

.gms-attachment-video video {
    width: 100%;
    max-height: 320px;
    display: block;
    background: #000;
}

.gms-attachment-video .gms-video-info {
    padding: 10px 14px;
    font-size: 13px;
    font-weight: 600;
    color: var(--black-color, #1f2d3a);
    display: flex;
    align-items: center;
    gap: 8px;
}

.gms-attachment-video .gms-video-info i {
    color: var(--theme-color, #c98643);
}

.gms-attachment-doc {
    grid-column: 1 / -1;
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    background: var(--bg-color, #f4f5f7);
    border: 1px solid var(--light-color, #e3e8ee);
    border-radius: 8px;
    transition: border-color 0.2s ease, background 0.2s ease;
}

.gms-attachment-doc:hover {
    background: var(--white-color, #ffffff);
    border-color: var(--theme-light, #f4ddc9);
}

.gms-attachment-doc > i {
    font-size: 28px;
    flex-shrink: 0;
    color: var(--theme-color, #c98643);
}

.gms-doc-info {
    flex: 1;
    min-width: 0;
}

.gms-doc-name {
    font-size: 13px;
    font-weight: 600;
    color: var(--black-color, #1f2d3a);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.gms-doc-meta {
    font-size: 10px;
    color: var(--secondary-color, #82868b);
    margin-top: 2px;
}

.gms-doc-actions {
    display: flex;
    gap: 6px;
    flex-shrink: 0;
}

.gms-btn-icon {
    width: 32px;
    height: 32px;
    border-radius: 6px;
    border: 1px solid var(--light-color, #e3e8ee);
    background: var(--white-color, #ffffff);
    color: var(--secondary-color, #82868b);
    cursor: pointer;
    transition: border-color 0.2s ease, color 0.2s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
}

.gms-btn-icon:hover {
    border-color: var(--theme-color, #c98643);
    color: var(--theme-color, #c98643);
}

/* Buttons reused in footer */
.gms-btn-primary {
    padding: 8px 20px;
    background: linear-gradient(135deg, var(--theme-color, #c98643), var(--theme-darken, #b87338));
    color: #ffffff;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    font-size: 13px;
    cursor: pointer;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.gms-btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.gms-btn-secondary {
    padding: 8px 20px;
    background: var(--white-color, #ffffff);
    border: 1.5px solid var(--light-color, #e3e8ee);
    border-radius: 8px;
    color: var(--black-color, #1f2d3a);
    font-weight: 600;
    font-size: 13px;
    cursor: pointer;
    transition: border-color 0.2s ease, color 0.2s ease, background 0.2s ease;
}

.gms-btn-secondary:hover {
    border-color: var(--theme-color, #c98643);
    color: var(--theme-color, #c98643);
    background: rgba(201, 134, 67, 0.08);
}

/* Modal transition */
.gms-modal-fade-enter-active,
.gms-modal-fade-leave-active {
    transition: opacity 0.25s ease;
}

.gms-modal-fade-enter-active .gms-modal,
.gms-modal-fade-leave-active .gms-modal {
    transition: transform 0.25s ease, opacity 0.25s ease;
}

.gms-modal-fade-enter-from,
.gms-modal-fade-leave-to {
    opacity: 0;
}

.gms-modal-fade-enter-from .gms-modal,
.gms-modal-fade-leave-to .gms-modal {
    transform: translateY(24px) scale(0.98);
    opacity: 0;
}

@media (max-width: 768px) {
    .gms-modal-overlay {
        padding: 0;
        align-items: flex-end;
    }

    .gms-modal {
        max-width: 100%;
        max-height: 92vh;
        border-radius: 12px 12px 0 0;
    }
}
</style>
