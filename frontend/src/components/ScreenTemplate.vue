<script setup>
    const props = defineProps({
        breadcrumbs: {
            type: Array,
            default: () => []
        }
    })
</script>
<template>
    <div class="screen">
        <header>
            <breadcrumbs :items="breadcrumbs" v-if="breadcrumbs.length > 0"/>
            <div class="flex justify-between items-center border-b mb-4 pb-2">
                <slot name="header"></slot>
            </div>
        </header>
        <slot></slot>
    </div>
</template>