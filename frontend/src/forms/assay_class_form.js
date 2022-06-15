import {ref} from 'vue'
import {api, isValidationError} from '@/http'

export const errors = ref({})
export const currentItem = ref({});
export const originalItem = ref({});

export const fields = [
    {
        name: 'name'
    },
    {
        name: 'description',
        type: 'large-text',
        placeholder: 'A description of the assay class.'
    }
]

export const find = async (id) => {
    return await api.get(`/assay-classes/${id}`).then(response => {
        currentItem.value = response.data
        originalItem.value = response.data
        return response.data
    });
}

export const save = async (data) => {
    errors.value = {};
    try {
        await api.post('/assay-classes', data);
        currentItem.value = {};
    } catch (e) {
        if (isValidationError(e)) {
            errors.value = e.response.data.errors
        }
        throw e
    }
}

export const update = async (data) => {
    errors.value = {}
    try {
        currentItem.value = await api.put(`/assay-classes/${data.id}`, data)
            .then(response => response.data);
    } catch (e) {
        if (isValidationError(e)) {
            errors.value = e.response.data.errors
        }
        throw e
    }
}

export const destroy = async (currentItem) => {
    console.log(`delete assay_class with id: ${currentItem.id}`);
}

export const cancel = () => {
    clearErrors()
    if (!currentItem.value.id) {
        clearCurrentItem()
        return;
    }

    find(currentItem.value.id);
}

export const clearCurrentItem = () => {
    currentItem.value = {}
}

export const clearErrors = () => {
    errors.value = {}
}

export default {
    fields,
    errors,
    currentItem,
    originalItem,
    find,
    save,
    update,
    destroy,
    cancel,
    clearCurrentItem,
    clearErrors
}