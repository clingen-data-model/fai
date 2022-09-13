<script setup>
    import {onMounted, onUnmounted} from 'vue'
    import {useRoute, useRouter} from 'vue-router'

    const props = defineProps({
        formDef: {
            type: Object,
            required: true
        },
        submitText: {
            type: String,
            default: 'Delete'
        }
    })

    const router = useRouter()
    const route = useRoute()

    const currentItem = props.formDef.currenItem

    onMounted(() => {
        props.formDef.find(route.params.id)
    })

    onUnmounted(() => {
        props.formDef.clearCurrentItem()
        props.formDef.clearErrors()
    })

    const attemptDelete = async () => {
        try {
            await props.formDef.destroy(assayClass.value);
            router.go(-1);
        } catch (e) {

        }
    }

    const handleCancel = () => {
        props.formDef.cancel();
        router.go(-1);
    }
</script>

<template>
    <div>
        <p>This cannot be undone.</p>
        <p>Are you sure you want to continue?</p>
        <ButtonRow submit-text="Delete" @submitted="attemptDelete" @cancel="handleCancel" />
    </div>
</template>
