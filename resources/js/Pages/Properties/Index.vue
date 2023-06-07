<template>
    <AppLayout title="Properties">

        <create-edit-property-modal :showModal="openModal" :property="property" @reset="closeModal"/>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <h2 class="text-2xl leading-7 font-bold text-purple-900 mt-10">Properties</h2>

            <statistics-card-component :statistics="$page.props.statistics" class="mt-6"/>

        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex mt-10 justify-between">
                <div class="mt-1">
                    <text-input v-model="form.search" placeholder="search ..." type="text"/>
                </div>
                <div>
                    <PrimaryButton type="button" @click.prevent="openModal = true" v-if="$page.props.can.create">
                        Add Property
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
                    <table-th>Location</table-th>
                    <table-th>Address</table-th>
                    <table-th>Phone</table-th>
                    <table-th>Email</table-th>
                    <table-th>Actions</table-th>
                </slot>

                <tr v-if="properties.data.length" v-for="property in properties.data" :key="property.id">
                    <table-td>
                        <Checkbox :id="property.id" v-model:checked="selected" :value="property.id"/>
                    </table-td>
                    <table-td>{{ property.name }}</table-td>
                    <table-td class="capitalize">{{ property.location }}</table-td>
                    <table-td class="capitalize">{{ property.address }}</table-td>
                    <table-td class="capitalize">{{ property.phone }}</table-td>
                    <table-td class="capitalize">{{ property.email }}</table-td>
                    <table-td>
                        <button v-if="property.can.view" type="button" @click.prevent="router.get(route('properties.show', property.id))">
                            <EyeIcon aria-hidden="true" class="w-4 h-4 text-blue-800 ml-1"/>
                        </button>
                        <button v-if="property.can.edit" type="button" @click.prevent="editProperty(property)">
                            <PencilSquareIcon aria-hidden="true" class="w-4 h-4 text-purple-900 ml-1"/>
                        </button>
                        <button v-if="property.can.delete" type="button" @click.prevent="deleteProperty(property)">
                            <TrashIcon aria-hidden="true" class="w-4 h-4 text-red-500 ml-1"/>
                        </button>
                    </table-td>
                </tr>
                <table-no-data-tr v-else colspan="10" />
            </data-table>

            <div class="mt-4">
                <pagination :meta="properties.meta"/>
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
import StatisticsCardComponent from "@/Components/StatisticsCardComponent.vue"
import {EyeIcon, PencilSquareIcon, PlusIcon, CheckCircleIcon, XCircleIcon, ArrowPathIcon, TrashIcon} from '@heroicons/vue/24/outline'
import CreateEditPropertyModal from "@/Pages/Properties/Components/CreateEditPropertyModal.vue";

const props = defineProps({
    properties: Object,
    filters: Object,
})

const property = ref(null)
const openModal = ref(false)

let selected = ref([])
let selectAll = ref(false)
let selectedFilters = ref({})

let form = useForm({
    search: props.filters.search,
})

watch((selectAll), () => {
    selectAll.value ? selected.value = props.properties.data.map(property => property.id) : selected.value = []
})

watch(form, () => fetchPropertys(), {deep: true})

const applyFilters = (payload) => {
    selectedFilters.value = payload
    fetchPropertyAdjustments()
}

const fetchPropertys = debounce(() => {
    router.get(route('properties.index'), {...form.data(), ...selectedFilters.value}, {preserveState: true, only: ['properties']})
}, 200)

const closeModal = () => {
    property.value = null
    openModal.value = false
}

const editProperty = (data) => {
    property.value = data
    openModal.value = true
}

const search = () => {
    router.get(route('properties.index', {
        _query: {
            search: form.search,
        },
        preserveState: true,
    }))
}

const deleteProperty = (data) => {
    router.delete(route('properties.destroy', data.id), {
        preserveState: true
    })
}

const restoreProperty = (data) => {
    router.patch(route('properties.restore', data.id), {
        preserveState: true
    })
}

</script>
