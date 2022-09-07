<script setup>
    import {ref, computed, watch} from 'vue'
    import {debounce} from 'lodash'
    import axios from 'axios'
    import PubmedCitation from './PubmedCitation.vue'

    const props = defineProps({
        modelValue: {
            type: Object,
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
    const pubLoading = ref(false);
    const lookupPublication = debounce(async () => {
        if (!props.modelValue.code) {
            pubInfo.value = null;
            return;
        }
        const baseUri = 'https://eutils.ncbi.nlm.nih.gov/entrez/eutils';
        const entriesUrl = `${baseUri}/esummary.fcgi?db=pubmed&id=${props.modelValue.code}&retmode=json`

        pubLoading.value = true
        pubInfo.value = await axios.get(entriesUrl)
            .then(rsp => {
                pubLoading.value = false
                return rsp.data.result[props.modelValue.code]

            })
            .catch(async error => {
                console.log(error);
            });

        workingCopy.value.title = pubInfo.value.title;
        workingCopy.value.author = pubInfo.value.sortfirstauthor;
        workingCopy.value.year = pubInfo.value.pubdate.substring(0, 4)
    }, 250)

    const pubLabel = computed(() => {
        if (pubLoading.value) {
            return 'loading...'
        }
        if (!pubInfo.value) {
            return '';
        }
        return `${pubInfo.value.sortfirstauthor} et. al. - ${pubInfo.value.pubdate}`
    })
    const pubBadgeColor = computed(() => {
        if (pubLoading.value) {
            return 'gray';
        }

        return 'blue';
    })

    watch(() => props.modelValue, (to) => {
        console.log(to)
        if (to.title) {
            pubInfo.value = {
                title: to.title,
                sortfirstauthor: to.author,
                pubdate: to.year
            }
            return;
        }
        lookupPublication()
    }, {immediate: true})

</script>

<template>
    <div class="flex space-x-2">
        <input type="text" v-model="modelValue.code" @update:modelValue="lookupPublication">
        <div>
            <a :href="`https://pubmed.ncbi.nlm.nih.gov/${modelValue.code}`" target="pubmed" v-if="pubInfo">
                <badge size="xs" :color="pubBadgeColor">
                    {{pubLabel}}
                </badge>
            </a>
        </div>
    </div>
</template>
