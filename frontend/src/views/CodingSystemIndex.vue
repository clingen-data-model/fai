<script setup>
</script>
<template>
    <ScreenTemplate>
        <template #header>
            <h1>Coding Systems</h1>
            <router-link :to="{name: 'CodingSystemCreate'}" class="btn xs">Add</router-link>
        </template>
        <CrudIndex 
            resourceUrl="/coding-systems"
            title="Coding Systems"
            createRouteName="CodingSystemCreate"
            editRouteName="CodingSystemUpdate"
            deleteRouteName="CodingSystemDelete"
        ></CrudIndex>
    </ScreenTemplate>
</template>