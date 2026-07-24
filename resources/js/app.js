import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import {createAppI18n} from './i18n'
import Toast from 'vue-toastification';
import "vue-toastification/dist/index.css"
import 'primeicons/primeicons.css'

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';


createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        const i18n = createAppI18n(
          props.initialPage.props.locale
        )
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(i18n)
            .use(ZiggyVue)
            .use(Toast, {
                position: "top-right",
                timeout: 5000,
                closeOnClick: true,
                pauseOnHover: true,
            })
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
