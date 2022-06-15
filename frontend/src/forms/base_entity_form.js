import {ref} from 'vue';
import {api, isValidationError} from '@/http'

class BaseEntityForm {
    constructor(fields, baseUrl) {
        this.fields = fields;
        this.baseUrl = baseUrl
        
        this.currentItem = ref({})
        this.originalItem = ref({})
        this.errors = ref({})
    }

     async find (id) {
        return await api.get(`${this.baseUrl}/${id}`)
            .then(response => {
                this.currentItem.value = response.data
                this.originalItem.value = response.data
                return response.data
            });
    }

     async save (data) {
        this.clearErrors()
        try {
            await api.post(this.baseUrl, data);
            this.clearCurrentItem()
        } catch (e) {
            if (isValidationError(e)) {
                this.errors.value = e.response.data.errors
            }
            throw e
        }
    }
    
     async update (data) {
        this.clearErrors()
        try {
            this.currentItem.value = await api.put(`${this.baseUrl}/${data.id}`, data)
                .then(response => response.data);
        } catch (e) {
            if (isValidationError(e)) {
                this.errors.value = e.response.data.errors
            }
            throw e
        }
    }
    
     async destroy (item) {
        console.log(`delete assay_class with id: ${item.id}`);
    }
    
    cancel () {
        this.clearErrors()
        if (!this.currentItem.value.id) {
            this.clearCurrentItem()
            return;
        }
    }
    
    clearCurrentItem () {
        this.currentItem.value = {}
    }
    
    clearErrors () {
        this.errors.value = {}
    }
}

export default BaseEntityForm