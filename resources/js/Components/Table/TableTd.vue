<template>
    <td :colspan="colspan" class=" px-6 py-4 whitespace-nowrap text-sm text-gray-900">
        <slot/>
    </td>
</template>

<script setup>
defineProps({
    colspan: {required: false, type: [String, Number], default: 0}
})
</script>
