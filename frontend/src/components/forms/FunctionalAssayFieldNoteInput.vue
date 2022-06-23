<script setup>
    import {set, get} from 'lodash'
    import {ref, computed} from 'vue'
    import {setupMirror, mirrorProps, mirrorEmits} from '@/composables/setup_working_mirror'
    import utils from '@/utils.js'

    const props = defineProps({
        ...mirrorProps,
        modelValue: {
            required: true,
        },
        field: {
            required: true,
            type: Object
        }
    });

    const emit = defineEmits(mirrorEmits);

    const {workingCopy} = setupMirror(props, {emit});

    const fieldNoteValue = computed({
        get () {
            const val = get(workingCopy.value.field_notes, props.field.name);
            return val;
        },
        set (value) {
            if (get(workingCopy.value.field_notes, props.field.name) !== value) {
                set(workingCopy.value.field_notes, props.field.name, value);

                emit('update:modelValue', workingCopy.value)
            }
        }
    })

    const showEditor = ref(false);
    const initEdit = () => {
        showEditor.value = true
    }

    const endEdit = () => {
        showEditor.value = false
    }

</script>
<template>
    <div>
        <PopOver hover arrow v-if="fieldNoteValue">
            <button class="xs" @click="initEdit">
                Edit Note
            </button>
            <template v-slot:content>
                <div class="text-sm">
                    <strong>Note: </strong>{{fieldNoteValue}}
                </div>
            </template>
        </PopOver>
        <button class="xs" @click="initEdit" v-else>
            Add Note
        </button>
        <teleport to='body'>
            <ModalDialog v-model="showEditor" :title="`Edit note for ${utils.titleCase(field.label || field.name)}`">
                <textarea v-model="fieldNoteValue" class="w-full h-40"></textarea>
                <button @click="endEdit">OK</button>
            </ModalDialog>
        </teleport>
    </div>
</template>