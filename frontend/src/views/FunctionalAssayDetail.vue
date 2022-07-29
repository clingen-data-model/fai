<script setup>
    import {ref, onMounted} from 'vue'
    import repo from '../repositories/functional_assay_repository';
    import formDef from '../forms/functional_assay_form';
    import DataFormSection from '../components/forms/DataFormSection.vue';
    import { titleCase } from '../utils';

    const props = defineProps({
        id: {
            type: Number,
            required: true
        }
    });

    const fields = formDef.fields
    const item = ref({assay_classes: [], publication: {coding_system: {}}})
    onMounted(async () => {
        item.value = await repo.find(props.id)
    })

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
                                    <span v-if="item[sectionField.name]">
                                        {{item[sectionField.name]}}
                                    </span>
                                    <span v-else class="muted">null</span>
                                    <FunctionalAssayNoteView :fieldNotes="item.field_notes" fieldName="publication_id" />
                                </td>
                            </tr>
                        </table>
                        <!-- <DictionaryRow
                            v-for="sectionField in field.contents"
                            :key="sectionField.name"
                            :label="titleCase(getLabel(sectionField))"
                            labelClass="w-60 font-bold border-r border-gray-100 pr-2"
                            class="border-b border-gray-100 pb-2"
                        >
                        </DictionaryRow> -->
                    </div>
                </DataFormSection>
            </div>
        </div>
        <!-- <DictionaryRow
            label="Assay Classes"
            labelClass="font-bold w-56 mb-2"
            class="mt-4 pb-2 border-b"
        >
            <div>
                <ul>
                    <li v-for="ac in item.assay_classes">
                        <router-link :to="{name: 'AssayClassDetail', params: {id: ac.id}}">{{ac.name}}</router-link>
                    </li>
                </ul>
                <FunctionalAssayNoteView :fieldNotes="item.field_notes" fieldName="assay_class_ids" />
            </div>
        </DictionaryRow>
        <DictionaryRow
            label="Publication"
            labelClass="font-bold w-56 mb-2"
            class="mt-4 pb-2 border-b"
        >
            <div>
                <u>{{item.publication.title}}</u>
                <br>
                {{`${item.publication.coding_system.name}:${item.publication.code}`}}
                <FunctionalAssayNoteView :fieldNotes="item.field_notes" fieldName="publication_id" />
            </div>
        </DictionaryRow>
        <ObjectDictionary
            :obj="item"
            :except="['assay_classes', 'publication', 'publication_id', 'field_notes']"
            labelClass="font-bold w-56 mb-2"
        >
            <template v-slot="{label, labelClass, rowValue, key}">
                <dictionary-row
                    :label="label"
                    :label-class="labelClass"
                    class="mt-4 pb-2 border-b"
                >
                    <div>
                        {{rowValue}}
                        <FunctionalAssayNoteView :fieldNotes="item.field_notes" :fieldName="key" />
                    </div>
                </dictionary-row>
            </template>
        </ObjectDictionary> -->
    </ScreenTemplate>
</template>
