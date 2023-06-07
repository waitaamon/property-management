<template>
    <AppLayout title="Tenants">

        <create-edit-tenant-modal :showModal="openModal" :tenant="tenant" @reset="closeModal"/>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <h2 class="text-2xl leading-7 font-bold text-purple-900 mt-10">Tenants</h2>

            <statistics-card-component :statistics="$page.props.statistics" class="mt-6"/>

        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex mt-10 justify-between">

                <div class="mt-1 flex rounded-md shadow-sm">
                    <div class="relative flex items-stretch flex-grow focus-within:z-10">
                        <input id="search" v-model="form.search" class="focus:ring-purple-500 focus:border-purple-500 block w-full rounded-none rounded-l-md sm:text-sm border-gray-300" name="search" placeholder="Search..."
                               type="text" @keyup.enter="search"/>
                    </div>
                    <button class="-ml-px relative inline-flex items-center space-x-2 px-4 py-2 border border-gray-300 text-sm font-medium rounded-r-md text-gray-700 bg-gray-50 hover:bg-gray-100 focus:outline-none focus:ring-1 focus:ring-purple-500 focus:border-purple-500" type="button"
                            @click.prevent="search">
                        <MagnifyingGlassIcon aria-hidden="true" class="h-5 w-5 text-gray-400"/>
                    </button>
                </div>

                <div>

                    <PrimaryButton type="button" @click.prevent="openModal = true" v-if="$page.props.can.create">
                        Add Tenant
                        <PlusIcon aria-hidden="true" class="ml-2 h-4 w-4"/>
                    </PrimaryButton>
                </div>

            </div>
        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-4">

            <data-table class="capitalize">
                <slot name="header">
                    <table-th>
                        <Checkbox v-model:checked="selectAll" type="checkbox"/>
                    </table-th>
                    <table-th>Name</table-th>
                    <table-th>Pin</table-th>
                    <table-th>Phone</table-th>
                    <table-th>Balance</table-th>
                    <table-th>Status</table-th>
                    <table-th>Actions</table-th>
                </slot>

                <tr v-if="tenants.data.length" v-for="tenant in tenants.data" :key="tenant.id">
                    <table-td>
                        <Checkbox :id="tenant.id" v-model:checked="selected" :value="tenant.id"/>
                    </table-td>
                    <table-td>{{ tenant.name }}</table-td>
                    <table-td class="capitalize">{{ tenant.pin }}</table-td>
                    <table-td class="capitalize">{{ tenant.phone }}</table-td>
                    <table-td class="capitalize">{{ tenant.balance.toLocaleString() }}</table-td>
                    <table-td>
                        <CheckCircleIcon aria-hidden="true" class="w-4 h-4 text-green-500 ml-1" v-if="tenant.is_active"/>
                        <XCircleIcon aria-hidden="true" class="w-4 h-4 text-red-500 ml-1" v-else/>
                    </table-td>
                    <table-td>
                        <button v-if="tenant.can.view" type="button" @click.prevent="router.get(route('tenants.show', tenant.id))">
                            <EyeIcon aria-hidden="true" class="w-4 h-4 text-blue-800 ml-1"/>
                        </button>
                        <button v-if="tenant.can.edit" type="button" @click.prevent="editTenant(tenant)">
                            <PencilSquareIcon aria-hidden="true" class="w-4 h-4 text-purple-900 ml-1"/>
                        </button>
                        <button v-if="tenant.can.delete && tenant.is_active" type="button" @click.prevent="deleteTenant(tenant)">
                            <TrashIcon aria-hidden="true" class="w-4 h-4 text-red-500 ml-1"/>
                        </button>
                        <button v-if="tenant.can.delete && !tenant.is_active" type="button" @click.prevent="restoreTenant(tenant)">
                            <ArrowPathIcon aria-hidden="true" class="w-4 h-4 text-green-500 ml-1"/>
                        </button>
                    </table-td>
                </tr>
                <table-no-data-tr v-else colspan="7" />
            </data-table>

            <div class="mt-4">
                <pagination :meta="tenants.meta"/>
            </div>

        </div>

    </AppLayout>
</template>

<script setup>
import {ref, watch} from 'vue'
import {router, useForm} from "@inertiajs/vue3";
import AppLayout from '@/Layouts/AppLayout.vue'
import Checkbox from '@/Components/Checkbox.vue'
import TableTh from "@/Components/Table/TableTh.vue"
import TableTd from "@/Components/Table/TableTd.vue"
import Pagination from "@/Components/Pagination.vue"
import PrimaryButton from '@/Components/PrimaryButton.vue'
import DataTable from "@/Components/Table/DataTable.vue"
import TableNoDataTr from "@/Components/Table/TableNoDataTr.vue";
import StatisticsCardComponent from "@/Components/StatisticsCardComponent.vue"
import CreateEditTenantModal from "@/Pages/Tenants/Components/CreateEditTenantModal.vue";
import {EyeIcon, PencilSquareIcon, PlusIcon, MagnifyingGlassIcon, CheckCircleIcon, XCircleIcon, ArrowPathIcon, TrashIcon} from '@heroicons/vue/24/outline'

const props = defineProps({
    tenants: Object,
    errors: Object,
    filters: Object,
})

const tenant = ref(null)
const openModal = ref(false)

let selected = ref([])
let selectAll = ref(false)

watch((selectAll), () => {
    selectAll.value ? selected.value = props.tenants.data.map(tenant => tenant.id) : selected.value = []
})

const closeModal = () => openModal.value = false

const editTenant = (data) => {
    tenant.value = data
    openModal.value = true
}

let form = useForm({
    errors: props.errors,
    search: props.filters.search,
})

const search = () => {
    router.get(route('tenants.index', {
        _query: {
            search: form.search,
        },
        preserveState: true,
    }))
}

const deleteTenant = (data) => {
    router.delete(route('tenants.destroy', data.id), {
        preserveState: true
    })
}

const restoreTenant = (data) => {
    router.patch(route('tenants.restore', data.id), {
        preserveState: true
    })
}

</script>
