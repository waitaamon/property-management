<template>
    <div v-if="meta.links.length > 3">
        <div class="pb-6 flex justify-between items-center">
            <div>
                <p class="text-gray-600 text-sm tracking-wide">Showing {{ meta.from}} - {{ meta.to}} of {{ meta.total}} </p>
            </div>
            <div class="flex flex-wrap -mb-1 justify-end items-center space-x-2">

                <pagination-per-page-dropdown :meta="meta" @updatePerPage="updatePerPage"/>

                <template v-for="(link, key) in meta.links">
                    <div v-if="link.url === null" :key="key"
                         class="mb-1 mr-1 px-4 py-3 text-gray-400 text-sm leading-4 border rounded" v-html="link.label"/>
                    <a v-else
                       :key="`link-${key}`"
                       class="mb-1 mr-1 px-4 py-3 focus:text-purple-800 text-sm leading-4 hover:bg-white border focus:border-purple-800 rounded"
                       :class="{ 'bg-purple-100 text-purple-900 border border-purple-900': link.active }"
                       href=""
                       @click.prevent="$inertia.get(link.url, {perPage: perPage}, {preserveState: true})"
                       v-html="link.label"
                    />
                </template>
            </div>
        </div>
    </div>
</template>

<script setup>
import {ref} from "vue";
import {router} from "@inertiajs/vue3";
import PaginationPerPageDropdown from "./PaginationPerPageDropdown.vue";

const props = defineProps({
    meta: Object,
})

const perPage = ref(10)

const updatePerPage = (payload) => {
    perPage.value = payload

    router.get(props.meta.path, {perPage: payload, page: 1}, {preserveState: true})
}
</script>
