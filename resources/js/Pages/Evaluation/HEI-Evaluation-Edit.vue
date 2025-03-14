<template>
    <Head title="Program Self-Evaluation Tool" />
    <page-title title="Program Self-Evaluation" />
    <content-container :hasStickyDiv="true" :hasSearch="true" :hasFilters="true" :hasTopMainContent="true">
        <template v-slot:content-title>
            <div class="w-full flex flex-col md:flex-row justify-between items-center">
                <div class="w-full flex flex-row items-center">
                    <div class="w-full flex flex-row items-center">
                        <Link href="/evaluation">
                        <button class="w-8 h-8 rounded-full hover:bg-gray-200 tooltipForActions" data-tooltip="Back"><i class="fas fa-arrow-left"></i></button>
                        </Link>
                        <div class="ml-3 font-bold">Self-Evaluate</div>
                    </div>
                </div>
                <div class="w-full md:w-fit md:mt-0 mt-3 flex flex-row justify-center">
                    <div class="w-full flex flex-row">
                        <button @click="toggleResubmitModal" v-if="progress[3] == 100 && evaluation.evaluationDate != null"
                            class="select-none whitespace-nowrap px-3 w-fit text-white bg-blue-500 hover:bg-blue-600 h-10 rounded mr-1">
                            Resubmit
                        </button>
                        <button @click="toggleReadyForVisitModal" v-else-if="progress[3] == 100 && evaluation.evaluationDate == null"
                            class="select-none whitespace-nowrap px-3 w-fit text-white bg-gray-700 hover:bg-gray-800 h-10 rounded mr-1">
                            Ready for visit
                        </button>
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
                    <div></div>
                </div>
                <div class="flex flex-row">
                    <button @click="update" ref="saveBtn" :disabled="saving"
                        class="select-none whitespace-nowrap cursor-pointer bg-blue-500 text-white px-2 w-fit h-10 rounded">Save
                        Changes
                    </button>
                </div>
            </div>
        </template>
        <template v-slot:top-main-content>
            <div class="flex flex-col xl:flex-row items-center justify-between w-auto rounded border border-gray-300 bg-white my-3 p-5">
                <div class="w-full text-left">
                    <div class="w-auto flex flex-row items-center">
                        <i class="fas fa-institution mr-3 text-blue-500"></i>
                        <div>{{ evaluation.institution_program.institution?.name }}</div>
                    </div>
                    <div class="w-auto flex flex-row items-center">
                        <i class="fas fa-book mr-3 text-blue-500"></i>
                        <div>
                            {{ evaluation.institution_program.program?.program }}
                            <span></span>
                        </div>
                    </div>
                    <div class="w-auto flex flex-row items-center">
                        <i class="fas fa-calendar-check mr-3 text-blue-500"></i>
                        <div>A.Y. {{ evaluation.effectivity }}</div>
                    </div>
                </div>
                <div class="xl:w-auto w-full text-right mt-5 xl:mt-0">
                    <div class="flex flex-col md:flex-row lg:mt-0 md:w-auto w-full">
                        <div class=" flex-col xl:mt-0 mt-2 text-center mx-1 p-2 px-3 md:w-fit w-full rounded border border-gray-300 bg-white">
                            <div class="w-full font-bold ">{{ progress[0] }}</div>
                            <div class="font-bold text-blue-500 whitespace-nowrap text-sm">Complied</div>
                        </div>
                        <div class=" flex-col xl:mt-0 mt-2 text-center mx-1 p-2 px-3 md:w-fit w-full rounded border border-gray-300 bg-white">
                            <div class="w-full font-bold">{{ progress[1] }}</div>
                            <div class="font-bold text-blue-500 whitespace-nowrap text-sm">Not Complied</div>
                        </div>
                        <div class=" flex-col xl:mt-0 mt-2 text-center mx-1 p-2 px-3 md:w-fit w-full rounded border border-gray-300 bg-white">
                            <div class="w-full font-bold">{{ progress[2] }}</div>
                            <div class="font-bold text-blue-500 whitespace-nowrap text-sm">Not Applicable</div>
                        </div>
                        <div class=" flex-col xl:mt-0 mt-2 text-center mx-1 p-2 px-3 md:w-fit w-full rounded border border-gray-300 bg-white">
                            <div class="w-full font-bold">{{ progress[3] }}%</div>
                            <div class="font-bold text-blue-500 whitespace-nowrap text-sm">Progress</div>
                        </div>
                    </div>
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
                <Link :href="'/hei/evaluation/'+props.evaluation.id+'/edit'">
                <button
                    class="w-10 h-10 hover:bg-gray-200 rounded-full text-gray-700 hover:text-blue-500 active:text-white active:bg-blue-600 tooltipForActions"
                    data-tooltip="Refresh page">
                    <i class="fas fa-refresh"></i>
                </button>
                </Link>
            </div>
        </template>
        <template v-slot:main-content>
            <content-table v-show="itemsLayout === 'list'">
                <template v-slot:table-head>
                    <th class="p-2">Area</th>
                    <th class="p-2">Minimum Requirement</th>
                    <th class="p-2">Actual Situation</th>
                    <th class="p-2">Evidence</th>
                    <th class="p-2">Self-Evaluation Status</th>
                    <th class="p-2 pr-5 text-right">
                        <i class="fas fa-ellipsis-v"></i>
                    </th>
                </template>
                <template v-slot:table-body>
                    <tr v-if="items.data.length == 0">
                        <td colspan="6" class="text-gray-500 text-center items-center py-10">
                            No data
                        </td>
                    </tr>
                    <tr v-else v-for="(item, index) in items.data" :key="item.id" class=" hover:bg-gray-200 align-top text-base"
                        :class="[{ 'bg-slate-200': index % 2 == 1 }, {'bg-red-100' : filters.evaluationStatus != 'Not complied' && item.evaluationStatus == 'Not complied' && evaluation.evaluationDate != null}]">
                        <td class="p-2 whitespace-normal max-w-10">
                            <div class="flex flex-col">
                                <div class="font-bold">
                                    {{ item.criteria.area }}
                                </div>
                            </div>
                        </td>
                        <td class="p-2 whitespace-normal max-w-xs">
                            <div class="flex flex-col text-justify" v-html="item.criteria.minimumRequirement">
                            </div>
                            <input type="text" class="w-24 h-10">
                        </td>
                        <td class="h-32 min-w-[21rem] max-w-md p-2 relative group">
                            <tip-tap-editor v-model="item.actualSituation" @input="updateData()"></tip-tap-editor>
                        </td>
                        <td class="h-auto p-3 w-16 flex flex-col justify-center">
                            <div class="flex flex-col"
                                :class="{ 'hidden': item.selfEvaluationStatus == 'Not applicable' }"
                                ref="evidenceFiles">
                                <div v-for="file in item.evidence" :key="file.id" class="w-full">
                                    <a class="px-1 text-gray-600 hover:text-blue-500 m-1 rounded whitespace-nowrap" :href="file.url"
                                        target="_blank">
                                        <span v-if="file.type == 'file'">
                                            File
                                        </span>
                                        <span v-else-if="file.type == 'link'">
                                            Link
                                        </span>
                                    </a>
                                    <button @click="toggleDeleteModal(); toDelete = file.id"
                                        class="text-gray-500 hover:text-red-500 tooltipForActions"
                                        :disabled="item.selfEvaluationStatus == 'Not applicable' || hasUpdate"
                                        :class="{ 'grayscale text-gray-500': hasUpdate }"
                                        :data-tooltip="file.type == 'file' ? 'Delete file' : 'Delete link'">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </td>
                        <td class="h-32 p-3">
                            <div class="w-full h-full">
                                <select ref="selfEvaluationStatus" v-model="item.selfEvaluationStatus"
                                    :id="'selfEvaluationStatus' + index" :name="'selfEvaluationStatus' + index"
                                    @change="handleChangeStatus(index, item.id)"
                                    class="p-1 rounded w-36 border border-gray-500 select-none">
                                    <option :value="null">Select status</option>
                                    <option value="Complied">Complied</option>
                                    <option value="Not complied">Not complied</option>
                                    <option value="Not applicable">Not applicable</option>
                                </select>
                            </div>
                        </td>
                        <td class="p-3 pr-5 text-right whitespace-nowrap">
                            <div class="w-full text-right text-sm visible" ref="actionButtons"
                                :class="{ 'invisible': item.selfEvaluationStatus == 'Not applicable' }">
                                <button @click="linkModal = true; evidenceLink = null; linkItem = item.id"
                                    class="rounded bg-blue-500  text-gray-100 h-8 px-2 tooltipForActions" data-tooltip="Drop link"
                                    :disabled="item.selfEvaluationStatus == 'Not applicable'">
                                    <i class="fas fa-upload mr-2"></i>Link
                                </button>
                            </div>
                        </td>
                    </tr>
                </template>
            </content-table>
        </template>
    </content-container>

    <!-- Filter Modal -->
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

    <!-- Ready for visit confirmation -->
    <confirmation @close="toggleReadyForVisitModal" :showModal="readyForVisitModal" title="Ready For Visit" width="md" height="short">

        <template v-slot:message>
            Are you sure you want to submit this compliance evaluation tool? You won't be able to make changes after
            submitting.
        </template>

        <template v-slot:buttons>
            <button class=" text-gray-100 bg-blue-500 hover:text-white hover:bg-blue-600 w-20 h-10 rounded mr-1"
                @click="submitTool">
                <span v-if="submitting"><i class="fas fa-spinner animate-spin"></i></span>
                <span v-else>Submit</span>
            </button>
        </template>
    </confirmation>

    <!-- Resubmit confirmation -->
    <confirmation @close="toggleResubmitModal" :showModal="resubmitModal" title="Resubmit" width="md" height="short">

        <template v-slot:message>
            Are you sure you want to resubmit this compliance evaluation tool? You won't be able to make changes after
            resubmitting.
        </template>
        
        <template v-slot:buttons>
            <button class=" text-gray-100 bg-blue-500 hover:text-white hover:bg-blue-600 w-20 h-10 rounded mr-1"
                @click="submitTool">
                <span v-if="submitting"><i class="fas fa-spinner animate-spin"></i></span>
                <span v-else>Submit</span>
            </button>
        </template>
    </confirmation>
    
    <!-- Select evidence file -->
    <div>
        <input type="file" hidden ref="inputEvidenceFile" id="inputEvidenceFile"
            @change="evidenceFile = $event.target.files[0]">
    </div>
    
    <!-- Upload link modal -->
    <modal :showModal="linkModal" title="Evidence Link" @close="linkModal = false" width="lg" height="long">
        <div class="text-center">
            <textarea maxlength="1000" class="w-full h-32 resize-none" placeholder="Input URL/Link here" id="link"
                v-model="evidenceLink">
            </textarea>
        </div>
        <div v-if="$page.props.errors.link" class="text-red-500">
            {{$page.props.errors.link}}
        </div>
        <template v-slot:custom-button>
            <button @click="submitLink" :disabled="uploadingLink"
                class="text-white bg-blue-600 hover:bg-blue-700 w-24 py-2 px-3 rounded border">
                <span v-if="uploadingLink"><i class="fas fa-spinner animate-spin"></i></span>
                <span v-else>Upload</span>
            </button>
        </template>
    </modal>



    <confirmation @close="toggleDeleteModal" :showModal="deleteModal" title="Delete link" width="md" height="short">
        <template v-slot:message>
            Are you sure you want to delete this link?
        </template>
        
        <template v-slot:buttons>
            <button class="select-none text-white h-10 w-20 rounded bg-red-500 hover:bg-red-600"
                @click="deleteLink">Delete
            </button>
        </template>
    </confirmation>
</template>

<script setup>
// Imports
import { ref, onMounted, onUnmounted, computed, reactive, watch, inject } from 'vue';
import { useForm, router } from '@inertiajs/vue3';


// Inject the reactive object
const hasUnsavedChanges = inject("hasUnsavedChanges");

// Function to update value
const updateData = () => {
    hasUnsavedChanges.value = true;
};

// Props
const props = defineProps([
    'evaluation',
    'items',
    'progress',
    'filters',
]);

const itemsArray = computed(() => {
    return props.items.data.map(item => ({
        id: item.id,
        actualSituation: item.actualSituation,
        selfEvaluationStatus: item.selfEvaluationStatus,
    }));
});

const form = reactive({
    id: ref(props.evaluation.id),
    items: ref(itemsArray),
});




const handleBeforeUnload = (event) => {
    if (hasChanges.value) {
        event.returnValue = true;
    }
};

// Mount, Unmount
onMounted(() => {
    window.addEventListener('keydown', handleKeyDown);
    window.addEventListener('beforeunload', handleBeforeUnload);
});

onUnmounted(() => {
    window.removeEventListener('keydown', handleKeyDown);
    window.removeEventListener('beforeunload', handleBeforeUnload);
});

// Variables
const deleteModal = ref(false);
const deleting = ref(false);
const toDelete = ref(null);

const linkModal = ref(false);
const uploadingLink = ref(false);
const linkItem = ref(null);
const evidenceLink = ref(null);

const fileModal = ref(false);
const uploadingFile = ref(false);
const fileItem = ref(null);
const evidenceFile = ref(null);
const inputEvidenceFile = ref([]);

const isEditorOpen = ref(false);
const textEditorIndex = ref('');
const texteditor = ref([]);
const readyForVisitModal = ref(false);
const resubmitModal = ref(false);

const actualSituationInput = ref([]);
const selfEvaluationStatus = ref([]);
const actionButtons = ref([]);
const evidenceFiles = ref([]);

const hasUpdate = ref(false);
const updatedRows = ref([]);
const saveBtn = ref(null);
const saving = ref(false);
const submitting = ref(false);

const processing = ref(false);
const showFilterModal = ref(false);
const hasChanges = ref(false);

const refs = { actualSituationInput, selfEvaluationStatus, actionButtons, evidenceFiles, saveBtn, texteditor, inputEvidenceFile };

function hasInput() {
    hasChanges.value = true;
}


function toggleDeleteModal() {
    deleteModal.value = !deleteModal.value;
}


// Functions
function handleTextInput(index, id) {
    let textarea = actualSituationInput.value[index];
    textarea.style.height = "128px";
    let newHeight = textarea.scrollHeight + "px";
    let maxHeight = 384;
    textarea.style.height = Math.min(maxHeight, Math.max(128, parseInt(newHeight))) + "px";
    hasUpdate.value = true;
    getRowsWithUpdates(id);
}

function handleTextEditorInput(index, id) {
    let textEditor = refs.texteditor.value[index];
    textEditor.style.height = "250px";
    let newHeight = textEditor.scrollHeight + "px";
    let maxHeight = 400;
    textEditor.style.height = Math.min(maxHeight, Math.max(128, parseInt(newHeight))) + "px";
    hasUpdate.value = true;
    getRowsWithUpdates(id);
}

function handleChangeStatus(index, id) {
    let selfEvaluationStatus = refs.selfEvaluationStatus.value[index];
    let actualSituationInput = refs.actualSituationInput.value[index];
    let actionButtons = refs.actionButtons.value[index];
    let evidenceFiles = refs.evidenceFiles.value[index];
    hasUpdate.value = true;
    getRowsWithUpdates(id);

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

function getRowsWithUpdates(id) {
    if (!updatedRows.value.includes(id)) {
        updatedRows.value.push(id);
    }
}


function toggleReadyForVisitModal() {
readyForVisitModal.value = !readyForVisitModal.value;
}

function toggleResubmitModal() {
resubmitModal.value = !resubmitModal.value;
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
    router.post('/hei/evaluation/update', form, {
        replace: true,
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            hasUnsavedChanges.value = false;
        },
    });
}

function submitLink() {
    router.post('/hei/evaluation/link', {
        id: linkItem.value,
        link: evidenceLink.value,
        items: props.items,
        rows: updatedRows.value,
    }, {
        onStart: () => {
            linkModal.value = true;
        },
        onSuccess: () => {
            linkModal.value = false;
        },
        preserveState: true,
        preserveScroll: true,
    });
}


function deleteLink() {
    router.post('/hei/evaluation/link/delete', {
        'id': props.evaluation.id,
        'item': toDelete.value,
    }, {
        onStart: () => {
            deleting.value = true;
        },
        onFinish: () => {
            deleting.value = false;
            deleteModal.value = false;
        },
        preserveState: true,
        preserveScroll: true,
    });
}

function submitTool() {
    router.post('/hei/evaluation/submit', props.evaluation, {
        onStart: () => {
            submitting.value = true;
        },
        onFinish: () => {
            submitting.value = false;
        },
        preserveState: false,
        preserveScroll: true,
    });
}



watch(evidenceFile, value => {
    router.post('/hei/evaluation/upload', {
        id: props.evaluation.id,
        items: props.items,
        rows: updatedRows.value,
        itemId: fileItem.value,
        file: evidenceFile.value,
    }, {
        onStart: () => {
            uploadingFile.value = true;
        },
        onFinish: () => {
            uploadingFile.value = false;
        },
        preserveState: true,
        preserveScroll: true,
    })
});

const query = useForm({
    search: props.filters.search,
    selfEvaluationStatus: props.filters.selfEvaluationStatus != null ? props.filters.selfEvaluationStatus : null,
    evaluationStatus: props.filters.evaluationStatus != null ? props.filters.evaluationStatus : null,
});

function toggleFilterModal() {
    showFilterModal.value = !showFilterModal.value;
}

function submit() {
    query.get("/hei/evaluation/"+props.evaluation.id+"/edit", {
        onStart: () => {
            processing.value = true;
        },
        onFinish: () => {
            processing.value = false;
        },
        preserveState: true,
        preserveScroll: true,
    });
}

function filter() {
    query.get("/hei/evaluation/"+props.evaluation.id+"/edit", {
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