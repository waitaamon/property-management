<template>
  <div class="w-48">
    <v-select
        v-model="form.property"
        :clearable="false"
        :options="$page.props.properties"
        :reduce="property => property.id"
        class="w-full"
        label="name"
        @option:selected="updateSelectedProperty"
    />
  </div>
</template>

<script setup>
import {router, useForm, usePage} from "@inertiajs/vue3";

const form = useForm({
  property: usePage().props.selected_property
})

const updateSelectedProperty = (payload) => {
  form.post(route('update-selected-property'), {
    onSuccess: () => router.reload()
  })
}
</script>
