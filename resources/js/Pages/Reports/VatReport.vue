<template>
    <AppLayout title="VAT Report">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex justify-between pt-10">

            <h2 class="text-2xl leading-7 font-bold text-purple-900">VAT Report</h2>

        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="flex mt-6 justify-between">
                <div class="mt-1">
                    <text-input v-model="form.search" placeholder="search ..." type="text"/>
                </div>

                <div class="flex space-x-2">
                    <table-export v-if="invoices.data.length" :payload="selected" route-name="vat-report"/>

                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-4">

            <data-table class="capitalize">
                <slot name="header">
                    <table-th>
                        <Checkbox v-model:checked="selectAll" type="checkbox"/>
                    </table-th>
                    <table-th>Invoice Code</table-th>
                    <table-th>Tenant</table-th>
                    <table-th>House</table-th>
                    <table-th>Invoice Amount</table-th>
                    <table-th>VAT</table-th>
                    <table-th>Total</table-th>
                    <table-th>Date</table-th>
                </slot>

                <tr v-if="invoices.data.length" v-for="invoice in invoices.data" :key="invoice.id">
                    <table-td>
                        <Checkbox :id="invoice.id" v-model:checked="selected" :value="invoice.id"/>
                    </table-td>
                    <table-td class="capitalize">{{ invoice.code }}</table-td>
                    <table-td class="capitalize">{{ invoice.tenant.name }}</table-td>
                    <table-td class="capitalize">{{ invoice.invoiceable.lease.house.name }}</table-td>
                    <table-td class="capitalize">{{ invoice.amount.toLocaleString() }}</table-td>
                    <table-td class="capitalize">{{ invoice.tax_amount.toLocaleString() }}</table-td>
                    <table-td class="capitalize">{{ invoice.total_amount.toLocaleString() }}</table-td>
                    <table-td class="capitalize">{{ moment(invoice.created_at).format('DD MMM Y HH:mm:ss') }}</table-td>
                </tr>
                <table-no-data-tr v-else colspan="8" />
            </data-table>

            <div class="mt-4">
                <pagination :meta="invoices.meta"/>
            </div>

        </div>

    </AppLayout>
</template>

<script setup>
import moment from "moment";
import {ref, watch} from 'vue'
import {debounce} from "lodash";
import {router, useForm} from "@inertiajs/vue3";
import AppLayout from '@/Layouts/AppLayout.vue'
import Checkbox from '@/Components/Checkbox.vue'
import TableTh from "@/Components/Table/TableTh.vue"
import TableTd from "@/Components/Table/TableTd.vue"
import TextInput from "@/Components/TextInput.vue";
import Pagination from "@/Components/Pagination.vue"
import DataTable from "@/Components/Table/DataTable.vue"
import TableExport from "@/Components/Table/TableExport.vue";
import TableNoDataTr from "@/Components/Table/TableNoDataTr.vue";

const props = defineProps({
    invoices: Object,
    filters: Object,
})

let selected = ref([])
let selectAll = ref(false)
let selectedFilters = ref({})

let form = useForm({
    search: props.filters.search,
})

watch((selectAll), () => {
    selectAll.value ? selected.value = props.invoices.data.map(invoice => invoice.id) : selected.value = []
})

watch(form, () => fetchInvoices(), {deep: true})

const applyFilters = (payload) => {
    selectedFilters.value = payload
    fetchInvoices()
}

const fetchInvoices = debounce(() => {
    router.get(route('vat-report'), {...form.data(), ...selectedFilters.value}, {preserveState: true, only: ['invoices']})
}, 500)
</script>
