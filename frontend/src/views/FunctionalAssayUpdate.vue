<script setup>
    import {ref, onMounted} from 'vue'
    import formDefinition from '@/forms/functional_assay_form.js'

    const props = defineProps({
        id: {
            type: Number,
            required: true
        }
    })

    const breadcrumbs = ref([
        {
            route: {name: 'FunctionalAssayIndex'},
            label: 'Functional Assay'
        },
    ]);

    onMounted(async () => {
        await formDefinition.find(props.id);

        breadcrumbs.value.push({route: {
            name: 'FunctionalAssayDetail', 
            params: {
                id: formDefinition.currentItem.value.id
            }
        }, label: 'Detail'})
    })
</script>

<template>
    <ScreenTemplate :breadcrumbs="breadcrumbs">
        <template v-slot:header>
            <h1>Edit the Functional Assay</h1>
        </template>
        <CrudCreate :formDef="formDefinition"></CrudCreate>
    </ScreenTemplate>
</template>