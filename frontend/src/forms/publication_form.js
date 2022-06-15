import {ref} from 'vue'
import BaseEntityForm from "./base_entity_form";
import codingSystemRepo from '@/repositories/coding_system_repository'

const loadCodingSystemOptions = async () => {
    return await codingSystemRepo.query()
        .then(codingSystems => {
            return codingSystems.map(cs => {
                return {value: cs.id, label: cs.name}
            })
        })
}

export const fields = ref([]);

loadCodingSystemOptions()
    .then(result => {
        fields.value = [
            {
                name: 'title',
                type: 'text'
            },
            {
                name: 'coding_system_id',
                label: 'Coding System',
                type: 'select',
                options: result,
            },
            {
                name: 'code',
                label: 'Reference Code',
                type: 'text',
            }
        ]
        console.log(fields);
    });

export default (new BaseEntityForm(fields, '/publications'))