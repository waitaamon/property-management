<template>
    <div class="w-fit max-w-sm">
        <Popover v-slot="{ open }">
            <PopoverButton :class="open ? '' : 'text-opacity-90'" class="inline-flex items-center justify-center rounded-full bg-transparent p-2 text-base font-medium hover:text-opacity-100 focus:outline-none focus-visible:ring-1 focus-visible:ring-gray-800 focus-visible:ring-opacity-75">
                <FunnelIcon :class="open ? '' : ''" class="h-5 w-5 text-purple-900 transition duration-150 ease-in-out" aria-hidden="true"/>
            </PopoverButton>

            <PopoverPanel class="absolute bg-white border border-gray-200 shadow-sm rounded-lg px-4 py-3 mt-3 w-screen max-w-sm -translate-x-3/4 transform sm:px-0">

                <slot />

            </PopoverPanel>
        </Popover>
    </div>
</template>

<script setup>
import {FunnelIcon} from '@heroicons/vue/24/outline'
import {Popover, PopoverButton, PopoverPanel} from '@headlessui/vue'

</script>
