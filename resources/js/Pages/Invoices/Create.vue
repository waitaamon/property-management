<template>
    <AppLayout title="Invoice">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <h2 class="text-2xl leading-7 font-bold text-purple-900 mt-10">
                {{ invoice ? 'Update ' : 'Create new ' }}Invoice
            </h2>

        </div>

        <div class="max-w-7xl mx-auto px-6 py-6 mt-4 bg-white rounded">

            <form class="space-y-4" @submit.prevent="submit">

                <div class="grid grid-cols-2 gap-6">

                    <div class="col-span-1">
                        <InputLabel for="tenant" value="Tenant"/>

                        <v-select
                            id="tenant"
                            v-model="form.tenant"
                            :options=" $page.props.tenants.data"
                            :reduce="tenant => tenant.id"
                            class="mt-1 block w-full"
                            label="name"
                        />

                        <InputErrorMessage name="tenant"/>
                    </div>

                    <div class="col-span-1">
                        <InputLabel for="type" value="Invoice type"/>

                        <v-select
                            id="type"
                            v-model="form.type"
                            :options="$page.props.types"
                            class="mt-1 block w-full"
                            :disabled="invoice !== undefined"
                        />

                        <InputErrorMessage name="type"/>
                    </div>

                    <div class="col-span-1">
                        <InputLabel for="lease" value="Lease"/>

                        <v-select
                            id="house"
                            v-model="form.lease"
                            :options="leases"
                            :reduce="lease => lease.id"
                            class="mt-1 block w-full"
                            label="name"
                        />

                        <InputErrorMessage name="lease"/>
                    </div>


                    <div class="col-span-1">
                        <text-input-group v-model="form.amount" label="Amount" name="amount" type="number"/>
                    </div>

                </div>

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
import {computed, watch} from "vue";
import {useForm, usePage} from "@inertiajs/vue3";
import AppLayout from '@/Layouts/AppLayout.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import TextAreaInput from "@/Components/Inputs/TextAreaInput.vue";
import TextInputGroup from "@/Components/Inputs/TextInputGroup.vue"
import InputErrorMessage from "@/Components/Inputs/InputErrorMessage.vue";

const props = defineProps({
    invoice: {required: false, type: Object}
})

let form = useForm({})

watch(() => props.invoice, val => {
    form = useForm({
        amount: val ? val.data.amount : 0,
        type: val ? val.data.causer : 'rent',
        tenant: val ? val.data.tenant.id : '',
        lease: val ? val.data.invoiceable.lease.id : '',
    })
}, {immediate: true})

const leases = computed(() => {
    if (!form.tenant) return [];
    const tenant = usePage().props.tenants.data.find(tenant => tenant.id === form.tenant)
    return tenant.leases
})

watch(() => form.lease, val => {
    if (!val) return;

    const lease = leases.value.find(lease => lease.id === val)
    form.amount = lease.house.rent
})

const submit = () => {

    form.submit(
        props.invoice ? 'patch' : 'post',
        props.invoice ? route('invoices.update', props.invoice.data.id) : route('invoices.store'),
        {}
    )
}

</script>
