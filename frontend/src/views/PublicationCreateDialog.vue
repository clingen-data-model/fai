<script setup>
    import {useRoute, useRouter} from 'vue-router'
    import publicationForm from '@/forms/publication_form.js'
    import faForm from '@/forms/functional_assay_form.js'
    import routeHash from '@/composables/route_hash.js'
    

    const emit = defineEmits(['saved'])

    const route = useRoute();
    const router = useRouter();

    const {removeFromHash} = routeHash(route, router);

    const handleNewPublication = (newPublication) => {
        faForm.currentItem.value.publication_id = {value: newPublication.id, label: newPublication.name}
        faForm.loadPublications();
        emit('saved', newPublication)
    }

</script>

<template>
    <ModalDialog 
        title="Create a new Publication" 
        @closed="removeFromHash('create-publication')"
    >
        <CrudCreate :formDef="publicationForm" @saved="handleNewPublication" />
    </ModalDialog>
</template>