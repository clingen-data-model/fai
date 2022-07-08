<script setup>
    import { ref } from 'vue'
    import {useRouter} from 'vue-router'
    import setupRouterSortAndFilter from '@/composables/router_aware_sort_and_filter.js'

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
            sortable: true,
            type: String
        },
        { name: 'publication.name',
            label: 'Publication',
            resolveValue (item) {
                return item.publication.name
            },
            sortable: true,
            type: String
        },
        { 
            name: 'approved', 
            resolveValue (item) { return item.approved ? 'Yes' : 'No '},
            resolveSort (item) {
                const numericVal = item.approved ? 1 : 2;
                // console.log(numericVal);
                return numericVal;
            },
            sortable: true,
            type: Number
        },
    ];

    const {sort, filter} = setupRouterSortAndFilter({
        field: tableFields[0].name,
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