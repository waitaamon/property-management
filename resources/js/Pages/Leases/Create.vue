<template>
    <AppLayout title="Lease">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <h2 class="text-2xl leading-7 font-bold text-purple-900 mt-10">
                {{ lease ? 'Update ' : 'Create new ' }}lease
            </h2>

        </div>

        <div class="max-w-7xl mx-auto px-6 py-6 mt-4 bg-white rounded">

            <form class="space-y-4" @submit.prevent="submit">

                <div class="grid grid-cols-2 gap-3">

                    <div class="col-span-2">

                        <InputLabel for="tenant" value="Tenant *"/>

                        <v-select
                            id="tenant"
                            v-model="form.tenant"
                            :options="$page.props.tenants.data"
                            :reduce="tenant => tenant.id"
                            class="mt-1 block w-full"
                            label="name"
                        />

                        <InputErrorMessage name="tenant"/>
                    </div>

                    <div class="col-span-1">

                        <text-input-group :model-value="$page.props.user_properties.data.find(property => property.id === $page.props.selected_property).name" label="Property" name="property" disabled/>
                    </div>

                    <div class="col-span-1">

                        <InputLabel for="house" value="House *"/>

                        <v-select
                            id="house"
                            v-model="form.house"
                            :options="$page.props.houses.data"
                            :reduce="house => house.id"
                            class="mt-1 block w-full"
                            label="name"
                        />

                        <InputErrorMessage name="house"/>
                    </div>

                    <div class="col-span-1">
                        <text-input-group v-model="form.start_date" label="Start date *" name="start_date" type="date"/>
                    </div>
                    <div class="col-span-1">
                        <text-input-group v-model="form.end_date" label="End date" name="end_date" type="date"/>
                    </div>
                </div>

                <text-area-input v-model="form.notes" label="Note" name="note"/>

                <div class="mt-4">

                    <PrimaryButton :class="{ 'opacity-25': form.processing }" type="submit">
                        Save
                    </PrimaryButton>

                </div>
            </form>
        </div>

    </AppLayout>
</template>

<script setup>
import {ref, watch} from "vue";
import {useForm, usePage} from "@inertiajs/vue3";
import {useToast} from "vue-toastification";
import AppLayout from '@/Layouts/AppLayout.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import TextAreaInput from "@/Components/Inputs/TextAreaInput.vue";
import TextInputGroup from "@/Components/Inputs/TextInputGroup.vue"
import InputErrorMessage from "@/Components/Inputs/InputErrorMessage.vue";

const props = defineProps({
    lease: {required: false, type: Object}
})

const toast = useToast()

let form = useForm({})

watch(() => props.lease, val => {
    form = useForm({
        notes: val ? val.data.notes : '',
        house: val ? val.data.house.id : '',
        tenant: val ? val.data.tenant.id : '',
        end_date: val ? val.data.end_date : '',
        start_date: val ? val.data.start_date : '',
    })
}, {immediate: true})

const submit = () => {

    form.submit(
        props.lease ? 'patch' : 'post',
        props.lease ? route('leases.update', props.lease.data.id) : route('leases.store'),
        {}
    )
}

</script>
