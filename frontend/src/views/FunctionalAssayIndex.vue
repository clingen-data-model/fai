<script setup>
    import { ref } from 'vue'
    import {useRouter} from 'vue-router'

    const tableFields = [
        { name: 'assay_classes',
            label: 'Class of Assay',
            resolveValue (item) {
                return item.assay_classes.map(ac => ac.name).join(', ');
            },
            sortable: true
        },
        { name: 'gene_symbol',
            label: 'Gene',
            sortable: true,
            type: String
        },
        { name: 'affiliation_id',
            label: 'Affiliation',
            sortable: true
        },
        { name: 'publication.name',
            label: 'Publication',
            resolveValue (item) {
                return item.publication.name
            },
            sortable: true
        },
        { name: 'approved', resolveValue (item) { return item.approved ? 'Yes' : 'No '} },
    ];

    const filter = ref();
    const sort = ref({
        field: tableFields[0],
        desc: false
    })

    const router = useRouter();
    const goToItem = (item) => {
        router.push({
            name: 'FunctionalAssayDetail', 
            params: {
                id: item.id
            }
        });
    }


    const getItems = async () => {
       return await api.get(props.resourceUrl)
            .then(response => response.data.data)
    }
</script>

<template>
    <ScreenTemplate>
        <template v-slot:header>
            <h1>FunctionalAssays</h1>
            <router-link :to="{name: 'FunctionalAssayCreate'}" class="btn xs">Add</router-link>
        </template>

        <CrudIndex 
            resourceUrl="/functional-assays"
            createRouteName="PublicationCreate"
            editRouteName="PublicationUpdate"
            deleteRouteName="PublicationDelete"
        >
            <template v-slot="{items}">
                <InputRow label="filter" v-model="filter" labelWidthClass="w-auto pr-2"/>
                <DataTable
                    :fields="tableFields"
                    :data="items"
                    v-model:sort="sort"
                    @rowClick="goToItem"
                    rowClass="cursor-pointer"
                    :filterTerm="filter"
                ></DataTable>
            </template>
        </CrudIndex>
    </ScreenTemplate>
</template>

<style scoped>
</style>