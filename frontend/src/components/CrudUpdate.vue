<script setup>
    import {ref, onMounted, onUnmounted} from 'vue'
    import {useRoute, useRouter} from 'vue-router'

    const props = defineProps({
        formDef: {
            type: Object,
            required: true
        }
    })

    const route = useRoute();
    const router = useRouter();

    const fields = props.formDef.fields
    const currentItem = props.formDef.currentItem
    const errors = props.formDef.errors

    const handleSubmission = async () => {
        try {
            await props.formDef.update(currentItem.value);
            router.go(-1);
        } catch (e) {
            throw e
        }
    }

    const handleCancel = () => {
        props.formDef.cancel();
        // router.go(-1);
    }

    const originalItem = ref({})
    onMounted(async () => {
       await props.formDef.find(route.params.id)
    })
    onUnmounted(() => {
        props.formDef.clearCurrentItem()
        props.formDef.clearErrors()
    })

</script>
<template>
    <div>
        <DataForm :fields="fields" :errors="errors" v-model="currentItem" wrapperClass="my-2" />
        <button-row submit-text="Update" @click="handleSubmission" @cancel="handleCancel"></button-row>
    </div>
</template>
