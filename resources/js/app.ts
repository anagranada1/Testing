import { createInertiaApp } from '@inertiajs/vue3';
import { en, es } from '@placetopay/spartan-vue/locales';
import { createI18n } from 'vue-i18n';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

const i18n = createI18n({
    locale: 'es',
    fallbackLocale: 'en',
    legacy: false,
    messages: { es, en },
});

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    progress: {
        color: '#4B5563',
    },
    withApp(app) {
        app.use(i18n);
    },
});
