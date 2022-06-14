<script setup></script>
<template>
    <div class="screen">
        <header>
            <slot name="header"></slot>
        </header>
        <slot></slot>
    </div>
</template>
<style scoped>
    header {
        @apply flex justify-between items-center border-b mb-4 pb-2
    }
</style>