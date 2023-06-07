<template>

    <app-modal :showModal="showModal" @close="closeModal">

        <template v-slot:title>
            <div class="flex flex-col gap-y-2">
                <h3 class="text-xl font-semibold text-purple-900">
                    {{ account ? 'Edit ' : 'New ' }} Bank Account
                </h3>
            </div>
        </template>

        <form class="space-y-4" @submit.prevent="submit">

            <text-input-group v-model="form.name" label="Name" name="name"/>

            <div class="mt-4">

                <PrimaryButton :class="{ 'opacity-25': form.processing }" type="submit">
                    Save
                </PrimaryButton>

            </div>
        </form>
    </app-modal>

</template>

<script setup>
import {watch} from 'vue'
import {useForm} from "@inertiajs/vue3";
import AppModal from "@/Components/Modal/AppModal.vue"
import PrimaryButton from '@/Components/PrimaryButton.vue'
import TextInputGroup from "@/Components/Inputs/TextInputGroup.vue"

const props = defineProps({
    account: {type: Object, required: false},
    showModal: Boolean,
})

const emits = defineEmits(['reset'])

let form = useForm({})

watch(() => props.account, val => {
    form = useForm({
        name: val ? val.name : '',
    })
}, {immediate: true});

const closeModal = () => {
    form.reset()
    emits('reset', true)
}

const submit = () => {
    form.submit(
        props.account ? 'patch' : 'post',
        props.account ? route('bank-accounts.update', props.account) : route('bank-accounts.store'),
        {
            onSuccess: () => closeModal()
        }
    )
};


</script>
