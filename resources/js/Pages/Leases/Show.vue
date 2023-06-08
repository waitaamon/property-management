<template>
    <AppLayout title="Sale order">

        <div class="max-w-7xl mx-auto flex justify-between py-6">

            <div>
                <h2 class="text-xl leading-7 font-bold text-purple-900">{{ saleOrder.data.code }} Sale</h2>
            </div>

            <div>
                <app-approval-buttons :model="saleOrder.data" model-string="SaleOrder"/>
            </div>

        </div>

        <div class="max-w-7xl mx-auto px-6 py-6 mt-4 bg-white rounded shadow">

            <div class="flex justify-between">
                <div class="space-y-3">
                    <template v-if="saleOrder.data.customer">
                        <p>Customer: <span class="font-bold">{{ saleOrder.data.customer.name }}</span></p>
                        <p>Customer Pin: <span class="font-bold">{{ saleOrder.data.customer.pin }}</span></p>
                    </template>
                </div>
                <div class="space-y-3">
                    <p>Code: <span class="font-bold">{{ saleOrder.data.code }}</span></p>
                    <p>Created By: <span class="font-bold">{{ saleOrder.data.user.name }}</span></p>
                    <p>Date: <span class="font-bold">{{ moment(saleOrder.data.created_at).format('DD MMM Y HH:mm:ss') }}</span></p>
                    <p>Status: <span class="font-bold capitalize">{{ saleOrder.data.status }}</span></p>
                </div>
            </div>

            <div class="mt-6">
                <data-table class="capitalize">
                    <slot name="header">
                        <table-th>
                            #
                        </table-th>
                        <table-th>Product</table-th>
                        <table-th>Quantity</table-th>
                        <table-th>Unit price</table-th>
                        <table-th>Total amount</table-th>
                    </slot>

                   <template v-if="saleOrder.data.items.length">
                       <tr v-for="(item, index) in saleOrder.data.items"  :key="item.id">
                           <table-td>
                               {{ index + 1 }}
                           </table-td>
                           <table-td>{{ item.product.name }}</table-td>
                           <table-td>{{ item.quantity }}</table-td>
                           <table-td>{{ item.unit_price.toLocaleString() }}</table-td>
                           <table-td>{{ item.total_price.toLocaleString() }}</table-td>
                       </tr>
                       <template v-if="saleOrder.data.tax">
                           <tr>
                               <table-td />
                               <table-td colspan="3" class="font-bold">Amount</table-td>
                               <table-td> {{ saleOrder.data.amount.toLocaleString() }}</table-td>
                           </tr>
                           <tr>
                               <table-td />
                               <table-td colspan="3" class="font-bold">Tax ({{ saleOrder.data.tax.name }})</table-td>
                               <table-td> {{ saleOrder.data.total_tax.toLocaleString() }}</table-td>
                           </tr>
                       </template>
                       <tr>
                           <table-td />
                           <table-td colspan="3" class="font-bold">Total amount</table-td>
                           <table-td class="font-bold"> {{ saleOrder.data.total_amount.toLocaleString() }}</table-td>
                       </tr>

                   </template>
                    <table-no-data-tr v-else colspan="5"/>
                </data-table>

                <div class="py-4 w-full grid grid-cols-2 gap-6">

                    <div class="col-span-1">
                        <h4 class="font-bold text-sm text-gray-800">Note:</h4>
                        <p class="mt-2 text-sm text-gray-700 tracking-wide text-justify">{{ saleOrder.data.note }}</p>
                    </div>

                    <div class="col-span-1 mt-6">

                        <div v-for="approval in saleOrder.data.approvals" :key="approval.id" >
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
    saleOrder: Object
})

</script>
