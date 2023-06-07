<template>
    <AppLayout title="Expense categories">

        <create-edit-expense-category-modal :showModal="openModal" :category="category" @reset="closeModal"/>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <h2 class="text-2xl leading-7 font-bold text-purple-900 mt-10">Expense categories</h2>

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
                        Add Expense category
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
                    <table-th>Total Amount</table-th>
                    <table-th>Status</table-th>
                    <table-th>Actions</table-th>
                </slot>

                <tr v-if="categories.data.length" v-for="category in categories.data" :key="category.id">
                    <table-td>
                        <Checkbox :id="category.id" v-model:checked="selected" :value="category.id"/>
                    </table-td>
                    <table-td>{{ category.name }}</table-td>
                    <table-td class="capitalize">{{ category.total_amount }}</table-td>
                    <table-td>
                        <CheckCircleIcon aria-hidden="true" class="w-4 h-4 text-green-500 ml-1" v-if="category.is_active"/>
                        <XCircleIcon aria-hidden="true" class="w-4 h-4 text-red-500 ml-1" v-else/>
                    </table-td>
                    <table-td>
                        <button v-if="category.can.view" type="button" @click.prevent="router.get(route('expense-categories.show', category.id))">
                            <EyeIcon aria-hidden="true" class="w-4 h-4 text-blue-800 ml-1"/>
                        </button>
                        <button v-if="category.can.edit" type="button" @click.prevent="editCategory(category)">
                            <PencilSquareIcon aria-hidden="true" class="w-4 h-4 text-purple-900 ml-1"/>
                        </button>
                        <button v-if="category.can.delete && category.is_active" type="button" @click.prevent="deleteCategory(category)">
                            <TrashIcon aria-hidden="true" class="w-4 h-4 text-red-500 ml-1"/>
                        </button>
                        <button v-if="category.can.delete && !category.is_active" type="button" @click.prevent="restoreCategory(category)">
                            <ArrowPathIcon aria-hidden="true" class="w-4 h-4 text-green-500 ml-1"/>
                        </button>
                    </table-td>
                </tr>
                <table-no-data-tr v-else colspan="5" />
            </data-table>

            <div class="mt-4">
                <pagination :meta="categories.meta"/>
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
import {EyeIcon, PencilSquareIcon, PlusIcon, MagnifyingGlassIcon, CheckCircleIcon, XCircleIcon, ArrowPathIcon, TrashIcon} from '@heroicons/vue/24/outline'
import CreateEditExpenseCategoryModal from "./Components/CreateEditExpenseCategoryModal.vue";


const props = defineProps({
    categories: Object,
    errors: Object,
    filters: Object,
})

const category = ref(null)
const openModal = ref(false)

let selected = ref([])
let selectAll = ref(false)

watch((selectAll), () => {
    selectAll.value ? selected.value = props.categories.data.map(category => category.id) : selected.value = []
})

const closeModal = () => openModal.value = false

const editCategory = (data) => {
    category.value = data
    openModal.value = true
}

let form = useForm({
    errors: props.errors,
    search: props.filters.search,
})

const search = () => {
    router.get(route('expense-categories.index', {
        _query: {
            search: form.search,
        },
        preserveState: true,
    }))
}

const deleteCategory = (data) => {
    router.delete(route('expense-categories.destroy', data.id), {
        preserveState: true
    })
}

const restoreCategory = (data) => {
    router.patch(route('expense-categories.restore', data.id), {
        preserveState: true
    })
}

</script>
