<template>
    <table-filters>
        <form class="px-5 py-4 space-y-3" @submit.prevent="submit">
            <div>
                <InputLabel for="account_type" value="Filter by user type"/>
                <v-select v-model="form.account_type" :options="$page.props.account_types" class="mt-1 block w-full h-full"/>
            </div>
            <div v-if="form.account_type">
                <InputLabel for="user" :value="`Filter by ${form.account_type}`"/>
                <v-select
                    v-model="form.user"
                    :options="form.account_type === 'customer' ? $page.props.customers.data : $page.props.suppliers.data"
                    :reduce="user => user.id"
                    class="mt-1 block w-full h-full"
                    label="name"
                />
            </div>
            <div>
                <InputLabel for="account" value="Filter by bank account"/>
                <v-select
                    v-model="form.account"
                    :options="$page.props.accounts.data"
                    :reduce="account => account.id"
                    class="mt-1 block w-full h-full"
                    label="name"
                />
            </div>

            <div>
                <InputLabel for="status" value="Filter by status"/>
                <v-select v-model="form.status" :options="$page.props.statuses" class="mt-1 block w-full h-full"/>
            </div>

            <div>
                <InputLabel for="from" value="Filter from"/>
                <TextInput id="from" v-model="form.from" class="w-full" type="date"/>
            </div>

            <div>
                <InputLabel for="to" value="Filter to"/>
                <TextInput id="to" v-model="form.to" class="w-full" type="date"/>
            </div>

            <div class="pt-1 flex justify-between items-center space-x-3">
                <a class="block text-gray-400 hover:text-red-500 focus:text-red-500" href="#" @click.prevent="reset"> Reset</a>
                <PrimaryButton class="" type="submit">
                    Apply Filters
                </PrimaryButton>
            </div>
        </form>
    </table-filters>
</template>
<script setup>
import {reactive, toRaw} from "vue";
import InputLabel from '@/Components/InputLabel.vue'
import TextInput from "@/Components/TextInput.vue"
import PrimaryButton from '@/Components/PrimaryButton.vue'
import TableFilters from "@/Components/Table/TableFilters.vue"

const props = defineProps({
    filters: Object,
})

const emits = defineEmits(['updateFilters'])

const form = reactive({
    to: props.filters.to,
    from: props.filters.from,
    status: props.filters.status ? props.filters.status : '',
    user: props.filters.user ? parseInt(props.filters.user) : '',
    account_type: props.filters.account_type ? props.filters.account_type : '',
})

const submit = () => emits('updateFilters', toRaw(form))

const reset = () => {
    Object.keys(form).forEach(i => form[i] = null)
    submit()
}

</script>
