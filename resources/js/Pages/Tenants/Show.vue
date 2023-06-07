<template>
    <AppLayout :title="tenant.data.name">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <h2 class="text-2xl leading-7 font-bold text-purple-900 mt-10">{{ tenant.data.name }}</h2>

            <statistics-card-component :statistics="$page.props.statistics" class="mt-6"/>

        </div>

        <div class="mt-8 max-w-7xl mx-auto bg-white rounded p-4">

            <app-tab :go-to="route('tenants.show', tenant.data.id)" :tabs="$page.props.tabs">
                <tenant-detail-component v-if="selectedTab.value === 'details'" :tenant="$page.props.payload.data"/>

                <tenant-invoice-component v-if="selectedTab.value === 'invoices'" :tab="selectedTab.value"/>

                <tenant-payments-component v-if="selectedTab.value === 'payments'" :tab="selectedTab.value"/>

                <tenant-statements-component v-if="selectedTab.value === 'statements'" :tab="selectedTab.value"/>
            </app-tab>

        </div>

    </AppLayout>
</template>

<script setup>
import {computed} from "vue";
import {usePage} from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import AppTab from "@/Components/Tabs/AppTab.vue";
import StatisticsCardComponent from "@/Components/StatisticsCardComponent.vue";
import TenantDetailComponent from "@/Pages/Tenants/Components/TenantDetailComponent.vue";
import TenantInvoiceComponent from "@/Pages/Tenants/Components/TenantInvoiceComponent.vue";
import TenantPaymentsComponent from "@/Pages/Tenants/Components/TenantPaymentsComponent.vue";
import TenantStatementsComponent from "@/Pages/Tenants/Components/TenantStatementsComponent.vue";

const props = defineProps({
    tenant: Object
})

const selectedTab = computed(() => usePage().props.tabs.find(tab => tab.selected))
</script>
