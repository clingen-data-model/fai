<script setup>
    import assayClassForm from '@/forms/assay_class_form.js'

    const originalItem = assayClassForm.originalItem
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
            <h1>Update <u>{{originalItem.name}}</u> Assay Class</h1>
        </template>
        <CrudUpdate :form-def="assayClassForm"></CrudUpdate>
    </ScreenTemplate>
</template>