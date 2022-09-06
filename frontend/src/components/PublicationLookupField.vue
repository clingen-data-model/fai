<script setup>
    import {ref, computed, watch} from 'vue'
    import {debounce} from 'lodash'
    import axios from 'axios'
    import PubmedCitation from './PubmedCitation.vue'

    const props = defineProps({
        modelValue: {
            type: String,
        }
    });

    const emits = defineEmits(['update:modelValue']);

    const workingCopy = computed({
        get () {
            return props.modelValue
        },
        set (value) {
            emits('update:modelValue', value)
        }
    })

    const pubInfo = ref({});
    const lookupPublication = debounce(async () => {
        console.log('lookupPublication: ',props.modelValue)
        if (!props.modelValue) {
            pubInfo.value = null;
            return;
        }
        const baseUri = 'https://eutils.ncbi.nlm.nih.gov/entrez/eutils';
        const entriesUrl = `${baseUri}/esummary.fcgi?db=pubmed&id=${props.modelValue}&retmode=json`

        pubInfo.value = await axios.get(entriesUrl)
            .then(rsp => {
                return rsp.data.result[props.modelValue]
            })
            .catch(async error => {
                console.log(error);
            });
    }, 250)

    const pubLabel = computed(() => {
        if (!pubInfo.value) {
            return '';
        }
        return `${pubInfo.value.sortfirstauthor} et. al. - ${pubInfo.value.pubdate}`
    })

    watch(() => props.modelValue, () => {
        lookupPublication()
    }, {immediate: true})

</script>

<template>
    <div class="flex space-x-2">
        <input type="text" v-model="workingCopy">
        <div>
            <PopOver hover arrow>
                <a :href="`https://pubmed.ncbi.nlm.nih.gov/${modelValue}`" target="pubmed" v-if="pubInfo">
                    <badge size="xs">{{pubLabel}}</badge>
                </a>
                <template v-slot:content>
                    <div style="width: 300px">
                        <PubmedCitation :summary="pubInfo" />
                    </div>
                </template>
            </PopOver>
        </div>
    </div>
</template>
