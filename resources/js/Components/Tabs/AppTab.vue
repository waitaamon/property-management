<template>
    <div class="w-full px-2 py-4 sm:px-0">
        <TabGroup>
            <TabList class="flex space-x-1 rounded bg-blue-900/50 p-1">
                <Tab v-for="tab in tabs" :key="tab" v-slot="{ selected }" as="template">
                    <button :class="['w-full rounded-lg py-2.5 text-sm font-medium leading-5 text-blue-700 capitalize', 'ring-white ring-opacity-60 ring-offset-2 ring-offset-blue-400 focus:outline-none focus:ring-2',
              tab.selected
                ? 'bg-white shadow'
                : 'text-blue-50 hover:bg-white/[0.12] hover:text-white',
            ]"
                            @click="router.get(goTo, {tab: tab.value})">
                        {{ tab.name }}
                    </button>
                </Tab>
            </TabList>

            <TabPanels class="mt-2">
                <TabPanel v-for="(tabs, idx) in tabs" :key="idx" class="bg-white p-2">
                    <div class="w-full">
                        <slot/>
                    </div>
                </TabPanel>
            </TabPanels>
        </TabGroup>
    </div>
</template>

<script setup>
import {TabGroup, TabList, Tab, TabPanels, TabPanel} from '@headlessui/vue'
import {router} from "@inertiajs/vue3";

const props = defineProps({
    tabs: Array,
    goTo: String
})
</script>
