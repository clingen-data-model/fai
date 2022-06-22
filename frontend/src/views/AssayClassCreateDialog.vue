<script setup>
    import {useRoute, useRouter} from 'vue-router'
    import assayClassForm from '@/forms/assay_class_form.js'
    import faForm from '@/forms/functional_assay_form.js'
    import routeHash from '@/composables/route_hash.js'

    const emit = defineEmits(['saved'])

    const route = useRoute();
    const router = useRouter();

    const {removeFromHash} = routeHash(route, router);

    const handleNewAssayClass = (newAssayClass) => {
        if (!faForm.currentItem.value.assay_class_ids) {
            faForm.currentItem.value.assay_class_ids = [];
        }
       faForm. currentItem.value.assay_class_ids.push({label: newAssayClass.name, value: newAssayClass.id})
        emit('saved', newAssayClass)
    }

</script>

<template>
    <ModalDialog 
        title="Create a new Assay Class" 
        @closed="removeFromHash('create-assay-class')"
    >
        <CrudCreate :formDef="assayClassForm" @saved="handleNewAssayClass" />
    </ModalDialog>
</template>