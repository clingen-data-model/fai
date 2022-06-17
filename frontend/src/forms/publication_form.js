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
    },
    {
        name: 'coding_system_id',
        label: 'Coding System',
        type: 'select',
        options: [],
        required: true,
    },
    {
        name: 'code',
        label: 'Reference Code',
        required: true,
    }
]);

loadCodingSystemOptions()
    .then(result => {
        const fieldIdx = fields.value.findIndex(f => f.name == 'coding_system_id');
        fields.value[fieldIdx].options = result
    });

export default (new BaseEntityForm(fields, pubRepo))