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
import Pagination from './Shared/Pagination.vue';
import Paginator from './Shared/Paginator.vue';
import ContentContainer from './Shared/ContentContainer.vue';
import ContentTable from './Shared/ContentTable.vue';
import FormErrorMessage from './Shared/Component/FormErrorMessage.vue';
import DropdownOption from './Shared/Component/DropdownOption.vue';
import NoSearchResult from './Shared/Component/NoSearchResult.vue';
import Status from './Shared/Component/Status.vue';
import PageTitle from './Shared/PageTitle.vue';
import TextEditor from './Shared/TextEditor.vue';
import DoughnutChart from './Shared/Charts/DoughnutChart.vue';
import ComplianceProgress from './Shared/Charts/ComplianceProgress.vue';
import HalfDoughnutChart from './Shared/Charts/HalfDoughnutChart.vue';
import BarChart from './Shared/Charts/BarChart.vue';
import GroupBarChart from './Shared/Charts/GroupBarChart.vue';
import AnalyticsLineChart from './Shared/Charts/AnalyticsLineChart.vue';
import LineChart from './Shared/Charts/LineChart.vue';
import ComplianceBarGraph from './Shared/Charts/ComplianceBarGraph.vue';
import DistributionChart from './Shared/Charts/DistributionChart.vue';
import PieChart from './Shared/Charts/PieChart.vue';
import ChartsContainer from './Shared/Charts/ChartsContainer.vue';
import MainContentNav from './Shared/MainContentNav.vue';
import TipTapEditor from './Shared/TipTapEditor.vue';
import TipTapFullScreen from './Shared/TipTapFullScreen.vue';
import EvaluationProgress from './Shared/Component/EvaluationProgress.vue';
import LoadingScreen from './Shared/Component/LoadingScreen.vue';
import SearchBox from './Shared/Component/SearchBox.vue';
import Options from './Shared/Component/Options.vue';
import ModalButton from './Shared/Component/ModalButton.vue';
import ActionButton from './Shared/Component/ActionButton.vue';
import FilterPageItems from './Shared/Component/FilterPageItems.vue';
import FilterDiscipline from './Shared/Component/FilterDiscipline.vue';
import FilterAcademicYear from './Shared/Component/FilterAcademicYear.vue';
import AcademicYear from './Shared/Component/AcademicYear.vue';
import FilterInstitution from './Shared/Component/FilterInstitution.vue';
import FilterProgram from './Shared/Component/FilterProgram.vue';
import FilterFormStatus from './Shared/Component/FilterFormStatus.vue';
import FilterComplianceStatus from './Shared/Component/FilterComplianceStatus.vue';
import FilterUserType from './Shared/Component/FilterUserType.vue';
import FilterUserRole from './Shared/Component/FilterUserRole.vue';
import ToolProgressDisplay from './Shared/Component/ToolProgressDisplay.vue';
import InfoCard from './Shared/Component/InfoCard.vue';

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
            .component('Pagination', Pagination)
            .component('Paginator', Paginator)
            .component('ContentContainer', ContentContainer)
            .component('ContentTable', ContentTable)
            .component('FormErrorMessage', FormErrorMessage)
            .component('DropdownOption', DropdownOption)
            .component('NoSearchResult', NoSearchResult)
            .component('Status', Status)
            .component('PageTitle', PageTitle)
            .component('TextEditor', TextEditor)
            .component('DoughnutChart', DoughnutChart)
            .component('ComplianceProgress', ComplianceProgress)
            .component('HalfDoughnutChart', HalfDoughnutChart)
            .component('BarChart', BarChart)
            .component('PieChart', PieChart)
            .component('GroupBarChart', GroupBarChart)
            .component('LineChart', LineChart)
            .component('AnalyticsLineChart', AnalyticsLineChart)
            .component('ComplianceBarGraph', ComplianceBarGraph)
            .component('DistributionChart', DistributionChart)
            .component('ChartsContainer', ChartsContainer)
            .component('MainContentNav', MainContentNav)
            .component('TipTapEditor', TipTapEditor)
            .component('EvaluationProgress', EvaluationProgress)
            .component('TipTapFullScreen', TipTapFullScreen)
            .component('LoadingScreen', LoadingScreen)
            .component('SearchBox', SearchBox)
            .component('Options', Options)
            .component('ModalButton', ModalButton)
            .component('ActionButton', ActionButton)
            .component('FilterPageItems', FilterPageItems)
            .component('FilterDiscipline', FilterDiscipline)
            .component('FilterAcademicYear', FilterAcademicYear)
            .component('AcademicYear', AcademicYear)
            .component('FilterInstitution', FilterInstitution)
            .component('FilterProgram', FilterProgram)
            .component('FilterFormStatus', FilterFormStatus)
            .component('FilterComplianceStatus', FilterComplianceStatus)
            .component('FilterUserType', FilterUserType)
            .component('FilterUserRole', FilterUserRole)
            .component('ToolProgressDisplay', ToolProgressDisplay)
            .component('InfoCard', InfoCard)
            .mount(el);
    },
    progress: false,
    
    // {
    //     color: '#5dade2',
    //     showSpinner: false,
    // },
    
});
