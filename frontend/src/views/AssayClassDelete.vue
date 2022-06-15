<script setup>
    import formDefinition from '@/forms/assay_class_form.js'
    const assayClass = formDefinition.currentItem
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
            <h1>You are about to delete the <span class="underline">{{assayClass.name}}</span> assay class.</h1>
        </template>
        <CrudDelete :form-def="formDefinition"></CrudDelete>
    </ScreenTemplate>
</template>