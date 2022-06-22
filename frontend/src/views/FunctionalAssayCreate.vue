<script setup>
    import { useRoute, useRouter} from 'vue-router'
    import formDefinition from '@/forms/functional_assay_form.js'
    import assayClassForm from '@/forms/assay_class_form.js'
    import publicationForm from '@/forms/publication_form.js'
    import routeHash from '@/composables/route_hash.js'


    const route = useRoute();
    const router = useRouter();

    const {hashData, removeFromHash} = routeHash(route, router);

    const breadcrumbs = [
        {
            route: {name: 'FunctionalAssayIndex'},
            label: 'Functional Assays'
        }
    ];

    const handleNewAssayClass = (newAssayClass) => {
        if (!formDefinition.currentItem.value.assay_class_ids) {
            formDefinition.currentItem.value.assay_class_ids = [];
        }
        formDefinition.currentItem.value.assay_class_ids.push({label: newAssayClass.name, value: newAssayClass.id})
        formDefinition.loadAssayClasses();
    }

    const handleNewPublication = (newPublication) => {
        formDefinition.currentItem.value.publication_id = newPublication.id
        formDefinition.loadPublications();
    }

</script>

<template>
    <ScreenTemplate :breadcrumbs="breadcrumbs">
        <template v-slot:header>
            <h1>Add a New Functional Assay</h1>
        </template>

        <CrudCreate :formDef="formDefinition"></CrudCreate>

        <teleport to='body'> 
            <ModalDialog 
                title="Create a new Assay Class" 
                v-show="hashData['create-assay-class']"
                @closed="removeFromHash('create-assay-class')"
            >
                <CrudCreate :formDef="assayClassForm" @saved="handleNewAssayClass" />
            </ModalDialog>
            <ModalDialog title="Create a new Publication" v-show="hashData['create-publication']" @closed="removeFromHash('create-publication')">
                <CrudCreate :formDef="publicationForm" @saved="handleNewPublication" />
            </ModalDialog>
        </teleport>
    </ScreenTemplate>
</template>