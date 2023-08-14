<template>
    <div>
        <InputLabel  :for="name" :value="label ?? name" class=""/>
        <vue-number
            :id="name"
            :name="name"
            :disabled="disabled"
            :modelValue="modelValue"
            :placeholder="placeholder"
            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-1 bg-white h-11 disabled:cursor-not-allowed focus:ring-gray-700 focus:ring-opacity-50 rounded-md"
            :class="{'border-red-200 placeholder-red-300': $page.props.errors[name]}"
            @input="$emit('update:modelValue', $event.target.value.replace(/\,/g,''))"
        />
        <input-error-message :name="name"/>
    </div>
</template>
<script setup>
import InputLabel from '@/Components/InputLabel.vue';
import InputErrorMessage from "@/Components/Inputs/InputErrorMessage.vue";
defineProps({
    name: String,
    modelValue: [String, Number],
    label: {
        required: false,
        type: String,
    },
    placeholder: {
        type: String,
        default: 'Please input required info'
    },
    disabled: {
        required: false,
        type: Boolean,
    },
    type: {
        required: false,
        type: String,
        default: 'text'
    }
})
</script>
