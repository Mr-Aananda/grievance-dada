<template>
    <div>
        <label :for="inputId" class="form-label small mb-1">
            <i class="bi bi-sticky me-1"></i>Additional Notes
            <span v-if="!required" class="text-muted small">(Optional)</span>
        </label>
        <textarea :id="inputId" v-model="noteValue" class="form-control form-control-sm"
            :class="{ 'is-invalid': error }" :rows="rows" :placeholder="placeholder"
            :maxlength="maxLength" @input="handleInput"></textarea>

        <div v-if="error" class="invalid-feedback d-block">
            {{ error }}
        </div>

        <div v-if="showCharCount" class="text-end mt-1">
            <small class="text-muted">
                {{ noteLength }}/{{ maxLength }} characters
            </small>
        </div>

        <div v-if="helperText" class="mt-1">
            <small class="text-muted">
                <i class="bi bi-info-circle me-1"></i>{{ helperText }}
            </small>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue'

const props = defineProps({
    modelValue: {
        type: String,
        default: ''
    },
    placeholder: {
        type: String,
        default: 'Any additional information or comments...'
    },
    rows: {
        type: Number,
        default: 2
    },
    maxLength: {
        type: Number,
        default: 1000
    },
    required: {
        type: Boolean,
        default: false
    },
    error: {
        type: String,
        default: ''
    },
    showCharCount: {
        type: Boolean,
        default: true
    },
    helperText: {
        type: String,
        default: ''
    }
})

const emit = defineEmits(['update:modelValue', 'change'])

const inputId = `note_${Math.random().toString(36).substr(2, 9)}`
const noteValue = ref(props.modelValue)

// Computed
const noteLength = computed(() => {
    return noteValue.value?.length || 0
})

// Watch for external changes
watch(() => props.modelValue, (newValue) => {
    noteValue.value = newValue
})

// Handle input
const handleInput = () => {
    emit('update:modelValue', noteValue.value)
    emit('change', noteValue.value)
}

// Expose methods
defineExpose({
    clear: () => {
        noteValue.value = ''
        emit('update:modelValue', '')
        emit('change', '')
    },
    getValue: () => noteValue.value
})
</script>
