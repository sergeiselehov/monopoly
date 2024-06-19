import './bootstrap';
import {createApp} from "vue/dist/vue.esm-bundler";
import permissions from "./permissions.js";
import container from "../components/helpers/Container.vue";
import routes from "./routes.js";
import store from "./stores.js";
import { Chart, registerables } from 'chart.js';


//register vue
const vueApp = createApp(container)

//auto register vue components
const components = import.meta.glob('../components/*.vue', {eager: true})
Object.entries(components).forEach(([path, definition]) => {
    const componentName = path.split('/').pop().replace(/\.\w+$/, '')
    vueApp.component(componentName, definition.default)
})
const myCustomLightTheme = {
    dark: {
        background: '#FFFFFF',
        surface: '#FFFFFF',
        primary: '#6200EE',
        'primary-darken-1': '#3700B3',
        secondary: '#03DAC6',
        'secondary-darken-1': '#018786',
        error: '#B00020',
        info: '#2196F3',
        success: '#4CAF50',
        warning: '#FB8C00',
        'common-dark-blue' : '#000D39',
        'common-blue': '#005493',
        'common-light-gray': '#C8DDEB',
        'common-gray': '#AEBCC8',
        'common-green': '#64C466'
    },
    colors: {
        background: '#1A1D1F', //up
        surface: '#012F1C', //up
        primary: '#0B5838', //up green
        secondary: '#03DAC6',
        'dgreen': '#034027', //up
        'ldgreen': '#024B2D', //up
        'action-btn': '#CC1624', //up
        error: '#B00020',
        info: '#2196F3',
        success: '#4CAF50',
        warning: '#FB8C00',
        'input-bg': '#1b523b', //up
        'card-dark' : '#082A0F',//up
        'common-light-gray': '#C8DDEB',
        'common-gray': '#1E1E1E', //up
        'common-green': '#0D501B',//up
    },
}

// Vuetify configuration
import '@mdi/font/css/materialdesignicons.css'
import 'vuetify/styles'
import {createVuetify} from 'vuetify'
import * as VuetifyComponents from 'vuetify/components'
import * as VuetifyDirectives from 'vuetify/directives'
const vuetify = createVuetify({
    VuetifyComponents,
    VuetifyDirectives,
    ssr: true,
    theme: {
        defaultTheme: 'myCustomLightTheme',
        themes: {
            myCustomLightTheme,
        },
    },
})

vueApp.use(vuetify)
vueApp.use(store)
vueApp.use(permissions)
vueApp.use(routes)
vueApp.mount("#app")
