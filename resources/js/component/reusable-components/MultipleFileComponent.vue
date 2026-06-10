<template>
    <div>
        <!-- Drag & Drop Area -->
        <div class="upload-area text-center p-5 mb-3"
             @dragover.prevent="onDragOver"
             @drop.prevent="onDrop"
             @dragleave="onDragLeave"
             @click="triggerFileInput"
             :class="{ 'drag-over': isDragging, 'disabled': disabled }">
            <i class="bi bi-cloud-arrow-up fs-1 text-muted mb-2"></i>
            <p class="mb-1">Drop files here or click to browse</p>
            <small class="text-muted">
                Max 20 files (Images: 10MB each, Other files: 15MB each)
            </small>

            <!-- Supported file types info -->
            <div class="supported-files mt-2">
                <small class="text-muted d-block">
                    <strong>Supported Image Types:</strong> All image formats (JPG, PNG, GIF, WEBP, SVG, BMP, ICO, TIFF, etc.)
                </small>
                <small class="text-muted d-block">
                    <strong>Supported File Types:</strong> All document formats (PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, TXT, CSV, MSG, ZIP, RAR, 7Z, JSON, XML, etc.)
                </small>
            </div>

            <!-- Hidden file input -->
            <input type="file" ref="fileInput" multiple @change="handleFileSelect"
                   class="d-none" :disabled="disabled">
        </div>

        <!-- File Previews -->
        <div v-if="modelValue.length > 0" class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="mb-0">Selected Files ({{ modelValue.length }}/20)</h6>
                <button type="button" class="btn btn-sm btn-outline-danger"
                        @click="clearAllFiles" :disabled="disabled">
                    <i class="bi bi-trash"></i> Clear All
                </button>
            </div>

            <div class="row g-2">
                <div class="col-6 col-md-4 col-lg-6 col-xl-4"
                     v-for="(file, index) in modelValue"
                     :key="getFileKey(file, index)">
                    <div class="file-preview">
                        <!-- For images -->
                        <div v-if="isImage(file)" class="image-preview">
                            <img :src="file.preview" :alt="file.name" class="img-fluid rounded">
                            <div class="preview-overlay">
                                <button type="button" class="btn btn-danger btn-xs"
                                        @click="removeFile(index)" :disabled="disabled">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>

                        <!-- For other files with icons -->
                        <div v-else class="document-preview">
                            <div class="document-icon">
                                <i :class="getFileIcon(file)" :style="{ fontSize: '3rem' }"></i>
                            </div>
                            <div class="preview-overlay">
                                <button type="button" class="btn btn-danger btn-xs"
                                        @click="removeFile(index)" :disabled="disabled">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>

                        <div class="file-info">
                            <small class="file-name">{{ truncateFileName(file.name) }}</small>
                            <small class="file-size">{{ formatSize(file.size) }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue'

const props = defineProps({
    modelValue: {
        type: Array,
        default: () => []
    },
    existingFiles: {
        type: Array,
        default: () => []
    },
    deletedFiles: {
        type: Array,
        default: () => []
    },
    disabled: {
        type: Boolean,
        default: false
    }
})

const emit = defineEmits([
    'update:modelValue',
    'update:deletedFiles',
    'file-warning',
    'file:added',
    'file:removed',
    'files:cleared'
])

// Refs
const fileInput = ref(null)
const isDragging = ref(false)

// Constants
const MAX_FILES = 20
const MAX_IMAGE_SIZE = 10 * 1024 * 1024 // 10MB
const MAX_FILE_SIZE = 15 * 1024 * 1024  // 15MB

// সব ধরনের ইমেজ MIME টাইপ
const IMAGE_TYPES = [
    'image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp',
    'image/svg+xml', 'image/bmp', 'image/x-ms-bmp', 'image/tiff',
    'image/x-tiff', 'image/vnd.microsoft.icon', 'image/x-icon',
    'image/heic', 'image/heif', 'image/avif', 'image/apng',
    'image/jp2', 'image/jpx', 'image/jpm', 'image/mj2'
]

// সব ধরনের ফাইল MIME টাইপ (প্রায় সব গ্রহণযোগ্য)
const isFileTypeAllowed = (file) => {
    const fileType = file.type || ''
    const fileName = file.name || ''
    const extension = fileName.split('.').pop().toLowerCase()

    // ইমেজ ফাইল সব ধরনের অনুমোদিত
    if (IMAGE_TYPES.includes(fileType) ||
        extension.match(/^(jpg|jpeg|png|gif|webp|svg|bmp|ico|tiff|tif|heic|heif|avif)$/)) {
        return true
    }

    // কমন ফাইল এক্সটেনশন
    const allowedExtensions = [
        // Documents
        'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'rtf', 'odt', 'ods', 'odp',
        // Archives
        'zip', 'rar', '7z', 'tar', 'gz', 'bz2',
        // Emails
        'msg', 'eml', 'pst', 'ost',
        // Data
        'csv', 'json', 'xml', 'yaml', 'yml',
        // Others
        'log', 'ini', 'cfg', 'conf', 'bat', 'sh', 'ps1'
    ]

    return allowedExtensions.includes(extension)
}

// Helper functions
const isImage = (file) => {
    const fileType = file.type || ''
    const fileName = file.name || ''
    const extension = fileName.split('.').pop().toLowerCase()

    // MIME টাইপ বা এক্সটেনশন দিয়ে চেক
    return IMAGE_TYPES.includes(fileType) ||
           extension.match(/^(jpg|jpeg|png|gif|webp|svg|bmp|ico|tiff|tif|heic|heif|avif)$/)
}

const isNewFile = (fileId) => {
    if (!fileId) return false
    const idString = fileId.toString()
    return idString.startsWith('new_')
}

const getFileKey = (file, index) => {
    return file.id ? `file_${file.id}` : `file_new_${index}_${Date.now()}`
}

// শুধু প্রয়োজনীয় আইকন (অডিও, ডাটাবেস, কোড সব বাদ)
const getFileIcon = (file) => {
    const name = file.name || ''
    const ext = name.split('.').pop().toLowerCase()
    const mimeType = file.type || ''

    // PDF
    if (ext === 'pdf' || mimeType.includes('pdf')) {
        return 'bi bi-file-pdf text-danger'
    }

    // Word documents
    if (ext.match(/^(doc|docx|odt)$/) || mimeType.includes('word')) {
        return 'bi bi-file-word text-primary'
    }

    // Excel spreadsheets
    if (ext.match(/^(xls|xlsx|csv|ods)$/) || mimeType.includes('excel') || mimeType.includes('sheet')) {
        return 'bi bi-file-excel text-success'
    }

    // PowerPoint presentations
    if (ext.match(/^(ppt|pptx|odp)$/) || mimeType.includes('presentation')) {
        return 'bi bi-file-ppt text-danger'
    }

    // Archives
    if (ext.match(/^(zip|rar|7z|tar|gz|bz2)$/) || mimeType.includes('zip') || mimeType.includes('rar')) {
        return 'bi bi-file-zip text-warning'
    }

    // Text files
    if (ext.match(/^(txt|rtf|log|ini|cfg|conf)$/) || mimeType.includes('text')) {
        return 'bi bi-file-text text-secondary'
    }

    // Email files
    if (ext.match(/^(msg|eml|pst|ost)$/) || mimeType.includes('outlook')) {
        return 'bi bi-envelope-paper text-info'
    }

    // Font files
    if (ext.match(/^(ttf|otf|woff|woff2|eot)$/) || mimeType.includes('font')) {
        return 'bi bi-fonts text-warning'
    }

    // Default (কোড, অডিও, ডাটাবেস সহ সব অচেনা ফাইল এখানে আসবে)
    return 'bi bi-file-earmark text-muted'
}

const formatSize = (bytes) => {
    if (!bytes || bytes === 0) return '0 B'
    const units = ['B', 'KB', 'MB', 'GB']
    const i = Math.floor(Math.log(bytes) / Math.log(1024))
    return (bytes / Math.pow(1024, i)).toFixed(i > 0 ? 2 : 0) + ' ' + units[i]
}

const truncateFileName = (name) => {
    if (name.length <= 15) return name
    const ext = name.split('.').pop()
    const nameWithoutExt = name.substring(0, name.lastIndexOf('.'))
    return nameWithoutExt.substring(0, 10) + '...' + ext
}

// Show error alert
const showErrorAlert = (message, type = 'Error') => {
    emit('file-warning', {
        message,
        type
    })
}

// Methods
const triggerFileInput = () => {
    if (props.disabled) return
    fileInput.value.click()
}

const onDragOver = (event) => {
    event.preventDefault()
    if (!props.disabled) {
        isDragging.value = true
    }
}

const onDragLeave = () => {
    isDragging.value = false
}

const onDrop = (event) => {
    event.preventDefault()
    isDragging.value = false
    if (props.disabled) return
    handleFiles(event.dataTransfer.files)
}

const handleFileSelect = (event) => {
    handleFiles(event.target.files)
    event.target.value = ''
}

const handleFiles = (fileList) => {
    const files = Array.from(fileList)

    // Check total files
    if (props.modelValue.length + files.length > MAX_FILES) {
        showErrorAlert(
            `You can only select ${MAX_FILES - props.modelValue.length} more file(s). Maximum ${MAX_FILES} files allowed.`,
            "Maximum Files Exceeded"
        )
        return
    }

    const newFiles = []
    const invalidTypeFiles = []
    const oversizedFiles = []

    // Process each file
    files.forEach(file => {
        // Check if file type is allowed
        if (!isFileTypeAllowed(file)) {
            invalidTypeFiles.push({
                name: file.name,
                type: file.type || 'unknown'
            })
            return
        }

        // Validate file size based on type
        const isImageFile = isImage(file)
        const maxAllowedSize = isImageFile ? MAX_IMAGE_SIZE : MAX_FILE_SIZE

        if (file.size > maxAllowedSize) {
            oversizedFiles.push({
                name: file.name,
                size: file.size,
                maxSize: isImageFile ? '10MB' : '15MB'
            })
            return
        }

        // Create file object
        const fileObj = {
            id: `new_${Date.now()}_${Math.random().toString(36).substr(2, 9)}`,
            name: file.name,
            type: file.type,
            size: file.size,
            file: file,
            isNew: true,
            isImage: isImageFile
        }

        // Create preview for images
        if (isImageFile) {
            const reader = new FileReader()
            reader.onload = (e) => {
                fileObj.preview = e.target.result
                newFiles.push(fileObj)

                // If all files processed, update the model value
                if (newFiles.length === files.length - invalidTypeFiles.length - oversizedFiles.length) {
                    updateFiles(newFiles)
                }
            }
            reader.readAsDataURL(file)
        } else {
            fileObj.preview = null
            newFiles.push(fileObj)
        }
    })

    // Show error for invalid type files
    if (invalidTypeFiles.length > 0) {
        const fileTypes = invalidTypeFiles.map(f => `"${f.name}"`).join(', ')
        showErrorAlert(
            `${invalidTypeFiles.length} file(s) have unsupported types: ${fileTypes}`,
            "Invalid File Type"
        )
    }

    // Show error for oversized files
    if (oversizedFiles.length > 0) {
        const errorMessages = oversizedFiles.map(f =>
            `"${f.name}" exceeds ${f.maxSize} size limit`
        ).join('\n')

        showErrorAlert(
            `${oversizedFiles.length} file(s) exceed size limit:\n${errorMessages}`,
            "Files Too Large"
        )
    }

    // Update files if no async processing needed
    if (!files.some(f => isImage(f))) {
        updateFiles(newFiles)
    }
}

const updateFiles = (newFiles) => {
    if (newFiles.length > 0) {
        const updatedFiles = [...props.modelValue, ...newFiles]
        emit('update:modelValue', updatedFiles)
        emit('file:added', { files: newFiles })
    }
}

const removeFile = (index) => {
    const file = props.modelValue[index]

    // Create a new array without the file
    const updatedFiles = [...props.modelValue]
    updatedFiles.splice(index, 1)

    // If it's an existing file (not new), mark for deletion
    if (!isNewFile(file.id)) {
        const fileId = Number(file.id)
        const updatedDeleted = [...props.deletedFiles, fileId]
        emit('update:deletedFiles', updatedDeleted)
    }

    // Update the files array
    emit('update:modelValue', updatedFiles)
    emit('file:removed', { file, index })
}

const clearAllFiles = () => {
    if (props.disabled) return

    // Show confirmation before clearing
    const confirmationMessage = "Are you sure you want to remove all files?"
    const confirmationType = "confirm"

    // Emit confirmation event to parent
    emit('file-warning', {
        message: confirmationMessage,
        type: confirmationType,
        callback: (confirmed) => {
            if (confirmed) {
                // Mark all existing files for deletion
                const existingFiles = props.modelValue.filter(file => !isNewFile(file.id))
                if (existingFiles.length > 0) {
                    const deletedIds = existingFiles.map(file => Number(file.id))
                    const updatedDeleted = [...props.deletedFiles, ...deletedIds]
                    emit('update:deletedFiles', updatedDeleted)
                }

                // Clear all files
                emit('update:modelValue', [])
                emit('files:cleared')
            }
        }
    })
}

// Watch for existingFiles prop changes (for edit mode)
watch(() => props.existingFiles, (newFiles) => {
    if (newFiles && newFiles.length > 0 && props.modelValue.length === 0) {
        const formattedFiles = newFiles.map(file => {
            const fileType = file.mime_type || ''
            const fileName = file.original_name || file.name || 'File'
            const isImageFile = isImage({ type: fileType, name: fileName })

            return {
                id: file.id,
                name: fileName,
                type: fileType || 'application/octet-stream',
                size: file.size || 0,
                isNew: false,
                isImage: isImageFile,
                preview: isImageFile ? (file.url || file.original_url || file.thumb_url) : null
            }
        })
        emit('update:modelValue', formattedFiles)
    }
}, { immediate: true })
</script>

<style scoped>
.upload-area {
    border: 2px dashed #dee2e6;
    border-radius: 8px;
    background: #f8f9fa;
    cursor: pointer;
    transition: all 0.3s;
}

.upload-area:hover:not(.disabled),
.upload-area.drag-over:not(.disabled) {
    border-color: #F47922;
    background: rgba(244, 121, 34, 0.05);
}

.upload-area.disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.supported-files {
    font-size: 0.8rem;
}

.file-preview {
    position: relative;
    border-radius: 6px;
    overflow: hidden;
    margin-bottom: 10px;
    border: 1px solid #dee2e6;
    background: white;
    padding: 8px;
}

.image-preview {
    position: relative;
    height: 100px;
    margin-bottom: 5px;
}

.image-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.document-preview {
    position: relative;
    height: 100px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 5px;
    background: #f8f9fa;
    border-radius: 4px;
}

.document-icon {
    font-size: 2.5rem;
    opacity: 0.7;
}

.preview-overlay {
    position: absolute;
    top: 5px;
    right: 5px;
    opacity: 0;
    transition: opacity 0.3s;
}

.file-preview:hover .preview-overlay {
    opacity: 1;
}

.file-info {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.file-name {
    font-size: 11px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    text-align: center;
    color: #495057;
}

.file-size {
    font-size: 10px;
    color: #6c757d;
    text-align: center;
}

.btn-xs {
    padding: 0.15rem 0.3rem;
    font-size: 0.75rem;
}

/* Icon colors */
.text-primary { color: #0d6efd; }
.text-success { color: #198754; }
.text-danger { color: #dc3545; }
.text-warning { color: #ffc107; }
.text-info { color: #0dcaf0; }
.text-secondary { color: #6c757d; }
.text-muted { color: #6c757d; }
</style>
