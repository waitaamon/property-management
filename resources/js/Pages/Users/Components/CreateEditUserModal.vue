<template>

    <app-modal :showModal="showModal" @close="closeModal">

        <template v-slot:title>
            <div class="flex flex-col gap-y-2">
                <h3 class="text-xl font-semibold text-purple-900">
                    Add New Staff
                </h3>
            </div>
        </template>

        <form class="space-y-4" @submit.prevent="submit">

            <text-input-group v-model="form.name" label="Name" name="name"/>

            <text-input-group v-model="form.email" label="Email" name="email" type="email"/>

            <div class="mt-2">
                <InputLabel class="text-gray-500 after:content-['*'] after:ml-0.5 after:text-red-500" for="password" value="Password"/>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <input v-model="form.password" :type="passwordFieldType" class="focus:ring-purple-200 focus:border-purple-300 block w-full pr-10 sm:text-sm border-gray-300 rounded-md" placeholder="Password"/>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <EyeIcon v-if="passwordFieldType === 'password'" aria-hidden="true" as="button" class="h-5 w-5 text-gray-400 hover:text-purple-800" @click="showPassword"/>
                        <EyeSlashIcon v-else-if="passwordFieldType === 'text'" aria-hidden="true" as="button" class="h-5 w-5 text-gray-400 hover:text-purple-800" @click="hidePassword"/>
                    </div>
                </div>
                <InputErrorMessage :message="form.errors.password" class="mt-2"/>
            </div>

            <div>
                <InputLabel for="roles" value="User roles"/>

                <v-select v-model="form.roles" :options="$page.props.roles.data" :reduce="role => role.id" class="mt-1 block w-full border-gray-300" label="name" multiple/>

                <input-error-message name="roles"/>
            </div>

            <div class="mt-4">

                <PrimaryButton :class="{ 'opacity-25': form.processing }" type="submit">
                    Save
                </PrimaryButton>

            </div>
        </form>
    </app-modal>

</template>

<script setup>
import {watch, ref} from 'vue'
import {useForm} from "@inertiajs/vue3";
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import {EyeIcon, EyeSlashIcon} from '@heroicons/vue/24/outline'
import AppModal from "@/Components/Modal/AppModal.vue"
import TextInputGroup from "@/Components/Inputs/TextInputGroup.vue"
import InputErrorMessage from "@/Components/Inputs/InputErrorMessage.vue"

const props = defineProps({
    user: {type: Object, required: false},
    showModal: Boolean,
})

const emits = defineEmits(['reset'])

const passwordFieldType = ref('password')

const showPassword = (() => {
    passwordFieldType.value = 'text'
})

const hidePassword = (() => {
    passwordFieldType.value = 'password'
})

let form = useForm({})

watch(() => props.user, val => {
    form = useForm({
        password: '',
        name: val ? val.name : '',
        email: val ? val.email : '',
        roles: val ? val.roles.map(role => role.id) : []
    })
}, {immediate: true});

const closeModal = () => {
    form.reset()
    emits('reset', true)
}

const submit = () => {
    if (props.user) {
        form.put(route('users.update', props.user), {
            onSuccess: () => closeModal()
        })

        return
    }

    form.post(route('users.store'), {
        onSuccess: () => closeModal()
    })
};


</script>
