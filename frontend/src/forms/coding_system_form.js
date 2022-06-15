import {ref} from 'vue'
import {api, isValidationError} from '@/http'

export const currentItem = ref({})
export const originalItem = ref({})
export const errors = ref({});

export const fields = [
    {
        name: 'name',
        type: 'text',
        placeholder: 'My coding system'
    },
    {
        name: 'description',
        type: 'large-text',
        placeholder: 'This coding system is the best...'
    }
]

export const find = async (id) => {
    return await api.get(`/coding-systems/${id}`)
        .then(response => {
            currentItem.value = response.data
            originalItem.value = response.data
            return response.data
        });
}

export const save = async (data) => {
    clearErrors()
    try {
        await api.post('/coding-systems', data);
        clearCurrentItem()
    } catch (e) {
        if (isValidationError(e)) {
            errors.value = e.response.data.errors
        }
        throw e
    }
}

export const update = async (data) => {
    clearErrors()
    try {
        currentItem.value = await api.put(`/coding-systems/${data.id}`, data)
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
    currentItem,
    originalItem,
    errors,
    find,
    save,
    update,
    destroy,
    cancel,
    clearCurrentItem,
    clearErrors
}