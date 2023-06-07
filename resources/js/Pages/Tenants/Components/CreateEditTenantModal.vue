<template>

    <app-modal :showModal="showModal" @close="closeModal">

        <template v-slot:title>
            <div class="flex flex-col gap-y-2">
                <h3 class="text-xl font-semibold text-purple-900">
                    {{ tenant ? 'Edit' : 'Add New'}} Customer
                </h3>
            </div>
        </template>

        <form class="space-y-4" @submit.prevent="submit">

            <text-input-group v-model="form.name" label="Name" name="name"/>

            <text-input-group v-model="form.pin" label="Pin" name="pin"/>

            <text-input-group type="email" v-model="form.email" label="Email" name="email"/>

            <text-input-group  v-model="form.address" label="Address" name="address"/>

            <text-input-group v-model="form.phone" label="Phone" name="phone"/>

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
import PrimaryButton from '@/Components/PrimaryButton.vue'
import AppModal from "@/Components/Modal/AppModal.vue"
import TextInputGroup from "@/Components/Inputs/TextInputGroup.vue"

const props = defineProps({
    tenant: {type: Object, required: false},
    showModal: Boolean,
})

const emits = defineEmits(['reset'])

let form = useForm({})

watch(() => props.tenant, val => {
    form = useForm({
        name: val ? val.name : '',
        pin: val ? val.pin : '',
        phone: val ? val.phone : '',
        email: val ? val.email : '',
        address: val ? val.address : '',
    })
}, {immediate: true});

const closeModal = () => {
    form.reset()
    emits('reset', true)
}

const submit = () => {
    form.submit(
        props.tenant ? 'patch' : 'post',
        props.tenant ? route('tenants.update', props.tenant) : route('tenants.store'),
        {
            onSuccess: () => closeModal()
        }
    )
};
</script>
