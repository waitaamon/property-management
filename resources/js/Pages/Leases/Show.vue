<template>
    <AppLayout title="Lease">

        <div class="max-w-7xl mx-auto flex justify-between py-6">

            <div>
                <h2 class="text-xl leading-7 font-bold text-purple-900">{{ lease.data.code }} Lease</h2>
            </div>

            <div>
                <app-approval-buttons :model="lease.data" model-string="lease"/>
            </div>

        </div>

        <div class="max-w-7xl mx-auto px-6 py-6 mt-4 bg-white rounded shadow">

            <div class="flex justify-between">
                <div class="space-y-3">
                    <template v-if="lease.data.tenant">
                        <p>Tenant: <span class="font-bold">{{ lease.data.tenant.name }}</span></p>
                        <p>Tenant Pin: <span class="font-bold">{{ lease.data.tenant.pin }}</span></p>
                        <p>Address: <span class="font-bold">{{ lease.data.tenant.address }}</span></p>
                    </template>
                </div>
                <div class="space-y-3">
                    <p>Code: <span class="font-bold">{{ lease.data.code }}</span></p>
                    <p>Created By: <span class="font-bold">{{ lease.data.user.name }}</span></p>
                    <p>Date: <span class="font-bold">{{ moment(lease.data.created_at).format('DD MMM Y HH:mm:ss') }}</span></p>
                    <p>Status: <span class="font-bold capitalize">{{ lease.data.status }}</span></p>
                </div>
            </div>

            <div class="mt-6">
                <data-table class="capitalize">
                    <slot name="header">
                        <table-th>
                            #
                        </table-th>
                        <table-th>Property</table-th>
                        <table-th>House</table-th>
                        <table-th>Rent</table-th>
                        <table-th>Discount</table-th>
                        <table-th>Good will</table-th>
                        <table-th>Total amount</table-th>
                    </slot>

                    <tr>
                        <table-td>
                            1
                        </table-td>
                        <table-td>{{ lease.data.house.property.name }}</table-td>
                        <table-td>{{ lease.data.house.name }}</table-td>
                        <table-td>{{ lease.data.house.rent.toLocaleString() }}</table-td>
                        <table-td>{{ lease.data.house.deposit.toLocaleString() }}</table-td>
                        <table-td>{{ lease.data.house.goodwill.toLocaleString() }}</table-td>
                        <table-td class="font-bold">{{ lease.data.amount.toLocaleString() }}</table-td>
                    </tr>
                </data-table>

                <div class="py-4 w-full grid grid-cols-2 gap-6">

                    <div class="col-span-1">
                        <h4 class="font-bold text-sm text-gray-800">Notes:</h4>
                        <p class="mt-2 text-sm text-gray-700 tracking-wide text-justify">{{ lease.data.notes }}</p>
                    </div>

                    <div class="col-span-1 mt-6">

                        <div v-for="approval in lease.data.approvals" :key="approval.id">
                            <div class="bg-blue-50 px-2 py-2 rounded border-b mb-2">
                                <p class="capitalize text-gray-800">
                                    <span class="font-bold">{{ approval.status }}</span>
                                    By: <span class="font-bold">{{ approval.user.name }} </span>
                                    On: <span class="font-bold">{{ moment(approval.date).format('DD MMM Y HH:mm:ss') }}</span>
                                </p>
                                <p class="text-sm text-gray-600">{{ approval.note }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </AppLayout>
</template>

<script setup>
import moment from "moment";
import AppLayout from '@/Layouts/AppLayout.vue'
import TableTh from "@/Components/Table/TableTh.vue";
import TableTd from "@/Components/Table/TableTd.vue";
import DataTable from "@/Components/Table/DataTable.vue";
import TableNoDataTr from "@/Components/Table/TableNoDataTr.vue";
import AppApprovalButtons from "@/Components/AppApprovalButtons.vue";

const props = defineProps({
    lease: Object
})

</script>
