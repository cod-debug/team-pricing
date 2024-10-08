import './bootstrap';
import '../css/app.css';
import 'primeicons/primeicons.css';
import './style.css';
import './flags.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import PrimeVue from 'primevue/config';
import ConfirmationService from 'primevue/confirmationservice';
import DialogService from 'primevue/dialogservice';
import ToastService from 'primevue/toastservice';
import axiosPlugin from './Plugins/axios'; // Your custom axios plugin
import AppState from './Plugins/appState.js'; // Your custom app state plugin
import ThemeSwitcher from './Components/ThemeSwitcher.vue'; // Your ThemeSwitcher component
import Noir from './presets/Noir.js'; // PrimeVue custom theme preset

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });

        app.use(plugin); // Inertia.js plugin
        app.use(ZiggyVue, Ziggy); // Ziggy for routing
        app.use(axiosPlugin); // Axios plugin

        // PrimeVue setup
        app.use(PrimeVue, {
            theme: {
                preset: Noir,
                options: {
                    prefix: 'p',
                    darkModeSelector: '.p-dark',
                    cssLayer: false,
                }
            }
        });

        // Other PrimeVue services
        app.use(ConfirmationService);
        app.use(ToastService);
        app.use(DialogService);

        // Custom app state plugin
        app.use(AppState);

        // Register ThemeSwitcher globally
        app.component('ThemeSwitcher', ThemeSwitcher);

        // Mount the app to the DOM
        app.mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
