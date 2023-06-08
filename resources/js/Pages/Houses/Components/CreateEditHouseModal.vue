<template>

    <app-modal :showModal="showModal" @close="closeModal">

        <template v-slot:title>
            <div class="flex flex-col gap-y-2">
                <h3 class="text-xl font-semibold text-purple-900">
                    {{ house ? 'Edit' : 'Add New' }} House
                </h3>
            </div>
        </template>

        <form class="space-y-4" @submit.prevent="submit">

            <div class="">

                <InputLabel for="property" value="Property"/>

                <v-select
                    id="property"
                    v-model="form.property"
                    :options="$page.props.properties.data"
                    :reduce="property => property.id"
                    class="mt-1 block w-full"
                    label="name"
                />

                <InputErrorMessage name="property"/>
            </div>

            <text-input-group v-model="form.name" label="Name" name="name"/>

            <text-input-group v-model="form.rent" label="Rent" name="rent"/>

            <text-input-group v-model="form.deposit" label="Deposit" name="deposit"/>

            <text-input-group v-model="form.description" label="Description" name="description"/>

            <div>
                <label class="flex items-center">
                    <Checkbox v-model:checked="form.is_active" name="is_active"/>
                    <span class="ml-2 text-sm text-gray-600">Is Active</span>
                </label>
            </div>

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
import Checkbox from '@/Components/Checkbox.vue';
import AppModal from "@/Components/Modal/AppModal.vue"
import PrimaryButton from '@/Components/PrimaryButton.vue'
import TextInputGroup from "@/Components/Inputs/TextInputGroup.vue"
import InputErrorMessage from "@/Components/Inputs/InputErrorMessage.vue";
import InputLabel from "@/Components/InputLabel.vue";

const props = defineProps({
    house: {type: Object, required: false},
    showModal: Boolean,
})

const emits = defineEmits(['reset'])

let form = useForm({})

watch(() => props.house, val => {
    form = useForm({
        name: val ? val.name : '',
        rent: val ? val.rent : '',
        deposit: val ? val.deposit : '',
        is_active: val ? val.is_active : true,
        description: val ? val.description : '',
        property: val ? val.property.id : '',
    })
}, {immediate: true});

const closeModal = () => {
    form.reset()
    emits('reset', true)
}

const submit = () => {
    form.submit(
        props.house ? 'patch' : 'post',
        props.house ? route('houses.update', props.house) : route('houses.store'),
        {
            onSuccess: () => closeModal()
        }
    )
};
</script>
