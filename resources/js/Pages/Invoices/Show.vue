<template>
    <AppLayout title="Invoice">

        <div class="max-w-7xl mx-auto flex justify-between py-6">

            <div>
                <h2 class="text-xl leading-7 font-bold text-purple-900">{{ invoice.data.code }} invoice</h2>
            </div>

            <div v-if="invoice.data.can.print" class="space-x-3">
                <print-button :model="invoice.data.id" model-type="invoice">Print invoice</print-button>
            </div>

            <div>
                <app-approval-buttons :model="invoice.data" model-string="invoice"/>
            </div>

        </div>

        <div class="max-w-7xl mx-auto px-6 py-6 mt-4 bg-white rounded shadow">

            <div class="flex justify-between">

                <div class="space-y-3">
                    <template v-if="invoice.data.tenant">
                        <p>Name: <span class="font-bold">{{ invoice.data.lease.tenant.name }}</span></p>
                        <p>Phone: <span class="font-bold">{{ invoice.data.lease.tenant.phone }}</span></p>
                        <p>Pin: <span class="font-bold">{{ invoice.data.lease.tenant.pin }}</span></p>
                    </template>
                </div>

                <div class="space-y-3">
                    <p>Invoice No: <span class="font-bold">{{ invoice.data.code }}</span></p>
                    <p>Created By: <span class="font-bold">{{ invoice.data.user.name }}</span></p>
                    <p>Invoice Date: <span class="font-bold">{{ moment(invoice.data.created_at).format('DD MMM Y HH:mm:ss') }}</span></p>
                    <p>Status: <span class="font-bold capitalize">{{ invoice.data.status }}</span></p>
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
                        <table-th>Amount</table-th>
                    </slot>

                    <template v-if="invoice.data">
                        <tr style="margin-top: 5px;">
                            <table-td>
                                1.
                            </table-td>
                            <table-td>{{ invoice.data.lease.house.property.name }}</table-td>
                            <table-td>{{ invoice.data.lease.house.name }}</table-td>
                            <table-td>{{ invoice.data.amount.toLocaleString() }}</table-td>
                        </tr>

                        <tr style="margin-top: 5px;">
                            <table-td colspan="2" />
                            <table-td class="font-semibold">Amount</table-td>
                            <table-td class="font-semibold">{{ invoice.data.amount.toLocaleString() }}</table-td>
                        </tr>

                        <tr style="margin-top: 5px;">
                            <table-td colspan="2" />
                            <table-td class="font-semibold">{{ invoice.data.tax.name }}</table-td>
                            <table-td class="font-semibold">{{ invoice.data.tax_amount.toLocaleString() }}</table-td>
                        </tr>

                        <tr style="margin-top: 5px;">
                            <table-td colspan="2" />
                            <table-td class="font-semibold">Total</table-td>
                            <table-td class="font-semibold">{{ invoice.data.total_amount.toLocaleString() }}</table-td>
                        </tr>

                    </template>
                    <table-no-data-tr v-else colspan="3"/>
                </data-table>

                <div class="py-4 w-full grid grid-cols-2 gap-6">

                    <div class="col-span-1">
                        <h4 class="font-bold text-sm text-gray-800">Note:</h4>
                        <p class="mt-2 text-sm text-gray-700 tracking-wide text-justify">{{ invoice.data.note }}</p>
                    </div>

                    <div class="col-span-1 mt-6">

                        <div v-for="approval in invoice.data.approvals" :key="approval.id">
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
import PrintButton from "@/Components/PrintButton.vue";
import TableNoDataTr from "@/Components/Table/TableNoDataTr.vue";
import AppApprovalButtons from "@/Components/AppApprovalButtons.vue";

const props = defineProps({
    invoice: Object
})

</script>
