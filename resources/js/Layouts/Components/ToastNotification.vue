<script setup>
const toast = useToast()
import {computed, watch} from "vue";
import {useToast} from "vue-toastification";
import {usePage} from "@inertiajs/vue3";

let alert = computed(() => usePage().props.flash.toast)
watch(alert, (val) => {
    if (!val) return
    toast(val.message, {
        type: val.type,
        onClose: () => {
            alert = null
        }
    })
})
</script>
<template>
    {{ alert }}
</template>
