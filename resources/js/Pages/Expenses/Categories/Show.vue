<template>
    <AppLayout title="Expense categories">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{ category }}
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";

defineProps({
    category: Object
})
</script>
