<template>
    <AppLayout title="Bank accounts">

        <create-edit-bank-account-modal :account="account" :showModal="openModal" @reset="closeModal"/>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <h2 class="text-2xl leading-7 font-bold text-purple-900 mt-10">Bank Accounts</h2>

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

                    <PrimaryButton v-if="$page.props.can.create" type="button" @click.prevent="openModal = true">
                        Add Bank Account
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
                    <table-th>Amount</table-th>
                    <table-th>Status</table-th>
                    <table-th>Actions</table-th>
                </slot>

                <tr v-for="account in accounts.data" v-if="accounts.data.length" :key="account.id">
                    <table-td>
                        <Checkbox :id="account.id" v-model:checked="selected" :value="account.id"/>
                    </table-td>
                    <table-td>{{ account.name }}</table-td>
                    <table-td class="lowercase">{{ account.balance }}</table-td>
                    <table-td class="capitalize">{{ account.is_active ? 'active' : 'inactive' }}</table-td>
                    <table-td>
                        <button v-if="account.can.view" type="button" @click.prevent="router.get(route('accounts.show', account.id))">
                            <EyeIcon aria-hidden="true" class="w-4 h-4 text-blue-800 ml-1"/>
                        </button>
                        <button v-if="account.can.edit" type="button" @click.prevent="editAccount(account)">
                            <PencilSquareIcon aria-hidden="true" class="w-4 h-4 text-purple-900 ml-1"/>
                        </button>
                        <button v-if="account.can.delete && account.is_active" type="button" @click.prevent="deleteAccount(account)">
                            <TrashIcon aria-hidden="true" class="w-4 h-4 text-red-500 ml-1"/>
                        </button>
                        <button v-if="account.can.delete && !account.is_active" type="button" @click.prevent="restoreAccount(account)">
                            <ArrowPathIcon aria-hidden="true" class="w-4 h-4 text-green-500 ml-1"/>
                        </button>
                    </table-td>
                </tr>
                <table-no-data-tr v-else colspan="5"/>
            </data-table>

            <div class="mt-4">
                <pagination :meta="accounts.meta"/>
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
import CreateEditBankAccountModal from "./Components/CreateEditBankAccountModal.vue";
import {EyeIcon, PencilSquareIcon, PlusIcon, MagnifyingGlassIcon, TrashIcon, ArrowPathIcon} from '@heroicons/vue/24/outline'


const props = defineProps({
    accounts: Object,
    errors: Object,
    filters: Object,
})

const account = ref(null)
const openModal = ref(false)

let selected = ref([])
let selectAll = ref(false)

let form = useForm({
    errors: props.errors,
    search: props.filters.search,
})

watch((selectAll), () => {
    selectAll.value ? selected.value = props.accounts.data.map(account => account.id) : selected.value = []
})

const closeModal = () => openModal.value = false

const editAccount = (data) => {
    account.value = data
    openModal.value = true
}

const deleteAccount = (data) => {
    router.delete(route('bank-accounts.destroy', data.id), {
        preserveState: true
    })
}

const restoreAccount = (data) => {
    router.patch(route('bank-accounts.restore', data.id), {
        preserveState: true
    })
}

const search = () => {
    router.get(route('accounts.index', {
        _query: {
            search: form.search,
        },
        preserveState: true,
    }))
}

</script>
