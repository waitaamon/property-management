<template>

    <dialog-modal :show="isOpen" @close="closeApprovalModal">
        <template v-slot:title>
            <h3 class="text-xl font-semibold text-purple-900 capitalize">
                {{ payload.action }} {{ payload.model }}
            </h3>
        </template>
        <template v-slot:content>
            <form>
                <text-input-group v-model="form.note" name="note"/>
            </form>
        </template>
        <template v-slot:footer>
            <primary-button type="button" @click.prevent="submit">{{ payload.action }}</primary-button>
        </template>
    </dialog-modal>
</template>
<script setup>
import {ref, watch} from "vue";
import {useForm} from "@inertiajs/vue3";
import DialogModal from "./DialogModal.vue";
import PrimaryButton from "./PrimaryButton.vue";
import TextInputGroup from "./Inputs/TextInputGroup.vue";

const props = defineProps({
    payload: Object
})

const emit = defineEmits(['reset'])

const isOpen = ref(false)

const form = useForm({note: ''})

watch(() => props.payload, val => {
    isOpen.value = val.show
}, {immediate: true})

const closeApprovalModal = () => {
    isOpen.value = false
    emit('reset', true)
}

const submit = () => {
    form
        .transform((data) => ({
            ...data,
            id: props.payload.id,
            model: props.payload.model,
            action: props.payload.action,
        }))
        .post(route('approve'), {
            onSuccess: () => {
                closeApprovalModal()
                window.location.reload()
            }
        })
}
</script>
