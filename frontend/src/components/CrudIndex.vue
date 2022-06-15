<script setup>
    import {ref, onMounted} from 'vue'
    import {api} from '@/http.js'

    const props  = defineProps({
        resourceUrl: {
            type: String,
            requird: true
        },
        title: {
            type: String,
            required: true
        },
        addButtonLabel: {
            type: String,
            default: 'Add'
        },
        createRouteName: {
            type: String,
            required: true
        },
        editRouteName: {
            type: String,
            required: true
        },
        deleteRouteName: {
            type: String,
            required: true
        },
    })

    const items = ref([]);

    const buildEditRoute = (item) => {
        return {
            name: props.editRouteName,
            params: {id: item.id}
        }
    }
    const buildDeleteRoute = (item) => {
        return {
            name: props.deleteRouteName,
            params: {id: item.id}
        }
    }

    const getItems = async () => {
        items.value = await api.get(props.resourceUrl)
                                .then(response => response.data)
    }

    onMounted(() => {
        getItems()
    })
</script>

<template>
    <ScreenTemplate>
        <template v-slot:header>
            <h1>{{title}}</h1>
            <router-link :to="{name: createRouteName}" class="btn xs">{{addButtonLabel}}</router-link>
        </template>

        <item-list :items="items">
            <template v-slot="item">
                <div class="flex py-2 w-full border-b justify-between">
                    <div>
                        <h4>{{item.name}}</h4>
                        <div>{{item.description}}</div>
                    </div>
                    <ul>
                        <li><router-link :to="buildEditRoute(item)" class="text-xs">Edit</router-link></li>
                        <li><router-link :to="buildDeleteRoute(item)" class="text-xs">Delete</router-link></li>
                    </ul>
                </div>
            </template>
        </item-list>

    </ScreenTemplate>
</template>

<style scoped>
</style>