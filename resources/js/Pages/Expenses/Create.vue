<template>
    <AppLayout title="Expenses">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <h2 class="text-2xl leading-7 font-bold text-purple-900 mt-10"> {{ expense ? 'Update ' : 'Create new ' }}expense</h2>

        </div>

        <div class="max-w-7xl mx-auto px-6 py-6 mt-4 bg-white rounded">

            <form class="space-y-4" @submit.prevent="submit">

                <div class="grid grid-cols-3 gap-3">

                    <div class="col-span-1">

                        <InputLabel for="category" value="Category"/>

                        <v-select
                            id="category"
                            v-model="form.category"
                            :options="$page.props.categories.data"
                            :reduce="category => category.id"
                            class="mt-1 block w-full"
                            label="name"
                        />

                        <InputErrorMessage name="category"/>
                    </div>

                    <div class="col-span-1">

                        <InputLabel for="supplier" value="Supplier"/>

                        <v-select
                            id="supplier"
                            v-model="form.supplier"
                            :options="$page.props.suppliers.data"
                            :reduce="supplier => supplier.id"
                            class="mt-1 block w-full"
                            label="name"
                        />

                        <InputErrorMessage name="supplier"/>
                    </div>

                    <div class="col-span-1">
                        <text-input-group v-model="form.amount" label="Amount" name="amount" type="number"/>
                    </div>
                </div>

                <div class="mt-4 grid grid-cols-7 gap-4 items-center bg-indigo-50 px-4 py-3 rounded">
                    <div class="col-span-1">
                        <div class="flex items-center">
                            <Checkbox id="has_payment" v-model:checked="form.has_payment"/>
                            <div class="ml-2">
                                <InputLabel for="has_payment" value="Pay now"/>
                            </div>
                        </div>
                    </div>

                    <template v-if="form.has_payment">
                        <div class="col-span-3">

                            <InputLabel for="account" value="Account"/>

                            <v-select
                                id="account"
                                v-model="form.account"
                                :options="$page.props.bank_accounts.data"
                                :reduce="account => account.id"
                                class="mt-1 block w-full"
                                label="name"
                            />
                            <InputErrorMessage name="account"/>
                        </div>
                        <div class="col-span-3">
                            <text-input-group v-model="form.amount" label="Amount paid" name="payment" type="number" disabled/>
                        </div>
                    </template>

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
import {watch} from "vue";
import {useForm,} from "@inertiajs/vue3";
import {useToast} from "vue-toastification";
import AppLayout from '@/Layouts/AppLayout.vue'
import Checkbox from "@/Components/Checkbox.vue";
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import TextAreaInput from "@/Components/Inputs/TextAreaInput.vue";
import TextInputGroup from "@/Components/Inputs/TextInputGroup.vue"
import InputErrorMessage from "@/Components/Inputs/InputErrorMessage.vue";

const props = defineProps({
    expense: {required: false, type: Object}
})

const toast = useToast()

let form = useForm({})

watch(() => props.expense, val => {
    form = useForm({
        note: val ? val.data.note : '',
        amount: val  ? val.data.amount : 0,
        category: val  ? val.data.category.id : '',
        has_payment: val && val.data.payment !== null ? true : true,
        supplier: val && val.data.supplier ? val.data.supplier.id : '',
        account: val && val.data.payment ? val.data.payment.bankAccount.id : 1,
    })
}, {immediate: true})

const submit = () => {

    form.submit(
        props.expense ? 'patch' : 'post',
        props.expense ? route('expenses.update', props.expense.data.id) : route('expenses.store'),
        {}
    )
}

</script>
