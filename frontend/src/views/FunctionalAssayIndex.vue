<script setup>
    import { ref } from 'vue'
    import {useRouter} from 'vue-router'

    const tableFields = [
        {
            name: 'hgnc_id',
            label: 'Gene'
        },
        {
            name: 'affiliation_id',
            label: 'Affiliation'
        },
        {
            name: 'publication_id',
            label: 'Publication'
        },
        { name: 'approved' },
        { name: 'material_used' },
        { name: 'patient_material_used' },
    ];

    const sort = ref({
        field: 'hgnc_id',
        desc: false
    })

    const router = useRouter();
    const goToItem = (item) => {
        router.push({
            name: 'FunctionalAssayDetail', 
            params: {
                id: item.id
            }
        });
    }

</script>

<template>
    <ScreenTemplate>
        <template v-slot:header>
            <h1>FunctionalAssays</h1>
            <router-link :to="{name: 'FunctionalAssayCreate'}" class="btn xs">Add</router-link>
        </template>

        <CrudIndex 
            resourceUrl="/functional-assays"
            createRouteName="PublicationCreate"
            editRouteName="PublicationUpdate"
            deleteRouteName="PublicationDelete"
        >
            <template v-slot="{items}">
                <DataTable
                    :fields="tableFields"
                    :data="items"
                    v-model:sort="sort"
                    @rowClick="goToItem"
                ></DataTable>
            </template>
        </CrudIndex>
    </ScreenTemplate>
</template>

<style scoped>
</style>