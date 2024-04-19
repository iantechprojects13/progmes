<template>
    <Head title="Create Report"/>
    <page-title title="Create Report" />
    <div class="mt-8 md:mx-8 mx-3 flex flex-col lg:flex-row justify-between sticky top-0 z-80">
        <div>
            <Link :href="'/ched/evaluation/'+ tool.id +'/evaluate'">
            <button class="select-none w-24 h-10 border border-gray-500 rounded bg-white hover:bg-gray-700 hover:text-white">
                <i class="fas fa-arrow-left mr-2"></i>
                Back
            </button>
            </Link>
        </div>
        <div class="flex flex-col lg:flex-row lg:mt-0 mt-2 w-full lg:w-auto">
            <div class="mr-0 lg:mr-2">
                <select v-model="submitReport.type" id="selectReport" class="select-none h-10 rounded text-sm lg:w-fit w-full">
                    <option value="monitoring">
                        Monitoring/Evaluation
                    </option>
                    <option value="deficiency">Deficiency</option>
                </select>
            </div>
            <div class="mt-2 lg:mt-0" v-show="submitReport.type == 'monitoring'">
                <a :href="'/report/monitoring/' + tool.id + '/view'" target="_blank" class="underline mr-2">
                    <button class="select-none bg-blue-500 hover:bg-blue-600 text-white h-10 px-2 rounded text-sm">
                        <i class="fas fa-eye mr-2"></i>Preview
                    </button>
                </a>
                <a :href="'/report/monitoring/' + tool.id + '/download'" target="_blank">
                    <button class="select-none bg-blue-500 hover:bg-blue-600 text-white h-10 px-2 rounded text-sm">
                        <i class="fas fa-download mr-2"></i>Download
                    </button>
                </a>
            </div>
            <div v-if="submitReport.type == 'deficiency'" class="mt-2 lg:mt-0">
                <a :href="'/report/deficiency/' + tool.id + '/view'" target="_blank" class="underline mr-2">
                    <button class="select-none bg-blue-500 hover:bg-blue-600 text-white h-10 px-2 rounded text-sm">
                        <i class="fas fa-eye mr-2"></i>Preview
                    </button>
                </a>
                <a :href="'/report/deficiency/' + tool.id + '/download'" target="_blank">
                    <button class="select-none bg-blue-500 hover:bg-blue-600 text-white h-10 px-2 rounded text-sm">
                        <i class="fas fa-download mr-2"></i>Download
                    </button>
                </a>
            </div>
        </div>
    </div>
    <content-container :hasTopButton="true">
        <template v-slot:top-button>
            <button @click="generate" class="select-none bg-blue-500 hover:bg-blue-600 text-white px-3 h-10 rounded" :disabled="generatingReport">
                Save
            </button>
        </template>
        <template v-slot:main-content>
            <div class="p-5 text-left">
                <div class="flex items-center lg:flex-row flex-col">
                    <label for="institution" class="font-bold whitespace-nowrap w-full lg:mr-3 lg:w-32 text-start lg:text-right">HEI Name</label>
                    <input type="text" id="institution" class="w-full rounded bg-gray-200" :value="tool.institution_program.institution.name" disabled>
                </div>
                <div class="mt-3 flex items-center lg:flex-row flex-col">
                    <label for="program" class="font-bold whitespace-nowrap w-full lg:mr-3 lg:w-32 text-start lg:text-right">Program</label>
                    <input type="text" id="program" class="w-full rounded bg-gray-200" :value="tool.institution_program.program.program" disabled>
                </div>
                <div class="mt-3 flex items-center lg:flex-row flex-col">
                    <label for="effectivity" class="font-bold whitespace-nowrap w-full lg:mr-3 lg:w-32 text-start lg:text-right">Effectivity</label>
                    <input type="text" id="effectivity" class="w-full rounded bg-gray-200" :value="'A.Y. '+tool.effectivity" disabled>
                </div>
                <div class="mt-3 flex items-center lg:flex-row flex-col">
                    <label for="visitDate" class="font-bold whitespace-nowrap w-full lg:mr-3 lg:w-32 text-start lg:text-right">Evaluation Date</label>
                    <input type="date" id="visitDate" class="w-full rounded" placeholder="Monitoring/Evaluation Date" v-model="report.visitDate">
                </div>
                <div class="mt-3 flex items-center lg:flex-row flex-col">
                    <label for="conforme" class="font-bold whitespace-nowrap w-full lg:mr-3 lg:w-32 text-start lg:text-right">Conforme</label>
                    <input type="text" id="conforme" class="w-full lg:w-1/2 rounded lg:mr-2 lg:mb-0 mb-1" placeholder="Conforme" v-model="report.conforme">
                    <input type="text" id="conformeTitle" class="w-full lg:w-1/2 rounded" placeholder="Title" v-model="report.conformeTitle">
                </div>
                <div class="mt-3 flex items-center lg:flex-row flex-col">
                    <label for="evaluatedBy" class="font-bold whitespace-nowrap w-full lg:mr-3 lg:w-32 text-start lg:text-right">Evaluated by</label>
                    <input type="text" id="evaluatedBy" class="w-full lg:w-1/2 rounded lg:mr-2 lg:mb-0 mb-1" placeholder="Evaluated by" v-model="report.evaluatedBy">
                    <input type="text" id="evaluatedByTitle" class="w-full lg:w-1/2 rounded" placeholder="Title" v-model="report.evaluatedByTitle">
                </div>
                <div class="mt-3 flex items-center lg:flex-row flex-col">
                    <label for="reviewedBy" class="font-bold whitespace-nowrap w-full lg:mr-3 lg:w-32 text-start lg:text-right">Reviewed by</label>
                    <input type="text" id="reviewedBy" class="w-full lg:w-1/2 rounded lg:mr-2 lg:mb-0 mb-1" placeholder="Reviewed by" v-model="report.reviewedBy">
                    <input type="text" id="reviewedByTitle" class="w-full lg:w-1/2 rounded" placeholder="Title" v-model="report.reviewedByTitle">
                </div>
                <div class="mt-3 flex items-center lg:flex-row flex-col">
                    <label for="notedBy" class="font-bold whitespace-nowrap w-full lg:mr-3 lg:w-32 text-start lg:text-right">Noted by</label>
                    <input type="text" id="notedBy" class="w-full lg:w-1/2 rounded lg:mr-2 lg:mb-0 mb-1" placeholder="Noted by" v-model="report.notedBy">
                    <input type="text" id="notedByTitle" class="w-full lg:w-1/2 rounded" placeholder="Title" v-model="report.notedByTitle">
                </div>
            </div>
        </template>
    </content-container>
    <Notification :message="$page.props.flash.success" type="success" />
</template>

<script setup>
import { router } from '@inertiajs/vue3';
import { ref, reactive } from 'vue';

const props = defineProps([
    'tool',
]);


const report = reactive({
    id: ref(props.tool.id),
    visitDate: ref(props.tool.visitDate),
    conforme: ref(props.tool.conforme),
    conformeTitle: ref(props.tool.conformeTitle),
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
    import Layout from '../Shared/Layout.vue'
import { router } from '@inertiajs/vue3';
    export default {
        layout: Layout,
    }
</script>