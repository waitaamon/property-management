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
            <text-input-group label="Property" name="property" :model-value="$page.props.selected_property.name" disabled/>

            <text-input-group v-model="form.name" label="Name" name="name"/>

            <number-input-group type="number" v-model="form.rent" label="Rent" name="rent"/>

            <number-input-group type="number" v-model="form.deposit" label="Deposit" name="deposit"/>

            <number-input-group type="number" v-model="form.goodwill" label="Good will" name="goodwill"/>

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
import NumberInputGroup from "@/Components/Inputs/NumberInputGroup.vue";

const props = defineProps({
    house: {type: Object, required: false},
    showModal: Boolean,
})

const emits = defineEmits(['reset'])

let form = useForm({})

watch(() => props.house, val => {
    form = useForm({
        name: val ? val.name : '',
        rent: val ? val.rent : 0,
        deposit: val ? val.deposit : 0,
        goodwill: val ? val.goodwill : 0,
        is_active: val ? val.is_active : true,
        description: val ? val.description : '',
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
