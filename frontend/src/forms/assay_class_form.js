import BaseEntityForm from "./base_entity_form"

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

export default (new BaseEntityForm(fields, '/assay-classes'))