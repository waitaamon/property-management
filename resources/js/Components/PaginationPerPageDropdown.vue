<template>
    <div>
        <select name="" id="" v-model="perPage" @change="onChange($event)" class="mb-1 mr-1 pr-8 py-3 text-gray-700 text-sm leading-4 border rounded">
            <option v-for="(item, index) in items" :key="index" :value="item.value" class="rounded text-gray-700"> {{ item.name}} </option>
        </select>
    </div>
</template>

<script setup>
    import {ref} from "vue";

    const props = defineProps({
        meta: [Object]
    })
    const emit = defineEmits(['updatePerPage'])

    const perPage = props.meta.per_page

    const items = ref([
        {name: '2', value: 2},
        {name: '10', value: 10},
        {name: '20', value: 20},
        {name: '50', value: 50},
        {name: '100', value: 100},
        {name: '200', value: 200},
        {name: 'All', value: props.meta.total},
    ])

    const onChange = (event) => {
        emit('updatePerPage', event.target.value)
    }
</script>
