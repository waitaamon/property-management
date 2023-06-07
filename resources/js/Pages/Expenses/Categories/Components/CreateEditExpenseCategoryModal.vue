<template>

    <app-modal :showModal="showModal" @close="closeModal">

        <template v-slot:title>
            <div class="flex flex-col gap-y-2">
                <h3 class="text-xl font-semibold text-purple-900">
                    {{ category ? 'Edit' : 'Add New'}} Category
                </h3>
            </div>
        </template>

        <form class="space-y-4" @submit.prevent="submit">

            <text-input-group v-model="form.name" label="Name" name="name"/>

            <text-input-group v-model="form.description" label="Description" name="description"/>

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
    category: {type: Object, required: false},
    showModal: Boolean,
})

const emits = defineEmits(['reset'])

let form = useForm({})

watch(() => props.category, val => {
    form = useForm({
        name: val ? val.name : '',
        description: val ? val.description : '',
    })
}, {immediate: true});

const closeModal = () => {
    form.reset()
    emits('reset', true)
}

const submit = () => {
    form.submit(
        props.category ? 'patch' : 'post',
        props.category ? route('expense-categories.update', props.category) : route('expense-categories.store'),
        {
            onSuccess: () => closeModal()
        }
    )
};
</script>
