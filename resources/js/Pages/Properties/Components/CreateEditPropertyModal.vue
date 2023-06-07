<template>

    <app-modal :showModal="showModal" @close="closeModal">

        <template v-slot:title>
            <div class="flex flex-col gap-y-2">
                <h3 class="text-xl font-semibold text-purple-900">
                    {{ property ? 'Edit' : 'Add New' }} Property
                </h3>
            </div>
        </template>

        <form class="space-y-4" @submit.prevent="submit">

            <text-input-group v-model="form.name" label="Name" name="name"/>

            <text-input-group v-model="form.location" label="Location" name="location"/>

            <text-input-group v-model="form.address" label="Address" name="address"/>

            <text-input-group v-model="form.phone" label="Phone" name="phone"/>

            <text-input-group v-model="form.email" label="Email" name="email"/>

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
    property: {type: Object, required: false},
    showModal: Boolean,
})

const emits = defineEmits(['reset'])

let form = useForm({})

watch(() => props.property, val => {
    form = useForm({
        name: val ? val.name : '',
        email: val ? val.email : '',
        phone: val ? val.phone : '',
        address: val ? val.address : '',
        location: val ? val.location : '',
    })
}, {immediate: true});

const closeModal = () => {
    form.reset()
    emits('reset', true)
}

const submit = () => {
    form.submit(
        props.property ? 'patch' : 'post',
        props.property ? route('properties.update', props.property) : route('properties.store'),
        {
            onSuccess: () => closeModal()
        }
    )
};
</script>
