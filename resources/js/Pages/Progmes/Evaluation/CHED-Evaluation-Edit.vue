<template>

    <Head title="Evaluation Compliance Tool" />
    <page-title title="Program Evaluation" />
    <content-container :hasStickyDiv="true">
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
        <template v-slot:main-content>
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
                    <tr v-for="(item, index) in items" :key="item.id" class="hover:bg-gray-200 align-top text-base"
                        :class="{ 'bg-gray-100': index % 2 == 1 }">
                        <td class="p-2 text-justify">
                            <div class="flex flex-col">
                                <div class="font-bold">
                                    {{ item.area }}
                                </div>
                                <div>
                                    {{ item.minimumRequirement }}
                                </div>
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
                        <td class="h-32 min-w-16 p-2 relative group">
                            <textarea maxlength="5000" ref="findingsInput" v-model="item.findings" :name="'findings' + index"
                                :id="'findings' + index"
                                class="w-full min-w-md h-32 rounded border-gray-500 resize-none group custom-scrollbar"
                                placeholder="Input findings here" @input="handleFindingsInput(index, item.id)">
                                            </textarea>
                            <button @click="openFindingsEditor(index)"
                                class="absolute bottom-10 left-52 text-gray-500 hover:text-black group-focus-within:visible invisible tooltipForActions"
                                data-tooltip="Open editor">
                                <i class="fas fa-pen"></i>
                            </button>
                            <text-editor title="Findings" :showModal="isFindingsEditorOpen && textEditorIndex == index"
                                @close="closeFindingsEditor">
                                <textarea maxlength="5000" ref="findingstexteditor" v-model="item.findings"
                                    :name="'findingsEditor' + index" :id="'findingsEditor' + index"
                                    class="w-full h-32 rounded border-gray-500 resize-none group"
                                    placeholder="Input findings here" @focus="handleFindingsEditorInput(index)"
                                    @input="handleFindingsEditorInput(index, item.id)">
                                                </textarea>
                            </text-editor>
                        </td>
                        <td class="h-32 min-w-16 p-2 relative group">
                            <textarea maxlength="5000" ref="recommendationsInput" v-model="item.recommendations"
                                :name="'recommendations' + index" :id="'recommendations' + index"
                                class="w-full h-32 rounded border-gray-500 resize-none group custom-scrollbar"
                                placeholder="Input comments here" @input="handleRecommendationsInput(index, item.id)">
                                            </textarea>
                            <button @click="openRecommendationsEditor(index)"
                                class="absolute bottom-4 right-10 text-gray-500 hover:text-black group-focus-within:visible invisible tooltipForActions"
                                data-tooltip="Open editor">
                                <i class="fas fa-pen"></i>
                            </button>
                            <text-editor title="Comments/Recommendations"
                                :showModal="isRecommendationsEditorOpen && textEditorIndex == index"
                                @close="closeRecommendationsEditor">
                                <textarea maxlength="5000" ref="recommendationstexteditor" v-model="item.findings"
                                    :name="'recommendationsEditor' + index" :id="'recommendationsEditor' + index"
                                    class="w-full h-32 rounded border-gray-500 resize-none group"
                                    placeholder="Input findings here" @focus="handleRecommendationEditorInput(index)"
                                    @input="handleRecommendationEditorInput(index, item.id)">
                                                </textarea>
                            </text-editor>
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

    <Notification :message="$page.props.flash.success" type="success" />
    <Notification :message="$page.props.flash.failed" type="failed"/>
    <Notification :message="$page.props.flash.updated" type="success" />
    <Notification :message="$page.props.flash.deleted" type="success" />
    <Notification :message="$page.props.errors.link" type="failed" />
    <Notification v-show="saving" message="Saving..." type="processing" />
    <Notification v-show="archiving" message="Archiving..." type="processing" />
</template>

<script setup>

import { ref, onMounted, onUnmounted } from 'vue';
import { router } from '@inertiajs/vue3';


const props = defineProps([
    'evaluation',
    'items',
    'deficiencyReport',
    'evaluationDate',
    'evaluatedBy',
    'reviewedBy',
    'notedBy',
    'auth',
]);

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



function openFindingsEditor(index) {
    isFindingsEditorOpen.value = true;
    textEditorIndex.value = index;
}

function closeFindingsEditor() {
    isFindingsEditorOpen.value = false;
}

function openRecommendationsEditor(index) {
    isRecommendationsEditorOpen.value = true;
    textEditorIndex.value = index;
}

function closeRecommendationsEditor() {
    isRecommendationsEditorOpen.value = false;
}

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
    router.post('/ched/evaluation/update', {
        id: props.evaluation.id,
        items: props.items,
        rows: updatedRows.value,
    }, {
        onStart: () => {
            saving.value = true;
        },
        onFinish: () => {
            saving.value = false;
        },
        preserveState: false,
        preserveScroll: true,
    });
}


</script>

<script>
    import Layout from '../Shared/Layout.vue'
    export default {
        layout: Layout,
    }
    
</script>