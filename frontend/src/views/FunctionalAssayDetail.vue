<script setup>
    import {ref, watch} from 'vue'
    import repo from '../repositories/functional_assay_repository';
    import formDef from '../forms/functional_assay_form';
    import DataFormSection from '../components/forms/DataFormSection.vue';
    import { titleCase } from '../utils';
import MarkdownBlock from '../components/MarkdownBlock.vue';

    const props = defineProps({
        id: {
            type: Number,
            required: true
        }
    });

    const fields = formDef.fields
    const item = ref({assay_classes: [], publication: {coding_system: {}}})
    watch(() => props.id, async (to) => {
        item.value = await formDef.find(to)
    }, {immediate: true})

    const breadcrumbs = [
        {
            route: {name: 'FunctionalAssayIndex'},
            label: 'Functional Assays'
        },
    ];

    const getLabel = field => field.label || field.name
</script>

<template>
    <ScreenTemplate :breadcrumbs="breadcrumbs">
        <template #header>
            <h1>Functional Assay Detail</h1>
            <router-link v-if="item.id" :to="{name: 'FunctionalAssayEdit', params: {id: item.id}}" class="btn xs">Edit</router-link>
        </template>


        <div class="bg-gray-100 p-4">
            <div v-for="field in fields">
                <DataFormSection :section="field" v-if="field.type == 'section'" class="screen-block">
                    <div class="flex flex-col">
                        <table cellpadding="6px" class="-mt-2">
                            <tr
                                class="border-b last:border-b-0"
                                v-for="sectionField in field.contents"
                                :key="sectionField.name"
                            >
                                <td class="w-60 border-r">{{titleCase(getLabel(sectionField))}}</td>
                                <td class="">
                                    <MarkdownBlock v-if="item[sectionField.name]"
                                        :markdown="sectionField.display
                                            ? sectionField.display(item[sectionField.name])
                                            : item[sectionField.name].toString()"
                                        />
                                    <span v-else class="muted">null</span>
                                    <FunctionalAssayNoteView :fieldNotes="item.field_notes" fieldName="publication_id" />
                                </td>
                            </tr>
                        </table>
                    </div>
                </DataFormSection>
            </div>
        </div>
    </ScreenTemplate>
</template>

<style>
    .markdown ul {
        @apply pl-4 list-disc;
    }
</style>
