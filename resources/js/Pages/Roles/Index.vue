<template>
    <AppLayout title="Roles and Permissions">
        <div class="mt-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-2xl font-bold text-purple-900">Roles and Permissions</h2>
            </div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-3 py-6 flex justify-between">
                <div>
                    <div class="mt-1 flex rounded-md shadow-sm">
                        <div class="relative flex items-stretch flex-grow focus-within:z-10">
                            <input v-model="form.search" type="text" name="search" id="search" class="focus:ring-purple-500 focus:border-purple-500 block w-full rounded-none rounded-l-md sm:text-sm border-gray-300" placeholder="Search ..."/>
                        </div>
                        <button @click.prevent="search" type="button" class="-ml-px relative inline-flex items-center space-x-2 px-4 py-2 border border-gray-300 text-sm font-medium rounded-r-md text-gray-700 bg-gray-50 hover:bg-gray-100 focus:outline-none focus:ring-1 focus:ring-purple-500 focus:border-purple-500">
                            <MagnifyingGlassIcon class="h-5 w-5 text-gray-400" aria-hidden="true"/>
                        </button>
                    </div>
                </div>
                <JetButton type="button" @click.prevent="router.get(route('roles.create'))" class="mt-4">Create Role</JetButton>
            </div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <data-table>
                    <slot name="header">
                        <table-th>Name</table-th>
                        <table-th>Guard</table-th>
                        <table-th>Permissions Count</table-th>
                        <table-th>Users Count</table-th>
                        <table-th class="text-right">Actions</table-th>
                    </slot>
                    <tr v-if="roles.data.length" v-for="role in roles.data" :key="role.id" class="bg-white">
                        <table-td> {{ role.name }}</table-td>
                        <table-td> {{ role.guard_name }}</table-td>
                        <table-td> {{ role.permissions_count }}</table-td>
                        <table-td> {{ role.users_count }}</table-td>
                        <td class="text-right px-4 space-x-3">
                            <button @click.prevent="router.get(route('roles.show', role.id))" type="button" class="text-blue-700">
                                <EyeIcon class="-ml-0.5 mr-2 h-4 w-4" aria-hidden="true"/>
                            </button>
                            <button @click.prevent="router.get(route('roles.edit', role.id))" type="button" class="text-purple-800">
                                <PencilSquareIcon class="-ml-0.5 mr-2 h-4 w-4" aria-hidden="true"/>
                            </button>
                        </td>
                    </tr>
                    <table-no-data-tr v-else  colspan="5"/>
                </data-table>
                <pagination class="mt-6" :meta="roles.meta"/>
            </div>
        </div>
    </AppLayout>
</template>
<script setup>

import {router, useForm} from "@inertiajs/vue3";
import JetButton from "@/Components/PrimaryButton.vue";
import AppLayout from '@/Layouts/AppLayout.vue'
import Pagination from "@/Components/Pagination.vue"
import TableTh from "@/Components/Table/TableTh.vue"
import TableTd from "@/Components/Table/TableTd.vue"
import DataTable from "@/Components/Table/DataTable.vue"
import { EyeIcon, MagnifyingGlassIcon, PencilSquareIcon } from '@heroicons/vue/24/outline'
import TableNoDataTr from "@/Components/Table/TableNoDataTr.vue";
const props = defineProps({
    roles: Object,
    filters: Object
})
const form = useForm({
    search: props.filters.search
})
const search = () => {
    router.get(route('roles.index', {
        preserveState: true,
        _query: {
            search: form.search,
        },
    }))
}
</script>

