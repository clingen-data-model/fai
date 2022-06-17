import BaseEntityForm from "./base_entity_form"
import repository from '@/repositories/assay_class_repository.js'

export const fields = [
    {
        name: 'name',
        placeholder: 'Western Blot',
        required: true
    },
    {
        name: 'description',
        type: 'large-text',
        placeholder: 'A description of the assay class.'
    }
]

export default (new BaseEntityForm(fields, repository))