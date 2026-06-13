<template>
    <div class="gms-card gms-form-card">
        <div class="gms-card-header gms-form-header">
            <div>
                <div class="gms-card-title">
                    <i class="bi bi-pencil-square"></i> Submit New Grievance
                </div>
                <div class="gms-card-sub">অভিযোগ জমা দিন</div>
            </div>
        </div>

        <div class="gms-card-body">
            <form @submit.prevent="handleSubmit">
                <!-- Category -->
                <div class="gms-form-group">
                    <label class="gms-label">
                        <i class="bi bi-tag"></i> Category <span class="gms-required">*</span>
                    </label>
                    <select v-model="form.category_id" class="gms-input" :class="{ 'is-invalid': errors.category_id }">
                        <option value="">— Select a category —</option>
                        <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                            {{ cat.name }}
                        </option>
                    </select>
                    <div v-if="errors.category_id" class="gms-invalid-feedback">{{ errors.category_id[0] }}</div>
                </div>

                <!-- Department -->
                <div class="gms-form-group">
                    <label class="gms-label">
                        <i class="bi bi-building"></i> Department
                        <span class="gms-optional">Optional</span>
                    </label>
                    <select v-model="form.department_id" class="gms-input">
                        <option value="">— Select a department —</option>
                        <option v-for="dept in departments" :key="dept.id" :value="dept.id">
                            {{ dept.name }}
                        </option>
                    </select>
                </div>

                <!-- Employee ID -->
                <div class="gms-form-group">
                    <label class="gms-label">
                        <i class="bi bi-person-badge"></i> Employee ID
                        <span class="gms-optional">Optional</span>
                    </label>
                    <input type="text" v-model="form.employee_id" class="gms-input" placeholder="e.g., EMP-1023" />
                </div>

                <div class="gms-divider"></div>

                <!-- Description -->
                <div class="gms-form-group">
                    <label class="gms-label">
                        <i class="bi bi-chat-text"></i> Issue Description <span class="gms-required">*</span>
                    </label>
                    <textarea v-model="form.description" class="gms-input gms-textarea" rows="6" maxlength="5000"
                        placeholder="Describe your grievance clearly and in detail…"
                        :class="{ 'is-invalid': errors.description }"></textarea>
                    <div class="gms-character-count">
                        <span :class="{ 'text-danger': form.description.length > 4800 }">
                            {{ form.description.length }} / 5000
                        </span>
                    </div>
                    <div v-if="errors.description" class="gms-invalid-feedback">{{ errors.description[0] }}</div>
                </div>

                <!-- Attachments -->
                <div class="gms-form-group">
                    <label class="gms-label">
                        <i class="bi bi-paperclip"></i> Attachments
                        <span class="gms-optional">Optional</span>
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

                <button type="submit" class="gms-btn-submit" :disabled="!isFormValid || isSubmitting">
                    <span v-if="isSubmitting" class="gms-spinner"></span>
                    <i v-else class="bi bi-send-fill"></i>
                    {{ isSubmitting ? 'Submitting...' : 'Submit Grievance' }}
                </button>

                <div class="gms-secure-badge">
                    <i class="bi bi-lock-fill"></i> Secure & Confidential
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, computed } from 'vue';

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

const isFormValid = computed(() => form.category_id && form.description.trim());

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
    if (!form.description.trim()) {
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
