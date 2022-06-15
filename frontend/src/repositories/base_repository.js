import { api, isValidationError } from '@/http'

export default class BaseRepository {
    constructor (baseUrl) {
        this.baseUrl = baseUrl
    }

    query () {
        return api.get(this.baseUrl).then(response => response.data)
    }

    find (id) {
        return api.get(`${this.baseUrl}/${id}`).then(response => response.data)
    }

     save (data) {
        return api.post(this.baseUrl, data).then(response => response.data)
    }
    
     update (data) {
        return api.put(`${this.baseUrl}/${data.id}`, data).then(response => response.data)
    }
    
     destroy (item) {
        return api.delete(`${this.baseUrl}/${data.id}`)
    }}