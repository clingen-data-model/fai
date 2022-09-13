<script setup>
    import { ref, watch, computed } from 'vue'
    import PublicationLookupField from '@/components/PublicationLookupField.vue'
    // import setupWorkingCopy from '../composables/setup_working_mirror';
    import {cloneDeep, isEqual} from 'lodash';

    const props = defineProps({
        modelValue: { //is publication
            type: Object,
            default: {}
        }
    })
    const emits = defineEmits(['update:modelValue']);

    const clone = ref({});

    watch(() => props.modelValue, function (to) {
        if (!isEqual(to, clone.value)) {
            clone.value = cloneDeep(to);
        }
    }, {immediate: true, deep: true});

    watch(() => clone, function (to) {
        if (!isEqual(to.value, props.modelValue)) {
            emits('update:modelValue', to.value);
        }
    }, {deep: true});

</script>
<template>
    <flex>
        <select v-model="clone.coding_system_id">
            <option :value="1">PubMed</option>
            <option :value="2">URI</option>
        </select>
        <div>
            <div v-if="clone.coding_system_id == 1">
                <PublicationLookupField v-model="clone" />
            </div>
            <div v-if="clone.coding_system_id == 2">
                <input type="text" v-model="clone.code">
            </div>
        </div>
    </flex>
</template>
