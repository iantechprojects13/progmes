<template>
    <Head title="Library Compliance Evaluation Tool" />
    <page-title title="Library Compliance Evaluation" />
    <content-container
        :hasTopMainContent="true"
        :hasSearch="true"
        :hasFilters="true"
        :hasBackButton="true"
    >
        <template v-slot:content-title>
            <div
                class="w-full flex flex-col md:flex-row justify-between items-center"
            >
                <div class="w-full flex flex-row items-center">
                    <div class="font-bold">Evaluate</div>
                </div>
                <div
                    class="w-full md:w-fit md:mt-0 mt-3 flex flex-col md:flex-row justify-center gap-2"
                >
                    <Link
                        :href="'/ched/library/evaluation/' + evaluation.id + '/report'"
                    >
                        <button
                            class="whitespace-nowrap w-full md:w-auto inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:ring-4 focus:ring-blue-300 focus:outline-none transition duration-150 ease-in-out disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <span class="flex items-center">
                                Generate Report
                            </span>
                        </button>
                    </Link>
                </div>
            </div>
        </template>
        <template v-slot:search>
            <search-box v-model="query.search" @submit="filter" />
        </template>
        <template v-slot:options>
            <options
                @filter="toggleFilterModal"
                :href="`/ched/library/evaluation/${evaluation.id}/evaluate`"
            />
        </template>
        <template v-slot:main-content>
            <tool-info 
                :data="{
                    'HEI Name': evaluation.institution?.name,
                    'Effectivity': `A.Y. ${evaluation.effectivity}`,
                }"
            />

            <div class="p-3">
                <div
                    class="w-full p-2 md:p-5 my-3 md:shadow-lg md:border rounded-xl"
                >
                    <content-table>
                        <template v-slot:table-head>
                            <th>
                                Area/<br />Minimum Requirement
                            </th>
                            <th>
                                Self-Evaluation Status/<br />Actual Situation
                            </th>
                            <th>Evidence</th>
                            <th>Findings</th>
                            <th>
                                Evaluation Status
                            </th>
                            <th>
                                Comments/<br />Recommendations
                            </th>
                            <th></th>
                        </template>

                        <template v-slot:table-body>
                            <tr v-if="items.data.length == 0">
                                <no-search-result text="items" />
                            </tr>
                            <tr
                                v-else
                                v-for="(item, index) in items.data"
                                :key="item.id"
                            >
                                <td class="max-w-[24rem]">
                                    <div class="flex flex-col">
                                        <div class="font-bold" v-html="item.criteria.area">
                                        </div>
                                        <div
                                            v-html="
                                                item.criteria.minimumRequirement
                                            "
                                        ></div>
                                    </div>
                                </td>
                                <td>
                                    <status :text="item.selfEvaluationStatus" />
                                    <div v-html="item.actualSituation">
                                    </div>
                                </td>
                                <td>
                                    
                                        <div
                                            v-for="evidence in item.evidence"
                                            :key="evidence.id"
                                            class="w-full"
                                        >
                                            <a
                                                class="px-1 text-blue-600 hover:text-blue-700 mb-1"
                                                :href="evidence.url"
                                                target="_blank"
                                            > Link
                                            </a>
                                    </div>
                                </td>
                                <td
                                >
                                    <tip-tap-editor
                                        :hasSaveBtn="false"
                                        v-model="item.findings"
                                        @input="updateData(); getUpdatedRowId(item.id)"
                                        @update="update"
                                        title="Findings"
                                    ></tip-tap-editor>
                                </td>
                                <td>
                                    <select
                                        ref="evaluationStatus"
                                        v-model="item.evaluationStatus"
                                        :id="'evaluationStatus' + index"
                                        :name="'evaluationStatus' + index"
                                        @change="updateData(); getUpdatedRowId(item.id)"
                                        class="rounded w-32 border-gray-400 text-sm pr-6"
                                    >
                                        <option :value="null">
                                            Select status
                                        </option>
                                        <option value="Complied">
                                            Complied
                                        </option>
                                        <option value="Not complied">
                                            Not complied
                                        </option>
                                        <option value="Not applicable">
                                            Not applicable
                                        </option>
                                    </select>
                                </td>
                                <td
                                >
                                    <tip-tap-editor
                                        :hasSaveBtn="false"
                                        v-model="item.recommendations"
                                        @input="updateData(); getUpdatedRowId(item.id)"
                                        @update="update"
                                        title="Comments/Recommendations"
                                    />
                                </td>
                                <td></td>
                            </tr>
                        </template>
                    </content-table>
                </div>
            </div>
        </template>
    </content-container>

    <modal
        :showModal="showFilterModal"
        @close="toggleFilterModal"
        width="md"
        title="Filters"
    >
        <div class="flex flex-col space-y-4">
            <filter-compliance-status v-model="query.selfEvaluationStatus" type="HEI"/>
            <filter-compliance-status v-model="query.evaluationStatus" />
        </div>
        <template v-slot:custom-button>
            <modal-button text="Apply" color="green" @click="filter"/>
        </template>
    </modal>

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
// ------------------------------------------------------------------------------------------------------
import { ref, onMounted, onUnmounted, onBeforeUnmount, reactive, computed, inject } from "vue";
import { router, useForm } from "@inertiajs/vue3";
import axios from "axios";
import Layout from "@/Shared/Layout.vue";
defineOptions({ layout: Layout });

// ------------------------------------------------------------------------------------------------------
const props = defineProps([
    "evaluation",
    "items",
    "filters",
]);

// ------------------------------------------------------------------------------------------------------
const evaluationStatus = ref([]);
const saveBtn = ref(null);
const showFilterModal = ref(false);

// Inject the reactive object
const hasUnsavedChanges = inject("hasUnsavedChanges");
const hasChanges = ref(false);
const autoSaveTimeout = ref(null);
const allChangesSaved = ref(false);
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


const refs = { evaluationStatus, saveBtn };

const itemsArray = computed(() => {
    return props.items.data.map((item) => ({
        id: item.id,
        findings: item.findings,
        recommendations: item.recommendations,
        evaluationStatus: item.evaluationStatus,
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

const query = useForm({
    search: props.filters.search,
    selfEvaluationStatus:
        props.filters.selfEvaluationStatus != null
            ? props.filters.selfEvaluationStatus
            : null,
    evaluationStatus:
        props.filters.evaluationStatus != null
            ? props.filters.evaluationStatus
            : null,
});

// CTRL + S function
// const handleKeyDown = (event) => {
//     if (event.ctrlKey || event.metaKey) {
//         if (event.key === "s" || event.key === "S") {
//             event.preventDefault();
//             refs.saveBtn.value.click();
//         }
//     }
// };

// ------------------------------------------------------------------------------------------------------
onMounted(() => {
    // window.addEventListener("keydown", handleKeyDown);
    window.addEventListener("beforeunload", handleBeforeUnload);
});

onUnmounted(() => {
    // window.removeEventListener("keydown", handleKeyDown);
    window.removeEventListener("beforeunload", handleBeforeUnload);
});

onBeforeUnmount(() => {
    if (autoSaveTimeout.value) {
        clearTimeout(autoSaveTimeout.value);
    }
});

function toggleFilterModal() {
    showFilterModal.value = !showFilterModal.value;
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
        
        await axios.post('/ched/library/evaluation/update', form)
            .then(response => {
                hasUnsavedChanges.value = false;
                saving.value = false;
                allChangesSaved.value = true;
                // Clear the rowsWithUpdates array after successful save
                rowsWithUpdates.length = 0;
                
            })
            .catch(error => {
                console.error('Error updating evaluation:', error);
                saving.value = false;
            });
    }
};

function filter() {
    query.get("/ched/library/evaluation/" + props.evaluation.id + "/evaluate", {
        preserveState: false,
        preserveScroll: true,
        replace: true,
    });
}

</script>

