<template>
    <div class="mt-1 flex rounded-md shadow-sm">
        <div class="mt-1 flex rounded-md shadow-sm">
            <div class="relative flex items-stretch flex-grow focus-within:z-10">

                <input v-model="search" type="search" name="search" id="search" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full rounded-none rounded-l-md sm:text-sm border-gray-300" placeholder="Search..."/>
            </div>
            <button @click.prevent="$emit('update:modelValue', search)"  type="button" class="-ml-px relative inline-flex items-center space-x-2 px-4 py-2 border border-gray-300 text-sm font-medium rounded-r-md text-gray-700 bg-gray-50 hover:bg-gray-100 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                <SearchIcon class="h-5 w-5 text-gray-400" aria-hidden="true"/>
                <span>Search</span>
            </button>
        </div>
        <button class="ml-3 text-gray-500 hover:text-gray-700 focus:text-form-500 text-sm" type="button" @click="$emit('reset')">Reset</button>
    </div>
</template>

<script setup>
import {ref} from "vue";
import {SearchIcon} from '@heroicons/vue/solid'

defineProps({
      modelValue: String
})

const search = ref('')

const emits = defineEmits(['update:modelValue', 'reset'])

</script>