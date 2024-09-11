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
import Loading from '../js/Pages/Progmes/Shared/Loading.vue';
import Confirmation from '../js/Pages/Progmes/Shared/Confirmation.vue';
import ConfirmationModal from '../js/Pages/Progmes/Shared/ConfirmationModal.vue';

import DeleteModal from '../js/Pages/Progmes/Shared/DeleteModal.vue';

import Pagination from './Pages/Progmes/Shared/Pagination.vue';
import Paginator from './Pages/Progmes/Shared/Paginator.vue';
import ContentContainer from './Pages/Progmes/Shared/ContentContainer.vue';
import ContentTable from './Pages/Progmes/Shared/ContentTable.vue';
import ContentTableAction from './Pages/Progmes/Shared/ContentTableAction.vue';
import FormErrorMessage from './Pages/Progmes/Shared/FormErrorMessage.vue';
import DropdownOption from './Pages/Progmes/Shared/DropdownOption.vue';
import PageTitle from './Pages/Progmes/Shared/PageTitle.vue';
import TextEditor from './Pages/Progmes/Shared/TextEditor.vue';
import DoughnutChart from './Pages/Progmes/Shared/Charts/DoughnutChart.vue';
import ComplianceProgress from './Pages/Progmes/Shared/Charts/ComplianceProgress.vue';
import ComplianceStatus from './Pages/Progmes/Shared/Charts/ComplianceStatus.vue';
import BarChart from './Pages/Progmes/Shared/Charts/BarChart.vue';
import GroupBarChart from './Pages/Progmes/Shared/Charts/GroupBarChart.vue';
import AnalyticsLineChart from './Pages/Progmes/Shared/Charts/AnalyticsLineChart.vue';
import LineChart from './Pages/Progmes/Shared/Charts/LineChart.vue';
import ComplianceBarGraph from './Pages/Progmes/Shared/Charts/ComplianceBarGraph.vue';
import DistributionChart from './Pages/Progmes/Shared/Charts/DistributionChart.vue';
import PieChart from './Pages/Progmes/Shared/Charts/PieChart.vue';
import ChartsContainer from './Pages/Progmes/Shared/Charts/ChartsContainer.vue';



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
            .component('Loading', Loading)
            .component('ConfirmationModal', ConfirmationModal)
            .component('DeleteModal', DeleteModal)
            .component('Pagination', Pagination)
            .component('Paginator', Paginator)
            .component('ContentContainer', ContentContainer)
            .component('ContentTable', ContentTable)
            .component('FormErrorMessage', FormErrorMessage)
            .component('ContentTableAction', ContentTableAction)
            .component('DropdownOption', DropdownOption)
            .component('PageTitle', PageTitle)
            .component('TextEditor', TextEditor)
            .component('DoughnutChart', DoughnutChart)
            .component('ComplianceProgress', ComplianceProgress)
            .component('ComplianceStatus', ComplianceStatus)
            .component('BarChart', BarChart)
            .component('PieChart', PieChart)
            .component('GroupBarChart', GroupBarChart)
            .component('LineChart', LineChart)
            .component('AnalyticsLineChart', AnalyticsLineChart)
            .component('ComplianceBarGraph', ComplianceBarGraph)
            .component('DistributionChart', DistributionChart)
            .component('ChartsContainer', ChartsContainer)
            .mount(el);
    },
    progress: {
        color: '#5dade2',
        showSpinner: false,
    },
});
