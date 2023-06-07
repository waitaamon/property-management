<template>
    <Menu as="div" class="relative inline-block text-left">
        <div>
            <MenuButton
                class="inline-flex w-full justify-center rounded-md bg-transparent px-4 py-2 text-sm font-medium text-purple-800 border border-purple-800 hover:bg-purple-100">
                Export
                <DocumentArrowDownIcon aria-hidden="true" class="ml-2 -mr-1 h-5 w-5 text-purple-900 "/>
            </MenuButton>
        </div>

        <transition enter-active-class="transition duration-100 ease-out"
                    enter-from-class="transform scale-95 opacity-0" enter-to-class="transform scale-100 opacity-100"
                    leave-active-class="transition duration-75 ease-in" leave-from-class="transform scale-100 opacity-100"
                    leave-to-class="transform scale-95 opacity-0">
            <MenuItems
                class="absolute right-0 mt-2 w-48 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                <div class="px-2 py-1">
                    <MenuItem v-slot="{ active }">
                        <a :class="[active ? 'outline outline-2 outline-green-500' : '', 'group flex items-center rounded-md px-2 py-2 text-sm',]"
                           href="#" @click.prevent="exportFile('excel')">
                            <svg class="-ml-1 mr-2" height="24" viewBox="0 0 24 24" width="24">
                                <path d="M21.17 3.25q.33 0 .59.25q.24.24.24.58v15.84q0 .34-.24.58q-.26.25-.59.25H7.83q-.33 0-.59-.25q-.24-.24-.24-.58V17H2.83q-.33 0-.59-.24Q2 16.5 2 16.17V7.83q0-.33.24-.59Q2.5 7 2.83 7H7V4.08q0-.34.24-.58q.26-.25.59-.25M7 13.06l1.18 2.22h1.79L8 12.06l1.93-3.17H8.22L7.13 10.9l-.04.06l-.03.07q-.26-.53-.56-1.07q-.25-.53-.53-1.07H4.16l1.89 3.19L4 15.28h1.78m8.1 4.22V17H8.25v2.5m5.63-3.75v-3.12H12v3.12m1.88-4.37V8.25H12v3.13M13.88 7V4.5H8.25V7m12.5 12.5V17h-5.62v2.5m5.62-3.75v-3.12h-5.62v3.12m5.62-4.37V8.25h-5.62v3.13M20.75 7V4.5h-5.62V7Z"
                                      fill="#059669"/>
                            </svg>
                            Download as Excel
                        </a>
                    </MenuItem>
                </div>
            </MenuItems>
        </transition>
    </Menu>
</template>

<script setup>
import {useToast} from "vue-toastification";
import {DocumentArrowDownIcon} from '@heroicons/vue/24/outline'
import {Menu, MenuButton, MenuItem, MenuItems} from '@headlessui/vue'

const props = defineProps({
    routeName: String,
    payload: Array,
    parameters: {required: false, type: Object},
})

const toast = useToast();

const exportFile = (fileType) => {

    if (props.payload.length < 1) {
        toast.warning('Select at least one item to export.')
        return
    }

    window.open(
        route(
            `export-${props.routeName}-${fileType}`,
            {
                _query: {data: props.payload, parameters: props.parameters}
            }
        ),
        '_blank'
    )
}

</script>
