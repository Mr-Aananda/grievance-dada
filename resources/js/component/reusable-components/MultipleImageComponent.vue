<template>
    <div>
        <!-- Drag & Drop Area -->
        <div class="upload-area text-center p-5 mb-3" @dragover.prevent @drop.prevent="onDrop"
             @click="triggerFileInput" :class="{ 'drag-over': isDragging }">
            <i class="bi bi-cloud-arrow-up fs-1 text-muted mb-2"></i>
            <p class="mb-1">Drop images here or click to browse</p>
            <small class="text-muted">Max {{ maxFiles }} images, {{ maxSizeMB }}MB each (JPG, PNG, GIF, WebP)</small>
            <!-- Hidden file input -->
            <input type="file" ref="fileInput" multiple accept="image/*" @change="handleFileSelect"
                   class="d-none">
        </div>

        <!-- Image Previews -->
        <div v-if="modelValue.length > 0" class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="mb-0">Selected Images ({{ modelValue.length }})</h6>
                <button type="button" class="btn btn-sm btn-outline-danger" @click="clearAllImages">
                    <i class="bi bi-trash"></i> Clear All
                </button>
            </div>

            <div class="row g-2">
                <div class="col-6 col-md-4 col-lg-6 col-xl-4" v-for="(image, index) in modelValue" :key="getImageKey(image, index)">
                    <div class="image-preview">
                        <img :src="image.preview" :alt="image.name" class="img-fluid rounded">
                        <div class="preview-overlay">
                            <button type="button" class="btn btn-danger btn-xs" @click="removeImage(index)">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                        <div class="image-name">{{ image.name }}</div>
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
    maxFiles: {
        type: Number,
        default: 10
    },
    maxSizeMB: {
        type: Number,
        default: 5
    },
    existingImages: {
        type: Array,
        default: () => []
    },
    deletedImages: {
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
    'update:deletedImages',
    'image-warning',
    'file:added',
    'file:removed',
    'files:cleared'
])

// Refs
const fileInput = ref(null)
const isDragging = ref(false)

// Helper function to check if image is new
const isNewImage = (imageId) => {
    if (!imageId) return false
    const idString = imageId.toString()
    return idString.startsWith('new_')
}

// Helper function to generate image key
const getImageKey = (image, index) => {
    return image.id ? `image_${image.id}` : `image_new_${index}_${Date.now()}`
}

// Show error alert
const showErrorAlert = (message, type = 'Error') => {
    emit('image-warning', {
        message,
        type
    })
}

// Methods
const triggerFileInput = () => {
    if (props.disabled) return;
    fileInput.value.click()
}

const onDrop = (event) => {
    event.preventDefault()
    isDragging.value = false
    if (props.disabled) return;
    handleFiles(event.dataTransfer.files)
}

const handleFileSelect = (event) => {
    handleFiles(event.target.files)
    event.target.value = ''
}

const handleFiles = (fileList) => {
    const files = Array.from(fileList)

    // Check total files
    if (props.modelValue.length + files.length > props.maxFiles) {
        showErrorAlert(
            `You can only select ${props.maxFiles - props.modelValue.length} more image(s). Maximum ${props.maxFiles} images allowed.`,
            "Maximum Files Exceeded"
        )
        return
    }

    const maxSizeBytes = props.maxSizeMB * 1024 * 1024
    const newImages = []
    const oversizedFiles = []

    // Process each file
    files.forEach(file => {
        // Validate file type
        const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp']
        if (!validTypes.includes(file.type)) {
            showErrorAlert(
                `"${file.name}" is not a supported image type. Supported types: JPG, PNG, GIF, WebP.`,
                "Invalid File Type"
            )
            return
        }

        // Validate file size
        if (file.size > maxSizeBytes) {
            oversizedFiles.push({
                name: file.name,
                size: file.size
            })
            return
        }

        // Create preview and store the file
        const reader = new FileReader()
        reader.onload = (e) => {
            const newImage = {
                id: `new_${Date.now()}_${Math.random().toString(36).substr(2, 9)}`,
                preview: e.target.result,
                name: file.name,
                file: file,
                size: file.size,
                isNew: true
            }

            newImages.push(newImage)

            // If all files processed, update the model value
            if (newImages.length === files.length - oversizedFiles.length) {
                const updatedImages = [...props.modelValue, ...newImages]
                emit('update:modelValue', updatedImages)
                emit('file:added', { files: newImages })
            }
        }
        reader.readAsDataURL(file)
    })

    // Show error for oversized files
    if (oversizedFiles.length > 0) {
        if (oversizedFiles.length === 1) {
            showErrorAlert(
                `"${oversizedFiles[0].name}" exceeds ${props.maxSizeMB}MB size limit.`,
                "File Too Large"
            )
        } else {
            showErrorAlert(
                `${oversizedFiles.length} image(s) exceed ${props.maxSizeMB}MB size limit. Please select smaller images.`,
                "Files Too Large"
            )
        }
    }
}

const removeImage = (index) => {
    const image = props.modelValue[index]

    // Create a new array without the image
    const updatedImages = [...props.modelValue]
    updatedImages.splice(index, 1)

    // If it's an existing image (not new), mark for deletion
    if (!isNewImage(image.id)) {
        const imageId = Number(image.id)
        const updatedDeleted = [...props.deletedImages, imageId]
        emit('update:deletedImages', updatedDeleted)
    }

    // Update the images array
    emit('update:modelValue', updatedImages)
    emit('file:removed', { image, index })
}

const clearAllImages = () => {
    if (props.disabled) return;

    // Show confirmation before clearing
    const confirmationMessage = "Are you sure you want to remove all images?";
    const confirmationType = "confirm";

    // Emit confirmation event to parent
    emit('image-warning', {
        message: confirmationMessage,
        type: confirmationType,
        callback: (confirmed) => {
            if (confirmed) {
                // Mark all existing images for deletion
                const existingImages = props.modelValue.filter(img => !isNewImage(img.id))
                if (existingImages.length > 0) {
                    const deletedIds = existingImages.map(img => Number(img.id))
                    const updatedDeleted = [...props.deletedImages, ...deletedIds]
                    emit('update:deletedImages', updatedDeleted)
                }

                // Clear all images
                emit('update:modelValue', [])
                emit('files:cleared')
            }
        }
    })
}

// Watch for existingImages prop changes (for edit mode)
watch(() => props.existingImages, (newImages) => {
    if (newImages && newImages.length > 0 && props.modelValue.length === 0) {
        const formattedImages = newImages.map(img => ({
            id: img.id,
            preview: img.url || img.original_url || img.thumb_url,
            name: img.file_name || img.name || 'Image',
            size: img.size || 0,
            isNew: false
        }))
        emit('update:modelValue', formattedImages)
    }
}, { immediate: true })

// Watch for drag over
const onDragOver = (event) => {
    event.preventDefault()
    if (!props.disabled) {
        isDragging.value = true
    }
}

const onDragLeave = () => {
    isDragging.value = false
}
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

.image-preview {
    position: relative;
    border-radius: 6px;
    overflow: hidden;
    margin-bottom: 10px;
    border: 1px solid #dee2e6;
}

.image-preview img {
    width: 100%;
    height: 120px;
    object-fit: cover;
}

.preview-overlay {
    position: absolute;
    top: 5px;
    right: 5px;
    opacity: 0;
    transition: opacity 0.3s;
}

.image-preview:hover .preview-overlay {
    opacity: 1;
}

.btn-xs {
    padding: 0.15rem 0.3rem;
    font-size: 0.75rem;
}

.image-name {
    padding: 4px 8px;
    background: rgba(0,0,0,0.7);
    color: white;
    font-size: 11px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    text-align: center;
}
</style>
