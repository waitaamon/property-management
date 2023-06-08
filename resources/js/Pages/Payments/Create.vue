<template>
    <AppLayout title="Payment">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <h2 class="text-2xl leading-7 font-bold text-purple-900 mt-10"> {{ payment ? 'Update ' : 'Create new ' }}Payment </h2>

        </div>

        <div class="max-w-7xl mx-auto px-6 py-6 mt-4 bg-white rounded">

            <form class="space-y-4" @submit.prevent="submit">

                <div class="flex justify-end">
                    <h4 class="font-bold text-red-400">Balance: {{ accountBalance }}</h4>
                </div>

                <div class="space-y-8">

                    <div class="">
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

                    <div class="">
                        <InputLabel for="account" value="Bank account"/>

                        <v-select
                            id="account"
                            v-model="form.account"
                            :options=" $page.props.accounts.data"
                            :reduce="account => account.id"
                            class="mt-1 block w-full"
                            label="name"
                        />

                        <InputErrorMessage name="account"/>
                    </div>

                    <div>
                        <text-input-group v-model="form.amount" label="Amount" name="amount"/>
                    </div>
                </div>

                <text-area-input v-model="form.note" label="Note" name="note"/>

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
    payment: {required: false, type: Object}
})

let form = useForm({})

watch(() => props.payment, val => {
    form = useForm({
        note: val ? val.data.note : '',
        amount: val ? val.data.amount : 0,
        account: val ? val.data.account.id : '',
        tenant: val ? val.data.tenant.id : '',
    })
}, {immediate: true})

const accountBalance = computed(() => {
    if (!form.tenant) return 0;
    const tenant = usePage().props.tenants.data.find(tenant => tenant.id === form.tenant)
    return tenant.balance.toLocaleString()
})

const submit = () => {

    form.submit(
        props.payment ? 'patch' : 'post',
        props.payment ? route('payments.update', props.payment.data.id) : route('payments.store'),
        {}
    )
}

</script>
