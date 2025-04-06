import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

import 'boxicons';
import 'material-icons/iconfont/material-icons.css';

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { library } from '@fortawesome/fontawesome-svg-core';
import { fas } from '@fortawesome/free-solid-svg-icons'; // Solid icons
import { far } from '@fortawesome/free-regular-svg-icons'; // Regular icons
import { fab } from '@fortawesome/free-brands-svg-icons'; // Brand icons

library.add(fas, far, fab);

import { initFlowbite } from 'flowbite';
import 'flowbite/dist/flowbite.css';

import PrimeVue from 'primevue/config';
import Tooltip from 'primevue/tooltip';
import Datepicker from 'primevue/datepicker';
import InputText from 'primevue/inputtext';
import Aura from '@primevue/themes/aura';

import VueApexCharts from 'vue3-apexcharts';
// import { format, isToday } from 'date-fns';

import Papa from 'papaparse';

import { createVuetify } from 'vuetify';
// import 'vuetify/styles';
import { VCalendar } from 'vuetify/labs/VCalendar';

const vuetify = createVuetify({
    components: {
        VCalendar, 
    },
    theme: {
        defaultTheme: 'customTheme',
        themes: {
          customTheme: {
            colors: {
              primary: '#003366'
            }
          }
        }
    }
});

window.Echo.channel('test-channel')
    .listen('.TestEvent', (e) => {
        console.log('Received event:', e.message);
    });


const appName = import.meta.env.VITE_APP_NAME || 'URScholar';


createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(Papa)
            .use(PrimeVue, {
                theme: {
                    preset: Aura,
                },
            })
            .use(initFlowbite)
            .use(vuetify)
            .use(VueApexCharts)
            .component('FontAwesomeIcon', FontAwesomeIcon)
            .component('apexchart', VueApexCharts)
            .directive('tooltip', Tooltip, 'datepicker', Datepicker, InputText)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
