<script setup>
    import { ref } from 'vue'

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
                ></DataTable>
            </template>
        </CrudIndex>
    </ScreenTemplate>
</template>

<style scoped>
</style>