<script setup>
    import formDefinition from '@/forms/publication_form.js'

    const breadcrumbs = [
        {
            route: {name: 'PublicationIndex'},
            label: 'Publications'
        }
    ];
</script>

<template>
    <ScreenTemplate :breadcrumbs="breadcrumbs">
        <template v-slot:header>
            <h1>Add a New Publication</h1>
        </template>
        <CrudCreate :formDef="formDefinition"></CrudCreate>
    </ScreenTemplate>
</template>