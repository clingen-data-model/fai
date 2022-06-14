<script setup>
    import {ref, onMounted} from 'vue'
    import {api} from '@/http.js'

    const assayClasses = ref([]);

    const fetchAssayClasses = async () => {
        assayClasses.value = await api.get('/assay-classes')
                                .then(response => response.data)
    }

    onMounted(() => {
        fetchAssayClasses()
    })
</script>

<template>
    <ScreenTemplate>
        <template v-slot:header>
            <h1>Assay Classes</h1>
            <router-link :to="{name: 'AssayClassCreate'}" class="btn xs">Add Assay Class</router-link>
        </template>

        <ul class="item-list">
            <li v-for="assayClass in assayClasses" :key="assayClass.id">
                <div>
                    <h4>{{assayClass.name}}</h4>
                    <div>{{assayClass.description}}</div>
                </div>
                <ul>
                    <li><router-link :to="`/assay-classes/${assayClass.id}/edit`" class="text-xs">Edit</router-link></li>
                    <li><router-link :to="{name: 'AssayClassDelete', params: {id: assayClass.id}}" class="text-xs">Delete</router-link></li>
                </ul>
            </li>
        </ul>

    </ScreenTemplate>
</template>

<style scoped>
    .item-list > li {
        @apply flex py-2 w-full border-b justify-between
    }
</style>