<script setup>
    import { h, useSlots, useAttrs } from 'vue'
    
    const props = defineProps({
        items: {
            type: Array,
            required: true
        }
    });

    const slots = useSlots();
    
    const render = () => {
        if (props.items.length > 0) {
            const listItems = props.items.map(item => h('li', null, [slots.default(item)]));
            return h('ul', {class: 'item-list'}, listItems);
        }

        if (!props.hideNoItems) {
            return h('div', {class: 'text-gray-500'}, ['There are no items...'])
        }
    }


</script>
<template>
    <render />
</template>