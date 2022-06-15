<script setup>
    import assayClassForm from '@/forms/publication_form.js'

    const breadcrumbs = [
        {
            route: {name: 'PublicationIndex'},
            label: 'Publications'
        }
    ];
    const originalItem = formDefinition.currentItem
</script>

<template>
    <ScreenTemplate :breadcrumbs="breadcrumbs">
        <template v-slot:header>
            <h1>You are about to delete the <span class="underline">{{originalItem.name}}</span> publication.</h1>
        </template>
        <CrudDelete :form-def="formDefinition"></CrudDelete>
    </ScreenTemplate>
</template>