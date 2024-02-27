import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import { Head, Link } from '@inertiajs/vue3';

import AdminPanel from '../js/Pages/Progmes/Shared/AdminPanel.vue';
import Modal from '../js/Pages/Progmes/Shared/Modal.vue';
import Notification from '../js/Pages/Progmes/Shared/Notification.vue';
import Confirmation from '../js/Pages/Progmes/Shared/Confirmation.vue';
import Pagination from './Pages/Progmes/Shared/Pagination.vue';
import ContentContainer from './Pages/Progmes/Shared/ContentContainer.vue';
import ContentTable from './Pages/Progmes/Shared/ContentTable.vue';
import ContentTableAction from './Pages/Progmes/Shared/ContentTableAction.vue';
import FormErrorMessage from './Pages/Progmes/Shared/FormErrorMessage.vue';
import SubmitButton from './Pages/Progmes/Shared/SubmitButton.vue';
import DropdownOption from './Pages/Progmes/Shared/DropdownOption.vue';
import PageTitle from './Pages/Progmes/Shared/PageTitle.vue';
import TextEditor from './Pages/Progmes/Shared/TextEditor.vue';
import DoughnutChart from './Pages/Progmes/Shared/Charts/DoughnutChart.vue';


const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} | ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .component('Head', Head)
            .component('Link', Link)
            .component('AdminPanel', AdminPanel)
            .component('Modal', Modal)
            .component('Notification', Notification)
            .component('Confirmation', Confirmation)
            .component('Pagination', Pagination)
            .component('ContentContainer', ContentContainer)
            .component('ContentTable', ContentTable)
            .component('FormErrorMessage', FormErrorMessage)
            .component('ContentTableAction', ContentTableAction)
            .component('SubmitButton', SubmitButton)
            .component('DropdownOption', DropdownOption)
            .component('PageTitle', PageTitle)
            .component('TextEditor', TextEditor)
            .component('DoughnutChart', DoughnutChart)
            .mount(el);
    },
    progress: {
        color: '#0047AB',
        showSpinner: false,
    },
});
