<template>
    <tr>
        <td :colspan="colspan">
            <div class="w-full py-3 flex justify-center items-center ">
                <DocumentMagnifyingGlassIcon class="text-gray-500 h-6 w-6" aria-hidden="true"/>
                <span class="ml-4 text-gray-500 text-medium leading-4">
                    <slot>No data found!</slot>
                </span>
            </div>
        </td>
    </tr>
</template>

<script setup>
import {DocumentMagnifyingGlassIcon} from '@heroicons/vue/24/outline'

defineProps({
      colspan: {
            required: false,
            type: [String, Number],
            default: 6
      }
})
</script>
