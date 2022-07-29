<script setup>
    import {ref, onBeforeUpdate, h, onUpdated} from 'vue'
    import DataFormField from '@/components/forms/DataFormField.vue'
    import DataFormSection from './DataFormSection.vue'
    import mirror from '@/composables/setup_working_mirror'
    import {titleCase} from '@/utils'
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
        wrapperClass: {
            type: String || null,
            default: null
        },
        hideOptional: {
            type: Boolean,
            default: false
        }
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

    const renderElement = ({field, modelValue}) => {
        if (field.type == 'raw-component') {
            return h(
                field.component.component,
                field.component.options,
                field.component.slots
            );
        }

        return h(
            DataFormField,
            {
                field: field,
                modelValue: workingCopy.value,
                'onUpdate:modelValue': (value) => {
                    emits('update:modelValue', value)
                },
                ref: setFieldRef(),
                class: 'flex-grow',
                errors: props.errors,
            },
         )
    }

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

    const showOptional = ref(true)
    const toggleOptional = () => {
        showOptional.value = !showOptional.value
    }

    const showField = (field) => {
        if (showOptional.value) {
            return true;
        }

        if (field.required) {
            return true;
        }

        return false;
    }

</script>
<template>

    <div class="data-form">
        <div class="flex flex-row-reverse justify-between items-center mb-4" v-if="hideOptional">
            <button class="xs" @click="toggleOptional">{{showOptional ? 'Hide' : 'Show'}} Optional</button>
            <static-alert class="text-xs" variant="warning" v-show="!showOptional">
                Only showing <strong>required</strong> fields.
                <button class="link" @click="toggleOptional">Show all fields</button>
            </static-alert>
        </div>
        <div v-for="field in fields" :key="field.name">
            <DataFormSection v-if="field.type == 'section'" :section="field" class="screen-block">
                <div v-for="sectionField in field.contents.filter(f => !f.hidden)" :key="sectionField.name">
                    <div :class="wrapperClass" class="flex space-x-2 items-start" v-show="showField(sectionField)">
                        <renderElement :field="sectionField" :modelValue="workingCopy" />
                        <renderExtra :field="sectionField" :modelValue="workingCopy" />
                    </div>
                </div>
            </DataFormSection>
            <section :class="wrapperClass" class="screen-block" v-else-if="!field.hidden">
                <renderElement :field="field" :modelValue="workingCopy" v-show="showField(field)"/>
                <renderExtra :field="field" :modelValue="workingCopy" />
            </section>
        </div>
    </div>
</template>

<style scoped>
    .data-form {
        @apply bg-gray-100 p-4;
    }
</style>
