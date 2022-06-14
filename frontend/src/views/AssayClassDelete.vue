<script setup>
    import {onMounted} from 'vue'
    import {assayClass, find, destroy, cancel} from '@/forms/assay_class_form.js'
    import {useRoute, useRouter} from 'vue-router'

    const router = useRouter()
    const route = useRoute()

    onMounted(() => {
        find(route.params.id)
    })

    const attemptDelete = async () => {
        try {
            await destroy(assayClass.value);
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
            <h1>You are about to delete the <span class="underline">{{assayClass.name}}</span> assay class.</h1>
        </template>
        <p>This cannot be undone.</p>
        <p>Are you sure you want to continue?</p>
        <ButtonRow submit-text="Delete Assay Class" @submitted="attemptDelete" @cancel="handleCancel" />
    </ScreenTemplate>
</template>