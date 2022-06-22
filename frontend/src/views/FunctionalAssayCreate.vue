<script setup>
    import { useRoute, useRouter} from 'vue-router'
    import formDefinition from '@/forms/functional_assay_form.js'
    import routeHash from '@/composables/route_hash.js'
    import AssayClassCreateDialog from './AssayClassCreateDialog.vue'
    import PublicationCreateDialog from './PublicationCreateDialog.vue'


    const route = useRoute();
    const router = useRouter();

    const {hashData, removeFromHash} = routeHash(route, router);

    const breadcrumbs = [
        {
            route: {name: 'FunctionalAssayIndex'},
            label: 'Functional Assays'
        }
    ];
</script>

<template>
    <ScreenTemplate :breadcrumbs="breadcrumbs">
        <template v-slot:header>
            <h1>Add a New Functional Assay</h1>
        </template>

        <CrudCreate :formDef="formDefinition"></CrudCreate>

        <teleport to='body'> 
            <AssayClassCreateDialog 
                @saved="formDefinition.loadAssayClasses()" 
                v-model="hashData['create-assay-class']"
             />
             <PublicationCreateDialog
                @saved="formDefinition.loadPublications()"
                v-model="hashData['create-publication']"
            />
        </teleport>
    </ScreenTemplate>
</template>