<script setup>
    import {onUnmounted, useAttrs} from 'vue'
    import {useRouter} from 'vue-router'

    const router = useRouter();
    const attrs = useAttrs();

    const props = defineProps({
        formDef: {
            required: true,
            type: Object
        }
    })

    const emit = defineEmits(['canceled', 'saved']);

    const fields = props.formDef.fields
    const currentItem = props.formDef.currentItem
    const errors = props.formDef.errors

    const handleSubmission = async () => {
        props.formDef.save(currentItem.value)
            .then(newItem => {
                emit('saved', newItem);
                router.go(-1);
            });
    }

    const handleCancel = () => {
        props.formDef.cancel();
        emit('canceled');
        router.go(-1);
    }

    onUnmounted(() => {
        props.formDef.clearCurrentItem()
        props.formDef.clearErrors()
    })
</script>

<template>
    <div>
        <DataForm
            :fields="fields"
            :errors="errors"
            v-model="currentItem"
            wrapperClass="my-2 flex space-x-2 items-start"
            :hideOptional="attrs.hideOptional"
        />
        <ButtonRow submit-text="Save" @submitted="handleSubmission" @cancel="handleCancel" />
    </div>
</template>
