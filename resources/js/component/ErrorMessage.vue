<template>
    <div v-if="error" class="text-danger">
        <strong>{{ error }}</strong>
    </div>
</template>

<script>
export default {
    name: "ErrorMessage",
    props: {
        error: {
            type: String,
            required: false,
            default: null,
        },
    },
};
</script>

<style scoped>
.text-danger {
    color: #dc3545;
    font-size: 0.875rem; /* Adjust font size as needed */
}
</style>
