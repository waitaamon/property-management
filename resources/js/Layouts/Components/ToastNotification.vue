<script setup>
import {computed, watch} from "vue";
import {useToast} from "vue-toastification";
import {usePage} from "@inertiajs/vue3";

const toast = useToast()

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
    <div></div>
</template>
