import { createInertiaApp } from '@inertiajs/vue3';
import { initializeTheme } from '@/composables/useAppearance';
import AppLayout from '@/layouts/AppLayout.vue';
import AuthLayout from '@/layouts/AuthLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';

import { createApp, h } from 'vue';

import Oruga from '@oruga-ui/oruga-next';

import { OButton, OModal } from "@oruga-ui/oruga-next";

import '/node_modules/@oruga-ui/theme-oruga/dist/theme.css';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    layout: (name) => {
        switch (true) {
            case name === 'Welcome':
                return null;
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
        const app = createApp({ render: () => h(App, props) })

            app.use(plugin)
            app.use(Oruga)
            
            app.component('o-button', OButton);
            app.component('o-modal', OModal);

            app.mount(el);
            

    },
});

// This will set light / dark mode on page load...
initializeTheme();
