<template>
    <div>
        <div class="mt-5 border-t border-gray-200">
            <dl class="sm:divide-y sm:divide-gray-200">

                <data-display-component title="Name" :value="house.name" />

                <data-display-component title="Property" :value="house.property.name" />

<!--                <data-display-component title="Tenant" :value="house.tenant.name" />-->

                <data-display-component title="Rent" :value="house.rent.toLocaleString()" />

                <data-display-component title="Deposit" :value="house.deposit.toLocaleString()" />

                <data-display-component title="Is active" :value="house.is_active ? 'active' : 'inactive'" />

                <data-display-component title="Description" :value="house.description" />
            </dl>
        </div>
    </div>
</template>

<script setup>
import DataDisplayComponent from "@/Components/DataDisplayComponent.vue";

const props = defineProps({
    house: Object
})
</script>
