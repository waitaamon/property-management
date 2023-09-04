import './bootstrap';
import '../css/app.css';

import {createApp, h} from 'vue';
import {createInertiaApp} from '@inertiajs/vue3';
import {resolvePageComponent} from 'laravel-vite-plugin/inertia-helpers';
import {ZiggyVue} from '../../vendor/tightenco/ziggy/dist/vue.m';

import VueNumberFormat from '@coders-tm/vue-number-format'

import vSelect from "vue-select"
import 'vue-select/dist/vue-select.css';

import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";

import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({el, App, props, plugin}) {
        return createApp({render: () => h(App, props)})
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .use(Toast, {})
            .use(VueNumberFormat, {
                "prefix": "KES",
            })
            .component('date-picker', VueDatePicker)
            .component("v-select", vSelect)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
