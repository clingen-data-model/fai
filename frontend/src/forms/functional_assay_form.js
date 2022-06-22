import {ref, markRaw, h} from 'vue'
import {RouterLink} from 'vue-router'
import BaseEntityForm from "./base_entity_form.js";
import assayClassRepo from '@/repositories/assay_class_repository.js'
import pubRepo from '@/repositories/publication_repository.js'
import functionalAssayRepo from '@/repositories/functional_assay_repository.js'
import SearchSelect from '@/components/forms/SearchSelect.vue'


const assayClassOptions = ref([]);
const publicationOptions = ref([]);

const loadAssayClasses = async () => {
    return await assayClassRepo.query()
        .then(assayClasses => {
            assayClassOptions.value = assayClasses.map(i => {
                return {value: i.id, label: i.name}
            })
        })
}
const loadPublications = async () => {
    return await pubRepo.query()
        .then(pubs => {
            publicationOptions.value = pubs.map(i => {
                return {value: i.id, label: i.name}
            })
        })
}

export const fields = ref([
    { 
        name: 'publication_id',
        label: 'Publication',
        type: 'component',
        component: {
            component: markRaw(SearchSelect),
            options: {
                options: publicationOptions,
                labelField: 'label',
                showOnOptionsOnFocus: true,
            },
            slots: {
                additionalOption: () =>  h(
                    'a', 
                    { href: `#create-publication`, innerHTML: 'Create new Publication', class: 'btn xs' } 
                )
            }
        },
        required: true
    },
    { 
        name: 'assay_class_ids', 
        type: 'component', 
        component: {
            component: markRaw(SearchSelect),
            options: {
                options: assayClassOptions,
                labelField: 'label',
                showOnOptionsOnFocus: true,
                multiple: true
            },
            slots: {
                additionalOption: () =>  h(
                    'a', 
                    { href: `#create-assay-class`, innerHTML: 'Create new Assay Class', class: 'btn xs' } 
                )
            }
        },
        required: true
    },
    { name: 'affiliation_id', type: 'number'},
    { name: 'hgnc_id',
        label: 'Gene',
        placeholder: 'HGNC:1234',
        required: true
    },
    { 
        name: 'approved', 
        type: 'radio-group',
        options: [
            {label: 'Yes', value: 1},
            {label: 'No', value: 0},
        ]
    },
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

loadAssayClasses();
loadPublications()
export class FunctionalAssayForm extends BaseEntityForm
{
    constructor () {
        super(fields, functionalAssayRepo)
    }

    async find (id) {
        await super.find(id);
        this.currentItem.value = this.prepareLoadedData(this.currentItem.value);
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
        data.publication_id =  data.publication_id 
                                ? data.publication_id.id 
                                : data.publciation_id;

        return data;
    }

    prepareLoadedData (data) {
        data.assay_class_ids = data.assay_classes 
                                ? data.assay_classes.map(i => ({value: i.id, label: i.name}))
                                : data.assay_classes;

        data.publication_id = data.publication
                                ? {value: data.publication.id, label: data.publication.name}
                                : null
        return data;
    }

    loadAssayClasses () {
        loadAssayClasses()    
    }
    loadPublications () {
        loadPublications()    
    }
    
}

export default (new FunctionalAssayForm())