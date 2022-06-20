<script setup>
    import {onMounted, ref} from 'vue'
    import repo from '@/repositories/assay_class_repository.js'

    const props = defineProps({
        id: {
            type: Number,
            required: true
        }
    });

    const assayClass = ref({});

    onMounted(async () => {
        assayClass.value = await repo.find(props.id)
    })

    const breadcrumbs = [
        {
            route: 'AssayClassIndex', 
            label: 'Assay Classes'
        }
    ]
</script>

<template>
    <ScreenTemplate :breadcrumbs="breadcrumbs">
        <template #header>
            <h1>{{assayClass.name}}</h1>
        </template>
        <ObjectDictionary :obj="assayClass" labelClass="font-bold" :only="['id', 'name', 'description']" />
    </ScreenTemplate>
</template>
