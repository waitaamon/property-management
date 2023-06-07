<template>
    <Menu as="div" class="relative inline-block mt-4 text-left">
        <div>
            <MenuButton
                class="inline-flex justify-center w-full rounded-md px-4 py-2 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-purple-500">
                Manage
                <ChevronDownIcon class="-mr-1 ml-2 h-5 w-5" aria-hidden="true"/>
            </MenuButton>
        </div>

        <transition
            enter-active-class="transition ease-out duration-100"
            enter-from-class="transform opacity-0 scale-95"
            enter-to-class="transform opacity-100 scale-100"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="transform opacity-100 scale-100"
            leave-to-class="transform opacity-0 scale-95">

            <MenuItems
                class="origin-top-right absolute right-0 mt-2 w-max rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none">
                <div class="py-1">

                    <MenuItem v-slot="{ active }" v-for="(item, index) in dropdownItems" :key="index">
                        <Link class="relative block" :href="route(item.route)" :active="route().current(item.route)"
                              :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block px-4 py-2 text-sm']">
                            {{ item.name }}
                        </Link>
                    </MenuItem>

                </div>
            </MenuItems>
        </transition>
    </Menu>
</template>

<script setup>
import {Link} from '@inertiajs/vue3'

import {ChevronDownIcon} from '@heroicons/vue/24/solid'
import {Menu, MenuButton, MenuItem, MenuItems} from '@headlessui/vue'

const dropdownItems = [
    // {name: 'Properties', route: 'properties.index'},
    // {name: 'Tenants', route: 'tenants.index'},
    // {name: 'Houses', route: 'houses.index'},
    {name: 'Bank accounts', route: 'bank-accounts.index'},
    {name: 'Expense categories', route: 'expense-categories.index'},
    {name: 'Users', route: 'users.index'},
    {name: 'Role & Permissions', route: 'roles.index'},
    // {name: 'Audit Trail', route: 'audit'},
]
</script>
