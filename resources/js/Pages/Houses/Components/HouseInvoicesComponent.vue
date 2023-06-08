<template>
    <div class="flex mt-6 justify-between">
        <div class="mt-1">
            <text-input v-model="form.search" placeholder="search ..." type="text"/>
        </div>

        <div class="flex space-x-2">
            <table-export v-if="sales.data.length" :payload="selected" route-name="sale-order-items"/>
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
                <table-th>Customer</table-th>
                <table-th>Quantity</table-th>
                <table-th>Unit price</table-th>
                <table-th>Total</table-th>
                <table-th>Date</table-th>
                <table-th>Actions</table-th>
            </slot>

            <tr v-for="item in sales.data" v-if="sales.data.length" :key="item.id">
                <table-td>
                    <Checkbox :id="item.id" v-model:checked="selected" :value="item.id"/>
                </table-td>
                <table-td>{{ item.sale_order.code }}</table-td>
                <table-td>{{ item.sale_order.customer.name }}</table-td>
                <table-td class="capitalize">{{ item.quantity.toLocaleString() }}</table-td>
                <table-td class="capitalize">{{ item.unit_price.toLocaleString() }}</table-td>
                <table-td class="capitalize">{{ item.total_price.toLocaleString() }}</table-td>
                <table-td class="capitalize">{{ moment(item.sale_order.created_at).format('DD MMM Y HH:mm:ss') }}</table-td>

                <table-td>
                    <button v-if="item.sale_order.can.view" type="button" @click.prevent="router.get(route('sale-orders.show', item.sale_order.id))">
                        <EyeIcon aria-hidden="true" class="w-4 h-4 text-blue-800 ml-1"/>
                    </button>
                </table-td>
            </tr>
            <table-no-data-tr v-else colspan="6"/>
        </data-table>

        <div class="mt-4">
            <pagination :meta="sales.meta"/>
        </div>
    </div>
</template>

<script setup>
import moment from "moment";
import {computed, ref, watch} from "vue";
import {router, useForm, usePage} from "@inertiajs/vue3";
import Checkbox from "@/Components/Checkbox.vue";
import {EyeIcon} from '@heroicons/vue/24/outline'
import Pagination from "@/Components/Pagination.vue";
import TableTd from "@/Components/Table/TableTd.vue";
import TableTh from "@/Components/Table/TableTh.vue";
import DataTable from "@/Components/Table/DataTable.vue";
import TableNoDataTr from "@/Components/Table/TableNoDataTr.vue";
import TextInput from "@/Components/TextInput.vue";
import TableExport from "@/Components/Table/TableExport.vue";
import DateOnlyFilters from "@/Components/DateOnlyFilters.vue";
import {debounce} from "lodash";

const props = defineProps({
    tab: String
})

const product = computed(() => usePage().props.product)
const filters = computed(() => usePage().props.filters)
const sales = computed(() => usePage().props.payload)

let selected = ref([])
let selectAll = ref(false)
let selectedFilters = ref({})

let form = useForm({
    search: filters.value.search,
})

watch((selectAll), () => {
    selectAll.value ? selected.value = sales.value.data.map(item => item.id) : selected.value = []
})

watch(form, () => fetchSales(), {deep: true})

const applyFilters = (payload) => {
    selectedFilters.value = payload
    fetchSales()
}

const fetchSales = debounce(() => {
    router.get(
        route('products.show', product.value.data.id),
        {tab: props.tab, ...form.data(), ...selectedFilters.value},
        {
            preserveState: true,
            only: ['payload']
        }
    )
}, 500)
</script>
