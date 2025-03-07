import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import { Head, Link } from '@inertiajs/vue3';

import AdminPanel from '../js/Shared/AdminPanel.vue';
import Modal from '../js/Shared/Modal.vue';
import Notification from '../js/Shared/Notification.vue';
import Confirmation from '../js/Shared/Confirmation.vue';
import ConfirmationModal from '../js/Shared/ConfirmationModal.vue';
import DeleteModal from '../js/Shared/DeleteModal.vue';
import Pagination from './Shared/Pagination.vue';
import Paginator from './Shared/Paginator.vue';
import ContentContainer from './Shared/ContentContainer.vue';
import ContentTable from './Shared/ContentTable.vue';
import FormErrorMessage from './Shared/FormErrorMessage.vue';
import DropdownOption from './Shared/DropdownOption.vue';
import PageTitle from './Shared/PageTitle.vue';
import TextEditor from './Shared/TextEditor.vue';
import DoughnutChart from './Shared/Charts/DoughnutChart.vue';
import ComplianceProgress from './Shared/Charts/ComplianceProgress.vue';
import ComplianceStatus from './Shared/Charts/ComplianceStatus.vue';
import BarChart from './Shared/Charts/BarChart.vue';
import GroupBarChart from './Shared/Charts/GroupBarChart.vue';
import AnalyticsLineChart from './Shared/Charts/AnalyticsLineChart.vue';
import LineChart from './Shared/Charts/LineChart.vue';
import ComplianceBarGraph from './Shared/Charts/ComplianceBarGraph.vue';
import DistributionChart from './Shared/Charts/DistributionChart.vue';
import PieChart from './Shared/Charts/PieChart.vue';
import ChartsContainer from './Shared/Charts/ChartsContainer.vue';
import UserManagementNav from './Shared/UserManagementNav.vue';
import TipTapEditor from './Shared/TipTapEditor.vue';
import NoSearchResult from './Shared/NoSearchResult.vue';



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
            .component('ConfirmationModal', ConfirmationModal)
            .component('DeleteModal', DeleteModal)
            .component('Pagination', Pagination)
            .component('Paginator', Paginator)
            .component('ContentContainer', ContentContainer)
            .component('ContentTable', ContentTable)
            .component('FormErrorMessage', FormErrorMessage)
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
            .component('UserManagementNav', UserManagementNav)
            .component('TipTapEditor', TipTapEditor)
            .component('NoSearchResult', NoSearchResult)
            .mount(el);
    },
    progress: false,
    
    // {
    //     color: '#5dade2',
    //     showSpinner: false,
    // },
    
});
