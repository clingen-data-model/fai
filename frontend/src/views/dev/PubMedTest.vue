<script setup>
    import { reactive, ref } from 'vue'
    import {esearch, esummary} from '@/clients/pubmed_client.js'

    const searchTerm = ref('myopathy');
    const results = ref([]);
    const searchParams = reactive({
        retmax: 20,
        retstart: 0
    })

    const initResults = () => {
        results.value = {
                            start: 0,
                            pageSize: 20,
                            count: 0,
                            results: []
                        }
    }

    const runSearch = async (term) => {
        const searchResult = await esearch(term, searchParams);
        console.log(searchResult);
        const summaryResults = await esummary(searchResult.esearchresult.idlist)

        initResults();

        results.value.start = searchResult.esearchresult.retstart
        results.value.pageSize = searchResult.esearchresult.retmax
        results.value.count = searchResult.esearchresult.count

        if (summaryResults.result) {
            results.value.results = Object.values(summaryResults.result).map(i => {
                return {
                    title: i.title,
                    sorttitle: i.sorttitle,
                    lastauthor: i.lastauthor,
                    sortfirstauthor: i.sortfirstauthor,
                    pubdate: i.pubdate,
                    source: i.source
                }
            })
        }
    }

    const resetPageAndSearch = () => {
        searchParams.retstart = 0
        runSearch(searchTerm.value)
    }

    const nextPage = () => {
        if (results.value.count >= searchParams.retmax + searchParams.retmax) {
            searchParams.retstart += searchParams.retmax
        }
        runSearch(searchTerm.value)
    }

    const prevPage = () => {
        searchParams.retstart -= searchParams.retmax
        if (searchParams.retstart < 0) {
            searchParams.retstart = 0
        }
        runSearch(searchTerm.value)
    }
</script>

<template>
    <div>
        <div class="flex space-x-2">
            <input-row v-model="searchTerm" label="Search PubMed" @keyup.enter="resetPageAndSearch"/>
            <button class="xs" @click="resetPageAndSearch">search</button>
        </div>
        <button class="xs" @click="prevPage">⬅️</button>
        <button class="xs" @click="nextPage">➡️</button>
        <hr>
        <h3>SearchParams</h3>
        <pre>{{searchParams}}</pre>
        <hr>
        <h3>Results</h3>
        <pre>{{results}}</pre>
    </div>
</template>
