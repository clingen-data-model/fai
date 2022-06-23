<script setup>
    import {ref, onBeforeUpdate, h} from 'vue'
    import DataFormField from '@/components/forms/DataFormField.vue'
    import mirror from '@/composables/setup_working_mirror'
    import {v4 as uuid4} from 'uuid'

    const props = defineProps({
            ...mirror.props,
            errors: {
                type: Object,
                required: false,
                default: () => ({})
            },
            fields: {
                type: Array,
                required: true,
            },
        });

    const emits = defineEmits([...mirror.emits])
    const formId = uuid4()
    const fieldRefs = ref([])

    const setFieldRef = (el) => {
        fieldRefs.value.push(el)
    }

    const focus = () => {
        if (fieldRefs.value.length > 0) {
            fieldRefs.value[0].focus();
        }
    }

    const {workingCopy} = mirror.setup(props, {emit: emits});

    onBeforeUpdate(() => {
        fieldRefs.value = [];
    })

    const renderExtra = ({field, modelvalue}) => {
            let extraSlot = null;
            if (field.extraSlot) {
                extraSlot = h(
                    field.extraSlot, 
                    {
                        field: field, 
                        modelValue: workingCopy.value, 
                        'onUpdate:modelValue': (value) => {
                            emits('update:modelValue', value)
                        }
                    }
                )
            }

        return extraSlot;
    }
</script>
<template>
    
    <!-- <render /> -->
    <div class="data-form">
        <div v-for="field in fields" :key="field.name" class="sm:flex sm:space-x-2 sm:space-y-2 items-start mt-3 pb-3 border-b">
            <DataFormField
                v-model="workingCopy"
                :field="field"
                :errors="errors"
                :ref="setFieldRef"
                class="flex-grow"
            />
            <renderExtra :field="field" :modelValue="workingCopy" />
        </div>
    </div>
</template>