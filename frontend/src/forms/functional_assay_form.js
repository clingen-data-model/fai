import {ref, markRaw} from 'vue'
import BaseEntityForm from "./base_entity_form.js";
import assayClassRepo from '@/repositories/assay_class_repository.js'
import pubRepo from '@/repositories/publication_repository.js'
import functionalAssayRepo from '@/repositories/functional_assay_repository.js'
import SearchSelect from '@/components/forms/SearchSelect.vue'

const loadAssayClasses = async () => {
    return await assayClassRepo.query()
        .then(assayClasses => {
            return assayClasses.map(i => {
                return {value: i.id, label: i.name}
            })
        })
}
const loadPublications = async () => {
    return await pubRepo.query()
        .then(pubs => {
            return pubs.map(i => {
                return {value: i.id, label: i.name}
            })
        })
}

export const fields = ref([
    { 
        name: 'assay_class_ids', 
        type: 'component', 
        component: {
            component: markRaw(SearchSelect),
            options: {
                options: [],
                labelField: 'label',
                showOnOptionsOnFocus: true,
                multiple: true
            }
        },
        required: true
    },
    { name: 'affiliation_id', type: 'number' },
    { 
        name: 'publication_id',
        label: 'Publication',
        type: 'select',
        options: [],
        required: true
    },
    { name: 'hgnc_id',
        label: 'Gene',
        placeholder: 'HGNC:1234',
        required: true
    },
    { name: 'approved', type: 'checkbox' },
    { name: 'material_used', type: 'large-text' },
    { name: 'patient_derived_material_used', type: 'large-text' },
    { name: 'description', type: 'large-text' },
    { name: 'read_out_description', type: 'large-text' },
    { name: 'range_type', type:'select', options: ['qualitative', 'quantitative'] },
    { name: 'range' },
    { name: 'normal_range' },
    { name: 'abnormal_range'},
    { name: 'indeterminate_range'},
    { name: 'validation_control_pathogenic'},
    { name: 'validation_control_benign'},
    { 
        name: 'replication', 
        type: 'large-text', 
        required: true
    },
    { 
        name: 'statistical_analysis_description', 
        type: 'large-text', 
        required: true
    },
    { name: 'significance_threshold' },
    { name: 'comment', type: 'large-text'},
    { name: 'units' },
    { name: 'field_notes', type: 'large-text', class: "hidden" },
    { name: 'assay_notes', type: 'large-text'}
]);

loadAssayClasses()
    .then(assayClasses => {
        fields.value[fields.value.findIndex(f => f.name == 'assay_class_ids')].component.options.options = assayClasses
    });

loadPublications()
    .then(publications => {
        console.log(publications)
        fields.value[fields.value.findIndex(f => f.name == 'publication_id')].options = publications
    });

export class FunctionalAssayForm extends BaseEntityForm
{
    constructor () {
        super(fields, functionalAssayRepo)
    }

    async save (data) {
        data = this.prepareDataForStore(data)
        return super.save(data); 
    }

    async update(data) {
        data = this.prepareDataForStore(data)
        return super.update(data)
    }

    prepareDataForStore (data) {
        data.assay_class_ids = data.assay_class_ids ? data.assay_class_ids.map(ac => ac.value) : undefined;

        return data;
    }
}

export default (new FunctionalAssayForm())