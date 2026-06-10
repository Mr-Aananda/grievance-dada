<template>
    <div class="quill-editor-wrapper">
        <div
            class="quill-container"
            :class="{
                'editor-error': hasError,
                'editor-disabled': disabled,
                'border-danger': hasError
            }"
        >
            <!-- Quill Editor -->
            <QuillEditor
                ref="quillEditorRef"
                v-model:content="editorContent"
                :options="editorOptions"
                :readOnly="disabled"
                contentType="html"
                @update:content="handleContentUpdate"
            />
        </div>

        <!-- Error Message -->
        <div v-if="errorMessage" class="invalid-feedback d-block">
            {{ errorMessage }}
        </div>

        <!-- Character Counter -->
        <div v-if="showCharCount" class="text-muted small mt-1 text-end">
            {{ charCount }} / {{ maxLength }} characters
        </div>

        <!-- Helper Text -->
        <div v-if="helperText" class="form-text mt-1">
            {{ helperText }}
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue'
import { QuillEditor } from "@vueup/vue-quill";
import "@vueup/vue-quill/dist/vue-quill.snow.css";

const props = defineProps({
    modelValue: {
        type: String,
        default: ''
    },
    placeholder: {
        type: String,
        default: 'Write your content here...'
    },
    disabled: {
        type: Boolean,
        default: false
    },
    errorMessage: {
        type: String,
        default: ''
    },
    maxLength: {
        type: Number,
        default: null
    },
    height: {
        type: String,
        default: '200px'
    },
    showToolbar: {
        type: Boolean,
        default: true
    },
    showCharCount: {
        type: Boolean,
        default: false
    },
    helperText: {
        type: String,
        default: ''
    }
})

const emit = defineEmits(['update:modelValue', 'content-change'])

// Refs
const quillEditorRef = ref(null)
const editorContent = ref('')

// Computed
const hasError = computed(() => !!props.errorMessage)
const charCount = computed(() => {
    if (!editorContent.value) return 0
    const text = editorContent.value.replace(/<[^>]*>/g, '')
    return text.length
})

// Editor options without image button
const editorOptions = computed(() => ({
    theme: 'snow',
    placeholder: props.placeholder,
    modules: {
        toolbar: props.showToolbar ? [
            ['bold', 'italic', 'underline', 'strike'],
            ['blockquote', 'code-block'],
            [{ 'list': 'ordered' }, { 'list': 'bullet' }],
            [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
            [{ 'color': [] }, { 'background': [] }],
            ['link'],
            ['clean']
        ] : false
    },
    readOnly: props.disabled
}))

// Methods
const handleContentUpdate = (content) => {
    emit('update:modelValue', content)
    emit('content-change', content)
}

const setContent = (content) => {
    editorContent.value = content
}

const getContent = () => {
    return editorContent.value
}

const clearContent = () => {
    editorContent.value = ''
}

const getQuillInstance = () => {
    return quillEditorRef.value?.getQuill()
}

const focus = () => {
    const quill = getQuillInstance()
    if (quill) {
        quill.focus()
    }
}

// Watch modelValue changes
watch(() => props.modelValue, (newValue) => {
    if (newValue !== editorContent.value) {
        editorContent.value = newValue
    }
}, { immediate: true })

// Watch maxLength for validation
watch([editorContent, () => props.maxLength], ([content, maxLength]) => {
    if (maxLength && content) {
        const text = content.replace(/<[^>]*>/g, '')
        if (text.length > maxLength) {
            emit('update:modelValue', content.substring(0, maxLength))
        }
    }
})

onMounted(() => {
    editorContent.value = props.modelValue || ''
})

// Expose methods
defineExpose({
    setContent,
    getContent,
    clearContent,
    getQuillInstance,
    focus,
    quillEditor: quillEditorRef
})
</script>

<style scoped>
.quill-editor-wrapper {
    width: 100%;
}

.quill-container {
    border-radius: 0.375rem;
    overflow: hidden;
    border: 1px solid #dee2e6;
}

.quill-container.editor-error {
    border-color: #dc3545;
}

.quill-container.editor-disabled {
    opacity: 0.6;
    background-color: #e9ecef;
}

:deep(.ql-toolbar.ql-snow) {
    border: none;
    border-bottom: 1px solid #dee2e6;
    background-color: #f8f9fa;
}

:deep(.ql-container.ql-snow) {
    border: none;
    min-height: v-bind(height);
}

:deep(.ql-editor) {
    min-height: v-bind(height);
    font-family: inherit;
    font-size: 0.875rem;
}

:deep(.ql-editor.ql-blank::before) {
    color: #6c757d;
    font-style: normal;
}
</style>
