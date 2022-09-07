import {ref, markRaw, h, cloneVNode} from 'vue'
import {api} from '@/http.js'
import BaseEntityForm from "./base_entity_form.js";
import assayClassRepo from '@/repositories/assay_class_repository.js'
import pubRepo from '@/repositories/publication_repository.js'
import functionalAssayRepo from '@/repositories/functional_assay_repository.js'
import SearchSelect from '@/components/forms/SearchSelect.vue'
import FunctionalAssayFieldNoteInput from '@/components/forms/FunctionalAssayFieldNoteInput.vue'
import PublicationField from '@/components/PublicationField.vue'
import PublicationsAdditionalField from '@/components/PublicationsAdditionalField.vue'

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
        type: 'section',
        name: 'basic-info',
        label: 'Basic Information',
        contents: [
            { name: 'affiliation_id',
                type: 'number',
                required: true
            },
            { name: 'gene',
                label: 'Gene',
                placeholder: 'HGNC:1234',
                type: 'component',
                component: {
                    component: markRaw(SearchSelect),
                    options: {
                        searchFunction: async (searchText) => {
                            return await api.get('https://gpm.clinicalgenome.org/api/genes/search?query_string='+searchText)
                                .then(response => {
                                    return response.data.map(i => ({hgnc_id: i.hgnc_id, gene_symbol: i.gene_symbol}));
                                });
                        },
                        placeholder: 'HGNC ID or Gene Symbol',
                        keyOptionsBy: 'hgnc_id',
                        labelField: 'gene_symbol'
                    },
                },
                errorKey: 'hgnc_id',
                required: true,
                display: (val) => {
                    return `${val.gene_symbol} (HGNC:${val.hgnc_id})`
                }
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
                    showOptionsOnFocus: true,
                        multiple: true
                    },
                    slots: {
                        additionalOption: () =>  h(
                            'a',
                            { href: `#create-assay-class`, innerHTML: 'Create new Assay Class', class: 'btn xs' }
                        )
                    }
                },
                required: true,
                display: (val) => {
                    return val.map(i => i.name).join(', ')
                }
            },
            {
                name: 'publication',
                label: 'Publication',
                type: 'component',
                component: {
                    component: markRaw(PublicationField),
                },
                required: true,
                display: (val) => {
                    return val.name+' - '+val.author+', '+val.year;
                }
            },
            {
                name: 'additional_publications',
                label: 'Additional Publications',
                type: 'component',
                component: {
                    component: markRaw(PublicationsAdditionalField),
                },
                required: false,
                display: (val) => {
                    return val.map(pub => ' * '+pub.name+' - '+pub.author+', '+pub.year).join('\n')
                }
            },
            {
                name: 'approved',
                type: 'select',
                options: [
                    {label: 'Yes', value: true},
                    {label: 'No', value: false},
                ]
            },
            { name: 'description', type: 'large-text' },
            { name: 'material_used', type: 'large-text' },
            { name: 'patient_derived_material_used', type: 'large-text' },
        ]
    },
    {
        type: 'section',
        name: 'read-out',
        contents: [
            { name: 'read_out_description', type: 'large-text' },
            { name: 'units' },
            { name: 'range_type', type:'select', options: ['qualitative', 'quantitative'], required: true },
            { name: 'range' },
            { name: 'normal_range' },
            { name: 'abnormal_range'},
            { name: 'indeterminate_range'},
        ]
    },
    {
        type: 'section',
        name: 'validation-countrols',
        contents: [
            { name: 'validation_control_pathogenic'},
            { name: 'validation_control_benign'},
        ]
    },
    {
        type: 'section',
        name: 'statistical-analysis',
        contents: [
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
        ]
    },
    { name: 'field_notes', type: 'large-text', hidden: true },
    { name: 'assay_notes', type: 'large-text'}
]);

const addNoteFields = fields => {
    Object.keys(fields).forEach(fieldKey => {
        const field = fields[fieldKey];
        if (field.hidden) return;
        if (field.type == 'section') {
            addNoteFields(field.contents)
            return;
        }
        field.extraSlot = markRaw(FunctionalAssayFieldNoteInput)
    })
}

addNoteFields(fields.value);

loadAssayClasses();
loadPublications();
export class FunctionalAssayForm extends BaseEntityForm
{
    constructor () {
        super(fields, functionalAssayRepo)
        this.currentItem.value.field_notes = {}
    }

    async find (id) {
        console.log(id);
        await super.find(id);
        this.currentItem.value = this.prepareLoadedData(this.currentItem.value);

        return this.currentItem.value;
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
        console.log(clone);
        clone.assay_class_ids = clone.assay_class_ids ? clone.assay_class_ids.map(ac => ac.id) : undefined;

        clone.hgnc_id = 'HGNC:'+clone.gene.hgnc_id;
        clone.gene_symbol = clone.gene.gene_symbol;

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

        clone.gene = {
            hgnc_id: parseInt(clone.hgnc_id.substr(5)),
            gene_symbol: clone.gene_symbol
        }

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
