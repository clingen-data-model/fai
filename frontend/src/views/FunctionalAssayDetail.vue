<script setup>
    import {ref, onMounted, onUnmounted} from 'vue'
    import repo from '../repositories/functional_assay_repository';

    const props = defineProps({
        id: {
            type: Number,
            required: true
        }
    });

    const item = ref({assay_classes: []})
    onMounted(async () => {
        item.value = await repo.find(props.id)
        console.log(item.value);
    })

    const breadcrumbs = [
        {
            route: {name: 'FunctionalAssayIndex'},
            label: 'Functional Assay'
        },
    ];
</script>

<template>
    <ScreenTemplate :breadcrumbs="breadcrumbs">
        <template #header>
            <h1>Functional Assay Detail</h1>
            <router-link v-if="item.id" :to="{name: 'FunctionalAssayEdit', params: {id: item.id}}" class="btn xs">Edit</router-link>
        </template>
        <DictionaryRow 
            label="Assay Classes" 
            labelClass="font-bold w-56 mb-2"
        >
            <ul>
                <li v-for="ac in item.assay_classes">
                    <router-link :to="{name: 'AssayClassDetail', params: {id: ac.id}}">{{ac.name}}</router-link>
                </li>
            </ul>
        </DictionaryRow>
        <ObjectDictionary 
            :obj="item" 
            :except="['assay_classes']"
            labelClass="font-bold w-56 mb-2"
        ></ObjectDictionary>
    </ScreenTemplate>
</template>