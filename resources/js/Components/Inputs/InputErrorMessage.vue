<template>
    <p v-if="$page.props.errors[name]" class="mt-2 text-sm text-red-600" :id="`${name}-error`">{{ $page.props.errors[name] }}</p>
</template>

<script setup>
defineProps({
      name: String,
})
</script>