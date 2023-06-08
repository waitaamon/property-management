<template>
    <AppLayout :title="product.data.name">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <h2 class="text-2xl leading-7 font-bold text-purple-900 mt-10">{{ product.data.name }}</h2>

            <statistics-card-component :statistics="$page.props.statistics" class="mt-6"/>

        </div>

        <div class="mt-8 max-w-7xl mx-auto bg-white rounded p-4">

            <app-tab :go-to="route('products.show', product.data.id)" :tabs="$page.props.tabs">

                <product-detail-component v-if="selectedTab.value === 'details'" :product="$page.props.payload.data"/>

                <product-adjustment-component v-if="selectedTab.value === 'adjustments'" :tab="selectedTab.value"/>

                <product-purchases-component v-if="selectedTab.value === 'purchases'" :tab="selectedTab.value"/>

                <product-sales-component v-if="selectedTab.value === 'sales'" :tab="selectedTab.value"/>

                <product-stock-movement-component v-if="selectedTab.value === 'stock-movements'" :tab="selectedTab.value"/>
            </app-tab>

        </div>

    </AppLayout>
</template>

<script setup>
import {computed} from "vue";
import {usePage} from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import AppTab from "@/Components/Tabs/AppTab.vue";
import ProductSalesComponent from "./Components/HouseInvoicesComponent.vue";
import ProductDetailComponent from "./Components/HouseDetailComponent.vue";
import StatisticsCardComponent from "@/Components/StatisticsCardComponent.vue";
import ProductPurchasesComponent from "./Components/ProductPurchasesComponent.vue";
import ProductAdjustmentComponent from "./Components/ProductAdjustmentComponent.vue";
import ProductStockMovementComponent from "./Components/ProductStockMovementComponent.vue";

const props = defineProps({
    product: Object,
    filters: Object
})

const selectedTab = computed(() => usePage().props.tabs.find(tab => tab.selected))
</script>
