<template>

    <Head title="Evaluation Compliance Tool" />
    <page-title title="Program Evaluation" />
    <content-container :hasStickyDiv="true" :hasTopMainContent="true" :hasSearch="true" :hasFilters="true">
        <template v-slot:content-title>
            <div class="w-full flex flex-col md:flex-row justify-between items-center">
                <div class="w-full flex flex-row items-center">
                    <div class="w-full flex flex-row items-center">
                        <Link href="/evaluation">
                        <button class="w-8 h-8 rounded-full hover:bg-gray-200 tooltipForActions" data-tooltip="Back"><i class="fas fa-arrow-left"></i></button>
                        </Link>
                        <div class="ml-3 font-bold">Evaluate</div>
                    </div>
                    <div class="w-full flex flex-row justify-between ml-2">
                        <div class="text-gray-500 h-10 lg:ml-1 flex items-center mr-5">
                            <div v-if="hasUpdate" class="text-red-500">
                                *Changes unsaved.
                            </div>
                            <div v-else-if="$page.props.flash.updated">
                                <i class="fas fa-check-circle text-green-500 mr-1"></i>
                                {{ $page.props.flash.updated }}
                            </div>
                            <div v-else></div>
                        </div>
                    </div>
                </div>
                <div class="w-full md:w-fit md:mt-0 mt-3 flex flex-row justify-center">
                    <div class="w-full mr-2">
                        <Link :href="'/ched/evaluation/' + evaluation.id + '/report'">
                            <button class="w-full select-none whitespace-nowrap rounded text-white bg-gray-700 hover:bg-gray-800 hover:text-white h-10 px-2">Generate Report</button>
                        </Link>
                    </div>
                    <div class="w-full">
                        <button @click="update" ref="saveBtn" :disabled="saving"
                            class="w-full select-none whitespace-nowrap cursor-pointer bg-blue-500 text-white h-10 rounded px-2">Save
                            Changes
                        </button>
                    </div>
                </div>
            </div>
        </template>
        <template v-slot:top-main-content>
            <div class="flex flex-col xl:flex-row items-center justify-between w-auto rounded border border-gray-300 bg-white my-3">
                <div class="w-full text-left p-3 md:p-5">
                    <div class="w-auto flex flex-row items-center">
                        <i class="fas fa-institution mr-3 text-blue-500"></i>
                        <div>{{ evaluation.institution_program.institution.name }}</div>
                    </div>
                    <div class="w-auto flex flex-row items-center">
                        <i class="fas fa-book mr-3 text-blue-500"></i>
                        <div>
                            {{ evaluation.institution_program.program.program }}
                            <span v-if="evaluation.institution_program.program.major"> - {{ evaluation.institution_program.program.major }}</span>
                        </div>
                    </div>
                    <div class="w-auto flex flex-row items-center">
                        <i class="fas fa-calendar-check mr-3 text-blue-500"></i>
                        <div>A.Y. {{ evaluation.effectivity }}</div>
                    </div>
                </div>
            </div>
        </template>
        <template v-slot:sticky-div>
            <div class="w-full flex items-end px-3 md:px-5 py-2">
                <div class="w-full flex flex-row justify-between ml-2">
                    <div class="text-gray-500 h-10 lg:ml-1 flex items-center mr-5">
                        <div v-if="hasUpdate" class="text-red-500">
                            <div>
                                *Changes unsaved.
                            </div>
                        </div>
                        <div v-else-if="$page.props.flash.updated">
                            <i class="fas fa-check-circle text-green-500 mr-1"></i>
                            {{ $page.props.flash.updated }}
                        </div>
                        <div v-else></div>
                    </div>
                </div>
                <div class="flex flex-row">
                    <button @click="update" ref="saveBtn" :disabled="saving"
                        class="select-none whitespace-nowrap cursor-pointer bg-blue-500 text-white px-2 w-fit h-10 rounded">Save
                        Changes
                    </button>
                </div>
            </div>
        </template>
        <template v-slot:search>
            <div class="w-full flex flex-row relative items-center">
                <i class="fa fa-search text-gray-400 absolute left-5"></i>
                <input @keydown.enter="submit" v-model="query.search" type="search" id="content-search"
                    placeholder="Search"
                    class="w-full rounded-lg border border-gray-300 indent-10 h-10 text-base placeholder-gray-400" />
            </div>
        </template>
        <template v-slot:options>
            <div class="mr-1">
                <button @click="toggleFilterModal"
                    class="w-10 h-10 hover:bg-gray-200 rounded-full text-gray-700 hover:text-blue-500 active:text-white active:bg-blue-600 tooltipForActions"
                    data-tooltip="Filters">
                    <i class="fas fa-filter"></i>
                </button>
                <Link :href="'/ched/evaluation/'+props.evaluation.id+'/evaluate'">
                <button
                    class="w-10 h-10 hover:bg-gray-200 rounded-full text-gray-700 hover:text-blue-500 active:text-white active:bg-blue-600 tooltipForActions"
                    data-tooltip="Refresh page">
                    <i class="fas fa-refresh"></i>
                </button>
                </Link>
            </div>
        </template>
        <template v-slot:main-content>
            <content-table>
                <template v-slot:table-head>
                    <th class="p-2">Area/<br>Minimum Requirement</th>
                    <th class="p-2">Self-Evaluation Status/<br>Actual
                        Situation
                    </th>
                    <th class="p-2">Evidence</th>
                    <th class="p-2">Findings</th>
                    <th class="p-2">Comments/<br>Recommendations</th>
                    <th class="p-2">Evaluation Status</th>
                </template>

                <template v-slot:table-body>
                    <tr v-if="items.data.length == 0">
                        <td colspan="6" class="p-2 py-10 items-center text-center">
                            No data
                        </td>
                    </tr>
                    <tr v-else v-for="(item, index) in items.data" :key="item.id" class="hover:bg-gray-200 align-top text-base"
                        :class="{ 'bg-gray-100': index % 2 == 1 }">
                        <td class="p-2 text-justify">
                            <div class="flex flex-col">
                                <div class="font-bold">
                                    {{ item.criteria.area }}
                                </div>
                                <div v-html="item.criteria.minimumRequirement"></div>
                            </div>
                        </td>
                        <td class="p-2 text-justify">
                            <div class="bg-gray-600 text-white w-fit rounded px-2 mb-2"
                                :class="[{ 'bg-green-600': item.selfEvaluationStatus == 'Complied' }]">
                                {{ item.selfEvaluationStatus }}
                            </div>
                            <div>
                                {{ item.actualSituation }}
                            </div>
                        </td>
                        <td class="h-auto p-2 flex flex-col justify-center">
                            <div class="flex flex-col"
                                :class="{ 'hidden': item.selfEvaluationStatus == 'Not applicable' }"
                                ref="evidenceFiles">
                                <div v-for="file in item.evidence" :key="file.id" class="w-full">
                                    <a class="px-1 text-gray-600 hover:text-blue-500 m-1 rounded" :href="file.url"
                                        target="_blank">
                                        <span v-if="file.type == 'file'">
                                            File
                                        </span>
                                        <span v-else-if="file.type == 'link'">
                                            Link
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </td>
                        <td class="h-32 min-w-[21rem] p-2 relative group">
                            <tip-tap-editor v-model="item.findings"></tip-tap-editor>
                        </td>
                        <td class="h-32 min-w-[21rem] p-2 relative group">
                            <tip-tap-editor v-model="item.recommendations" />
                        </td>
                        <td class="h-32 p-3 min-w-8">
                            <div class="w-full h-full">
                                <select ref="evaluationStatus" v-model="item.evaluationStatus"
                                    :id="'evaluationStatus' + index" :name="'evaluationStatus' + index"
                                    @change="handleChangeStatus(item.id)" class="p-1 rounded w-36 border-gray-400">
                                    <option :value="null">Select status</option>
                                    <option value="Complied">Complied</option>
                                    <option value="Not complied">Not complied</option>
                                    <option value="Not applicable">Not applicable</option>
                                </select>
                            </div>
                        </td>
                    </tr>
                </template>
            </content-table>
        </template>
    </content-container>

    <modal :showModal="showFilterModal" @close="toggleFilterModal" width="sm" height="long" title="Filters">
        <div>
            <div class="flex flex-col">
                <label for="selfEvalStatus">Self-Evaluation Status</label>
                <select v-model="query.selfEvaluationStatus" id="selfEvalStatus" class="rounded border-gray-400">
                    <option :value="null">All</option>
                    <option value="Complied">Complied</option>
                    <option value="Not complied">Not Complied</option>
                    <option value="Not applicable">Not Applicable</option>
                    <option value="No Status">No Status</option>
                </select>
            </div>
            <div class="mt-3 flex flex-col" v-show="evaluation.evaluationDate != null">
                <label for="evalStatus">Evaluation Status</label>
                <select v-model="query.evaluationStatus" id="evalStatus" class="rounded border-gray-400">
                    <option :value="null">All</option>
                    <option value="Complied">Complied</option>
                    <option value="Not complied">Not Complied</option>
                    <option value="Not applicable">Not Applicable</option>
                </select>
            </div>
        </div>
        <template v-slot:custom-button>
            <button @click="filter" class="text-white bg-green-600 hover:bg-green-700 w-20 rounded h-10">
                <span v-if="processing">
                    <i class="fas fa-spinner animate-spin"></i>
                </span>
                <span v-else>Apply</span>
            </button>
        </template>
    </modal>
    <pre>
        {{ items.data.length }}
    </pre>
</template>

<script setup>

import { ref, onMounted, onUnmounted, reactive, computed } from 'vue';
import { router, useForm } from '@inertiajs/vue3';

const props = defineProps([
    'evaluation',
    'items',
    'deficiencyReport',
    'evaluationDate',
    'evaluatedBy',
    'reviewedBy',
    'notedBy',
    'auth',
    'filters',
]);

const itemsArray = computed(() => {
    return props.items.data.map(item => ({
        id: item.id,
        findings: item.findings,
        recommendations: item.recommendations,
        evaluationStatus: item.evaluationStatus,
    }));
});



const form = reactive({
    id: ref(props.evaluation.id),
    items: ref(itemsArray),
    });

const handleBeforeUnload = (event) => {
    if (hasUpdate.value) {
        event.returnValue = true;
    }
};


onMounted(() => {
    window.addEventListener('keydown', handleKeyDown);
    window.addEventListener('beforeunload', handleBeforeUnload);
});

onUnmounted(() => {
    window.removeEventListener('keydown', handleKeyDown);
    window.removeEventListener('beforeunload', handleBeforeUnload);
});


const isFindingsEditorOpen = ref(false);
const isRecommendationsEditorOpen = ref(false);
const findingstexteditor = ref([]);
const recommendationstexteditor = ref([]);
const textEditorIndex = ref('');
const reportModal = ref(false);

const findingsInput = ref([]);
const recommendationsInput = ref([]);
const evaluationStatus = ref([]);
const actionButtons = ref([]);
const evidenceFiles = ref([]);
const hasUpdate = ref(false);


const saving = ref(false);
const saveBtn = ref(null);
const updatedRows = ref([]);


const refs = { findingsInput, evaluationStatus, actionButtons, evidenceFiles, saveBtn, findingstexteditor, recommendationstexteditor };


function handleFindingsInput(index, id) {
    let textarea = findingsInput.value[index];
    textarea.style.height = "128px";
    let newHeight = textarea.scrollHeight + "px";
    let maxHeight = 384;
    textarea.style.height = Math.min(maxHeight, Math.max(128, parseInt(newHeight))) + "px";
    hasUpdate.value = true;
    getRowsWithUpdates(id);
}

function handleRecommendationsInput(index, id) {
    let textarea = recommendationsInput.value[index];
    textarea.style.height = "128px";
    let newHeight = textarea.scrollHeight + "px";
    let maxHeight = 384;
    textarea.style.height = Math.min(maxHeight, Math.max(128, parseInt(newHeight))) + "px";
    hasUpdate.value = true;
    getRowsWithUpdates(id);
}

function handleFindingsEditorInput(index, id) {
    let textEditor = refs.findingstexteditor.value[index];
    textEditor.style.height = "128px";
    let newHeight = textEditor.scrollHeight + "px";
    let maxHeight = 384;
    textEditor.style.height = Math.min(maxHeight, Math.max(128, parseInt(newHeight))) + "px";
    hasUpdate.value = true;
    getRowsWithUpdates(id);
}

function handleRecommendationEditorInput(index, id) {
    let textEditor = refs.recommendationstexteditor.value[index];
    textEditor.style.height = "128px";
    let newHeight = textEditor.scrollHeight + "px";
    let maxHeight = 384;
    textEditor.style.height = Math.min(maxHeight, Math.max(128, parseInt(newHeight))) + "px";
    hasUpdate.value = true;
    getRowsWithUpdates(id);
}

function getRowsWithUpdates(id) {
    if (!updatedRows.value.includes(id)) {
        updatedRows.value.push(id);
    }
}

function handleChangeStatus(id) {
    hasUpdate.value = true;
    getRowsWithUpdates(id);
};



// CTRL + S function
const handleKeyDown = (event) => {
    if (event.ctrlKey || event.metaKey) {
        if (event.key === 's' || event.key === 'S') {
            event.preventDefault();
            refs.saveBtn.value.click();
        }
    }
};


function update() {
    router.post('/ched/evaluation/update', form, {
        replace: true,
        preserveState: false,
        preserveScroll: true,
    });
}


const processing = ref(false);
const showFilterModal = ref(false);

const query = useForm({
    search: props.filters.search,
    selfEvaluationStatus: props.filters.selfEvaluationStatus != null ? props.filters.selfEvaluationStatus : null,
    evaluationStatus: props.filters.evaluationStatus != null ? props.filters.evaluationStatus : null,
});

function toggleFilterModal() {
    showFilterModal.value = !showFilterModal.value;
}

function submit() {
    query.get("/ched/evaluation/"+props.evaluation.id+"/evaluate", {
        onStart: () => {
            processing.value = true;
        },
        onFinish: () => {
            processing.value = false;
        },
        preserveState: false,
        preserveScroll: true,
    });
}

function filter() {
    query.get("/ched/evaluation/"+props.evaluation.id+"/evaluate", {
        onStart: () => {
            processing.value = true;
        },
        onFinish: () => {
            processing.value = false;
            toggleFilterModal();
        },
        preserveState: true,
        preserveScroll: true,
    });
}


</script>

<script>
    import Layout from '@/Shared/Layout.vue'
    export default {
        layout: Layout,
    }
    
</script>