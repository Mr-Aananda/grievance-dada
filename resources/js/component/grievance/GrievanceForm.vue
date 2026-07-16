<template>
    <div class="card card-primary card-outline shadow-sm border-0 h-100">
        <div class="card-header bg-transparent border-bottom py-3">
            <h5 class="card-title mb-0 fw-bold text-primary">
                <i class="bi bi-pencil-square me-2"></i> {{ $t('Submit New Grievance') }}
            </h5>
        </div>

        <div class="card-body p-4">
            <form @submit.prevent="handleSubmit">
                <!-- Category -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-tag text-primary me-1"></i> {{ $t('Category') }} <span class="text-danger">*</span>
                    </label>
                    <select v-model="form.category_id" class="form-select" :class="{ 'is-invalid': errors.category_id }">
                        <option value="">— {{ $t('— Select a category —') }} —</option>
                        <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                            {{ cat.name }}
                        </option>
                    </select>
                    <div v-if="errors.category_id" class="invalid-feedback">{{ errors.category_id[0] }}</div>
                </div>

                <!-- Department -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-building text-primary me-1"></i> {{ $t('Department') }}
                        <span class="badge bg-light text-secondary ms-2 fw-normal" style="font-size: 10px;">{{ $t('Optional') }}</span>
                    </label>
                    <select v-model="form.department_id" class="form-select">
                        <option value="">— {{ $t('— Select a department —') }} —</option>
                        <option v-for="dept in departments" :key="dept.id" :value="dept.id">
                            {{ dept.name }}
                        </option>
                    </select>
                </div>

                <!-- Employee ID -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-person-badge text-primary me-1"></i> {{ $t('Employee ID') }}
                        <span class="badge bg-light text-secondary ms-2 fw-normal" style="font-size: 10px;">{{ $t('Optional') }}</span>
                    </label>
                    <input type="text" v-model="form.employee_id" class="form-control" :placeholder="$t('e.g., EMP-1023')" />
                </div>

                <!-- Description -->
                <div class="mb-4">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-chat-text text-primary me-1"></i> {{ $t('Issue Description') }} <span class="text-danger">*</span>
                    </label>
                    <div :class="{ 'is-invalid-quill': errors.description }">
                        <QuillEditor
                             ref="quillRef"
                             v-model:content="form.description"
                             contentType="html"
                             theme="snow"
                             :placeholder="$t('Describe your grievance clearly and in detail…')"
                             :toolbar="[
                                 ['bold', 'italic', 'underline'],
                                 [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                                 ['clean']
                             ]"
                             style="height: 180px;"
                        />
                    </div>
                    <div v-if="errors.description" class="invalid-feedback d-block">{{ errors.description[0] }}</div>
                </div>

                <!-- Attachments -->
                <div class="mb-4">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-paperclip text-primary me-1"></i> {{ $t('Attachments') }}
                        <span class="badge bg-light text-secondary ms-2 fw-normal" style="font-size: 10px;">{{ $t('Optional') }}</span>
                    </label>

                    <div v-if="!files.length" class="gms-dropzone" @click="openFilePicker"
                        @dragover.prevent="isDragging = true" @dragleave="isDragging = false" @drop.prevent="onDrop"
                        :class="{ 'is-dragging': isDragging }">
                        <div class="gms-dropzone-icon">
                            <i class="bi bi-cloud-upload"></i>
                        </div>
                        <div class="gms-dropzone-text">{{ $t('Click to browse or drag & drop') }}</div>
                        <div class="gms-dropzone-hint">{{ $t('Video max 50 MB · Image & File max 10 MB each') }}</div>
                    </div>

                    <div v-else class="gms-file-grid">
                        <div v-for="(file, index) in files" :key="index" class="gms-file-card-item">
                            <!-- Image Thumbnail Preview -->
                            <div v-if="file.type.startsWith('image/')" class="gms-file-preview-wrap">
                                <img :src="file.preview" class="gms-file-image-preview" alt="Preview" />
                            </div>
                            <!-- General Doc Icon -->
                            <div v-else class="gms-file-doc-icon-wrap">
                                <i :class="['bi', getFileIcon(file), 'gms-file-doc-icon']"></i>
                            </div>
                            <!-- Card Info Details -->
                            <div class="gms-file-card-details">
                                <span class="gms-file-card-name" :title="file.name">{{ file.name }}</span>
                                <span class="gms-file-card-size">{{ formatFileSize(file.size) }}</span>
                            </div>
                            <!-- Card Remove Button -->
                            <button type="button" class="gms-file-card-remove" @click="removeFile(index)" aria-label="Remove file">
                                <i class="bi bi-x"></i>
                            </button>
                        </div>
                    </div>

                    <button v-if="files.length" type="button" class="gms-btn-add" @click="openFilePicker">
                        <i class="bi bi-plus-circle"></i> {{ $t('Add more files') }}
                    </button>

                    <input ref="fileInput" type="file" multiple style="display: none" @change="onFileChange" />
                </div>

                <button type="submit" class="gms-btn-submit py-3 fw-bold" :disabled="!isFormValid || isSubmitting">
                    <span v-if="isSubmitting" class="gms-spinner"></span>
                    <i v-else class="bi bi-send-fill"></i>
                    {{ isSubmitting 
                        ? (uploadProgress > 0 && uploadProgress < 100 
                            ? $t('Uploading...') + ` ${uploadProgress}%` 
                            : $t('Submitting...')) 
                        : $t('Submit Grievance') }}
                </button>

                <div class="gms-secure-badge text-center text-muted mt-3 small">
                    <i class="bi bi-lock-fill me-1 text-success"></i> {{ $t('Secure & Confidential') }}
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, computed, nextTick } from 'vue';
import { QuillEditor } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import { showConfirmationAlert, showWarningAlert } from '../../utils/alert.js';
import Compressor from 'compressorjs';

const props = defineProps({
    categories: { type: Array, default: () => [] },
    departments: { type: Array, default: () => [] },
    isSubmitting: { type: Boolean, default: false },
    uploadProgress: { type: Number, default: 0 },
});

const emit = defineEmits(['submit']);

const form = reactive({
    category_id: '',
    department_id: '',
    employee_id: '',
    description: '',
});

const files = ref([]);
const errors = ref({});
const isDragging = ref(false);
const fileInput = ref(null);
const quillRef  = ref(null);

const isFormValid = computed(() => {
    if (!form.category_id || !form.description) return false;
    const text = form.description.replace(/<[^>]*>/g, '').trim();
    return text.length > 0;
});

function formatFileSize(bytes) {
    if (!bytes) return '';
    const units = ['B', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(1024));
    return (bytes / Math.pow(1024, i)).toFixed(1) + ' ' + units[i];
}

function getFileIcon(file) {
    const type = file.type || '';
    if (type.startsWith('image/')) return 'bi-file-earmark-image';
    if (type.startsWith('video/')) return 'bi-file-earmark-play';
    if (type.includes('pdf')) return 'bi-file-earmark-pdf';
    if (type.includes('word')) return 'bi-file-earmark-word';
    if (type.includes('excel')) return 'bi-file-earmark-excel';
    return 'bi-file-earmark';
}

function openFilePicker() {
    fileInput.value.value = '';
    fileInput.value.click();
}

async function onFileChange(e) {
    await addFiles([...e.target.files]);
}

// Drag and drop support
async function onDrop(e) {
    e.preventDefault();
    isDragging.value = false;
    await addFiles([...e.dataTransfer.files]);
}

function compressImage(file) {
    return new Promise((resolve) => {
        // Skip GIFs
        if (file.type === 'image/gif') {
            resolve(file);
            return;
        }
        
        new Compressor(file, {
            quality: 0.8,
            maxWidth: 1920,
            maxHeight: 1080,
            convertSize: 1048576, // convert PNG to JPEG if > 1MB to save space
            success(result) {
                // Keep the original name but potentially change extension if format changed
                let name = file.name;
                const originalExt = file.name.split('.').pop().toLowerCase();
                const newExt = result.type.split('/').pop(); // e.g. jpeg
                
                if (originalExt !== newExt && (newExt === 'jpeg' || newExt === 'png')) {
                    const baseName = file.name.substring(0, file.name.lastIndexOf('.'));
                    name = `${baseName}.${newExt === 'jpeg' ? 'jpg' : newExt}`;
                }
                
                const compressedFile = new File([result], name, {
                    type: result.type,
                    lastModified: Date.now(),
                });
                resolve(compressedFile);
            },
            error(err) {
                console.warn('Image compression failed, using original file:', err);
                resolve(file);
            },
        });
    });
}

async function addFiles(newFiles) {
    for (let file of newFiles) {
        // Compress image if it is compressible
        const isCompressible = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'].includes(file.type);
        if (isCompressible) {
            file = await compressImage(file);
        }

        let maxSize = 10 * 1024 * 1024; // 10 MB default
        let limitLabel = '10 MB';
        let limitKey = 'exceeds the 10 MB limit.';

        if (file.type.startsWith('video/')) {
            maxSize = 50 * 1024 * 1024; // 50 MB for video
            limitLabel = '50 MB';
            limitKey = 'exceeds the 50 MB limit.';
        }

        if (file.size > maxSize) {
            const limitText = window.translations[limitKey] || `exceeds the ${limitLabel} limit.`;
            const tooLargeText = window.translations['File Too Large'] || 'File Too Large';
            showWarningAlert(`"${file.name}" ${limitText}`, tooLargeText, {
                confirmButtonText: 'OK',
                customClass: { confirmButton: 'gms-swal-done-btn' },
                toast: false,
                position: 'center',
                timer: undefined,
                timerProgressBar: false
            });
            continue;
        }

        if (!files.value.some(f => f.name === file.name && f.size === file.size)) {
            // Generate local preview URL for image files
            if (file.type.startsWith('image/')) {
                file.preview = URL.createObjectURL(file);
            }
            files.value.push(file);
        }
    }
}

function removeFile(index) {
    const file = files.value[index];
    if (file && file.preview) {
        URL.revokeObjectURL(file.preview);
    }
    files.value.splice(index, 1);
}

async function handleSubmit() {
    errors.value = {};

    const categoryReq = window.translations['Category is required'] || 'Category is required';
    const descReq = window.translations['Issue description is required'] || 'Issue description is required';

    if (!form.category_id) {
        errors.value.category_id = [categoryReq];
    }

    const textDesc = form.description ? form.description.replace(/<[^>]*>/g, '').trim() : '';
    if (!textDesc) {
        errors.value.description = [descReq];
    }

    if (Object.keys(errors.value).length) return;

    const confirmTitle = window.translations['Submit Grievance?'] || 'Submit Grievance?';
    const confirmText = window.translations['Are you sure you want to submit this grievance? Once submitted, it cannot be edited.'] || 'Are you sure you want to submit this grievance? Once submitted, it cannot be edited.';
    const confirmBtn = window.translations['Submit'] || 'Submit';
    const cancelBtn = window.translations['Cancel'] || 'Cancel';

    // Confirmation dialog before submitting using showConfirmationAlert
    const isConfirmed = await showConfirmationAlert(
        confirmText,
        confirmTitle,
        `<i class="bi bi-check2 me-1"></i> ${confirmBtn}`,
        `<i class="bi bi-x-lg me-1"></i> ${cancelBtn}`,
        {
            html: `
                <div class="gms-swal-confirm-body">
                    <div class="gms-swal-confirm-icon">
                        <i class="bi bi-send-fill"></i>
                    </div>
                    <h2 class="gms-swal-title">${confirmTitle}</h2>
                    <p class="gms-swal-desc">${confirmText}</p>
                </div>
            `,
            title: '', // Empty because we defined it in HTML block
            text: '',  // Empty because we defined it in HTML block
            showClass: {
                popup: 'gms-swal-fade-in'
            },
            hideClass: {
                popup: 'gms-swal-fade-out'
            },
            customClass: {
                popup:         'gms-swal-popup',
                confirmButton: 'gms-swal-done-btn',
                cancelButton:  'gms-swal-cancel-btn',
                actions:       'gms-swal-confirm-actions',
            },
        }
    );

    if (!isConfirmed) return;

    emit('submit', { ...form }, files.value);
}

function reset() {
    // Revoke all created Object URLs to prevent leaks
    files.value.forEach(file => {
        if (file.preview) {
            URL.revokeObjectURL(file.preview);
        }
    });
    form.category_id  = '';
    form.department_id = '';
    form.employee_id  = '';
    form.description  = '';
    files.value       = [];
    errors.value      = {};
    // Reset Quill editor content
    nextTick(() => {
        if (quillRef.value?.setHTML) {
            quillRef.value.setHTML('');
        } else if (quillRef.value?.getQuill) {
            quillRef.value.getQuill().setText('');
        }
    });
}

function setErrors(errs) {
    errors.value = errs;
}

defineExpose({ reset, setErrors });
</script>
