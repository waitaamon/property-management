<template>
    <table-filters>
        <form class="px-5 py-4 space-y-3" @submit.prevent="submit">
            <div>
                <InputLabel for="supplier" value="Filter by supplier"/>
                <v-select
                    v-model="form.supplier"
                    :options="$page.props.suppliers.data"
                    :reduce="supplier => supplier.id"
                    class="mt-1 block w-full h-full"
                    label="name"
                />
            </div>
            <div>
                <InputLabel for="category" value="Filter by category"/>
                <v-select
                    v-model="form.category"
                    :options="$page.props.categories.data"
                    :reduce="category => category.id"
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
    supplier: props.filters.supplier ? parseInt(props.filters.supplier) : '',
    category: props.filters.category ? parseInt(props.filters.category) : '',
})

const submit = () => emits('updateFilters', toRaw(form))

const reset = () => {
    Object.keys(form).forEach(i => form[i] = null)
    submit()
}

</script>
