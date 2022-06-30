import {ref} from 'vue'
import BaseEntityForm from "./base_entity_form.js";
import codingSystemRepo from '@/repositories/coding_system_repository.js'
import pubRepo from '@/repositories/publication_repository.js'


const codingSystems = ref([]);
const loadCodingSystemOptions = async () => {
    return await codingSystemRepo.query()
        .then(items => {
            codingSystems.value = items.map(cs => ({value: cs.id, label: cs.name}));
            return codingSystems.value
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
        options: codingSystems,
        required: true,
    },
    {
        name: 'code',
        label: 'Reference Code',
        required: true,
    }
]);

loadCodingSystemOptions();

export default (new BaseEntityForm(fields, pubRepo))