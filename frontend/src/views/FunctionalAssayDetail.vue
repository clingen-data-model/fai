<script setup>
    import {ref, onMounted, onUnmounted, computed} from 'vue'
    import repo from '../repositories/functional_assay_repository';

    const props = defineProps({
        id: {
            type: Number,
            required: true
        }
    });

    const item = ref({assay_classes: [], publication: {coding_system: {}}})
    onMounted(async () => {
        item.value = await repo.find(props.id)
        console.log(item.value);
    })

    const breadcrumbs = [
        {
            route: {name: 'FunctionalAssayIndex'},
            label: 'Functional Assays'
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
            label="Publication"
            labelClass="font-bold w-56 mb-2"
        >
            <div>
                <u>{{item.publication.title}}</u>
                <br>
                {{`${item.publication.coding_system.name}:${item.publication.code}`}}
            </div>
        </DictionaryRow>
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
            :except="['assay_classes', 'publication', 'publication_id']"
            labelClass="font-bold w-56 mb-2"
        ></ObjectDictionary>
    </ScreenTemplate>
</template>