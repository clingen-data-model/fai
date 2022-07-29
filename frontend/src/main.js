import { createApp } from 'vue'
import App from '@/App.vue'
import router from '@/router/index.js'
import '@/assets/app.css'
import '@/assets/transitions.css'
import objectUid from '@/object_uid'
import "./assets/popper-theme.css"

const app = createApp(App)

const registerComponents = (components => {
    Object.entries(components).forEach(([path, definition]) => {
        const componentName = path.split('/').pop().replace(/\.\w+$/, '')
        app.component(componentName, definition.default)
    })
})

registerComponents(import.meta.globEager('./components/*.vue'))
registerComponents(import.meta.globEager('./components/buttons/*.vue'))
registerComponents(import.meta.globEager('./components/forms/*.vue'))
registerComponents(import.meta.globEager('./components/icons/*.vue'))

import ClickOutside from './directives/click_outside'
app.directive('click-outside', ClickOutside)
import RemainingHeight from '@/directives/remaining_height'
app.directive('remaining-height', RemainingHeight)

import PopOver from "@/components/PopOver.vue"
app.component('popper', PopOver);
app.component('popover', PopOver);


import {formatDate, formatDateTime, formatTime, addDays, yearAgo} from '@/date_utils'
import {titleCase, camelCase, snakeCase, kebabCase, sentenceCase} from '@/utils'

app.config.globalProperties.append = (path, pathToAppend) =>
  path + (path.endsWith('/') ? '' : '/') + pathToAppend

app.mixin({
        methods: {
            formatDate,
            formatDateTime,
            formatTime,
            addDays,
            yearAgo,
            titleCase,
            camelCase,
            snakeCase,
            kebabCase,
            sentenceCase
        }
    })
    .mixin(objectUid)
    .mixin({
        mounted () {
            if (this.id) {
                if (this.$route.hash) {
                    if (this.$route.hash.substr(1) == this.id) {
                        location.href = '#';
                        location.href = this.$route.hash;
                    }
                }
            }
        }
    })
    .use(router)
    .mount('#app')

