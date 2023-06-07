<template>
    <AppLayout title="Create Role">

        <div class="max-w-7xl mx-auto mt-8 px-4 sm:px-6 lg:px-8">

            <h2 class="text-2xl font-bold text-purple-900"> {{ role ? 'Edit' : 'Create'}} Roles & Permissions</h2>

        </div>

        <div class="py-10 m-auto">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="rounded overflow-hidden shadow p-12 bg-white">
                    <form class="m-auto" @submit.prevent="submit">

                        <text-input-group name="name" label="Role name" v-model="form.name"/>

                        <div class="mt-4">

                            <InputLabel for="users" value="Users"/>

                            <v-select
                                multiple
                                id="users"
                                label="name"
                                v-model="form.users"
                                :options="users.data"
                                class="mt-1 block w-full h-full"
                                :reduce="user => user.id"
                            />

                            <input-error-message name="users"/>
                        </div>

                        <InputLabel class="mt-8">
                            <h3>Permissions</h3>
                            <input-error-message name="permissions"/>
                            <hr>
                        </InputLabel>

                        <div class="mt-4 flex flex-col">

                            <div v-for="(permission_group, index) in permission_groups.data" :key="permission_group.id" class="flex flex-col" :class="{'rounded shadow px-4 pb-2 mt-4 bg-gray-100': index % 2 === 0}">

                                <div class="mt-6 col-1 flex items-center">
                                    <Checkbox :id="permission_group.name"  @change="permissionGroupChanged($event, permission_group)"/>
                                    <div class="ml-2">
                                        <InputLabel :for="permission_group.name" :value="permission_group.name" class=""/>
                                    </div>
                                </div>

                                <div class="grid grid-cols-4">
                                    <div v-for="permission in permission_group.permissions" :key="permission.id" class="mt-6 col-1 flex items-center">
                                        <Checkbox :id="`permission-${permission.id}`" v-model:checked="form.permissions" :value="permission.id"/>
                                        <div class="ml-2">
                                            <InputLabel :for="`permission-${permission.id}`" :value="permission.name"/>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>

                        <div class="flex justify-between mt-6 h-10">
                            <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }">
                                {{ role ? 'Edit' : 'Create'}} Role
                            </PrimaryButton>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>

import {useForm} from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import Checkbox from '@/Components/Checkbox.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInputGroup from "@/Components/Inputs/TextInputGroup.vue";
import InputErrorMessage from "@/Components/Inputs/InputErrorMessage.vue";

const props = defineProps({
    permission_groups: Object,
    users: Object,
    role: {
        required: false,
        type: Object
    }
})

const form = useForm({
    name: props.role ? props.role.data.name : '',
    users: props.role ? props.role.data.users.map(user => user.id) : [],
    permissions: props.role ? props.role.data.permissions.map(permission => permission.id) : [],
})

const permissionGroupChanged = (e, group) => {
    if(e.target.checked) {
        group.permissions.forEach(permission => {
            if (form.permissions.indexOf(permission.id) !== -1) return

            form.permissions.push(permission.id)
        })
        return
    }

    group.permissions.forEach(permission => {
        form.permissions.splice(form.permissions.indexOf(permission.id), 1)
    })
}

const submit = () => {
    if (props.role) {
        form.put(route('roles.update', props.role.data.id), {
            onSuccess: () => {
                form.reset()
            }
        })

        return
    }

    form.post(route('roles.store'), {
        onSuccess: () => {
            form.reset()
        }
    })
};


</script>
