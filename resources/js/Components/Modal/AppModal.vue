<template>
    <TransitionRoot appear :show="open" as="template">
        <Dialog as="div" class="relative z-10">
            <TransitionChild
                as="template"
                enter="duration-300 ease-out"
                enter-from="opacity-0"
                enter-to="opacity-100"
                leave="duration-200 ease-in"
                leave-from="opacity-100"
                leave-to="opacity-0"
            >
                <div class="fixed inset-0 bg-black bg-opacity-25"/>
            </TransitionChild>

            <div class="fixed inset-0 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4 text-center">
                    <TransitionChild
                        as="template"
                        enter="duration-300 ease-out"
                        enter-from="opacity-0 scale-95"
                        enter-to="opacity-100 scale-100"
                        leave="duration-200 ease-in"
                        leave-from="opacity-100 scale-100"
                        leave-to="opacity-0 scale-95"
                    >
                        <DialogPanel class="w-full max-w-6xl transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
                            <div class="flex flex-row items-center justify-between">

                                <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900">

                                    <div class="flex flex-col gap-y-2">
                                        <h3 class="text-xl font-semibold text-purple-900">
                                            <slot name="title" />
                                        </h3>
                                    </div>

                                </DialogTitle>

                                <XMarkIcon class="h-5 w-5 hover:text-gray-400" type="submit" aria-hidden="true" @click="closeModal"/>

                            </div>

                            <div class="mt-6 mb-3">

                                <slot />

                            </div>

                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<script setup>
import {ref, watch} from "vue";
import {XMarkIcon} from "@heroicons/vue/24/outline"
import {Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot} from '@headlessui/vue'

const props = defineProps({
    showModal: Boolean
})

const emit = defineEmits(['close'])

const open = ref(false)

watch(() => props.showModal, val => open.value = val);

const closeModal = () => {
    emit('close', true)
}
</script>
