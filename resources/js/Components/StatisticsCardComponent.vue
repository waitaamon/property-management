<template>
    <div>
        <dl class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            <div v-for="item in statistics" :key="item.name" class="bg-white py-5 sm:pt-6 sm:px-6 shadow-sm rounded-lg">

                <div class="grid grid-cols-5 gap-y-3">
                        <dt>
                            <div class="ml-5 md:ml-0 grid bg-purple-100 rounded-full w-10 h-10 place-content-center">
                                <component :is="getIcon(item.icon)" class="h-5 w-5 text-purple-900" aria-hidden="true"/>
                            </div>
                        </dt>

                    <div class="col-span-4">

                         <p class="capitalize text-sm font-medium text-gray-500 truncate">{{ item.name }}</p>

                        <dd class=" flex items-baseline">
                            <p class="text-2xl font-semibold text-gray-900" v-html="item.value"> </p>
                        </dd>

                    </div>
                </div>

            </div>
        </dl>
    </div>
</template>

<script setup>

import {
    CursorArrowRaysIcon,
    EnvelopeOpenIcon,
    UsersIcon,
    ClipboardDocumentCheckIcon,
    CalendarIcon,
    UserGroupIcon,
    ArrowRightOnRectangleIcon,
    BuildingOfficeIcon,
    MapPinIcon,
    ShieldCheckIcon,
    BriefcaseIcon,
    ArrowLeftOnRectangleIcon,
    } from '@heroicons/vue/24/outline'

import { CheckBadgeIcon } from '@heroicons/vue/24/solid'

const props = defineProps({
      statistics: Array
})

const getIcon = (iconName) => {
      switch (iconName) {
          case 'UsersIcon': return UsersIcon
          case 'LoginIcon': return ArrowLeftOnRectangleIcon
          case 'LogoutIcon': return ArrowRightOnRectangleIcon
          case 'MailOpenIcon': return EnvelopeOpenIcon
          case 'CalendarIcon': return CalendarIcon
          case 'BriefcaseIcon': return BriefcaseIcon
          case 'UserGroupIcon': return UserGroupIcon
          case 'BadgeCheckIcon': return CheckBadgeIcon
          case 'ShieldCheckIcon': return ShieldCheckIcon
          case 'ClipboardCheckIcon': return ClipboardDocumentCheckIcon
          case 'OfficeBuildingIcon': return BuildingOfficeIcon
          case 'LocationMarkerIcon': return MapPinIcon
          default: return CursorArrowRaysIcon
      }
}
</script>
