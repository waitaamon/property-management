<template>
    <AppLayout :title="property.data.name">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <h2 class="text-2xl leading-7 font-bold text-purple-900 mt-10">{{ property.data.name }}</h2>

            <statistics-card-component :statistics="$page.props.statistics" class="mt-6"/>

        </div>

        <div class="mt-8 max-w-7xl mx-auto bg-white rounded p-4">
            <div class="mt-5 border-t border-gray-200">
                <dl class="sm:divide-y sm:divide-gray-200">

                    <data-display-component title="Name" :value="property.data.name" />

                    <data-display-component title="Location" :value="property.data.location" />

                    <data-display-component title="Phone number" :value="property.data.phone" />

                    <data-display-component title="Email" :value="property.data.email" />

                    <data-display-component title="Address" :value="property.data.address" />

                    <data-display-component title="Created on" :value="moment(property.data.created_at).format('DD MMM Y HH:mm:ss')" />

                </dl>
            </div>
        </div>

    </AppLayout>
</template>

<script setup>
import moment from "moment";
import AppLayout from "@/Layouts/AppLayout.vue";
import DataDisplayComponent from "@/Components/DataDisplayComponent.vue";
import StatisticsCardComponent from "@/Components/StatisticsCardComponent.vue";

const props = defineProps({
    property: Object,
    filters: Object
})
</script>
