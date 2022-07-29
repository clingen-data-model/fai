<script setup>
    import { ref, watch } from 'vue'
    import { useRoute, useRouter} from 'vue-router'
    import formDefinition from '@/forms/functional_assay_form.js'
    import routeHash from '@/composables/route_hash.js'
    import AssayClassCreateDialog from './AssayClassCreateDialog.vue'
    import PublicationCreateDialog from './PublicationCreateDialog.vue'
import {api} from '../http'

    const route = useRoute();
    const router = useRouter();

    const rawImportId = ref();
    const rawImport = ref({data: {}});
    const fetchRawImport = async (id) => {
        // console.log('IMPLEMENT fetchRawImport!!');
        rawImport.value = await api.get(`/raw-imports/${id}`).then(response => response.data);
    }
    watch(() => route.query.rawImportId, (to) => {
        if (to) {
            rawImportId.value = to;
            fetchRawImport(to);
        }
    }, {immediate: true})

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

        <div class="flex space-x-8">
            <CrudCreate :formDef="formDefinition" class="w-full lg:w-1/2" :hideOptional="true"></CrudCreate>
            <!-- <div class="w-1/2 border-l pl-8" v-if="rawImportId">
                <h3>Raw Imported Data</h3>
                <ObjectDictionary :obj="rawImport.data" labelClass="font-bold" vertical />
            </div> -->
        </div>

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
