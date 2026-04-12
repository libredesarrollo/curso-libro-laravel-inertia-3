import { createInertiaApp } from '@inertiajs/vue3';
import Oruga from '@oruga-ui/oruga-next';
import {
    OButton,
    OModal,
    OUpload,
    OInput,
    ONotification,
} from '@oruga-ui/oruga-next';
import { createApp, h, createSSRApp } from 'vue';
import { initializeTheme } from '@/composables/useAppearance';

import AppLayout from '@/layouts/AppLayout.vue';
import AuthLayout from '@/layouts/AuthLayout.vue';
import WebLayout from '@/layouts/WebLayout.vue';

import SettingsLayout from '@/layouts/settings/Layout.vue';

import '/node_modules/@oruga-ui/theme-oruga/dist/theme.css';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    layout: (name) => {
        switch (true) {
            case name === 'Welcome':
                return null;
            // 2. Vistas de la Web Pública (Posts, Cursos, etc.)
            // Si tus archivos están en 'Pages/Web/...' usa name.startsWith('Web/')
            case name.startsWith('blog/'):
            case name.startsWith('contact/'):
            case name.startsWith('shop/'):
            case name.startsWith('todo/'):
                return WebLayout;
            case name.startsWith('auth/'):
                return AuthLayout;
            case name.startsWith('settings/'):
            case name.startsWith('teams/'):
                return [AppLayout, SettingsLayout];
            default:
                return AppLayout;
        }
    },
    progress: {
        color: '#F00',
        includeCSS: true,
        showSpinner: true,
    },
    setup({ el, App, props, plugin }) {
        // USAMOS createSSRApp para que sea compatible con el servidor
        const app = createSSRApp({ render: () => h(App, props) });

        app.use(plugin);
        app.use(Oruga);

        app.component('o-button', OButton);
        app.component('o-modal', OModal);
        app.component('o-upload', OUpload);
        app.component('o-input', OInput);
        app.component('o-notification', ONotification);

        if (el) {
            app.mount(el);
        }
        
        // Es vital retornar la app para que el motor de SSR pueda usarla
        return app; 
    },
    // setup({ el, App, props, plugin }) {
    //     const app = createApp({ render: () => h(App, props) });

    //     app.use(plugin);
    //     app.use(Oruga);

    //     app.component('o-button', OButton);
    //     app.component('o-modal', OModal);
    //     app.component('o-upload', OUpload);
    //     app.component('o-input', OInput);
    //     app.component('o-notification', ONotification);

    //     // Solo montar si estamos en el navegador
    //     if (el) {
    //         app.mount(el);
    //         // app.mount(el as Element);
    //     }
    // },
});

// This will set light / dark mode on page load...
initializeTheme();
