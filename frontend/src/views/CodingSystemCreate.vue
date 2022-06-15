<script setup>
    import {useRouter} from 'vue-router'
    import codingSystemForm from '@/forms/coding_system_form'

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
            <h1>Add a New Coding System</h1>
        </template>
        <CrudCreate :form-def="codingSystemForm"></CrudCreate>
    </ScreenTemplate>
</template>