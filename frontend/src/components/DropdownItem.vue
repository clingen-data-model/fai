<script>
export default {
    name: 'DropdownItem',
    data() {
        return {
            active: false   
        }
    },
    computed: {
        parentIsValid() {
            return this.$parent.$options.name == 'DropdownMenu'
        }
    },
    mounted() {
        // this.$parent.addItem(this)
    },
}
</script>
<template>
    <li class="menu-item hover:bg-blue-100 cursor-pointer">
        <slot></slot>
    </li>
</template>
<style scoped>
    .dropdown-items > li {
        @apply bg-white hover:bg-blue-100 cursor-pointer py-2 px-2;
        @apply border border-b-0 border-gray-300;
    }
    .dropdown-items > li:first-child {
        @apply rounded-t
    }
    .dropdown-items > li:last-child {
        @apply border-b rounded-b
    }
</style>