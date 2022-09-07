<script setup>
    import { ref } from 'vue'
    import PublicationField from './PublicationField.vue';
    const props = defineProps({
        modelValue: {
            type: Array,
        }
    })
    const emits = defineEmits([
        'update:modelValue'
    ])

    const cloneModelValue = () => props.modelValue ? [...props.modelValue] : []

    const updatePub = (val, idx) => {
        const clone = cloneModelValue();
        clone[idx] = val;

        emits('update:modelValue', clone);
    }

    const newPub = ref({});
    const addPub = () => {
        const clone = cloneModelValue();
        clone.push(newPub.value);

        emits('update:modelValue', clone);
        newPub.value = {};
    }

    const removePub = (idx) => {
        const clone = cloneModelValue();
        delete(clone[idx])
        emits('update:modelValue', clone.filter(i => i !== null));
    }
</script>
<template>
    <div class="flex flex-col space-y-3">
        <flex
            v-for="(pub, idx) in modelValue"
            class="space-x-2"
        >
            <PublicationField
                :modelValue="pub"
                @update:modelValue="newVal => updatePub(newVal, idx)"
            />
            <button class="xs" @click="removePub(idx)">X</button>
        </flex>
        <flex class="space-x-2">
            <PublicationField
                v-model="newPub"
            />
            <button class="xs" @click="addPub">add</button>
        </flex>
    </div>
</template>
