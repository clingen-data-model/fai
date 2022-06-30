<script setup>
    import {h, ref} from 'vue';
    import StaticAlert from '../../components/StaticAlert.vue';
    import SearchSelect from '../../components/forms/SearchSelect.vue';

    const fields = [
        { name: 'first_name',
            type: 'string',
            required: true
        },
        { name: 'last_name',
            type: 'string',
            required: true
        },
        {  name: 'honorific',
            type: 'component',
            component: {
                component: SearchSelect,
                options: {
                    options: [
                        'Mr.', 
                        'Ms.', 
                        'Mrs.', 
                        'Dr.', 
                        'Prof.',
                    ],
                    labelField: 'name',
                    showOptionsOnFocus: true,
                },
                slots: {
                    additionalOption: () => {
                        return h('div', {innerHTML: 'test'})
                    }
                }
            }
        },
        { name: 'info-alert',
            label: '',
            type: 'raw-component',
            component: {
                component: StaticAlert,
                slots: {
                    default: () => 'test'
                },
            }

        },
        { name: 'checkit!',
            type: 'checkbox'
        },
        { name: 'bad_options',
            label: 'Really?',
            type: 'radio-group',
            options: [{label: 'bad 1', value: 1}, {label: 'other', value: 2}]
        },
        { name: 'sometimes',
            label: '',
            show: (model) => {
                return model.value.bad_options == 2
            }
        }
    ]

    const model = ref({});
    const errors = ref({})
</script>

<template>
    <ScreenTemplate title="Example Form">
        <div class="flex items-start space-x-8">
            <DataForm 
                :fields="fields" 
                v-model="model" 
                :errors="errors"
                class="w-1/2 flex-grow-1"
                wrapperClass="my-4"
            />
            <div>
                <h2>ModelValue</h2>
                <pre class="flex-grow-1">{{model}}</pre>
            </div>
        </div>
    </ScreenTemplate>
</template>
