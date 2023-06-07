<template>
    <div>
        <InputLabel :for="name" :value="label ?? name"/>
        <TextInput
            :id="name"
            :disabled="disabled"
            :modelValue="modelValue"
            :name="name"
            :type="type"
            class="mt-1 block w-full"
            @input="$emit('update:modelValue', $event.target.value)"
        />
        <input-error-message :name="name"/>
    </div>
</template>

<script setup>
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputErrorMessage from "@/Components/Inputs/InputErrorMessage.vue";

defineProps({
    name: String,
    modelValue: [String, Number],
    label: {
        required: false,
        type: String,
    },
    disabled: {
        required: false,
        type: [Boolean, Object],
    },
    type: {
        required: false,
        type: String,
        default: 'text'
    }
})
</script>
