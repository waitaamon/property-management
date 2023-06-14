<template>
    <AppLayout :title="house.data.name">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <h2 class="text-2xl leading-7 font-bold text-purple-900 mt-10">{{ house.data.name }}</h2>


        </div>

        <div class="mt-8 max-w-7xl mx-auto bg-white rounded p-4">

        </div>

    </AppLayout>
</template>

<script setup>


import AppLayout from "@/Layouts/AppLayout.vue";

const props = defineProps({
    house: Object,
})
</script>
