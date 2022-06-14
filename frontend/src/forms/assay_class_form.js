import {ref} from 'vue'
import {api, isValidationError} from '@/http'

export const errors = ref({})
export const assayClass = ref({});

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
    assayClass.value = await api.get(`/assay-classes/${id}`).then(response => response.data);
}

export const save = async (data) => {
    errors.value = {};
    try {
        await api.post('/assay-classes', data);
        assayClass.value = {};
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
        assayClass.value = await api.put(`/assay-classes/${data.id}`, data)
            .then(response => response.data);
    } catch (e) {
        if (isValidationError(e)) {
            errors.value = e.response.data.errors
        }
        throw e
    }
}

export const destroy = async (assayClass) => {
    console.log(`delete assay_class with id: ${assayClass.id}`);
}

export const cancel = () => {
    errors.value = {};
    if (!assayClass.value.id) {
        assayClass.value = {};
        return;
    }

    find(assayClass.value.id);
}

export const clearAssayClass = () => {
    assayClass.value = {}
}