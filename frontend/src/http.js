import axios from 'axios'

export const api = axios.create({
    baseURL: '/api',
    withCredentials: true,
    headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        common: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    },

});

export const isValidationError = function (error) {
    return error.response && error.response.status == 422 && error.response.data.errors
}