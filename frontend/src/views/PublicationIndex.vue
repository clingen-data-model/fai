<script setup>
    const resolveReference = (item) => {
        return `${item.coding_system.name}:${item.code}`
    }
    const resolveTitle = (item) => {
        if (item.title) {
            return item.title
        }
        return resolveReference(item)
    }
</script>

<template>
    <CrudIndex 
        resourceUrl="/publications"
        title="Publications"
        createRouteName="PublicationCreate"
        editRouteName="PublicationUpdate"
        deleteRouteName="PublicationDelete"
    >
        <template #listItemDisplay="{item}">
            <h4>{{resolveTitle(item)}}</h4>
            <div v-if="item.title">{{resolveReference(item)}}</div>
        </template>
    </CrudIndex>
</template>

<style scoped>
</style>