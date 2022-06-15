<script setup>
    import assayClassForm from '@/forms/publication_form.js'

    const breadcrumbs = [
        {
            route: {name: 'PublicationIndex'},
            label: 'Publications'
        }
    ];

    const originalItem = assayClassForm.originalItem
</script>

<template>
    <ScreenTemplate :breadcrumbs="breadcrumbs">
        <template v-slot:header>
            <h1>Update <u>{{originalItem.name}}</u> Publication</h1>
        </template>
        <CrudUpdate :form-def="assayClassForm"></CrudUpdate>
    </ScreenTemplate>
</template>