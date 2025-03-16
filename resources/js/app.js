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
    // theme: {
    //     defaultTheme: 'customTheme',
    //     themes: {
    //         customTheme: {
    //             dark: false,
    //             fontFamily: {
    //                 poppins: [
    //                     'Poppins',
    //                     'sans-serif'
    //                 ],
    //                 cocogoose: [
    //                     'Cocogoose',
    //                     'sans-serif'
    //                 ],
    //                 onest: [
    //                     'Onest',
    //                     'sans-serif'
    //                 ],
    //                 sora: [
    //                     'Sora',
    //                     'sans-serif'
    //                 ],
    //                 inter: [
    //                     'Inter',
    //                     'sans-serif'
    //                 ],
    //                 quicksand: [
    //                     'Quicksand',
    //                     'sans-serif'
    //                 ],
    //                 albert: [
    //                     'Albert Sans',
    //                     'sans-serif'
    //                 ],
    //                 sans: [
    //                     'Instrument Sans',
    //                     'sans-serif'
    //                 ],
    //                 kanit: [
    //                     'Kanit',
    //                     'sans-serif'
    //                 ],
    //                 instrument: [
    //                     'Instrument Sans',
    //                     'sans-serif'
    //                 ]
    //             },
    //             colors: {
    //               primary: '#003366',
    //                 navy: '#3A4C7E',
    //                 lightblue: '#5BC0BE',
    //                 dirtywhite: '#F8F8FA',
    //                 myblack: '#1B1B1F',
    //                 lprimary: '#F5F7FA',
    //                 lhover: '#A3B1C6',
    //                 ltext: '#F5F7FA',
    //                 dprimary: '#0B132B',
    //                 dsecondary: '#1C2541',
    //                 dhover: '#1C2541',
    //                 dtext: '#D9E2EC',
    //                 dcontainer: '#1C2541',
    //                 dnavy: '#3A4C7E',
    //                 dlightblue: '#D2CFFE',
    //                 ddirtywhite: '#F8F8FA',
    //                 dmyblack: '#18181A',
    //                 border: 'hsl(var(--border))',
    //                 input: 'hsl(var(--input))',
    //                 ring: 'hsl(var(--ring))',
    //                 background: 'hsl(var(--background))',
    //                 foreground: 'hsl(var(--foreground))',
    //                 shadprimary: {
    //                     DEFAULT: 'hsl(var(--primary))',
    //                     foreground: 'hsl(var(--primary-foreground))'
    //                 },
    //                 secondary: {
    //                     DEFAULT: 'hsl(var(--secondary))',
    //                     foreground: 'hsl(var(--secondary-foreground))'
    //                 },
    //                 destructive: {
    //                     DEFAULT: 'hsl(var(--destructive))',
    //                     foreground: 'hsl(var(--destructive-foreground))'
    //                 },
    //                 muted: {
    //                     DEFAULT: 'hsl(var(--muted))',
    //                     foreground: 'hsl(var(--muted-foreground))'
    //                 },
    //                 accent: {
    //                     DEFAULT: 'hsl(var(--accent))',
    //                     foreground: 'hsl(var(--accent-foreground))'
    //                 },
    //                 popover: {
    //                     DEFAULT: 'hsl(var(--popover))',
    //                     foreground: 'hsl(var(--popover-foreground))'
    //                 },
    //                 card: {
    //                     DEFAULT: 'hsl(var(--card))',
    //                     foreground: 'hsl(var(--card-foreground))'
    //                 },
    //                 chart: {
    //                     '1': 'hsl(var(--chart-1))',
    //                     '2': 'hsl(var(--chart-2))',
    //                     '3': 'hsl(var(--chart-3))',
    //                     '4': 'hsl(var(--chart-4))',
    //                     '5': 'hsl(var(--chart-5))'
    //                 }
    //             },
    //         },
    //     },
    // },
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
            .component('FontAwesomeIcon', FontAwesomeIcon)
            .directive('tooltip', Tooltip, 'datepicker', Datepicker, InputText)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
