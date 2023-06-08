<script setup>
import {PrinterIcon} from "@heroicons/vue/20/solid/index.js";
import usePrintDocument from '@/Composables/usePrintDocument'

defineProps({
    model: Number,
    modelType: String,
    type: {type: String, default: 'button'},
    color: {type: String, default: 'emerald'},
});

const { printing, printDocument } = usePrintDocument()
</script>

<template>
    <button
        @click.prevent="printDocument(modelType, model)"
        :type="type"
        :class="`inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 focus:bg-emerald-700 active:bg-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition ease-in-out duration-150`"
    >

        <PrinterIcon aria-hidden="true" :class="`h-5 w-5 mr-2 text-emerald-100`"/>
        <slot />
    </button>
</template>
