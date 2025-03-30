<template>
    <Head title="Program Self-Evaluation Tool" />
    <page-title title="Program Self-Evaluation" />
    <content-container :hasStickyDiv="true" :hasSearch="true" :hasFilters="true" :hasTopMainContent="true" :hasBackButton="true">
        <template v-slot:content-title>
            <div class="w-full flex flex-col md:flex-row justify-between items-center">
                <div class="w-full flex flex-row items-center">
                    <div class="w-full flex flex-row items-center">
                        <div class="font-bold">Self-Evaluate</div>
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
                        <button
                            @click="update"
                            ref="saveBtn"
                            class="whitespace-nowrap w-full md:w-auto inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 focus:outline-none transition duration-150 ease-in-out disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <span class="flex items-center">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="w-5 h-5 mr-2"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                >
                                    <path
                                        d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"
                                    />
                                    <polyline points="17 21 17 13 7 13 7 21" />
                                    <polyline points="7 3 7 8 15 8" />
                                </svg>
                                Save changes
                            </span>
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
                    <button
                        @click="update"
                        class="whitespace-nowrap w-full md:w-auto inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 focus:outline-none transition duration-150 ease-in-out disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <span class="flex items-center">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="w-5 h-5 mr-2"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <path
                                    d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"
                                />
                                <polyline points="17 21 17 13 7 13 7 21" />
                                <polyline points="7 3 7 8 15 8" />
                            </svg>
                            Save changes
                        </span>
                    </button>
                </div>
            </div>
        </template>
        <!-- <template v-slot:top-main-content>
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
        </template> -->
        <template v-slot:search>
            <search-box v-model="query.search" @submit="filter" />
        </template>
        <template v-slot:options>
            <options @filter="toggleFilterModal" :href="'/hei/evaluation/'+props.evaluation.id+'/edit'"></options>
        </template>
        <template v-slot:main-content>
            <div class="md:p-3">
                <div
                    class="w-full p-2 md:p-5 my-3 md:shadow-lg md:border rounded-xl"
                >
                    <div class="flex flex-col gap-6">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div
                                class="flex items-center p-4 bg-gray-100 rounded-lg hover:bg-gray-200 transition-all"
                            >
                                <div class="w-full">
                                    <p class="text-sm text-gray-500">
                                        HEI Name
                                    </p>
                                    <div>
                                        {{
                                            evaluation.institution_program.institution?.name
                                        }}
                                    </div>
                                </div>
                            </div>

                            <div
                                class="flex items-center p-4 bg-gray-100 rounded-lg hover:bg-gray-200 transition-all"
                            >
                                <div class="w-full">
                                    <p class="text-sm text-gray-500">Program</p>
                                    {{
                                        evaluation.institution_program.program?.program
                                    }}
                                    <span
                                        v-if="
                                            evaluation.institution_program.program?.major
                                        "
                                    >
                                        -
                                        {{
                                            evaluation.institution_program.program?.major
                                        }}</span
                                    >
                                </div>
                            </div>
                        </div>

                        <div class="grid md:grid-cols-2 gap-6">
                            <div
                                class="flex items-center p-4 bg-gray-100 rounded-lg hover:bg-gray-200 transition-all"
                            >
                                <div class="w-full">
                                    <p class="text-sm text-gray-500">
                                        Effectivity
                                    </p>
                                    <div>AY: {{ evaluation.effectivity }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8 p-2 md:px-4">
                <div class="p-4 rounded-lg shadow-md bg-green-100 text-center">
                    <p class="text-3xl font-bold text-green-600">
                        {{ progress[0] }}
                    </p>
                    <p class="text-green-700 font-semibold">Complied</p>
                </div>
                <div class="p-4 rounded-lg shadow-md bg-red-100 text-center">
                    <p class="text-3xl font-bold text-red-600">
                        {{ progress[1] }}
                    </p>
                    <p class="text-red-700 font-semibold">Not Complied</p>
                </div>
                <div class="p-4 rounded-lg shadow-md bg-gray-100 text-center">
                    <p class="text-3xl font-bold text-gray-600">
                        {{ progress[2] }}
                    </p>
                    <p class="text-gray-700 font-semibold">Not Applicable</p>
                </div>
                <div class="p-4 rounded-lg shadow-md bg-blue-100 text-center">
                    <p class="text-3xl font-bold text-blue-600">
                        {{ progress[3] }}%
                    </p>
                    <p class="text-blue-700 font-semibold">Progress</p>
                </div>
            </div>
            <div class="p-3">
                <div
                    class="w-full p-2 md:p-8 my-3 md:shadow-lg md:border rounded-xl"
                >
            <content-table>
                <template v-slot:table-head>
                    <th>Area</th>
                    <th>Minimum Requirement</th>
                    <th>Actual Situation</th>
                    <th>Evidence</th>
                    <th>Self-Evaluation Status</th>
                    <th>
                        <i class="fas fa-ellipsis-v text-lg"></i>
                    </th>
                </template>
                <template v-slot:table-body>
                    <tr v-if="items.data.length == 0">
                        <no-search-result text="items"/>
                    </tr>
                    <tr v-else v-for="(item, index) in items.data" :key="item.id">
                        <td>
                            {{ item.criteria.area }}
                        </td>
                        <td>
                            {{ item.criteria.minimumRequirement }}
                        </td>
                        <td class="min-w-[16rem]">
                            <tip-tap-editor v-model="item.actualSituation" @input="updateData()"></tip-tap-editor>
                        </td>
                        <td>
                            <div class="flex flex-col"
                                :class="{ 'hidden': item.selfEvaluationStatus == 'Not applicable' }"
                                ref="evidenceFiles">
                                <div v-for="link in item.evidence" :key="link.id" class="w-full">
                                    <a class="px-1 text-blue-600 hover:text-blue-700 hover:underline rounded whitespace-nowrap" :href="link.url"
                                        target="_blank">
                                        Link
                                    </a>
                                    <button @click="toggleDeleteModal(); toDelete = link.id"
                                        class="text-gray-500 hover:text-red-500 ml-1 tooltipForActions"
                                        :disabled="item.selfEvaluationStatus == 'Not applicable' || hasUpdate"
                                        :class="{ 'grayscale text-gray-500': hasUpdate }"
                                        data-tooltip="Delete link">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </td>
                        <td class="h-32 p-3">
                            <div class="w-full h-full">
                                <select ref="selfEvaluationStatus" v-model="item.selfEvaluationStatus"
                                    :id="'selfEvaluationStatus' + index" :name="'selfEvaluationStatus' + index"
                                    @change="updateData"
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
        </div>
    </div>
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

function filter() {
    query.get("/hei/evaluation/"+props.evaluation.id+"/edit", {
        preserveState: false,
        preserveScroll: true,
        replace: true,
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