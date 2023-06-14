<template>
    <AppLayout title="Invoice">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <h2 class="text-2xl leading-7 font-bold text-purple-900 mt-10">
                {{invoice ? 'Update ' : 'Create new '}}Invoice
            </h2>

        </div>

        <div class="max-w-7xl mx-auto px-6 py-6 mt-4 bg-white rounded">

            <form class="space-y-4" @submit.prevent="submit">

                <div class="grid grid-cols-2 gap-6">

                    <div class="col-span-2">
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
                        <text-input-group type="number" v-model="form.amount" label="Amount" name="amount"/>
                    </div>

                    <div class="col-span-1">
                        <text-input-group type="date" v-model="form.from" label="From" name="from"/>
                    </div>

                    <div class="col-span-1">
                        <text-input-group type="date" v-model="form.to" label="To" name="to"/>
                    </div>

                    <div class="col-span-2">
                        <text-area-input v-model="form.note" label="Note" name="note"/>
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
        to: val ? val.data.to : '',
        note: val ? val.data.note : '',
        from: val ? val.data.from : '',
        amount: val ? val.data.amount : 0,
        lease: val ? val.data.lease.id : '',
        tenant: val ? val.data.lease.tenant.id : '',
    })
}, {immediate: true})

const leases = computed(() => {
    if (!form.tenant) return [];
    const tenant = usePage().props.tenants.data.find(tenant => tenant.id === form.tenant)
    return tenant.leases
})

const submit = () => {

    form.submit(
        props.invoice ? 'patch' : 'post',
        props.invoice ? route('invoices.update', props.invoice.data.id) : route('invoices.store'),
        {}
    )
}

</script>
