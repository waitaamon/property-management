<template>
    <table-filters>
        <form class="px-5 py-4 space-y-3" @submit.prevent="submit">
            <template v-if="showBoth">
                <div>
                    <InputLabel for="from" value="Filter from"/>
                    <TextInput id="from" v-model="form.from" class="w-full" type="date"/>
                </div>

                <div>
                    <InputLabel for="to" value="Filter to"/>
                    <TextInput id="to" v-model="form.to" class="w-full" type="date"/>
                </div>
            </template>
            <template v-else>
                <div>
                    <InputLabel for="date" value="Date"/>
                    <TextInput id="date" v-model="form.date" class="w-full" type="date"/>
                </div>
            </template>

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
    showBoth: {type: Boolean, default: true}
})

const emits = defineEmits(['updateFilters'])

const form = reactive({
    to: props.filters.to,
    from: props.filters.from,
    date: props.filters.date,
})

const submit = () => emits('updateFilters', toRaw(form))

const reset = () => {
    Object.keys(form).forEach(i => form[i] = null)
    submit()
}

</script>
