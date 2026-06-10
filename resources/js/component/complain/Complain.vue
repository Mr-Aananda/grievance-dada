<template>
    <div class="complain-form">
        <form @submit.prevent="confirmSubmitComplaint" novalidate>
            <div class="row g-3">
                <!-- Left Column -->
                <div class="col-lg-8">
                    <!-- Row 1: Date, Complain/Manual Type, Category -->
                    <div class="row g-2 mb-3">
                        <!-- Date - Dynamic column based on edit mode -->
                        <div :class="isEdit ? 'col-md-2' : 'col-md-3'">
                            <label class="form-label"
                                >Date <span class="text-danger">*</span></label
                            >
                            <input
                                type="date"
                                v-model="form.date"
                                class="form-control"
                                :class="{ 'is-invalid': errors.date }"
                                required
                                :disabled="isSubmitting"
                            />
                            <div
                                v-if="errors.date"
                                class="invalid-feedback d-block"
                            >
                                {{ errors.date[0] }}
                            </div>
                        </div>

                        <!-- Type Selection Row (Only in Edit Mode) -->
                        <div class="col-md-3" v-if="isEdit">
                            <label class="form-label"
                                >Type
                                <span class="text-danger">*</span></label
                            >
                            <select
                                v-model="form.type"
                                class="form-select"
                                :disabled="isSubmitting"
                                @change="handleTypeChange"
                            >
                                <option value="complain">Complain</option>
                                <option value="manual">Manual</option>
                            </select>
                        </div>

                        <!-- Complain/Manual Type - Dynamic column based on edit mode -->
                        <div :class="isEdit ? 'col-md-3' : 'col-md-4'">
                            <label class="form-label"
                                >{{
                                    form.type == "complain"
                                        ? "Complain Type"
                                        : "Manual Type"
                                }}
                                <span class="text-danger">*</span></label
                            >
                            <Multiselect
                                v-model="selectedComplainType"
                                :options="filteredComplainTypes"
                                label="name"
                                track-by="id"
                                :placeholder="
                                    '-- Select ' +
                                    (form.type == 'complain'
                                        ? 'Complain'
                                        : 'Manual') +
                                    ' Type --'
                                "
                                :class="{
                                    'is-invalid': errors.complain_type_id,
                                }"
                                :searchable="true"
                                :disabled="isSubmitting"
                                required
                            />
                            <div
                                v-if="errors.complain_type_id"
                                class="invalid-feedback d-block"
                            >
                                {{ errors.complain_type_id[0] }}
                            </div>
                        </div>

                        <!-- Category Section - Dynamic column based on edit mode -->
                        <div :class="isEdit ? 'col-md-4' : 'col-md-5'">
                            <template v-if="form.type == 'complain'">
                                <label class="form-label"
                                    >Complain Category</label
                                >
                                <Multiselect
                                    v-model="selectedCategory"
                                    :options="categories"
                                    label="name"
                                    track-by="id"
                                    placeholder="-- Select Category (Optional) --"
                                    :class="{
                                        'is-invalid': errors.category_id,
                                    }"
                                    :searchable="true"
                                    :disabled="isSubmitting"
                                />
                                <div
                                    v-if="errors.category_id"
                                    class="invalid-feedback d-block"
                                >
                                    {{ errors.category_id[0] }}
                                </div>
                            </template>

                            <template v-else>
                                <label class="form-label"
                                    >Manual Category</label
                                >
                                <input
                                    v-model="form.manual_category"
                                    type="text"
                                    class="form-control"
                                    :class="{
                                        'is-invalid': errors.manual_category,
                                    }"
                                    placeholder="Enter manual categories"
                                    :disabled="isSubmitting"
                                />
                                <div
                                    v-if="errors.manual_category"
                                    class="invalid-feedback d-block"
                                >
                                    {{ errors.manual_category[0] }}
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Row 2: Subject, Buyers -->
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Subject</label>
                            <input
                                v-model="form.subject"
                                class="form-control"
                                placeholder="Enter subject"
                                :disabled="isSubmitting"
                            />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"
                                >Buyers
                                <span class="text-danger">*</span></label
                            >
                            <Multiselect
                                v-model="selectedBuyer"
                                :options="buyers"
                                label="custom_name"
                                track-by="id"
                                placeholder="-- Select Buyer --"
                                :class="{
                                    'is-invalid': errors.buyer_id,
                                }"
                                :searchable="true"
                                :disabled="isSubmitting"
                                required
                            />
                            <div
                                v-if="errors.buyer_id"
                                class="invalid-feedback d-block"
                            >
                                {{ errors.buyer_id[0] }}
                            </div>
                        </div>
                    </div>

                    <!-- Row 3: PS, PO -->
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">PS Number</label>
                            <input
                                v-model="form.ps"
                                class="form-control"
                                placeholder="PS Number"
                                :disabled="isSubmitting"
                            />
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">PO Number</label>
                            <input
                                v-model="form.po"
                                class="form-control"
                                placeholder="PO Number"
                                :disabled="isSubmitting"
                            />
                        </div>
                    </div>

                    <!-- Row 4: CAP, Style Order, Line Floor -->
                    <div class="row g-2 mb-3">
                        <div class="col-md-4">
                            <label class="form-label">CAP Number</label>
                            <input
                                v-model="form.cap"
                                class="form-control"
                                placeholder="CAP Number"
                                :disabled="isSubmitting"
                            />
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Style/Order</label>
                            <input
                                v-model="form.style_order"
                                class="form-control"
                                placeholder="e.g., STY-2024-001"
                                :disabled="isSubmitting"
                            />
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Line/Floor</label>
                            <input
                                v-model="form.line_floor"
                                class="form-control"
                                placeholder="e.g., Line 5, Floor 3"
                                :disabled="isSubmitting"
                            />
                        </div>
                    </div>

                    <!-- Row 5: Quantity, Amount (Only for Complain) -->
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Quantity</label>
                            <input
                                type="number"
                                v-model="form.quantity"
                                class="form-control"
                                placeholder="Enter quantity"
                                min="0"
                                step="1"
                                :disabled="isSubmitting"
                            />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Amount</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input
                                    type="number"
                                    v-model="form.amount"
                                    class="form-control"
                                    placeholder="0.00"
                                    min="0"
                                    step="0.01"
                                    :disabled="isSubmitting"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Complaint/Manual Details Editor -->
                    <div class="mb-3">
                        <label class="form-label"
                            >{{
                                form.type == "complain"
                                    ? "Complaint Details"
                                    : "Manual Details"
                            }}
                            <span class="text-danger">*</span></label
                        >
                        <QuillEditorComponent
                            v-model="form.complain"
                            :error-message="
                                errors.complain ? errors.complain[0] : ''
                            "
                            :placeholder="
                                form.type == 'complain'
                                    ? 'Describe the complaint in detail...'
                                    : 'Describe the manual content in detail...'
                            "
                            :height="'150px'"
                            :show-toolbar="true"
                            :show-char-count="false"
                            :max-length="3000"
                            @content-change="clearError('complain')"
                            :disabled="isSubmitting"
                        />
                        <div
                            v-if="errors.complain"
                            class="invalid-feedback d-block"
                        >
                            {{ errors.complain[0] }}
                        </div>
                    </div>

                    <!-- Note Field -->
                    <div class="mb-3">
                        <NoteComponent
                            v-model="form.note"
                            :error="errors.note ? errors.note[0] : ''"
                            placeholder="Any additional information or comments..."
                            :rows="3"
                            :max-length="500"
                            :show-char-count="false"
                            @change="clearError('note')"
                            :disabled="isSubmitting"
                        />
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-lg-4">
                    <!-- Files Upload Section -->
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-header bg-light py-2">
                            <h6 class="mb-0">
                                <i class="bi bi-files me-2"></i>Files & Images
                                <small class="text-muted">(Optional)</small>
                            </h6>
                        </div>
                        <div class="card-body p-2">
                            <MultipleFileComponent
                                ref="fileComponent"
                                v-model="files"
                                :existing-files="existingFilesList"
                                :deleted-files="form.deleted_files"
                                @update:deleted-files="
                                    form.deleted_files = $event
                                "
                                @file-warning="handleFileWarning"
                                :disabled="isSubmitting"
                            />
                        </div>
                    </div>

                    <!-- Video Upload Section -->
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-header bg-light py-2">
                            <h6 class="mb-0">
                                <i class="bi bi-camera-video me-2"></i>Videos
                                <small class="text-muted">(Optional)</small>
                            </h6>
                        </div>
                        <div class="card-body p-2">
                            <MultipleVideoComponent
                                v-model="selectedVideos"
                                :existing-videos="existingVideos"
                                :deleted-videos="form.deleted_videos"
                                @update:deleted-videos="
                                    form.deleted_videos = $event
                                "
                                :max-files="5"
                                :max-size-mb="500"
                                :disabled="isSubmitting"
                                @video-warning="handleVideoWarning"
                            />
                        </div>
                    </div>

                    <!-- Status (Edit Mode Only) -->
                    <div class="card border-0 shadow-sm mb-3" v-if="isEdit">
                        <div class="card-header bg-light py-2">
                            <h6 class="mb-0">
                                <i class="bi bi-flag me-2"></i>Status
                            </h6>
                        </div>
                        <div class="card-body p-2">
                            <select
                                v-model="form.status"
                                class="form-select form-select-sm"
                                :class="{ 'is-invalid': errors.status }"
                                :disabled="isSubmitting"
                            >
                                <option
                                    v-for="(status, key) in statuses"
                                    :key="key"
                                    :value="key"
                                >
                                    {{ status }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-3">
                            <div class="d-grid gap-2">
                                <!-- Main Submit Button -->
                                <button
                                    type="submit"
                                    class="btn btn-theme btn-lg"
                                    :disabled="!isFormValid || isSubmitting"
                                >
                                    <template v-if="isSubmitting">
                                        <span
                                            class="spinner-border spinner-border-sm me-2"
                                        ></span>
                                        Saving...
                                    </template>
                                    <template v-else>
                                        <i class="bi bi-check-circle me-2"></i>
                                        {{ submitButtonText }}
                                    </template>
                                </button>

                                <!-- Bottom Buttons -->
                                <div class="d-flex gap-2">
                                    <button
                                        type="button"
                                        class="btn btn-outline-dark w-50"
                                        @click="resetForm"
                                        :disabled="isSubmitting"
                                    >
                                        <i
                                            class="bi bi-arrow-clockwise me-1"
                                        ></i>
                                        Reset
                                    </button>

                                    <a
                                        :href="cancelUrl"
                                        class="btn btn-outline-danger w-50"
                                        :class="{ disabled: isSubmitting }"
                                    >
                                        <i class="bi bi-x-circle me-1"></i>
                                        Cancel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!-- Success Alert Modal -->
        <div
            v-if="showSuccessAlert"
            class="modal-overlay"
            @click.self="closeSuccessAlert"
        >
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-body text-center p-4">
                        <div class="success-icon mb-3">
                            <i
                                class="bi bi-check-circle-fill text-success"
                                style="font-size: 3rem"
                            ></i>
                        </div>
                        <h5 class="text-success mb-2">Success!</h5>
                        <p class="text-muted mb-4">
                            {{ successMessage }}
                        </p>
                        <button
                            type="button"
                            class="btn btn-theme w-100"
                            @click="closeSuccessAlert"
                        >
                            OK
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted, watch } from "vue";
import MultipleFileComponent from "../reusable-components/MultipleFileComponent.vue";
import MultipleVideoComponent from "../reusable-components/MultipleVideoComponent.vue";
import NoteComponent from "../reusable-components/NoteComponent.vue";
import QuillEditorComponent from "../reusable-components/QuillEditorComponent.vue";
import {
    showErrorAlert,
    showConfirmationAlert,
    showLoadingAlert,
    closeAlert,
    showToast,
} from "@/utils/alert.js";

// Define props
const props = defineProps({
    type: {
        type: String,
        default: "complain",
    },
    complainTypes: {
        type: Array,
        default: () => [],
    },
    categories: {
        type: Array,
        default: () => [],
    },
    buyers: {
        type: Array,
        default: () => [],
    },
    statuses: {
        type: Object,
        default: () => ({}),
    },
    existingFiles: {
        type: Array,
        default: () => [],
    },
    existingVideos: {
        type: Array,
        default: () => [],
    },
    complainData: {
        type: Object,
        default: () => ({}),
    },
    submitUrl: {
        type: String,
        required: true,
    },
    cancelUrl: {
        type: String,
        default: "/admin/complain",
    },
    isEdit: {
        type: Boolean,
        default: false,
    },
});

// Refs
const fileComponent = ref(null);
const errors = ref({});
const files = ref([]);

// Multiselect refs
const selectedComplainType = ref(null);
const selectedBuyer = ref(null);
const selectedCategory = ref(null);

// State
const isSubmitting = ref(false);
const selectedVideos = ref([]);
const existingVideos = ref([]);
const showSuccessAlert = ref(false);
const successMessage = ref("");

// Computed Properties
const existingFilesList = computed(() => {
    return props.existingFiles || [];
});

const allVideosCount = computed(() => {
    return selectedVideos.value.length;
});

// Filter complain types based on current form type
const filteredComplainTypes = computed(() => {
    if (!props.complainTypes || props.complainTypes.length === 0) {
        return [];
    }

    // Filter based on the 'type' field in complainTypes array
    return props.complainTypes.filter((item) => item.type === form.type);
});

const isFormValid = computed(() => {
    // Basic validation
    if (!selectedComplainType.value) return false;
    if (!selectedBuyer.value) return false;
    if (!form.date) return false;
    if (!form.complain?.trim()) return false;

    return true;
});

const submitButtonText = computed(() => {
    if (form.type === "manual") {
        return props.isEdit ? "Update Manual" : "Save Manual";
    }
    return props.isEdit ? "Update Complaint" : "Save Complaint";
});

// Form Data
const form = reactive({
    type: props.type,
    date: new Date().toISOString().split("T")[0],
    name: "",
    ps: "",
    subject: "",
    po: "",
    cap: "",
    style_order: "",
    line_floor: "",
    quantity: "",
    amount: "",
    complain: "",
    note: "",
    status: "pending",
    manual_category: "",
    new_files: [],
    deleted_files: [],
    deleted_videos: [],
});

// Handle type change in edit mode
const handleTypeChange = () => {
    // Clear type-specific fields when switching
    if (form.type === "complain") {
        // Switching to complain - clear manual fields
        form.manual_category = "";
    } else {
        // Switching to manual - clear complain-specific fields
        form.quantity = "";
        form.amount = "";
        selectedCategory.value = null;
    }

    // Reset selected complain type since the options have changed
    selectedComplainType.value = null;

    // Clear errors for the switched fields
    delete errors.value.quantity;
    delete errors.value.amount;
    delete errors.value.manual_category;
    delete errors.value.category_id;
    delete errors.value.complain_type_id;

    showToast(
        `Switched to ${form.type === "complain" ? "Complaint" : "Manual"} mode`,
        "info",
    );
};

// Watch for type changes
watch(
    () => form.type,
    (newType, oldType) => {
        if (oldType && newType !== oldType) {
            handleTypeChange();
        }
    },
);

// File warning handler
const handleFileWarning = (warning) => {
    if (warning.type === "confirm") {
        showConfirmationAlert(
            warning.message,
            "Confirm Remove All",
            "Remove All",
            "Cancel",
        ).then((confirmed) => {
            if (warning.callback && confirmed) {
                warning.callback(confirmed);
            }
        });
    } else {
        showErrorAlert(warning.message, warning.type || "Warning");
    }
};

// Video warning handler
const handleVideoWarning = (warning) => {
    if (warning.type === "confirm") {
        showConfirmationAlert(
            warning.message,
            "Confirm Remove All",
            "Remove All",
            "Cancel",
        ).then((confirmed) => {
            if (warning.callback && confirmed) {
                warning.callback(confirmed);
            }
        });
    } else {
        showErrorAlert(warning.message, warning.type || "Warning");
    }
};

const closeSuccessAlert = () => {
    showSuccessAlert.value = false;
    successMessage.value = "";
};

// Form Validation
const validateForm = () => {
    errors.value = {};

    if (!selectedComplainType.value) {
        errors.value.complain_type_id = [
            form.type === "complain"
                ? "Complain type is required"
                : "Manual type is required",
        ];
        return false;
    }

    if (!selectedBuyer.value) {
        errors.value.buyer_id = ["Buyer is required"];
        return false;
    }

    if (!form.date) {
        errors.value.date = ["Date is required"];
        return false;
    }

    if (!form.complain?.trim()) {
        errors.value.complain = [
            form.type === "complain"
                ? "Complaint details are required"
                : "Manual details are required",
        ];
        return false;
    }

    return true;
};

const clearError = (field) => {
    if (errors.value[field]) {
        delete errors.value[field];
    }
};

// Confirmation before submit
const confirmSubmitComplaint = async () => {
    if (!validateForm()) return;

    // File validations
    const totalFiles = files.value.length;
    if (totalFiles > 20) {
        showErrorAlert(
            `You have ${totalFiles} files. Maximum 20 files allowed.`,
            "Too Many Files",
        );
        return;
    }

    // File size validation
    const imageTypes = [
        "image/jpeg",
        "image/jpg",
        "image/png",
        "image/gif",
        "image/webp",
        "image/svg+xml",
    ];
    const maxImageSize = 10 * 1024 * 1024; // 10MB
    const maxFileSize = 15 * 1024 * 1024; // 15MB

    const oversizedFiles = files.value.filter((file) => {
        const isImage = imageTypes.includes(file.type);
        const maxAllowedSize = isImage ? maxImageSize : maxFileSize;
        return file.file && file.file.size > maxAllowedSize;
    });

    if (oversizedFiles.length > 0) {
        const errorMessage = oversizedFiles
            .map((file) => {
                const isImg = imageTypes.includes(file.type);
                return `"${file.name}" exceeds ${isImg ? "10MB" : "15MB"} size limit`;
            })
            .join("\n");

        showErrorAlert(
            `Some files exceed size limit:\n${errorMessage}`,
            "Large Files Found",
        );
        return;
    }

    // Video validations
    if (allVideosCount.value > 5) {
        showErrorAlert(
            `You have ${allVideosCount.value} videos. Maximum 5 videos allowed.`,
            "Too Many Videos",
        );
        return;
    }

    const maxVideoSize = 500 * 1024 * 1024;
    const largeVideos = selectedVideos.value.filter(
        (video) => video.file && video.file.size > maxVideoSize,
    );

    if (largeVideos.length > 0) {
        showErrorAlert(
            `Some videos are larger than 500MB. Please remove or compress them before submitting.`,
            "Large Videos Found",
        );
        return;
    }

    const confirmed = await showConfirmationAlert(
        form.type === "manual"
            ? props.isEdit
                ? "Are you sure you want to update this manual?"
                : "Are you sure you want to save this manual?"
            : props.isEdit
              ? "Are you sure you want to update this complaint?"
              : "Are you sure you want to save this complaint?",
        form.type === "manual"
            ? props.isEdit
                ? "Update Manual"
                : "Save Manual"
            : props.isEdit
              ? "Update Complaint"
              : "Save Complaint",
        props.isEdit ? "Update" : "Save",
        "Cancel",
    );

    if (!confirmed) return;

    await submitComplaint();
};

// Main Submit Function
const submitComplaint = async () => {
    const loadingAlert = showLoadingAlert(
        props.isEdit ? "Updating..." : "Saving...",
    );
    isSubmitting.value = true;

    try {
        const formData = new FormData();

        // Add form fields
        const fields = [
            "type",
            "date",
            "name",
            "subject",
            "ps",
            "po",
            "cap",
            "style_order",
            "line_floor",
            "quantity",
            "amount",
            "complain",
            "note",
            "status",
            "manual_category",
        ];

        fields.forEach((field) => {
            if (
                form[field] !== null &&
                form[field] !== undefined &&
                form[field] !== ""
            ) {
                formData.append(field, form[field]);
            }
        });

        // Add multiselect values
        if (selectedComplainType.value) {
            formData.append("complain_type_id", selectedComplainType.value.id);
        }
        if (selectedBuyer.value) {
            formData.append("buyer_id", selectedBuyer.value.id);
        }
        if (selectedCategory.value) {
            formData.append("category_id", selectedCategory.value.id);
        }

        // Add files
        const newFiles = files.value.filter((file) => file.isNew && file.file);
        if (newFiles.length > 0) {
            newFiles.forEach((file, i) => {
                formData.append(`new_files[${i}]`, file.file);
            });
        }

        // Add deleted files
        if (form.deleted_files && form.deleted_files.length > 0) {
            form.deleted_files.forEach((id, i) => {
                formData.append(`deleted_files[${i}]`, id);
            });
        }

        // Add deleted videos
        if (form.deleted_videos && form.deleted_videos.length > 0) {
            form.deleted_videos.forEach((id, i) => {
                formData.append(`deleted_videos[${i}]`, id);
            });
        }

        // Add videos
        const newVideos = selectedVideos.value.filter(
            (video) => video.isNew && video.file,
        );
        if (newVideos.length > 0) {
            newVideos.forEach((video, i) => {
                formData.append(`videos[${i}]`, video.file);
            });
        }

        // For edit mode
        if (props.isEdit) {
            formData.append("_method", "PUT");
        }

        // CSRF token
        const csrfToken = document
            .querySelector('meta[name="csrf-token"]')
            ?.getAttribute("content");
        if (csrfToken) {
            formData.append("_token", csrfToken);
        }

        // Submit to server
        const response = await axios.post(props.submitUrl, formData, {
            headers: { "Content-Type": "multipart/form-data" },
            timeout: 300000,
            onUploadProgress: (progressEvent) => {
                if (progressEvent.lengthComputable) {
                    const totalSize = [...newFiles, ...newVideos].reduce(
                        (sum, item) => sum + (item.file ? item.file.size : 0),
                        0,
                    );
                    const percentCompleted = Math.round(
                        (progressEvent.loaded * 100) / progressEvent.total,
                    );

                    newVideos.forEach((video) => {
                        if (video.file) {
                            const videoRatio = video.file.size / totalSize;
                            video.progress = Math.round(
                                percentCompleted * videoRatio,
                            );
                        }
                    });
                }
            },
        });

        closeAlert();

        if (response.data.success) {
            successMessage.value =
                response.data.message ||
                (form.type === "complain"
                    ? props.isEdit
                        ? "Complaint updated successfully!"
                        : "Complaint saved successfully!"
                    : props.isEdit
                      ? "Manual updated successfully!"
                      : "Manual saved successfully!");
            showSuccessAlert.value = true;

            setTimeout(() => {
                window.location.href =
                    response.data.redirect || "/admin/complain";
            }, 1500);
        } else {
            showErrorAlert(
                response.data.message ||
                    (props.isEdit
                        ? "Failed to update entry"
                        : "Failed to save entry"),
                "Error",
            );
        }
    } catch (error) {
        closeAlert();

        if (error.response?.status === 422) {
            errors.value = error.response.data.errors || {};
            showErrorAlert(
                "Please fix the validation errors",
                "Validation Error",
            );
        } else {
            showErrorAlert(error.message || "Something went wrong", "Error");
        }

        console.error("Submit error:", error);
    } finally {
        isSubmitting.value = false;
    }
};

const resetForm = async () => {
    const confirmed = await showConfirmationAlert(
        "Reset the form? All unsaved changes will be lost.",
        "Confirm Reset",
        "Reset",
        "Cancel",
    );

    if (!confirmed) return;

    Object.keys(form).forEach((key) => {
        if (Array.isArray(form[key])) {
            form[key] = [];
        } else if (key === "date") {
            form[key] = new Date().toISOString().split("T")[0];
        } else if (key === "status") {
            form[key] = "pending";
        } else if (key === "type") {
            form[key] = props.type; // Reset to original type
        } else {
            form[key] = "";
        }
    });

    // Reset Multiselect values
    selectedComplainType.value = null;
    selectedBuyer.value = null;
    selectedCategory.value = null;

    files.value = [];
    selectedVideos.value = [];
    errors.value = {};

    if (props.isEdit) {
        existingVideos.value = [...props.existingVideos];
    }

    showToast("Form reset successfully", "info");
};

// Initialize
onMounted(() => {
    if (
        props.isEdit &&
        props.complainData &&
        Object.keys(props.complainData).length > 0
    ) {
        // Copy all form data
        Object.keys(form).forEach((key) => {
            if (
                key in props.complainData &&
                props.complainData[key] !== null &&
                key !== "complain_type_id" &&
                key !== "category_id"
            ) {
                form[key] = props.complainData[key];
            }
        });

        // Set the type from data or keep as prop.type
        form.type = props.complainData.type || props.type;

        if (props.complainData.date) {
            form.date = new Date(props.complainData.date)
                .toISOString()
                .split("T")[0];
        }

        // Set Multiselect values for edit mode
        if (props.complainData.complain_type_id && props.complainTypes) {
            const complainType = props.complainTypes.find(
                (type) => type.id === props.complainData.complain_type_id,
            );
            if (complainType && complainType.type === form.type) {
                selectedComplainType.value = complainType;
            }
        }

        if (props.complainData.buyer_id && props.buyers) {
            const buyer = props.buyers.find(
                (buyer) => buyer.id === props.complainData.buyer_id,
            );
            if (buyer) {
                selectedBuyer.value = buyer;
            }
        }

        // Category optional
        if (props.complainData.category_id && props.categories) {
            const category = props.categories.find(
                (cat) => cat.id === props.complainData.category_id,
            );
            if (category) {
                selectedCategory.value = category;
            }
        }

        // Load existing files
        if (props.existingFiles && props.existingFiles.length > 0) {
            files.value = props.existingFiles.map((file) => ({
                id: file.id,
                name: file.original_name || file.name || "File",
                type: file.mime_type || "application/octet-stream",
                size: file.size || 0,
                isNew: false,
                isImage: file.is_image || false,
                preview: file.is_image
                    ? file.url || file.original_url || file.thumb_url
                    : null,
                file: null,
            }));
        }

        // Load existing videos
        if (props.existingVideos && props.existingVideos.length > 0) {
            existingVideos.value = [...props.existingVideos];
        }

        // Initialize deleted arrays
        form.deleted_files = [];
        form.deleted_videos = [];
    }
    console.log("Component initialized with type:", form.type);
});
</script>

<style scoped>
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

.list-group-item {
    border-left: 3px solid transparent;
    transition: all 0.2s ease;
}

.list-group-item:hover {
    background-color: #f8f9fa;
    border-left-color: #f57a22;
}

.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1050;
    padding: 15px;
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

.modal-dialog {
    width: 100%;
    max-width: 400px;
    animation: slideIn 0.3s ease;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.modal-content {
    border-radius: 10px;
    border: none;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    overflow: hidden;
}

.success-icon {
    animation: bounce 0.5s ease;
}

@keyframes bounce {
    0%,
    20%,
    50%,
    80%,
    100% {
        transform: translateY(0);
    }
    40% {
        transform: translateY(-10px);
    }
    60% {
        transform: translateY(-5px);
    }
}

/* Theme Colors */
.bg-theme {
    background-color: #f57a22 !important;
    color: white;
}

.text-theme {
    color: #f57a22 !important;
}

.btn-theme {
    background-color: #f57a22;
    border-color: #f57a22;
    color: white;
}

.btn-theme:hover {
    background-color: #e0691a;
    border-color: #e0691a;
    color: white;
}

.btn-outline-theme {
    color: #f57a22;
    border-color: #f57a22;
}

.btn-outline-theme:hover {
    background-color: #f57a22;
    border-color: #f57a22;
    color: white;
}

/* Form styles */
.complain-form .card {
    border-radius: 0.5rem;
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
}

.complain-form .card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

.complain-form .card-header {
    border-bottom: 1px solid #e9ecef;
    background: #f8f9fa;
}

.form-control:focus,
.form-select:focus {
    border-color: #f57a22;
    box-shadow: 0 0 0 0.25rem rgba(245, 122, 34, 0.25);
}

.text-success {
    color: #198754 !important;
}

.form-label {
    font-weight: 500;
    color: #495057;
    margin-bottom: 0.25rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .modal-overlay {
        padding: 10px;
    }
}
</style>
