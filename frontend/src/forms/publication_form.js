import {ref} from 'vue'
import BaseEntityForm from "./base_entity_form.js";
import codingSystemRepo from '@/repositories/coding_system_repository.js'
import pubRepo from '@/repositories/publication_repository.js'

const loadCodingSystemOptions = async () => {
    return await codingSystemRepo.query()
        .then(codingSystems => {
            return codingSystems.map(cs => {
                return {value: cs.id, label: cs.name}
            })
        })
}

export const fields = ref([
    {
        name: 'title',
        type: 'text'
    },
    {
        name: 'coding_system_id',
        label: 'Coding System',
        type: 'select',
        options: [],
    },
    {
        name: 'code',
        label: 'Reference Code',
        type: 'text',
    }
]);

loadCodingSystemOptions()
    .then(result => {
        const fieldIdx = fields.value.findIndex(f => f.name == 'coding_system_id');
        fields.value[fieldIdx].options = result
    });

export default (new BaseEntityForm(fields, pubRepo))