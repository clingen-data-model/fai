<script setup>
    import {watch} from 'vue'
    import formDefinition from '@/forms/coding_system_form.js'

    const originalItem = formDefinition.originalItem
    const breadcrumbs = [
        {
            route: {name: 'CodingSystemIndex'},
            label: 'Coding Systems'
        }
    ]
</script>

<template>
    <ScreenTemplate :breadcrumbs="breadcrumbs">
        <template v-slot:header>
            <h1>Update <u>{{originalItem.name}}</u> Coding System</h1>
        </template>

        <CrudUpdate :form-def="formDefinition"></CrudUpdate>
    </ScreenTemplate>
</template>