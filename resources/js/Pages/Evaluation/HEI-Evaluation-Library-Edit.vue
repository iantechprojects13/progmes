<template>
    <Head title="Library Self-Evaluation Tool" />
    <page-title title="Library Self-Evaluation" />
    <content-container :hasStickyDiv="true" :hasSearch="true" :hasFilters="true" :hasTopMainContent="true" :hasBackButton="true">
        <template v-slot:content-title>
            <div class="w-full flex flex-col md:flex-row justify-between items-center">
                <div class="w-full flex flex-row items-center">
                    <div class="w-full flex flex-row items-center">
                        <div class="font-bold">Self-Evaluate</div>
                    </div>
                </div>
                <div class="w-full md:w-auto">
                    <dropdown-option position="right">
                        <template v-slot:button>
                            <button
                                class="flex whitespace-nowrap w-full md:w-auto items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg justify-center"
                            >
                                Options
                                <i class="fas fa-caret-down ml-2"></i>
                            </button>
                        </template>
                        <template v-slot:options>
                            <div class="w-64">
                                <div class="pb-3 border-b">
                                    <button @click="toggleCompletedModal" :disabled="progress[3] != 100"
                                        class="py-1.5 hover:bg-gray-200 w-full text-left indent-8"
                                        :class="{ 'grayscale text-gray-400 cursor-not-allowed': progress[3] != 100 }"
                                    >
                                        Completed
                                    </button>
                                </div>
                                <div class="pt-3">
                                    <div class="text-gray-600 font-semibold px-5 py-1">
                                        Monitoring Report
                                    </div>
                                    <button @click="previewFile" :disabled="evaluation.evaluationDate == null"
                                        class="py-1.5 hover:bg-gray-200 w-full text-left indent-8"
                                        :class="{ 'grayscale text-gray-400 cursor-not-allowed': evaluation.evaluationDate == null }"
                                    >
                                        Preview
                                    </button>
                                    <button @click="downloadFile" :disabled="evaluation.evaluationDate == null"
                                        class="py-1.5 hover:bg-gray-200 w-full text-left indent-8"
                                        :class="{ 'grayscale text-gray-400 cursor-not-allowed': evaluation.evaluationDate == null }"
                                    >
                                        Download
                                    </button>
                                </div>
                            </div>
                        </template>
                    </dropdown-option>
                </div>
            </div>
        </template>
        <template v-slot:search>
            <search-box v-model="query.search" @submit="filter" />
        </template>
        <template v-slot:options>
            <options @filter="toggleFilterModal" :href="'/hei/library/'+evaluation.id+'/edit'"></options>
        </template>
        <template v-slot:main-content>

            <tool-info :data="{
                'HEI Name': evaluation.institution?.name,
                'Effectivity': evaluation.effectivity,
                'CMO': cmo,
            }"/>

            <tool-progress-display :data="progress" type="HEI"/>

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
                                <td class="max-w-[32rem]">
                                    <div v-html="item.criteria.area">

                                    </div>
                                </td>
                                <td class="max-w-[32rem]">
                                    <div v-html="item.criteria.minimumRequirement">

                                    </div>
                                </td>
                                <td class="min-w-[16rem] max-w-[24rem] whitespace-normal">
                                    <tip-tap-editor
                                        :hasSaveBtn="false"
                                        v-model="item.actualSituation"
                                        @input="updateData(), getUpdatedRowId(item.id)"
                                        @update="update"
                                        title="Actual Situation"
                                        :inputRequired="(item.selfEvaluationStatus == 'Complied' || item.selfEvaluationStatus == 'Not complied') && (!item.actualSituation || item.actualSituation.replace(/<[^>]*>/g, '').trim() === '')"
                                    />
                                </td>
                                <td>
                                    <div class="text-red-500" v-if="item.selfEvaluationStatus == 'Complied' && item.evidence.length == 0">
                                        *required
                                    </div>
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
                                            @change="updateData(), getUpdatedRowId(item.id)"
                                            class="p-1 rounded w-36 border border-gray-500 text-sm font-medium select-none">
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
                                        <button @click="toggleLinkModal(); evidenceLink = null; linkItem = item.id"
                                            class="rounded bg-blue-600 hover:bg-blue-700  text-gray-100 p-2 tooltipForActions" data-tooltip="Drop evidence link"
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
        <div class="flex flex-col space-y-4">
            <filter-compliance-status type="HEI" v-model="query.selfEvaluationStatus"/>
            <filter-compliance-status v-show="evaluation.evaluationDate != null" v-model="query.evaluationStatus"/>
        </div>
        <template v-slot:custom-button>
            <modal-button text="Apply" color="green" @click="filter"/>
        </template>
    </modal>

    <!-- Completed confirmation -->
    <confirmation @close="toggleCompletedModal" :showModal="completedModal" title="Completed" width="md" height="short">

        <template v-slot:message>
            Are you sure you want to mark this library compliance evaluation tool as completed? You won't be able to make changes after marking it as completed.
        </template>

        <template v-slot:buttons>
            <modal-button text="Submit" @click="submitTool"/>
        </template>
    </confirmation>
    
    <!-- Select evidence file -->
    <div>
        <input type="file" hidden ref="inputEvidenceFile" id="inputEvidenceFile"
            @change="evidenceFile = $event.target.files[0]">
    </div>
    
    <!-- Upload link modal -->
    <modal
        :showModal="linkModal"
        @close="toggleLinkModal"
        title="Evidence Link"
        width="lg"
    >
        <div>
            <div class="text-center">
                <textarea maxlength="1000" class="w-full rounded-lg border border-gray-400 h-32 resize-none" placeholder="Input URL/Link here" id="link"
                    v-model="evidenceLink">
                </textarea>
            </div>
            <div v-if="$page.props.errors.link" class="text-red-500">
                {{$page.props.errors.link}}
            </div>
        </div>
        <template v-slot:custom-button>
            <modal-button text="Upload" color="blue" @click="submitLink"/>
        </template>
    </modal>


    <confirmation @close="toggleDeleteModal"
        :showModal="deleteModal"
        title="Delete Link"
        width="md"
    >
        <template v-slot:message>
            Are you sure you want to delete this link?
        </template>
        <template v-slot:buttons>
            <modal-button text="Delete" color="red" @click="deleteLink"/>
        </template>
    </confirmation>

    <div>
        <div class="w-auto border border-gray-400 h-auto bg-white fixed z-[995] bottom-2 left-2 p-2 px-4 rounded flex items-center" v-if="saving">
            <i class="fas fa-circle-notch animate-spin mr-3"></i> Saving changes...
        </div>
        <div class="w-auto border border-gray-400 h-auto bg-white fixed z-[995] bottom-2 left-2 p-2 px-4 rounded flex items-center" v-else-if="allChangesSaved">
            <i class="fas fa-check text-green-500 mr-3"></i> All changes saved.
        </div>
        <div class="w-auto border border-gray-400 h-auto bg-white fixed z-[995] bottom-2 left-2 p-2 px-4 rounded flex items-center" v-else-if="hasChanges">
            Changes unsaved!
        </div>
        <div v-else>

        </div>
    </div>
</template>

<script setup>
// Imports
import { ref, onMounted, onUnmounted, onBeforeUnmount, computed, reactive, watch, inject } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import axios from 'axios';
import Layout from '@/Shared/Layout.vue';
defineOptions({ layout: Layout });

// Inject the reactive object
const hasUnsavedChanges = inject("hasUnsavedChanges");

const hasChanges = ref(false);
const allChangesSaved = ref(false);
const autoSaveTimeout = ref(null);
const saving = ref(false);
const rowsWithUpdates = [];

// Function to update value
const updateData = () => {
    hasUnsavedChanges.value = true;
    hasChanges.value = true;
    allChangesSaved.value = false;
    
    // Clear any existing timeout
    if (autoSaveTimeout.value) {
        clearTimeout(autoSaveTimeout.value);
    }
    
    // Set a new timeout for 3 seconds
    autoSaveTimeout.value = setTimeout(() => {
        update();
    }, 3000);
};



// Props
const props = defineProps([
    'evaluation',
    'items',
    'cmo',
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
    rows: ref(rowsWithUpdates),
});

const handleBeforeUnload = (event) => {
    if (hasUnsavedChanges.value) {
        event.returnValue = true;
    }
};

// Mount, Unmount
onMounted(() => {
    // window.addEventListener('keydown', handleKeyDown);
    window.addEventListener('beforeunload', handleBeforeUnload);
    getProgress();
});

onUnmounted(() => {
    // window.removeEventListener('keydown', handleKeyDown);
    window.removeEventListener('beforeunload', handleBeforeUnload);
});

onBeforeUnmount(() => {
    if (autoSaveTimeout.value) {
        clearTimeout(autoSaveTimeout.value);
    }
});

// Variables
const deleteModal = ref(false);
const toDelete = ref(null);

const linkModal = ref(false);
const linkItem = ref(null);
const evidenceLink = ref(null);

const fileItem = ref(null);
const evidenceFile = ref(null);
const inputEvidenceFile = ref([]);

const completedModal = ref(false);

const selfEvaluationStatus = ref([]);
const actionButtons = ref([]);
const evidenceFiles = ref([]);

const hasUpdate = ref(false);
const updatedRows = ref([]);
const saveBtn = ref(null);

const progress = ref([]);

const showFilterModal = ref(false);

const refs = { selfEvaluationStatus, actionButtons, evidenceFiles, saveBtn, inputEvidenceFile };

function toggleDeleteModal() {
    deleteModal.value = !deleteModal.value;
}

function toggleCompletedModal() {
    completedModal.value = !completedModal.value;
}

function toggleLinkModal() {
    linkModal.value = !linkModal.value;
}

// CTRL + S function
// const handleKeyDown = (event) => {
//     if (event.ctrlKey || event.metaKey) {
//         if (event.key === 's' || event.key === 'S') {
//             event.preventDefault();
//             refs.saveBtn.value.click();
//         }
//     }
// };

function validateEvidence() {
  let isValid = true;
  let errorMessage = '';
  
  // Check each item
  props.items.data.forEach(item => {
    if (item.selfEvaluationStatus === 'Complied' && (!item.evidence || item.evidence.length === 0)) {
      isValid = false;
      errorMessage = 'Evidence is required for items marked as Complied';
    }
  });
  
  return { isValid, errorMessage };
}

function getUpdatedRowId(id) {
    const index = rowsWithUpdates.findIndex(row => row.id === id);
    if (index === -1) {
        rowsWithUpdates.push({ id });
    }
}

const update = async () => {
    if (hasUnsavedChanges.value) {
        // Clear the timeout to prevent duplicate saves
        if (autoSaveTimeout.value) {
            clearTimeout(autoSaveTimeout.value);
            autoSaveTimeout.value = null;
        }
        
        saving.value = true;
        hasChanges.value = false;
        
        await axios.post('/hei/library/evaluation/update', form)
            .then(response => {
                hasUnsavedChanges.value = false;
                saving.value = false;
                allChangesSaved.value = true;
                // Clear the rowsWithUpdates array after successful save
                rowsWithUpdates.length = 0;
                progress.value = response.data;
                
            })
            .catch(error => {
                console.error('Error updating evaluation:', error);
                saving.value = false;
            });
    }
};

const getProgress = async () => {
    await axios.get(`/api/hei/library/evaluation/${props.evaluation.id}/progress`)
        .then(response => {
            progress.value = response.data;
        })
        .catch(error => {
            console.error('Error updating evaluation:', error);
        });
};

function submitLink() {
    router.post('/hei/library/evaluation/link', {
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
            getProgress();
        },
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
}

function deleteLink() {
    router.post('/hei/library/evaluation/link/delete', {
        'id': props.evaluation.id,
        'item': toDelete.value,
    }, {
        onFinish: () => {
            deleteModal.value = false;
            getProgress();
        },
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
}

function submitTool() {
  const { isValid, errorMessage } = validateEvidence();

  if (!isValid) {
    // Show error message
    alert(errorMessage);
    completedModal.value = false;
    return;
  }
  
router.post('/hei/library/evaluation/submit', props.evaluation, {
    preserveState: false,
    preserveScroll: true,
    replace: true,
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
        preserveState: true,
        preserveScroll: true,
        replace: true,
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
    query.get("/hei/library/"+props.evaluation.id+"/edit", {
        preserveState: false,
        preserveScroll: true,
        replace: true,
    });
}


//Monitoring Report
const submitReport = reactive({
    orientation: "landscape",
    id: ref(props.tool),
});

function previewFile() {
    const url = `/report/library/monitoring/${props.evaluation.id}/${submitReport.orientation}/view`;
    window.open(url, "_blank");
}

function downloadFile() {
    const url = `/report/library/monitoring/${props.evaluation.id}/${submitReport.orientation}/download`;
    window.open(url, "_blank");
}
</script>