<script setup>
    import {computed, ref} from 'vue'
    import { titleCase } from '@/utils';
    const props = defineProps({
        section: {
            type: Object,
            required: true
        }
    })

    const title = computed(() => {
        if (props.section.label !== null && typeof props.section.label !== 'undefined') {
            return props.section.label
        }
        return props.section.name
    });
    const contentsCollapsed = ref(false)
    const toggleSection = () => {
        contentsCollapsed.value = !contentsCollapsed.value
    }
</script>
<template>
    <section>
        <header class="flex justify-between items-center">
            <h2 v-if="title">{{titleCase(title)}}</h2>
            <div class="xs" @click="toggleSection">
                <icon-cheveron-down v-if="contentsCollapsed" />
                <icon-cheveron-up v-else />
            </div>
        </header>

        <transition name="fade-slide-down">
            <div v-show="!contentsCollapsed">
                <slot></slot>
            </div>
        </transition>

    </section>
</template>
