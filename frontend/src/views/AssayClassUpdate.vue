<script setup>
    import {onMounted, onUnmounted} from 'vue'
    import {assayClass, errors, find, update, cancel, fields, clearAssayClass} from '@/forms/assay_class_form.js'
    import {useRoute, useRouter} from 'vue-router'

    const route = useRoute();
    const router = useRouter();

    const handleSubmission = async () => {
        try {
            await update(assayClass.value);
            router.go(-1);
        } catch (e) {

        }
    }

    onMounted(() => {
        find(route.params.id)
    })

    onUnmounted(() => {
        clearAssayClass()
    })
</script>
<template>
    <ScreenTemplate>
        <template v-slot:header>
            <h1>Update <span class="underline">{{assayClass.name}}</span> assay class</h1>
        </template>
        <DataForm :fields="fields" :errors="errors" v-model="assayClass" />
        <button-row submit-text="Update" @click="handleSubmission" @cancel="cancel"></button-row>
    </ScreenTemplate>
</template>