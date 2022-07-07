<script setup>
    import {ref, onMounted} from 'vue'
    import formDefinition from '@/forms/functional_assay_form.js'
    import AssayClassCreateDialog from './AssayClassCreateDialog.vue'
    import PublicationCreateDialog from './PublicationCreateDialog.vue'
    import { useRoute, useRouter} from 'vue-router'
    import routeHash from '@/composables/route_hash.js'

    const route = useRoute();
    const router = useRouter();

    const {hashData} = routeHash(route, router);

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
        <div class="flex space-x-8">
            <CrudUpdate :formDef="formDefinition"></CrudUpdate>
            <div class="flex-grow border-l pl-8">stuff</div>
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