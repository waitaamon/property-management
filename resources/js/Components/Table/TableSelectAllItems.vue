<template>

    <tr>
        <table-td :colspan="colspan">
            <p class="text-gray-500 text-sm ">
                Selected
                <span class="text-purple-800 font-bold tracking-wide">{{ selectedCount }}</span>
                out of
                <span class="text-purple-800 font-bold tracking-wide">{{ totalItems }}</span>.
                Do you want to
                <a
                    :class="[allSelected ? 'text-red-600' : 'text-purple-800', 'font-bold tracking-wide hover:underline hover:underline-offset-2']"
                    href="" @click.prevent="allSelected = !allSelected"
                >
                    {{ allSelected ? 'Deselect ' : 'Select ' }}All?
                </a>
            </p>
        </table-td>
    </tr>
</template>

<script setup>
import {ref, watch} from "vue";
import TableTd from "./TableTd.vue";

const props = defineProps({
    colspan: {
        required: false,
        type: [String, Number],
        default: 9
    },
    totalItems: {
        type: Number,
        required: true,
    },
    selectedCount: {
        type: Number,
        required: true
    }
})

const emit = defineEmits(['selectAll'])

let allSelected = ref(false)

watch(allSelected, (val) => {
    if (val) {
        emit('selectAll', true)
        return
    }
    emit('selectAll', false)
    allSelected.value = false
})
</script>
