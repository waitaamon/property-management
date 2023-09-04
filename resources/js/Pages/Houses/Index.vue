<template>
    <AppLayout title="Houses">

        <create-edit-house-modal :showModal="openModal" :house="house" @reset="closeModal"/>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <h2 class="text-2xl leading-7 font-bold text-purple-900 mt-10">Houses</h2>

            <statistics-card-component :statistics="$page.props.statistics" class="mt-6"/>

        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex mt-10 justify-between">
                <div class="mt-1">
                    <text-input v-model="form.search" placeholder="search ..." type="text"/>
                </div>
                <div>
                    <PrimaryButton type="button" @click.prevent="openModal = true" v-if="$page.props.can.create">
                        Add House
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
                    <table-th>Property</table-th>
                    <table-th>Rent</table-th>
                    <table-th>Deposit</table-th>
                    <table-th>Good will</table-th>
                    <table-th>Tenant</table-th>
                    <table-th>Is active</table-th>
                    <table-th>Actions</table-th>
                </slot>

                <tr v-if="houses.data.length" v-for="house in houses.data" :key="house.id">
                    <table-td>
                        <Checkbox :id="house.id" v-model:checked="selected" :value="house.id"/>
                    </table-td>
                    <table-td>{{ house.name }}</table-td>
                    <table-td class="capitalize">{{ house.property.name }}</table-td>
                    <table-td class="capitalize">{{ house.rent.toLocaleString() }}</table-td>
                    <table-td class="capitalize">{{ house.deposit.toLocaleString() }}</table-td>
                    <table-td class="capitalize">{{ house.goodwill.toLocaleString() }}</table-td>
                    <table-td class="capitalize"></table-td>
                    <table-td>
                        <CheckCircleIcon aria-hidden="true" class="w-4 h-4 text-green-500 ml-1" v-if="house.is_active"/>
                        <XCircleIcon aria-hidden="true" class="w-4 h-4 text-red-500 ml-1" v-else/>
                    </table-td>
                    <table-td>
                        <button v-if="house.can.view" type="button" @click.prevent="router.get(route('houses.show', house.id))">
                            <EyeIcon aria-hidden="true" class="w-4 h-4 text-blue-800 ml-1"/>
                        </button>
                        <button v-if="house.can.edit" type="button" @click.prevent="editHouse(house)">
                            <PencilSquareIcon aria-hidden="true" class="w-4 h-4 text-purple-900 ml-1"/>
                        </button>
                        <button v-if="house.can.delete && house.is_active" type="button" @click.prevent="router.delete(route('houses.destroy', house.id))">
                            <TrashIcon aria-hidden="true" class="w-4 h-4 text-red-500 ml-1"/>
                        </button>
                    </table-td>
                </tr>
                <table-no-data-tr v-else colspan="10" />
            </data-table>

            <div class="mt-4">
                <pagination :meta="houses.meta"/>
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
import TextInput from "@/Components/TextInput.vue";
import TableTd from "@/Components/Table/TableTd.vue"
import Pagination from "@/Components/Pagination.vue"
import PrimaryButton from '@/Components/PrimaryButton.vue'
import DataTable from "@/Components/Table/DataTable.vue"
import TableNoDataTr from "@/Components/Table/TableNoDataTr.vue";
import CreateEditHouseModal from "./Components/CreateEditHouseModal.vue";
import StatisticsCardComponent from "@/Components/StatisticsCardComponent.vue"
import {EyeIcon, PencilSquareIcon, PlusIcon, CheckCircleIcon, XCircleIcon, TrashIcon} from '@heroicons/vue/24/outline'

const props = defineProps({
    houses: Object,
    filters: Object,
})

const house = ref(null)
const openModal = ref(false)

let selected = ref([])
let selectAll = ref(false)
let selectedFilters = ref({})

let form = useForm({
    search: props.filters.search,
})

watch((selectAll), () => {
    selectAll.value ? selected.value = props.houses.data.map(house => house.id) : selected.value = []
})

watch(form, () => fetchHouses(), {deep: true})

const applyFilters = (payload) => {
    selectedFilters.value = payload
    fetchHouseAdjustments()
}

const fetchHouses = debounce(() => {
    router.get(route('houses.index'), {...form.data(), ...selectedFilters.value}, {preserveState: true, only: ['houses']})
}, 200)

const closeModal = () => {
    house.value = null
    openModal.value = false
}

const editHouse = (data) => {
    house.value = data
    openModal.value = true
}

const search = () => {
    router.get(route('houses.index', {
        _query: {
            search: form.search,
        },
        preserveState: true,
    }))
}

</script>
