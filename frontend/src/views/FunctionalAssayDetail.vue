<script setup>
    import {ref, onMounted, onUnmounted, computed} from 'vue'
    import repo from '../repositories/functional_assay_repository';

    const props = defineProps({
        id: {
            type: Number,
            required: true
        }
    });

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
</script>

<template>
    <ScreenTemplate :breadcrumbs="breadcrumbs">
        <template #header>
            <h1>Functional Assay Detail</h1>
            <router-link v-if="item.id" :to="{name: 'FunctionalAssayEdit', params: {id: item.id}}" class="btn xs">Edit</router-link>
        </template>
        <DictionaryRow
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
        </ObjectDictionary>
    </ScreenTemplate>
</template>
