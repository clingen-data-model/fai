<script setup>
    import {fields, errors, assayClass, save, cancel} from '@/forms/assay_class_form.js'
    import {useRouter} from 'vue-router'

    const router = useRouter()

    const showSomeErrors = () => {
        errors.value = {
            name: ['This field is required.']
        }
    }

    const handleSubmission = async () => {
        try {
            await save(assayClass.value);
            router.go(-1);
        } catch (e) {
            
        }
    }

    const handleCancel = () => {
        cancel();
        router.go(-1);
    }
</script>

<template>
    <ScreenTemplate>
        <template v-slot:header>
            <h1>Add a new Assay Class</h1>
        </template>
        <DataForm :fields="fields" :errors="errors" v-model="assayClass" />
        <ButtonRow submit-text="Save" @submitted="handleSubmission" @cancel="handleCancel" />
    </ScreenTemplate>
</template>