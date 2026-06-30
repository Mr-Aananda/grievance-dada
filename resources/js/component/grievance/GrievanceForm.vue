<template>
    <div class="card card-primary card-outline shadow-sm border-0 h-100">
        <div class="card-header bg-transparent border-bottom py-3">
            <h5 class="card-title mb-0 fw-bold text-primary">
                <i class="bi bi-pencil-square me-2"></i> Submit New Grievance
            </h5>
        </div>

        <div class="card-body p-4">
            <form @submit.prevent="handleSubmit">
                <!-- Category -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-tag text-primary me-1"></i> Category <span class="text-danger">*</span>
                    </label>
                    <select v-model="form.category_id" class="form-select" :class="{ 'is-invalid': errors.category_id }">
                        <option value="">— Select a category —</option>
                        <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                            {{ cat.name }}
                        </option>
                    </select>
                    <div v-if="errors.category_id" class="invalid-feedback">{{ errors.category_id[0] }}</div>
                </div>

                <!-- Department -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-building text-primary me-1"></i> Department
                        <span class="badge bg-light text-secondary ms-2 fw-normal" style="font-size: 10px;">Optional</span>
                    </label>
                    <select v-model="form.department_id" class="form-select">
                        <option value="">— Select a department —</option>
                        <option v-for="dept in departments" :key="dept.id" :value="dept.id">
                            {{ dept.name }}
                        </option>
                    </select>
                </div>

                <!-- Employee ID -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-person-badge text-primary me-1"></i> Employee ID
                        <span class="badge bg-light text-secondary ms-2 fw-normal" style="font-size: 10px;">Optional</span>
                    </label>
                    <input type="text" v-model="form.employee_id" class="form-control" placeholder="e.g., EMP-1023" />
                </div>

                <!-- Description -->
                <div class="mb-4">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-chat-text text-primary me-1"></i> Issue Description <span class="text-danger">*</span>
                    </label>
                    <div :class="{ 'is-invalid-quill': errors.description }">
                        <QuillEditor
                            v-model:content="form.description"
                            contentType="html"
                            theme="snow"
                            placeholder="Describe your grievance clearly and in detail…"
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
                        <i class="bi bi-paperclip text-primary me-1"></i> Attachments
                        <span class="badge bg-light text-secondary ms-2 fw-normal" style="font-size: 10px;">Optional</span>
                    </label>

                    <div v-if="!files.length" class="gms-dropzone" @click="openFilePicker"
                        @dragover.prevent="isDragging = true" @dragleave="isDragging = false" @drop.prevent="onDrop"
                        :class="{ 'is-dragging': isDragging }">
                        <div class="gms-dropzone-icon">
                            <i class="bi bi-cloud-upload"></i>
                        </div>
                        <div class="gms-dropzone-text">Click to browse or drag & drop</div>
                        <div class="gms-dropzone-hint">Images · Videos · PDF · Word · Excel — max 100 MB each</div>
                    </div>

                    <div v-else class="gms-file-list">
                        <div v-for="(file, index) in files" :key="index" class="gms-file-item">
                            <i :class="['bi', getFileIcon(file), 'gms-file-icon']"></i>
                            <div class="gms-file-info">
                                <div class="gms-file-name">{{ file.name }}</div>
                                <div class="gms-file-size">{{ formatFileSize(file.size) }}</div>
                            </div>
                            <button type="button" class="gms-file-remove" @click="removeFile(index)" aria-label="Remove file">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                    </div>

                    <button v-if="files.length" type="button" class="gms-btn-add" @click="openFilePicker">
                        <i class="bi bi-plus-circle"></i> Add more files
                    </button>

                    <input ref="fileInput" type="file" multiple style="display: none" @change="onFileChange" />
                </div>

                <button type="submit" class="gms-btn-submit py-3 fw-bold" :disabled="!isFormValid || isSubmitting">
                    <span v-if="isSubmitting" class="gms-spinner"></span>
                    <i v-else class="bi bi-send-fill"></i>
                    {{ isSubmitting ? 'Submitting...' : 'Submit Grievance' }}
                </button>

                <div class="gms-secure-badge text-center text-muted mt-3 small">
                    <i class="bi bi-lock-fill me-1 text-success"></i> Secure & Confidential
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, computed } from 'vue';
import { QuillEditor } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';

const props = defineProps({
    categories: { type: Array, default: () => [] },
    departments: { type: Array, default: () => [] },
    isSubmitting: { type: Boolean, default: false },
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

function onFileChange(e) {
    addFiles([...e.target.files]);
}

// Drag and drop support
function onDrop(e) {
    e.preventDefault();
    isDragging.value = false;
    addFiles([...e.dataTransfer.files]);
}

function addFiles(newFiles) {
    for (const file of newFiles) {
        if (file.size > 100 * 1024 * 1024) {
            alert(`"${file.name}" exceeds 100 MB limit.`);
            continue;
        }
        if (!files.value.some(f => f.name === file.name && f.size === file.size)) {
            files.value.push(file);
        }
    }
}

function removeFile(index) {
    files.value.splice(index, 1);
}

async function handleSubmit() {
    errors.value = {};

    if (!form.category_id) {
        errors.value.category_id = ['Category is required'];
    }
    
    const textDesc = form.description ? form.description.replace(/<[^>]*>/g, '').trim() : '';
    if (!textDesc) {
        errors.value.description = ['Issue description is required'];
    }

    if (Object.keys(errors.value).length) return;

    const result = await emit('submit', { ...form }, files.value);

    if (result?.success) {
        form.category_id = '';
        form.department_id = '';
        form.employee_id = '';
        form.description = '';
        files.value = [];
        errors.value = {};
    } else if (result?.errors) {
        errors.value = result.errors;
    }
}
</script>
