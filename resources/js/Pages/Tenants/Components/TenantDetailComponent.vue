<template>
    <div>
        <div class="mt-5 border-t border-gray-200">
            <dl class="sm:divide-y sm:divide-gray-200">

                <data-display-component title="Name" :value="tenant.name" />

                <data-display-component title="Pin" :value="tenant.pin" />

                <data-display-component title="Phone number" :value="tenant.phone" />

                <data-display-component title="Email" :value="tenant.email" />

                <data-display-component title="Address" :value="tenant.address" />

                <data-display-component title="Registered on" :value="moment(tenant.created_at).format('DD MMM Y HH:mm:ss')" />

            </dl>
        </div>
    </div>
</template>

<script setup>
import moment from "moment";
import DataDisplayComponent from "@/Components/DataDisplayComponent.vue";

const props = defineProps({
    tenant: Object
})
</script>
