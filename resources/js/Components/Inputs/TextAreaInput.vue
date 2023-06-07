<template>
    <div>
        <InputLabel :for="name" :value="label ?? name"/>
        <textarea
            :id="name"
            :name="name"
            :disabled="disabled"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
            @input="$emit('update:modelValue', $event.target.value)">{{modelValue}}</textarea>
        <input-error-message :name="name"/>
    </div>
</template>

<script setup>
import InputLabel from '@/Components/InputLabel.vue';
import InputErrorMessage from "@/Components/Inputs/InputErrorMessage.vue";

defineProps({
      name: String,
      modelValue: {type: [String, Number]},
      label: {
            required: false,
            type: String,
      },
      disabled: {
            required: false,
            type: [Boolean, Object],
      },
})
</script>
