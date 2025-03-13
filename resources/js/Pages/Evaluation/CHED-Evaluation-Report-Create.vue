<template>
    <Head title="Generate Report"/>
    <page-title title="Create Report"/>
    <content-container>
        <template v-slot:content-title>
            <div class="w-full  flex flex-col xl:flex-row items-center justify-between">
                <div class="w-full flex flex-row items-center">
                    <Link :href="'/ched/evaluation/'+ tool.id +'/evaluate'">
                    <button class="w-8 h-8 rounded-full hover:bg-gray-200 tooltipForActions" data-tooltip="Back"><i class="fas fa-arrow-left"></i></button>
                    </Link>
                    <div class="ml-3 font-bold">Generate Report</div>
                </div>
                <div class="w-full xl:w-auto flex flex-col md:flex-row items-center xl:mt-0 mt-3">
                    <div class="w-full items-center md:mr-2">
                        <select v-model="submitReport.type" id="selectReport" class="select-none h-10 rounded text-sm w-fit border-gray-400 hover:border-gray-500 cursor-pointer">
                            <option value="monitoring">
                                Monitoring/Evaluation
                            </option>
                            <option value="deficiency">Deficiency</option>
                        </select>
                    </div>
                    
                    <div class="flex flex-row items-center w-full md:w-auto mt-3 md:mt-0">
                        <a :href="submitReport.type == 'monitoring' ? '/report/monitoring/' + tool.id + '/'+ submitReport.orientation + '/view' : '/report/deficiency/' + tool.id + '/'+ submitReport.orientation + '/view'" target="_blank" class="mr-2">
                            <button class="select-none bg-green-500 hover:bg-green-600 text-white h-10 px-2 rounded whitespace-nowrap">
                                <i class="fas fa-eye mr-2"></i>Preview
                            </button>
                        </a>
                        <a :href="submitReport.type == 'monitoring' ? '/report/monitoring/' + tool.id + '/'+ submitReport.orientation + '/download' : '/report/deficiency/' + tool.id + '/'+ submitReport.orientation + '/download'" target="_blank" class="mr-2">
                            <button class="select-none bg-green-500 hover:bg-green-600 text-white h-10 px-2 rounded whitespace-nowrap">
                                <i class="fas fa-download mr-2"></i>Download
                            </button>
                        </a>
                        
                    <button @click="toggleOptionsModal"
                    class="w-16 h-10 mx-2 text text-white bg-gray-500 hover:bg-gray-600 rounded">
                    <i class="fas fa-cog"></i>
                </button>
                        <button ref="saveBtn" @click="generate" class="select-none bg-blue-500 hover:bg-blue-600 text-white w-24 h-10 rounded" :disabled="generatingReport">
                            Save
                        </button>
                    </div>
                </div>
            </div>
        </template>

        <template v-slot:top-button>
            <button @click="generate" class="select-none bg-blue-500 hover:bg-blue-600 text-white px-3 h-10 rounded" :disabled="generatingReport">
                Save
            </button>
        </template>
        <template v-slot:main-content>
            <div class="p-5 text-left">
            <div class="flex items-center lg:flex-row flex-col">
                <label for="institution" class="font-bold whitespace-nowrap w-full lg:mr-3 lg:w-32 text-start lg:text-right text-gray-700">HEI Name</label>
                <input type="text" id="institution" class="w-full rounded bg-gray-100 text-gray-700" :value="tool.institution_program.institution.name" disabled>
            </div>
            <div class="mt-3 flex items-center lg:flex-row flex-col">
                <label for="program" class="font-bold whitespace-nowrap w-full lg:mr-3 lg:w-32 text-start lg:text-right text-gray-700">Program</label>
                <input type="text" id="program" class="w-full rounded bg-gray-100 text-gray-700" :value="tool.institution_program.program.program" disabled>
            </div>
            <div class="mt-3 flex items-center lg:flex-row flex-col">
                <label for="effectivity" class="font-bold whitespace-nowrap w-full lg:mr-3 lg:w-32 text-start lg:text-right text-gray-700">Effectivity</label>
                <input type="text" id="effectivity" class="w-full rounded bg-gray-100 text-gray-700" :value="'A.Y. '+tool.effectivity" disabled>
            </div>
            <div class="mt-3 flex items-center lg:flex-row flex-col">
                <label for="evaluationDate" class="font-bold whitespace-nowrap w-full lg:mr-3 lg:w-32 text-start lg:text-right text-gray-700">Evaluation Date</label>
                <input type="date" id="evaluationDate" class="w-full rounded bg-gray-white text-gray-700" placeholder="Monitoring/Evaluation Date" v-model="report.evaluationDate">
            </div>
            <div class="mt-3 flex items-center lg:flex-row flex-col">
                <label for="conforme" class="font-bold whitespace-nowrap w-full lg:mr-3 lg:w-32 text-start lg:text-right text-gray-700">Conforme</label>
                <input maxlength="255" type="text" id="conforme" class="w-full lg:w-1/2 rounded lg:mr-2 lg:mb-0 mb-1 bg-gray-white text-gray-700" placeholder="Conforme" v-model="report.conforme1">
                <input maxlength="255" type="text" id="conformeTitle" class="w-full lg:w-1/2 rounded bg-gray-white text-gray-700" placeholder="Title" v-model="report.conforme1Title">
            </div>
            <div class="mt-3 flex items-center lg:flex-row flex-col">
                <label for="conforme" class="font-bold whitespace-nowrap w-full lg:mr-3 lg:w-32 text-start lg:text-right text-gray-700">Conforme</label>
                <input maxlength="255" type="text" id="conforme" class="w-full lg:w-1/2 rounded lg:mr-2 lg:mb-0 mb-1 bg-gray-white text-gray-700" placeholder="Conforme" v-model="report.conforme2">
                <input maxlength="255" type="text" id="conformeTitle" class="w-full lg:w-1/2 rounded bg-gray-white text-gray-700" placeholder="Title" v-model="report.conforme2Title">
            </div>
            <div class="mt-3 flex items-center lg:flex-row flex-col">
                <label for="evaluatedBy" class="font-bold whitespace-nowrap w-full lg:mr-3 lg:w-32 text-start lg:text-right text-gray-700">Evaluated by</label>
                <input maxlength="255" type="text" id="evaluatedBy" class="w-full lg:w-1/2 rounded lg:mr-2 lg:mb-0 mb-1 bg-gray-white text-gray-700" placeholder="Evaluated by" v-model="report.evaluatedBy">
                <input maxlength="255" type="text" id="evaluatedByTitle" class="w-full lg:w-1/2 rounded bg-gray-white text-gray-700" placeholder="Title" v-model="report.evaluatedByTitle">
            </div>
            <div class="mt-3 flex items-center lg:flex-row flex-col">
                <label for="reviewedBy" class="font-bold whitespace-nowrap w-full lg:mr-3 lg:w-32 text-start lg:text-right text-gray-700">Reviewed by</label>
                <input maxlength="255" type="text" id="reviewedBy" class="w-full lg:w-1/2 rounded lg:mr-2 lg:mb-0 mb-1 bg-gray-white text-gray-700" placeholder="Reviewed by" v-model="report.reviewedBy">
                <input maxlength="255" type="text" id="reviewedByTitle" class="w-full lg:w-1/2 rounded bg-gray-white text-gray-700" placeholder="Title" v-model="report.reviewedByTitle">
            </div>
            <div class="mt-3 flex items-center lg:flex-row flex-col">
                <label for="notedBy" class="font-bold whitespace-nowrap w-full lg:mr-3 lg:w-32 text-start lg:text-right text-gray-700">Noted by</label>
                <input maxlength="255" type="text" id="notedBy" class="w-full lg:w-1/2 rounded lg:mr-2 lg:mb-0 mb-1 bg-gray-white text-gray-700" placeholder="Noted by" v-model="report.notedBy">
                <input maxlength="255" type="text" id="notedByTitle" class="w-full lg:w-1/2 rounded bg-gray-white text-gray-700" placeholder="Title" v-model="report.notedByTitle">
            </div>
            </div>
        </template>
    </content-container>
    <modal :showModal="showOptionsModal" @close="toggleOptionsModal" width="md" height="long" title="Paper Options" :hasNoCancelButton="true">
        <div class="w-full items-center md:mr-2 mt-3 md:mt-0">
            <div class="mt-2">
                <label class="mr-2">
                    <input type="radio" v-model="submitReport.orientation" value="portrait" class="mr-1"> Portrait
                </label>
                <label>
                    <input type="radio" v-model="submitReport.orientation" value="landscape" class="mr-1"> Landscape
                </label>
            </div>
        </div>
        <template v-slot:custom-button>
            <button @click="showOptionsModal = false" class="text-white bg-green-600 hover:bg-green-700 w-20 rounded h-10">
                Done
            </button>
        </template>
    </modal>

</template>

<script setup>
import { router } from '@inertiajs/vue3';
import { ref, reactive, onMounted, onUnmounted } from 'vue';

const props = defineProps([
    'tool',
]);

const showOptionsModal = ref(false);

function toggleOptionsModal() {
    showOptionsModal.value = !showOptionsModal.value;
}

const saveBtn = ref(null);
const refs = { saveBtn };

const handleKeyDown = (event) => {
    if (event.ctrlKey || event.metaKey) {
        if (event.key === 's' || event.key === 'S') {
            event.preventDefault();
            refs.saveBtn.value.click();
        }
    }
};

onMounted(() => {
    window.addEventListener('keydown', handleKeyDown);
});

onUnmounted(() => {
    window.removeEventListener('keydown', handleKeyDown);
});


const report = reactive({
    id: ref(props.tool.id),
    evaluationDate: ref(props.tool.evaluationDate),
    conforme1: ref(props.tool.conforme1),
    conforme1Title: ref(props.tool.conforme1Title),
    conforme2: ref(props.tool.conforme2),
    conforme2Title: ref(props.tool.conforme2Title),
    evaluatedBy: ref(props.tool.evaluatedBy),
    evaluatedByTitle: ref(props.tool.evaluatedByTitle),
    reviewedBy: ref(props.tool.reviewedBy),
    reviewedByTitle: ref(props.tool.reviewedByTitle),
    notedBy: ref(props.tool.notedBy),
    notedByTitle: ref(props.tool.notedByTitle),
});

const generatingReport = ref(false);


const submitReport = reactive({
    type: 'monitoring',
    orientation: 'portrait',
    id: ref(props.tool),
});

function generate() {
    router.post('/report/generate', report, {
        onStart: () => {
            generatingReport.value = true;
        },
        onFinish: () => {
            generatingReport.value = false;
        },
        preserveScroll: true,
        preserveState: false,
    });
}

</script>


<script>
    import Layout from '@/Shared/Layout.vue'
import { router } from '@inertiajs/vue3';
    export default {
        layout: Layout,
    }
</script>