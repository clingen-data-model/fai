import {ref, markRaw, h} from 'vue'
import BaseEntityForm from "./base_entity_form.js";
import assayClassRepo from '@/repositories/assay_class_repository.js'
import pubRepo from '@/repositories/publication_repository.js'
import functionalAssayRepo from '@/repositories/functional_assay_repository.js'
import SearchSelect from '@/components/forms/SearchSelect.vue'
import FunctionalAssayFieldNoteInput from '@/components/forms/FunctionalAssayFieldNoteInput.vue'


const assayClassOptions = ref([]);
const publicationOptions = ref([]);

const loadAssayClasses = async () => {
    assayClassOptions.value = await assayClassRepo.query();
    return assayClassOptions.value;
}
const loadPublications = async () => {
    publicationOptions.value = await pubRepo.query();
    return publicationOptions.value;
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
                labelField: 'name',
                showOnOptionsOnFocus: true,
            },
            slots: {
                additionalOption: () => {
                    return h('a', { href: `#create-publication`, innerHTML: 'Create new Publication', class: 'btn xs' })
                } 
            }
        },
        required: true,
    },
    { 
        name: 'assay_class_ids', 
        label: 'Assay Classes',
        type: 'component', 
        component: {
            component: markRaw(SearchSelect),
            options: {
                options: assayClassOptions,
                labelField: 'name',
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
    { name: 'range_type', type:'select', options: ['qualitative', 'quantitative'], required: true },
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

Object.keys(fields.value).forEach(fieldKey => {
    fields.value[fieldKey].extraSlot = markRaw(FunctionalAssayFieldNoteInput)
})

loadAssayClasses();
loadPublications();
export class FunctionalAssayForm extends BaseEntityForm
{
    constructor () {
        super(fields, functionalAssayRepo)
        this.currentItem.value.field_notes = {}
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
        const clone = {...data}
        clone.assay_class_ids = clone.assay_class_ids ? clone.assay_class_ids.map(ac => ac.id) : undefined;
        clone.publication_id =  clone.publication_id 
                                ? clone.publication_id.id 
                                : clone.publciation_id;
        return clone;
    }

    prepareLoadedData (data) {
        const clone = {...data};
        clone.assay_class_ids = clone.assay_classes
                                ? clone.assay_classes.map(i => {
                                    delete(i.pivot);
                                    return i;
                                })
                                : clone.assay_classes;

        clone.publication_id = clone.publication
                                // ? {value: clone.publication.id, label: clone.publication.name}
                                // : null
        return clone;
    }

    loadAssayClasses () {
        loadAssayClasses()    
    }
    loadPublications () {
        loadPublications()    
    }
    
}

export default (new FunctionalAssayForm())