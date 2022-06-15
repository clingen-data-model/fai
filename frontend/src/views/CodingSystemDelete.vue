<script setup>
    import formDefinition from '@/forms/coding_system_form.js'

    const codingSystem = formDefinition.currentItem
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
            <h1>Delete <span class="underline">{{codingSystem.name}}</span> coding system?</h1>
        </template>
        <CrudDelete :form-def="formDefinition"></CrudDelete>
    </ScreenTemplate>
</template>