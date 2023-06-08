<template>
    <AppLayout title="Leases">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex justify-between pt-10">

            <h2 class="text-2xl leading-7 font-bold text-purple-900">Leases</h2>

            <div>
                <PrimaryButton type="button" @click.prevent="router.get(route('leases.create'))" v-if="$page.props.can.create">
                    Create New Lease
                    <PlusIcon aria-hidden="true" class="ml-2 h-4 w-4"/>
                </PrimaryButton>
            </div>

        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="flex mt-6 justify-between">
                <div class="mt-1">
                    <text-input v-model="form.search" placeholder="search ..." type="text"/>
                </div>

                <div class="flex space-x-2">
                    <table-export v-if="leases.data.length" :payload="selected" route-name="leases"/>
                    <lease-filters :filters="filters" @update-filters="applyFilters"/>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-4">

            <data-table class="capitalize">
                <slot name="header">
                    <table-th>
                        <Checkbox v-model:checked="selectAll" type="checkbox"/>
                    </table-th>
                    <table-th>Code</table-th>
                    <table-th>Tenant</table-th>
                    <table-th>House</table-th>
                    <table-th>Property</table-th>
                    <table-th>Start date</table-th>
                    <table-th>End date</table-th>
                    <table-th>State</table-th>
                    <table-th>Actions</table-th>
                </slot>

                <tr v-if="leases.data.length" v-for="lease in leases.data" :key="lease.id">
                    <table-td>
                        <Checkbox :id="lease.id" v-model:checked="selected" :value="lease.id"/>
                    </table-td>
                    <table-td>{{ lease.code }}</table-td>
                    <table-td class="capitalize">{{ lease.tenant.name }}</table-td>
                    <table-td class="capitalize">{{ lease.house.name }}</table-td>
                    <table-td class="capitalize">{{ lease.house.property.name }}</table-td>
                    <table-td class="capitalize">{{ moment(lease.start_date).format('DD MMM Y') }}</table-td>
                    <table-td class="capitalize">{{ lease.end_date ? moment(lease.end_date).format('DD MMM Y'): '' }}</table-td>
                    <table-td class="capitalize">{{ lease.state }}</table-td>

                    <table-td>
                        <button v-if="lease.can.view" type="button" @click.prevent="router.get(route('leases.show', lease.id))">
                            <EyeIcon aria-hidden="true" class="w-4 h-4 text-blue-800 ml-1"/>
                        </button>
                        <button v-if="lease.can.edit" type="button" @click.prevent="router.get(route('leases.edit', lease.id))">
                            <PencilSquareIcon aria-hidden="true" class="w-4 h-4 text-purple-900 ml-1"/>
                        </button>
                    </table-td>
                </tr>
                <table-no-data-tr v-else colspan="9" />
            </data-table>

            <div class="mt-4">
                <pagination :meta="leases.meta"/>
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
import TextInput from "@/Components/TextInput.vue";
import TableTd from "@/Components/Table/TableTd.vue"
import Pagination from "@/Components/Pagination.vue"
import PrimaryButton from '@/Components/PrimaryButton.vue'
import DataTable from "@/Components/Table/DataTable.vue"
import LeaseFilters from "./Components/LeaseFilters.vue";
import TableExport from "@/Components/Table/TableExport.vue";
import TableNoDataTr from "@/Components/Table/TableNoDataTr.vue";
import {EyeIcon, PencilSquareIcon, PlusIcon} from '@heroicons/vue/24/outline'

const props = defineProps({
    leases: Object,
    filters: Object,
})

let selected = ref([])
let selectAll = ref(false)
let selectedFilters = ref({})

let form = useForm({
    search: props.filters.search,
})

watch((selectAll), () => {
    selectAll.value ? selected.value = props.leases.data.map(lease => lease.id) : selected.value = []
})

watch(form, () => fetchLeases(), {deep: true})

const applyFilters = (payload) => {
    selectedFilters.value = payload
    fetchLeases()
}

const fetchLeases = debounce(() => {
    router.get(route('leases.index'), {...form.data(), ...selectedFilters.value}, {preserveState: true, only: ['leases']})
}, 200)
</script>
