import BaseEntityForm from './base_entity_form'

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
export default (new BaseEntityForm(fields, '/coding-systems'));