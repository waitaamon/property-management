<template>
    <AppLayout title="Debtors Report">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex justify-between pt-10">

            <h2 class="text-2xl leading-7 font-bold text-purple-900">Debtors Report</h2>

        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="flex mt-6 justify-between">
                <div class="mt-1">
                    <text-input v-model="form.search" placeholder="search ..." type="text"/>
                </div>

                <div class="flex space-x-2">
                    <table-export v-if="tenants.data.length" :payload="selected" route-name="debtors-report"/>

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
                    <table-th>Balance</table-th>
                    <table-th>Action</table-th>
                </slot>

                <tr v-if="tenants.data.length" v-for="tenant in tenants.data" :key="tenant.id">
                    <table-td>
                        <Checkbox :id="tenant.id" v-model:checked="selected" :value="tenant.id"/>
                    </table-td>
                    <table-td class="capitalize">{{ tenant.name }}</table-td>
                    <table-td class="capitalize">{{ tenant.balance.toLocaleString() }}</table-td>
                    <table-td>
                        <button type="button" @click.prevent="router.get(route('tenants.show', tenant.id))">
                            <EyeIcon aria-hidden="true" class="w-4 h-4 text-blue-800 ml-1"/>
                        </button>
                    </table-td>
                </tr>
                <table-no-data-tr v-else colspan="4" />
            </data-table>

            <div class="mt-4">
                <pagination :meta="tenants"/>
            </div>

        </div>

    </AppLayout>
</template>

<script setup>
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
import {EyeIcon} from "@heroicons/vue/24/outline";

const props = defineProps({
    tenants: Object,
    filters: Object,
})

let selected = ref([])
let selectAll = ref(false)
let selectedFilters = ref({})

let form = useForm({
    search: props.filters.search,
})

watch((selectAll), () => {
    selectAll.value ? selected.value = props.tenants.data.map(tenant => tenant.id) : selected.value = []
})

watch(form, () => fetchTenants(), {deep: true})

const applyFilters = (payload) => {
    selectedFilters.value = payload
    fetchTenants()
}

const fetchTenants = debounce(() => {
    router.get(route('debtors-report'), {...form.data(), ...selectedFilters.value}, {preserveState: true, only: ['tenants']})
}, 500)
</script>
