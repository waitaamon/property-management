<template>
    <AppLayout title="Deposit Report">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex justify-between pt-10">

            <h2 class="text-2xl leading-7 font-bold text-purple-900">Deposit Report</h2>

        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="flex mt-6 justify-between">
                <div class="mt-1">
                    <text-input v-model="form.search" placeholder="search ..." type="text"/>
                </div>

                <div class="flex space-x-2">
                    <table-export v-if="deposits.data.length" :payload="selected" route-name="deposit-report"/>

                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-4">

            <data-table class="capitalize">
                <slot name="header">
                    <table-th>
                        <Checkbox v-model:checked="selectAll" type="checkbox"/>
                    </table-th>
                    <table-th>Tenant</table-th>
                    <table-th>House</table-th>
                    <table-th>Amount</table-th>
                    <table-th>Refund Date</table-th>
                    <table-th>Date</table-th>
                </slot>

                <tr v-if="deposits.data.length" v-for="deposit in deposits.data" :key="deposit.id">
                    <table-td>
                        <Checkbox :id="deposit.id" v-model:checked="selected" :value="deposit.id"/>
                    </table-td>
                    <table-td class="capitalize">{{ deposit.lease.tenant.name }}</table-td>
                    <table-td class="capitalize">{{ deposit.lease.house.name }}</table-td>
                    <table-td class="capitalize">{{ deposit.amount.toLocaleString() }}</table-td>
                    <table-td class="capitalize">{{ deposit.refund_date ? moment(deposit.refund_date).format('DD MMM Y') : '' }}</table-td>
                    <table-td class="capitalize">{{ moment(deposit.created_at).format('DD MMM Y HH:mm:ss') }}</table-td>
                </tr>
                <table-no-data-tr v-else colspan="6" />
            </data-table>

            <div class="mt-4">
                <pagination :meta="deposits.meta"/>
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
    deposits: Object,
    filters: Object,
})

let selected = ref([])
let selectAll = ref(false)
let selectedFilters = ref({})

let form = useForm({
    search: props.filters.search,
})

watch((selectAll), () => {
    selectAll.value ? selected.value = props.deposits.data.map(deposit => deposit.id) : selected.value = []
})

watch(form, () => fetchDeposits(), {deep: true})

const applyFilters = (payload) => {
    selectedFilters.value = payload
    fetchDeposits()
}

const fetchDeposits = debounce(() => {
    router.get(route('deposit-report'), {...form.data(), ...selectedFilters.value}, {preserveState: true, only: ['deposits']})
}, 500)
</script>
