<script setup>
    import {ref} from 'vue'
    import formDefinition from '@/forms/functional_assay_form.js'

    const breadcrumbs = [
        {
            route: {name: 'FunctionalAssayIndex'},
            label: 'Functional Assay'
        }
    ];

    const testVal = ref();

</script>

<template>
    <ScreenTemplate :breadcrumbs="breadcrumbs">
        <template v-slot:header>
            <h1>Add a New Functional Assay</h1>
        </template>
        <CrudCreate :formDef="formDefinition"></CrudCreate>
    </ScreenTemplate>
</template>