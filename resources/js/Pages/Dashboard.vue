<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import StatisticsCardComponent from "@/Components/StatisticsCardComponent.vue";
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                <statistics-card-component :statistics="$page.props.statistics" class="mt-6"/>

            </div>
        </div>
    </AppLayout>
</template>
