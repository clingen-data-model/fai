<script setup>
</script>

<template>
    <ScreenTemplate>
        <template #header>
            <h1>Assay Classes</h1>
            <router-link :to="{name: 'AssayClassCreate'}" class="btn xs">Add</router-link>
        </template>
        <CrudIndex 
            resourceUrl="/assay-classes"
            title="Assay Classes"
            createRouteName="AssayClassCreate"
            editRouteName="AssayClassUpdate"
            deleteRouteName="AssayClassDelete"
        ></CrudIndex>
    </ScreenTemplate>
</template>

<style scoped>
</style>