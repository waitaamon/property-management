<template>
    <div class="flex mt-6 justify-between">
        <div class="mt-1">
            <text-input v-model="form.search" placeholder="search ..." type="text"/>
        </div>

        <div class="flex space-x-2">
            <table-export v-if="statements.data.length" :payload="selected" route-name="account-statements"/>
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
                <table-th>Causer</table-th>
                <table-th>Amount</table-th>
                <table-th>Balance</table-th>
                <table-th>Date</table-th>
            </slot>

            <tr v-for="statement in statements.data" v-if="statements.data.length" :key="statement.id">
                <table-td>
                    <Checkbox :id="statement.id" v-model:checked="selected" :value="statement.id"/>
                </table-td>
                <table-td>{{ statement.code }}</table-td>
                <table-td>{{ statement.causer }}</table-td>
                <table-td class="capitalize">{{ statement.action ? '' : '-' }} {{ statement.amount.toLocaleString() }}</table-td>
                <table-td class="capitalize">{{ statement.balance.toLocaleString() }}</table-td>
                <table-td class="capitalize">{{ moment(statement.created_at).format('DD MMM Y HH:mm:ss') }}</table-td>
            </tr>
            <table-no-data-tr v-else colspan="6"/>
        </data-table>

        <div class="mt-4">
            <pagination :meta="statements.meta"/>
        </div>
    </div>
</template>

<script setup>
import moment from "moment";
import {debounce} from "lodash";
import {computed, ref, watch} from "vue";
import Checkbox from "@/Components/Checkbox.vue";
import Pagination from "@/Components/Pagination.vue";
import TableTd from "@/Components/Table/TableTd.vue";
import TableTh from "@/Components/Table/TableTh.vue";
import TextInput from "@/Components/TextInput.vue";
import DataTable from "@/Components/Table/DataTable.vue";
import {router, useForm, usePage} from "@inertiajs/vue3";
import TableExport from "@/Components/Table/TableExport.vue";
import DateOnlyFilters from "@/Components/DateOnlyFilters.vue";
import TableNoDataTr from "@/Components/Table/TableNoDataTr.vue";

const props = defineProps({
    tab: String
})

const tenant = computed(() => usePage().props.tenant)
const filters = computed(() => usePage().props.filters)
const statements = computed(() => usePage().props.payload)

let selected = ref([])
let selectAll = ref(false)
let selectedFilters = ref({})

let form = useForm({
    search: filters.value.search,
})

watch((selectAll), () => {
    selectAll.value ? selected.value = statements.value.data.map(item => item.id) : selected.value = []
})


watch(form, () => fetchStatements(), {deep: true})

const applyFilters = (payload) => {
    selectedFilters.value = payload
    fetchStatements()
}

const fetchStatements = debounce(() => {
    router.get(
        route('tenants.show', tenant.value.data.id),
        {tab: props.tab, ...form.data(), ...selectedFilters.value},
        {
            preserveState: true,
            only: ['payload']
        }
    )
}, 500)
</script>
