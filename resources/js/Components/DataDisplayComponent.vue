<template>
    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5">
        <dt class="text-sm font-medium text-gray-500">{{ title }}</dt>
        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0"> {{ value }}</dd>
    </div>
</template>

<script setup>
    defineProps({
        title: String,
        value: String,
    })
</script>
