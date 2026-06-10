<template>
    <div>
        <!-- Drag & Drop Area -->
        <div class="upload-area text-center p-5 mb-3"
             @dragover.prevent="onDragOver"
             @drop.prevent="onDrop"
             @dragleave="onDragLeave"
             @click="triggerFileInput"
             :class="{ 'drag-over': isDragging, 'disabled': disabled }">
            <i class="bi bi-camera-video fs-1 text-muted mb-2"></i>
            <p class="mb-1">Drop videos here or click to browse</p>
            <small class="text-muted">Max {{ maxFiles }} videos, {{ maxSizeMB }}MB each (MP4, AVI, MOV, WMV, FLV, MKV, WEBM)</small>
            <!-- Hidden file input -->
            <input type="file" ref="fileInput" multiple accept="video/*" @change="handleFileSelect"
                   class="d-none" :disabled="disabled">
        </div>

        <!-- Video Previews -->
        <div v-if="modelValue.length > 0" class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="mb-0">Selected Videos ({{ modelValue.length }})</h6>
                <button type="button" class="btn sm btn-outline-danger"
                        @click="clearAllVideos" :disabled="disabled">
                    <i class="bi bi-trash"></i> Clear All
                </button>
            </div>

            <div class="list-group">
                <div class="list-group-item list-group-item-action"
                     v-for="(video, index) in modelValue"
                     :key="getVideoKey(video, index)">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-file-play text-theme me-2"></i>
                            <div>
                                <div class="fw-medium text-truncate" style="max-width: 200px">
                                    {{ video.name }}
                                    <span v-if="video.isNew" class="badge bg-theme ms-1" style="font-size: 0.6rem">
                                        New
                                    </span>
                                    <span v-else class="badge bg-success ms-1" style="font-size: 0.6rem">
                                        Existing
                                    </span>
                                </div>
                                <small class="text-muted">{{ formatSize(video.size) }}</small>
                            </div>
                        </div>
                        <div class="d-flex gap-1 align-items-center">
                            <!-- Progress Indicator -->
                            <span v-if="video.progress > 0 && video.progress < 100"
                                  class="badge bg-warning">
                                {{ video.progress }}%
                            </span>
                            <span v-else-if="video.progress === 100"
                                  class="badge bg-success">
                                Done
                            </span>

                            <!-- Remove Button -->
                            <button type="button" class="btn sm btn-danger"
                                    @click="removeVideo(index)"
                                    :disabled="disabled || (video.progress > 0 && video.progress < 100)">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div v-if="video.progress > 0 && video.progress < 100" class="mt-2">
                        <div class="progress" style="height: 4px">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-theme"
                                 :style="{ width: video.progress + '%' }"></div>
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
    maxFiles: {
        type: Number,
        default: 5
    },
    maxSizeMB: {
        type: Number,
        default: 500
    },
    existingVideos: {
        type: Array,
        default: () => []
    },
    deletedVideos: {
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
    'update:deletedVideos',
    'video-warning',
    'video:added',
    'video:removed',
    'videos:cleared',
    'progress:update'
])

// Refs
const fileInput = ref(null)
const isDragging = ref(false)

// Helper function to check if video is new
const isNewVideo = (videoId) => {
    if (!videoId) return true
    const idString = videoId.toString()
    return idString.startsWith('new_')
}

// Helper function to generate video key
const getVideoKey = (video, index) => {
    return video.id ? `video_${video.id}` : `video_new_${index}_${Date.now()}`
}

// Format file size
const formatSize = (bytes) => {
    if (!bytes) return '0 B'
    const units = ['B', 'KB', 'MB', 'GB']
    const i = Math.floor(Math.log(bytes) / Math.log(1024))
    return (bytes / Math.pow(1024, i)).toFixed(i > 0 ? 2 : 0) + ' ' + units[i]
}

// Show error alert
const showErrorAlert = (message, type = 'Error') => {
    emit('video-warning', {
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
    if (props.modelValue.length + files.length > props.maxFiles) {
        showErrorAlert(
            `You can only select ${props.maxFiles - props.modelValue.length} more video(s). Maximum ${props.maxFiles} videos allowed.`,
            "Maximum Files Exceeded"
        )
        return
    }

    const maxSizeBytes = props.maxSizeMB * 1024 * 1024
    const allowedTypes = [
        'video/mp4',
        'video/avi',
        'video/mov',
        'video/wmv',
        'video/flv',
        'video/mkv',
        'video/webm'
    ]

    const newVideos = []
    const oversizedFiles = []
    const invalidTypeFiles = []

    // Process each file
    files.forEach(file => {
        // Validate file type
        if (!allowedTypes.includes(file.type)) {
            invalidTypeFiles.push(file.name)
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

        // Create video object
        const newVideo = {
            id: `new_${Date.now()}_${Math.random().toString(36).substr(2, 9)}`,
            name: file.name,
            file: file,
            size: file.size,
            type: file.type,
            progress: 0,
            isNew: true
        }

        newVideos.push(newVideo)
    })

    // Show error for invalid type files
    if (invalidTypeFiles.length > 0) {
        if (invalidTypeFiles.length === 1) {
            showErrorAlert(
                `"${invalidTypeFiles[0]}" is not a supported video format. Supported formats: MP4, AVI, MOV, WMV, FLV, MKV, WEBM.`,
                "Invalid File Type"
            )
        } else {
            showErrorAlert(
                `${invalidTypeFiles.length} file(s) are not supported video formats. Supported formats: MP4, AVI, MOV, WMV, FLV, MKV, WEBM.`,
                "Invalid File Types"
            )
        }
    }

    // Show error for oversized files
    if (oversizedFiles.length > 0) {
        if (oversizedFiles.length === 1) {
            showErrorAlert(
                `"${oversizedFiles[0].name}" exceeds ${props.maxSizeMB}MB size limit.`,
                "File Too Large"
            )
        } else {
            showErrorAlert(
                `${oversizedFiles.length} video(s) exceed ${props.maxSizeMB}MB size limit. Please select smaller videos.`,
                "Files Too Large"
            )
        }
    }

    // Add valid videos
    if (newVideos.length > 0) {
        const updatedVideos = [...props.modelValue, ...newVideos]
        emit('update:modelValue', updatedVideos)
        emit('video:added', { videos: newVideos })
    }
}

const removeVideo = (index) => {
    const video = props.modelValue[index]

    // Create a new array without the video
    const updatedVideos = [...props.modelValue]
    updatedVideos.splice(index, 1)

    // If it's an existing video (not new), mark for deletion
    if (!isNewVideo(video.id)) {
        const videoId = Number(video.id)
        const updatedDeleted = [...props.deletedVideos, videoId]
        emit('update:deletedVideos', updatedDeleted)
    }

    // Update the videos array
    emit('update:modelValue', updatedVideos)
    emit('video:removed', { video, index })
}

const clearAllVideos = () => {
    if (props.disabled) return

    // Show confirmation before clearing
    const confirmationMessage = "Are you sure you want to remove all videos?"
    const confirmationType = "confirm"

    // Emit confirmation event to parent
    emit('video-warning', {
        message: confirmationMessage,
        type: confirmationType,
        callback: (confirmed) => {
            if (confirmed) {
                // Mark all existing videos for deletion
                const existingVideos = props.modelValue.filter(video => !isNewVideo(video.id))
                if (existingVideos.length > 0) {
                    const deletedIds = existingVideos.map(video => Number(video.id))
                    const updatedDeleted = [...props.deletedVideos, ...deletedIds]
                    emit('update:deletedVideos', updatedDeleted)
                }

                // Clear all videos
                emit('update:modelValue', [])
                emit('videos:cleared')
            }
        }
    })
}

// Watch for existingVideos prop changes (for edit mode)
watch(() => props.existingVideos, (newVideos) => {
    if (newVideos && newVideos.length > 0 && props.modelValue.length === 0) {
        const formattedVideos = newVideos.map(video => ({
            id: video.id,
            name: video.original_name || video.name || 'Video',
            size: video.size || 0,
            type: video.mime_type || 'video/mp4',
            progress: 100, // Already uploaded
            isNew: false,
            // If you have a preview URL for videos, you can add it here
            // preview: video.preview_url
        }))
        emit('update:modelValue', formattedVideos)
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

.list-group-item {
    border-left: 3px solid transparent;
    transition: all 0.2s ease;
}

.list-group-item:hover {
    background-color: #f8f9fa;
    border-left-color: #f57a22;
}

/* Theme Colors */
.bg-theme {
    background-color: #f57a22 !important;
    color: white;
}

.text-theme {
    color: #f57a22 !important;
}

/* Progress bar animation */
.progress-bar-striped {
    background-image: linear-gradient(
        45deg,
        rgba(255, 255, 255, 0.15) 25%,
        transparent 25%,
        transparent 50%,
        rgba(255, 255, 255, 0.15) 50%,
        rgba(255, 255, 255, 0.15) 75%,
        transparent 75%,
        transparent
    );
    background-size: 1rem 1rem;
}

.progress-bar-animated {
    animation: progress-bar-stripes 1s linear infinite;
}

@keyframes progress-bar-stripes {
    0% {
        background-position: 1rem 0;
    }
    100% {
        background-position: 0 0;
    }
}
</style>
