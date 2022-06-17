<script setup>
    import {debounce} from 'lodash'
    import {ref, computed} from 'vue'
    import { setupMirror, mirrorProps, mirrorEmits } from '@/composables/setup_working_mirror';

    const props = defineProps({...mirrorProps, ...{
        type: {
            type: String,
            default: 'String',
            validator (value) {
                return ['String', 'Array', 'Object', 'Number'].includes(value)
            }
        }
    }});
    const emits = defineEmits(mirrorEmits);
    const {workingCopy} = setupMirror(props, {emit: emits})

    const newItem = ref();

    const addToList = () => {
        if (!Array.isArray(workingCopy.value)) {
            workingCopy.value = [];
        }
        if (!newItem.value || newItem.value.trim() == '') {
            return;
        }
        workingCopy.value.push(newItem.value.trim());
        newItem.value = null;
    }

    const removeItem = (idx) => {
        workingCopy.value.splice(idx, 1);
    }

    const watchForAddToList = debounce(addToList, 500)

</script>

<template>
    <div>
        <div v-if="type !== 'String'" class="text-red-800">Only Strings are currently supported.</div>
       <ul v-else class="space-y-1">
            <li v-for="item, idx in workingCopy" :key="idx">
                <input type="text" v-model="workingCopy[idx]">
                &nbsp;<remove-button @click="removeItem(idx)" />
            </li>
            <li>
                <input type="text" v-model="newItem" @keyup.enter="addToList" @keyup="watchForAddToList">
                &nbsp;<icon-add @click="addToList" class="inline-block cursor-pointer"></icon-add>
            </li>
        </ul>
    </div>
</template>