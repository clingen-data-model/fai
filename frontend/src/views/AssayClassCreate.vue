<script setup>
    import assayClassForm from '@/forms/assay_class_form.js'

    const breadcrumbs = [
        {
            route: {name: 'AssayClassIndex'},
            label: 'Assay Classes'
        }
    ];
</script>

<template>
    <ScreenTemplate :breadcrumbs="breadcrumbs">
        <template v-slot:header>
            <h1>Add a New Assay Class</h1>
        </template>
        <CrudCreate :formDef="assayClassForm"></CrudCreate>
    </ScreenTemplate>
</template>