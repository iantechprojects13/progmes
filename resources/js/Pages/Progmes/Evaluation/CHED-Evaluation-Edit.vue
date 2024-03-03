<template>

    <Head title="Evaluation Form" />
    <div ref="containerDiv" class="h-screen overflow-y-auto">
        <page-title title="Program Evaluation" />
        <div class="md:mx-8 mx-3 mt-8 flex flex-col lg:flex-row justify-between">
            <div class="flex flex-col w-auto rounded">
                <div class="w-auto flex flex-col md:flex-row">
                    <div class="mr-2 font-bold">
                        HEI Name:
                    </div>
                    <div>
                        {{ evaluation.institution_program.institution.name }}
                    </div>
                </div>
                <div class="w-auto flex flex-col md:flex-row mt-2 md:mt-1">
                    <div class="w-auto flex flex-col md:flex-row">
                        <div class="mr-2 font-bold">
                            Program:
                        </div>
                        <div>
                            {{ evaluation.institution_program.program.program }}
                        </div>
                    </div>
                </div>
                <div class="w-auto flex flex-col md:flex-row mt-2 md:mt-1">
                    <div class="w-auto flex flex-col md:flex-row">
                        <div class="mr-2 font-bold">
                            Effectivity:
                        </div>
                        <div>
                            A.Y. {{ evaluation.effectivity }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mx-8 my-5 ">

            Progress
            <div class="relative flex flex-row">
                <div class="h-5 w-1/3 bg-blue-500"></div>
                <div class="h-5 w-2/3 bg-gray-300"></div>
            </div>
        </div>

        <content-container :hasAction="true">
            <template v-slot:channel>
                <div class="text-gray-500 h-10 lg:ml-2 flex items-center">
                    <div v-if="hasUpdate" class="text-red-500">
                        <i class="fas fa-asterisk text-xs text-red-500 mr-2"></i>
                        Changes unsaved.
                    </div>
                    <div v-else-if="!hasUpdate && updateIndicator != $page.url && updateIndicator != null">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        All changes saved.

                    </div>
                    <div v-else-if="updateIndicator == $page.url && updateIndicator != null">
                        <i class="fas fa-spinner animate-spin mr-2"></i>Saving
                    </div>
                    <div v-else></div>
                </div>
            </template>

            <template v-slot:actions>

                <div class="flex items-center justify-between z-80">
                    <div class="flex">
                        <!-- <dropdown-option position="right">
                            <template v-slot:button>
                                <button
                                    class="lg:px-3 w-16 lg:w-auto text-gray-700 border hover:text-black border-gray-500 shadow shadow-gray-500 bg-white h-10 rounded mr-1">
                                    <i class="fas fa-cog mr-2"></i>
                                    <i class="fas fa-caret-down"></i>
                                </button>
                            </template>

<template v-slot:options>
                                <div class="w-64">
                                    <div class="hover:bg-gray-100">
                                        <input type="checkbox" id="showItemNo" class="cursor-pointer mx-3">
                                        <label for="showItemNo" class="cursor-pointer ">Show item no</label>
                                    </div>
                                    <div class="flex items-center mt-3 hover:bg-gray-100">
                                        <input type="checkbox" id="merge" class="cursor-pointer mx-3">
                                        <div>
                                            <label for="merge" class="cursor-pointer ">Merge area and minimum
                                                requirement
                                                columns</label>
                                        </div>
                                    </div>
                                    <div class="flex items-center mt-3 hover:bg-gray-100">
                                        <input type="checkbox" id="showName" class="cursor-pointer mx-3">
                                        <div>
                                            <label for="showName" class="cursor-pointer ">Show evidence file
                                                name</label>
                                        </div>
                                    </div>
                                </div>
                            </template>
</dropdown-option> -->
                        <button @click="update($page.url)" ref="saveBtn"
                            class="lg:px-3 w-10 lg:w-auto h-10 rounded text-gray-200 border bg-blue-500 border-blue-500 hover:bg-blue-600 hover:text-white tooltipBottom"
                            data-tooltip="Save changes" :disabled="!hasUpdate">
                            <i class="fas fa-floppy-disk lg:mr-2"></i>
                            <span class="hidden lg:inline-block">Save</span>
                        </button>
                    </div>
                </div>
            </template>

            <template v-slot:content>
                <content-table v-show="itemsLayout === 'list'">
                    <template v-slot:table-head>
                        <!-- <th class="p-3 max-w-8">Item</th> -->
                        <th class="p-3 min-w-16 max-w-18 whitespace-normal">Area /<br>Minimum Requirement</th>
                        <th class="p-3 min-w-xs whitespace-normal">Self-Evaluation Status /<br>Actual Situation</th>
                        <th class="p-3 w-10">Evidence</th>
                        <th class="p-3 min-w-18">Findings</th>
                        <th class="p-3 min-w-18 whitespace-normal">Comments /<br>Recommendations</th>
                        <th class="p-3 whitespace-normal">Evaluation Status</th>
                    </template>

                    <template v-slot:table-body>
                        <tr v-for="(item, index) in items" :key="item.id" class="align-top"
                            :class="{ 'bg-slate-200': index % 2 == 1 }">
                            <!-- <td class="p-3 max-w-6 font-bold">
                                {{ item.itemNo }}
                            </td> -->
                            <td class="p-3 min-w-16 max-w-18 whitespace-normal text-justify">
                                <div class="flex flex-col">
                                    <div class="font-bold">
                                        {{ item.area }}
                                    </div>
                                    <div>
                                        {{ item.minimumRequirement }}
                                    </div>
                                </div>
                            </td>
                            <td class="p-3 align-top whitespace-normal min-w-18 text-justify">
                                <div class="bg-gray-600 text-white w-fit rounded px-2 mb-2"
                                    :class="[{ 'bg-green-600': item.selfEvaluationStatus == 'Complied' }]">
                                    {{ item.selfEvaluationStatus }}
                                </div>
                                <div>
                                    {{ item.actualSituation }}
                                </div>
                            </td>
                            <td class="h-auto p-3 w-10 flex flex-col justify-center">
                                <div class="flex flex-col"
                                    :class="{ 'hidden': item.selfEvaluationStatus == 'Not applicable' }"
                                    ref="evidenceFiles">
                                    <div v-for="(file, index) in item.evidence" :key="file.id" class="w-full">
                                        <a class="px-1 text-gray-600 hover:text-blue-500 m-1 rounded" :href="file.url"
                                            target="_blank">
                                            <span v-if="file.type == 'file'">
                                                <!-- <i class="fas fa-file mr-2"></i> -->
                                                File
                                            </span>
                                            <span v-else-if="file.type == 'link'">
                                                <!-- <i class="fas fa-link mr-2"></i> -->
                                                Link
                                            </span>
                                        </a>
                                    </div>
                                </div>
                            </td>
                            <td class="h-32 max-w-lg min-w-18 p-3 relative group">
                                <textarea ref="findingsInput" v-model="item.findings" :name="'findings' + index"
                                    :id="'findings' + index"
                                    class="w-full min-w-16 h-32 rounded border-gray-500 resize-none group custom-scrollbar"
                                    placeholder="Input findings here" @input="handleFindingsInput(index)">
                                </textarea>
                                <button @click="openFindingsEditor(index)"
                                    class="absolute bottom-4 right-10 text-gray-500 hover:text-black group-focus-within:visible invisible tooltipForActions"
                                    data-tooltip="Open editor">
                                    <i class="fas fa-pen"></i>
                                </button>
                                <text-editor title="Findings"
                                    :showModal="isFindingsEditorOpen && textEditorIndex == index"
                                    @close="closeFindingsEditor">
                                    <textarea ref="findingstexteditor" v-model="item.findings"
                                        :name="'findingsEditor' + index" :id="'findingsEditor' + index"
                                        class="w-full h-32 rounded border-gray-500 resize-none group"
                                        placeholder="Input findings here" @focus="handleFindingsEditorInput(index)"
                                        @input="handleFindingsEditorInput(index)">
                                    </textarea>
                                </text-editor>
                            </td>
                            <td class="h-32 max-w-lg min-w-18 p-3 relative group">
                                <textarea ref="recommendationsInput" v-model="item.recommendations"
                                    :name="'recommendations' + index" :id="'recommendations' + index"
                                    class="w-full min-w-16 h-32 rounded border-gray-500 resize-none group custom-scrollbar"
                                    placeholder="Input recommendations here" @input="handleRecommendationsInput(index)">
                                </textarea>
                                <button @click="openRecommendationsEditor(index)"
                                    class="absolute bottom-4 right-10 text-gray-500 hover:text-black group-focus-within:visible invisible tooltipForActions"
                                    data-tooltip="Open editor">
                                    <i class="fas fa-pen"></i>
                                </button>
                                <text-editor title="Comments/Recommendations"
                                    :showModal="isRecommendationsEditorOpen && textEditorIndex == index"
                                    @close="closeRecommendationsEditor">
                                    <textarea ref="recommendationstexteditor" v-model="item.findings"
                                        :name="'recommendationsEditor' + index" :id="'recommendationsEditor' + index"
                                        class="w-full h-32 rounded border-gray-500 resize-none group"
                                        placeholder="Input findings here"
                                        @focus="handleRecommendationEditorInput(index)"
                                        @input="handleRecommendationEditorInput(index)">
                                    </textarea>
                                </text-editor>
                            </td>
                            <td class="h-32 p-3">
                                <div class="w-full h-full">
                                    <select ref="evaluationStatus" v-model="item.evaluationStatus"
                                        :id="'evaluationStatus' + index" :name="'evaluationStatus' + index"
                                        @change="handleChangeStatus()" class="p-1 rounded w-36 border-gray-400">
                                        <option value="Complied">Complied</option>
                                        <option value="Not complied">Not complied</option>
                                        <option value="Not applicable">Not applicable</option>
                                    </select>
                                </div>
                            </td>
                        </tr>
                    </template>
                </content-table>


                <!-- ------ grid view ------- -->


                <!-- <div class="flex flex-col max-w-5xl mx-auto px-3 p-4" v-show="itemsLayout === 'grid'">
                <div v-for="(  item, index  ) in   items  " :key="item.id"
                    class="flex flex-col mb-5 p-2 m-2 bg-gray-200 rounded shadow-lg">
                    <div class="font-bold bg-gray-100 w-fit rounded-t p-2">
                        #{{ item.criteria.itemNo }} {{ item.criteria.area }}
                    </div>
                    <div class="bg-gray-100 p-2 px-3 rounded-b">
                        {{ item.criteria.minimumRequirement }}
                    </div>
                    <div class="bg-gray-200 p-0.5 pt-2">
                        <textarea class="w-full h-24 border-none " placeholder="Input actual situation here"></textarea>
                    </div>
                    <div class="w-full py-2 px-1">
                        <select class=" py-1 px-2 ml-1 rounded w-36 border-gray-300 bg-gray-100">
                            <option value="Not complied">Not complied</option>
                            <option value="Complied">Complied</option>
                            <option value="Not applicable">Not applicable</option>
                        </select>
                        <button class=" py-1 px-2 hover:bg-blue-600 bg-blue-500 text-white rounded">
                            <i class="fas fa-upload mr-2"></i>
                            Upload evidence
                        </button>
                    </div>
                </div>
            </div> -->


            </template>
        </content-container>
        <!-- <modal :showModal="uploadEvidenceFileModal && $page.url == uploadFileIndicator" title="Upload Evidence File"
            @close="closeEvidenceUploadModal" @upload="upload" type="uploadEvidenceFile">
            <div class="text-center pt-5">
                <input ref="uploadEvidenceFile" hidden type="file" @change="handleFileChange" id="evidence">
                <div v-if="evidenceFile" class="text-black text-left flex flex-col">
                    <div class="p-2 rounded bg-gray-200">
                        <div>File name: {{ evidenceFile.name }}</div>
                        <div>Size: {{ (evidenceFile.size / 1024).toFixed(2) }} KB</div>
                        <div class="w-full text-right">
                            <button
                                class="inline-block mr-1 py-1 px-2 border border-gray-400 rounded hover:text-orange-500 tooltipForActions"
                                data-tooltip="Change file" @click.prevent="$refs.uploadEvidenceFile.click()">
                                <i class="fas fa-refresh"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <button v-if="!evidenceFile" @click.prevent="$refs.uploadEvidenceFile.click()"
                    class="hover:text-white border border-green-700 hover:bg-green-700 py-2 px-10 rounded"><i
                        class="fas fa-cloud-upload mr-5 text-xl"></i>
                    Select file
                </button>
                <div class="mt-3 w-full text-left">
                    <form-error-message :message="$page.props.errors['evidence.file']" theme="dark" />
                </div>
            </div>
        </modal> -->

        <Notification :message="$page.props.flash.message" />
        <!-- <confirmation @close="closeReadyForVisitConfirmation"
            v-show="readyForVisitModal && readyForVisitIndicator == $page.url" title="Ready for visit">

            <template v-slot:message>
                Are you sure you want to submit this compliant tool? You won't be able to make changes after submitting.
            </template>

            <template v-slot:buttons>
                <button class=" text-gray-100 bg-blue-500 hover:text-white hover:bg-blue-600 p-2 px-3 rounded mr-1"
                    @click="readyForVisit">
                    Submit
                </button>
            </template>
        </confirmation> -->
    </div>



</template>

<script setup>
// Imports
import { ref, onMounted, onUnmounted, computed, reactive } from 'vue';
import { router } from '@inertiajs/vue3';

// Props
const props = defineProps([
    'evaluation',
    'items',
]);

// Mount, Unmount
onMounted(() => {
    window.addEventListener('keydown', handleKeyDown);
    window.onbeforeunload = function () {
        return null;
    };
});

onUnmounted(() => {
    window.removeEventListener('keydown', handleKeyDown);
    window.onbeforeunload = null;
});

// Variables
const isFindingsEditorOpen = ref(false);
const isRecommendationsEditorOpen = ref(false);
const findingstexteditor = ref([]);
const recommendationstexteditor = ref([]);
const textEditorIndex = ref('');
const readyForVisitModal = ref(false);

const findingsInput = ref([]);
const recommendationsInput = ref([]);
const evaluationStatus = ref([]);
const actionButtons = ref([]);
const evidenceFiles = ref([]);
const hasUpdate = ref(false);

const updateIndicator = ref(null);
const submitLinkIndicator = ref(null);
const readyForVisitIndicator = ref(null);
const saveBtn = ref(null);
const currentURL = ref(null);


const refs = { findingsInput, evaluationStatus, actionButtons, evidenceFiles, saveBtn, findingstexteditor, recommendationstexteditor };

// Functions
function handleFindingsInput(index) {
    let textarea = findingsInput.value[index];
    textarea.style.height = "128px";
    let newHeight = textarea.scrollHeight + "px";
    let maxHeight = 384;
    textarea.style.height = Math.min(maxHeight, Math.max(128, parseInt(newHeight))) + "px";
    hasUpdate.value = true;
}

function handleRecommendationsInput(index) {
    let textarea = recommendationsInput.value[index];
    textarea.style.height = "128px";
    let newHeight = textarea.scrollHeight + "px";
    let maxHeight = 384;
    textarea.style.height = Math.min(maxHeight, Math.max(128, parseInt(newHeight))) + "px";
    hasUpdate.value = true;
}

function handleFindingsEditorInput(index) {
    let textEditor = refs.findingstexteditor.value[index];
    textEditor.style.height = "128px";
    let newHeight = textEditor.scrollHeight + "px";
    let maxHeight = 384;
    textEditor.style.height = Math.min(maxHeight, Math.max(128, parseInt(newHeight))) + "px";
    hasUpdate.value = true;
}

function handleRecommendationEditorInput(index) {
    let textEditor = refs.recommendationstexteditor.value[index];
    textEditor.style.height = "128px";
    let newHeight = textEditor.scrollHeight + "px";
    let maxHeight = 384;
    textEditor.style.height = Math.min(maxHeight, Math.max(128, parseInt(newHeight))) + "px";
    hasUpdate.value = true;
}

function handleChangeStatus() {
    hasUpdate.value = true;
};



function openFindingsEditor(index) {
    isFindingsEditorOpen.value = true;
    textEditorIndex.value = index;
}

function closeFindingsEditor() {
    isFindingsEditorOpen.value = false;
}


// function closeReadyForVisitConfirmation() {
//     readyForVisitModal.value = false;
// }

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
            console.log('CTRL+S');
            console.log(hasUpdate.value);
        }
    }
};


function update(url) {
    updateIndicator.value = url;
    router.post('/ched/evaluation/update', { 'id': props.evaluation.id, 'items': props.items });
    hasUpdate.value = false;
}

function readyForVisit() {
    router.post('/hei/evaluation/submit', props.evaluation);
}

</script>

<script>
import Layout from '../Shared/Layout.vue'
export default {
    layout: Layout,
    data() {
        return {
            itemsLayout: 'list',
        }
    },
    methods: {
        listLayout() {
            this.itemsLayout = 'list';
        },
        gridLayout() {
            this.itemsLayout = 'grid';
        },
    },

}
</script>