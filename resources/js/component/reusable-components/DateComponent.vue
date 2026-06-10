<template>
    <div class="mb-2">
        <label for="date" class="form-label required">Date</label>
        <input
            type="date"
            class="form-control"
            name="date"
            v-model="date"
            id="date"
            required
        />
        <ErrorMessage v-if="store.errors.date" :error="store.errors.date[0]" />
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from "vue";
import { usePiniaStore } from "@/store";
import ErrorMessage from "../ErrorMessage.vue";

const store = usePiniaStore();
const date = ref(new Date().toISOString().substr(0, 10));

onMounted(() => {
    setTimeout(() => {
        if (store.oldPurchase) {
            date.value = store.oldPurchase?.date;
        }
    },);
});
defineExpose({
    date,
});
</script>
