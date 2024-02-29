<template>
    <Head title="Evaluation Form" />
    <page-title title="Program Self-Evaluation" />
    <!-- <div class="ml-8">
        <div>
            {{ $page.url }}
        </div>
        <div>
            {{ updateIndicator }}
        </div>
    </div> -->
    <div class="sticky top-0 bg-white lg:mx-8 mx-3 rounded mt-7 z-30">
        <div class="flex items-center justify-between px-3 py-5">
            <div class="text-gray-500 h-10 lg:ml-2">
                <!-- <div v-if="!hasUpdate && updateIndicator == null">
                </div> -->
                <div v-if="hasUpdate">
                    * Changes unsaved
                </div>
                <div v-else-if="!hasUpdate && updateIndicator != $page.url && updateIndicator != null">
                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                    All changes saved.
                </div>
                <div v-else-if="updateIndicator == $page.url && updateIndicator != null">
                    <i class="fas fa-spinner animate-spin mr-2"></i>Saving
                </div>
                <div v-else>

                </div>
            </div>
            <div>
                <button disabled class="lg:px-3 w-10 lg:w-auto text-gray-400 border border-gray-400 h-10 rounded mr-1">
                    <i class="fas fa-check lg:mr-2"></i>
                    <span class="hidden lg:inline-block">Ready for visit</span>
                </button>
                <button @click="update(); updateIndicator = $page.url"
                    class="lg:px-3 w-10 lg:w-auto h-10 border border-gray-500 bg-blue-500 text-gray-100 rounded tooltipForActions"
                    :class="{ 'bg-gray-200 text-gray-700': !hasUpdate }" data-tooltip="Save changes" :disabled="!hasUpdate">
                    <i class="fas fa-floppy-disk lg:mr-2"></i>
                    <span class="hidden lg:inline-block">Save</span>
                </button>
            </div>
        </div>
    </div>
    <content-container>
        <template v-slot:content-head>
            <div class="flex flex-col w-full bg-white rounded p-3 py-5">
                <div class="w-full flex flex-col md:flex-row">
                    <label for="heiName"
                        class="mr-3 h-8 flex items-center justify-start md:justify-end align-middle text-gray-800 w-full md:w-32">HEI
                        Name
                    </label>
                    <input type="text" id="heiName"
                        class="h-8 w-full rounded-full max-w-md font-bold border border-gray-500" disabled
                        :value="evaluation.institution_program.institution.name">
                </div>
                <div class="w-auto flex flex-col md:flex-row mt-2 md:mt-1">
                    <label for="program"
                        class="mr-3 h-8 flex items-center justify-start md:justify-end align-middle text-gray-800 w-full md:w-32">
                        Program
                    </label>
                    <input type="text" id="program"
                        class="h-8 w-full rounded-full max-w-md font-bold border border-gray-500" disabled
                        :value="evaluation.institution_program.program.program">
                </div>
                <div class="w-auto flex flex-col md:flex-row mt-2 md:mt-1">
                    <label for="effectivity"
                        class="mr-3 h-8 flex items-center justify-start md:justify-end align-middle text-gray-800 w-full md:w-32">
                        Effectivity
                    </label>
                    <input type="text" id="effectivity"
                        class="h-8 w-full rounded-full max-w-md font-bold border border-gray-500" disabled
                        :value="'A.Y. ' + evaluation.effectivity">
                </div>
            </div>
        </template>
        <template v-slot:content>
            <content-table v-show="itemsLayout === 'list'">
                <template v-slot:table-head>
                    <th class="p-3 max-w-8">Item</th>
                    <th class="p-3 max-w-xs">Area</th>
                    <th class="p-3 max-w-xs">Minimum Requirement</th>
                    <th class="p-3 w-16">Actual Situation</th>
                    <th class="p-3">Evidence</th>
                    <th class="p-3 whitespace-normal">Self-Evaluation Status</th>
                    <th class="p-3 text-right">
                        <i class="fas fa-ellipsis-v"></i>
                    </th>
                </template>
                <template v-slot:table-body>
                    <tr v-for="(item, index) in items" :key="item.id" class="align-top"
                        :class="{ 'bg-slate-200': index % 2 == 0 }">
                        <td class="p-3 max-w-6 font-bold">
                            {{ item.itemNo }}
                        </td>
                        <td class="p-3 whitespace-normal max-w-10">
                            <div class="flex flex-col">
                                <div class="font-bold">
                                    {{ item.area }}
                                </div>
                            </div>
                        </td>
                        <td class="p-3 whitespace-normal max-w-xs">
                            <div class="flex flex-col text-justify">
                                {{ item.minimumRequirement }}
                            </div>
                        </td>
                        <td class="h-32 max-w-lg p-3 relative group">
                            <textarea ref="actualSituationInput" v-model="item.actualSituation"
                                :name="'actualSituation' + index" :id="'actualSituation' + index"
                                class="w-full min-w-16 h-32 rounded border-gray-500 resize-none group custom-scrollbar"
                                :disabled="item.selfEvaluationStatus == 'Not applicable'"
                                placeholder="Input actual situation here" @input="handleTextInput(index)">
                            </textarea>
                            <button @click="openEditor(index)"
                                class="absolute bottom-4 right-10 text-gray-500 hover:text-black group-focus-within:visible invisible tooltipForActions"
                                data-tooltip="Open editor">
                                <i class="fas fa-pen"></i>
                            </button>
                            <text-editor title="Actual Situation" :showModal="isEditorOpen && textEditorIndex == index"
                                @close="closeEditor">
                                <textarea ref="texteditor" v-model="item.actualSituation"
                                    :name="'actualSituationEditor' + index" :id="'actualSituationEditor' + index"
                                    class="w-full h-32 rounded border-gray-500 resize-none group"
                                    placeholder="Input actual situation here" @focus="handleTextEditorInput(index)"
                                    @input="handleTextEditorInput(index)">
                                </textarea>
                            </text-editor>
                        </td>
                        <td class="h-auto p-3 max-w-12 min-w-8 flex flex-col justify-center">
                            <div class="flex flex-col" :class="{ 'hidden': item.selfEvaluationStatus == 'Not applicable' }"
                                ref="evidenceFiles">
                                <a class="px-1 text-gray-600 hover:text-black m-1 w-fit rounded"
                                    v-for="(file, index) in item.evidence" :key="file.id" :href="file.url" target="_blank">
                                    <span v-if="file.type == 'file'"><i class="fas fa-file mr-2"></i>File {{ index + 1
                                    }}</span>
                                    <span v-else-if="file.type == 'link'"><i class="fas fa-link mr-2"></i>Link {{ index + 1
                                    }}</span>
                                </a>
                            </div>
                        </td>
                        <td class="h-32 p-3">
                            <div class="w-full h-full">
                                <select ref="selfEvaluationStatus" v-model="item.selfEvaluationStatus"
                                    :id="'selfEvaluationStatus' + index" :name="'selfEvaluationStatus' + index"
                                    @change="handleChangeStatus(index)" class="p-1 rounded w-36 border-gray-400">
                                    <option value="Complied">Complied</option>
                                    <option value="Not complied">Not complied</option>
                                    <option value="Not applicable">Not applicable</option>
                                </select>
                            </div>
                        </td>
                        <td class="p-3">
                            <div class="w-full text-right" ref="actionButtons"
                                :class="{ 'hidden': item.selfEvaluationStatus == 'Not applicable' }">
                                <button @click="openEvidenceUploadModal()"
                                    class="h-8 w-10 shadow shadow-gray-500 hover:bg-gray-300 rounded border border-gray-500 bg-white tooltipForActions"
                                    data-tooltip="Upload file" :disabled="item.selfEvaluationStatus == 'Not applicable'">
                                    <i class="fas fa-file-upload"></i>
                                </button>
                                <button @click="openEvidenceLinkModal(item); submitLinkIndicator = $page.url; removeLink()"
                                    class="ml-1 h-8 w-10 shadow shadow-gray-500 hover:bg-gray-300 rounded border border-gray-500 bg-white tooltipForActions"
                                    data-tooltip="Submit link" :disabled="item.selfEvaluationStatus == 'Not applicable'">
                                    <i class="fas fa-link"></i>
                                </button>
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
    <!-- upload file, link modals -->
    <modal :showModal="uploadEvidenceFileModal" title="Upload Evidence File" @close="closeEvidenceUploadModal"
        @upload="upload" type="uploadEvidenceFile">
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
                <div class="flex items-center w-full justify-center">

                </div>
            </div>
            <button v-if="!evidenceFile" @click.prevent="$refs.uploadEvidenceFile.click()"
                class="hover:text-white border border-green-700 hover:bg-green-700 py-2 px-10 rounded"><i
                    class="fas fa-cloud-upload mr-5 text-xl"></i>
                Select file
            </button>
            <div class="mt-3">
                <form-error-message :message="$page.props.errors.file" theme="dark" />
            </div>
        </div>
    </modal>

    <modal :showModal="submitEvidenceLinkModal && $page.url == submitLinkIndicator" title="Evidence Link"
        @close="closeEvidenceLinkModal" @submit="submitLink" type="submitEvidenceLink">
        <div class="text-center">
            <textarea class="w-full h-32 resize-none" placeholder="Input URL/Link here" id="link"
                v-model="evidenceLink.link"></textarea>
            <div class="mt-1 w-full text-left" v-show="$page.props.errors">
                <form-error-message :message="$page.props.errors['evidenceLink.link']" theme="dark" />
            </div>
        </div>
        <template v-slot:custom-button>
            <button @click="submitLink()" class="text-white bg-blue-600 hover:bg-blue-700 w-24 py-2 px-3 rounded border">
                Submit
            </button>
            <!-- <button disabled class="text-white bg-blue-600 hover:bg-blue-700 w-24 py-2 px-3 rounded border">
                <i class="fas fa-spinner animate-spin"></i>
            </button> -->
            <!-- <div>
                {{ $page.url }}
            </div>
            <div>
                {{ submitLinkIndicator }}
            </div> -->
        </template>
    </modal>
    <Notification :message="$page.props.flash.message" />
    <div v-if="$page.url != submitLinkIndicator && submitLinkIndicator != null">
        <Notification :message="'Success submit link'" />
    </div>
    <div v-if="!hasUpdate && updateIndicator != $page.url && updateIndicator != null">
        <Notification :message="'All changes saved.'" />
    </div>
</template>

<script setup>
// Imports
import { ref, onMounted, onUnmounted, computed, reactive } from 'vue';
import { useForm, router } from '@inertiajs/vue3';

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
const uploadEvidenceFileModal = ref(false);
const submitEvidenceLinkModal = ref(false);
const isEditorOpen = ref(false);
const textEditorIndex = ref('');
const evidenceFile = ref(null);

const actualSituationInput = ref([]);
const selfEvaluationStatus = ref([]);
const actionButtons = ref([]);
const evidenceFiles = ref([]);
const hasUpdate = ref(false);

const updateIndicator = ref(null);
const uploadFileIndicator = ref(null);
const submitLinkIndicator = ref(null);

const refs = { actualSituationInput, selfEvaluationStatus, actionButtons, evidenceFiles };

// Functions
function handleTextInput(index) {
    let textarea = actualSituationInput.value[index];
    textarea.style.height = "128px";
    let newHeight = textarea.scrollHeight + "px";
    let maxHeight = 384;
    textarea.style.height = Math.min(maxHeight, Math.max(128, parseInt(newHeight))) + "px";
    hasUpdate.value = true;
}

function handleTextEditorInput(index) {
    let textEditor = refs.texteditor.value[index];
    textEditor.style.height = "128px";
    let newHeight = textEditor.scrollHeight + "px";
    let maxHeight = 384;
    textEditor.style.height = Math.min(maxHeight, Math.max(128, parseInt(newHeight))) + "px";
    hasUpdate.value = true;
}

function handleChangeStatus(index) {
    let selfEvaluationStatus = refs.selfEvaluationStatus.value[index];
    let actualSituationInput = refs.actualSituationInput.value[index];
    let actionButtons = refs.actionButtons.value[index];
    let evidenceFiles = refs.evidenceFiles.value[index];
    hasUpdate.value = true;

    if (selfEvaluationStatus.value == 'Not applicable') {
        actualSituationInput.disabled = true;
        if (!actionButtons.classList.contains('hidden')) {
            actionButtons.classList.add('hidden');
        }
        if (!evidenceFiles.classList.contains('hidden')) {
            evidenceFiles.classList.add('hidden');
        }

    } else {
        actualSituationInput.disabled = false;
        if (actionButtons.classList.contains('hidden')) {
            actionButtons.classList.remove('hidden');
        }
        if (evidenceFiles.classList.contains('hidden')) {
            evidenceFiles.classList.remove('hidden');
        }
    }
};


const handleFileChange = (event) => {
    evidence.file = event.target.files[0];
    evidenceFile.value = event.target.files[0];
};

const removeFile = () => {
    evidence.file = null;
    evidenceFile.value = null;
};

const removeLink = () => {
    evidenceLink.link = null;
};


function openEditor(index) {
    isEditorOpen.value = true;
    textEditorIndex.value = index;
}

function closeEvidenceUploadModal() {
    removeFile();
    uploadEvidenceFileModal.value = false;
}

function closeEvidenceLinkModal() {
    removeLink();
    submitEvidenceLinkModal.value = false;
}

function openEvidenceUploadModal(item) {
    uploadEvidenceFileModal.value = true;
    evidence.itemId = item.id;
};

function openEvidenceLinkModal(item) {
    submitEvidenceLinkModal.value = true;
    evidenceLink.itemId = item.id
};

function closeEditor() {
    isEditorOpen.value = false;
}

// CTRL + S function
const handleKeyDown = (event) => {
    if (event.ctrlKey || event.metaKey) {
        if (event.key === 's' || event.key === 'S') {
            event.preventDefault();
            update();
        }
    }
};


// Computed
// const itemsArray = computed(() => {
//     return props.items.map(item => ({
//         id: item.id,
//         itemNo: item.criteria.itemNo,
//         area: item.criteria.area,
//         minimumRequirement: item.criteria.minimumRequirement,
//         actualSituation: item.actualSituation,
//         selfEvaluationStatus: item.selfEvaluationStatus,
//     }));
// });


// Submit
// const form = useForm({
//     items: itemsArray,
// });

// const form = useForm({
//     id: ref(props.items.id),
//     itemNo: ref(props.items.itemNo),
//     area: ref(props.items.area),
//     minimumRequirement: ref(props.items.minimumRequirement),
//     actualSituation: ref(props.items.actualSituation),
//     selfEvaluationStatus: ref(props.items.selfEvaluationStatus),
//     evidence: ref(props.items.evidence),
// });

const evidence = reactive({
    itemId: null,
    file: null,
});

const evidenceLink = reactive({
    itemId: null,
    link: null,
});

function update() {
    router.post('/hei/evaluation/update', { 'id': props.evaluation.id, 'items': props.items });
    hasUpdate.value = false;
}

function upload() {
    router.post('/hei/evaluation/upload', { 'id': props.evaluation.id, 'evidence': evidence });
}

function submitLink() {
    router.post('/hei/evaluation/link', { 'id': props.evaluation.id, 'evidenceLink': evidenceLink });
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