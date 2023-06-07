<template>
    <div class="flex mt-6 justify-between">
        <div class="mt-1">
            <text-input v-model="form.search" placeholder="search ..." type="text"/>
        </div>

        <div class="flex space-x-2">
            <table-export v-if="payments.data.length" :payload="selected" route-name="payments"/>
            <date-only-filters :filters="filters" @update-filters="applyFilters"/>
        </div>
    </div>
    <div class="mt-4">
        <data-table class="capitalize">
            <slot name="header">
                <table-th>
                    <Checkbox v-model:checked="selectAll" type="checkbox"/>
                </table-th>
                <table-th>Code</table-th>
                <table-th>Amount</table-th>
                <table-th>Account</table-th>
                <table-th>Date</table-th>
                <table-th>Actions</table-th>
            </slot>

            <tr v-for="payment in payments.data" v-if="payments.data.length" :key="payment.id">
                <table-td>
                    <Checkbox :id="payment.id" v-model:checked="selected" :value="payment.id"/>
                </table-td>
                <table-td>{{ payment.code }}</table-td>
                <table-td class="capitalize">{{ payment.amount.toLocaleString() }}</table-td>
                <table-td class="capitalize">{{ payment.bank_account.name }}</table-td>
                <table-td class="capitalize">{{ moment(payment.created_at).format('DD MMM Y HH:mm:ss') }}</table-td>

                <table-td>
                    <button v-if="payment.can.view" type="button" @click.prevent="router.get(route('payments.show', payment.id))">
                        <EyeIcon aria-hidden="true" class="w-4 h-4 text-blue-800 ml-1"/>
                    </button>
                </table-td>
            </tr>
            <table-no-data-tr v-else colspan="6"/>
        </data-table>

        <div class="mt-4">
            <pagination :meta="payments.meta"/>
        </div>
    </div>
</template>

<script setup>
import moment from "moment";
import {computed, ref, watch} from "vue";
import {router, useForm, usePage} from "@inertiajs/vue3";
import Checkbox from "@/Components/Checkbox.vue";
import Pagination from "@/Components/Pagination.vue";
import TableTd from "@/Components/Table/TableTd.vue";
import TableTh from "@/Components/Table/TableTh.vue";
import DataTable from "@/Components/Table/DataTable.vue";
import TableNoDataTr from "@/Components/Table/TableNoDataTr.vue";
import {EyeIcon,} from '@heroicons/vue/24/outline'
import TextInput from "@/Components/TextInput.vue";
import TableExport from "@/Components/Table/TableExport.vue";
import DateOnlyFilters from "@/Components/DateOnlyFilters.vue";
import {debounce} from "lodash";

const props = defineProps({
    tab: String
})

const customer = computed(() => usePage().props.customer)
const filters = computed(() => usePage().props.filters)
const payments = computed(() => usePage().props.payload)

let selected = ref([])
let selectAll = ref(false)
let selectedFilters = ref({})

let form = useForm({
    search: filters.value.search,
})

watch((selectAll), () => {
    selectAll.value ? selected.value = payments.value.data.map(payment => payment.id) : selected.value = []
})

watch(form, () => fetchPayments(), {deep: true})

const applyFilters = (payload) => {
    selectedFilters.value = payload
    fetchPayments()
}

const fetchPayments = debounce(() => {
    router.get(
        route('customers.show', customer.value.data.id),
        {tab: props.tab, ...form.data(), ...selectedFilters.value},
        {
            preserveState: true,
            only: ['payload']
        }
    )
}, 500)
</script>
