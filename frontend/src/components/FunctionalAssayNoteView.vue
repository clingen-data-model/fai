<script setup>
    import { computed } from 'vue'

    const props = defineProps({
        fieldNotes: {
            required: true
        },
        fieldName: {
            type: String,
            required: true
        }
    })

    const fieldNote = computed(() => {
        if (!props.fieldNotes) {
            return null
        }
        return props.fieldNotes[props.fieldName]
    })
</script>

<template>
    <div v-if="fieldNote" class="bg-gray-50 font-xs px-2 py-1 rounded text-gray-800">
        <strong>Note: </strong> {{fieldNote}}
    </div>
</template>
