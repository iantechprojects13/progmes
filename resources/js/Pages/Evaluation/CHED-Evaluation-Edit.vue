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
                </div>
                <div class="w-full md:w-fit md:mt-0 mt-3 flex flex-col md:flex-row justify-center gap-2">
                        <Link :href="'/ched/evaluation/' + evaluation.id + '/report'">
                            <button
                                class="whitespace-nowrap w-full md:w-auto inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:ring-4 focus:ring-blue-300 focus:outline-none transition duration-150 ease-in-out disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                <span class="flex items-center">
                                Generate Report
                                </span>
                                </button>
                        </Link>
                        <button
                            @click="update" ref="saveBtn"
                            class=" whitespace-nowrap w-full md:w-auto inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 focus:outline-none transition duration-150 ease-in-out disabled:opacity-50 disabled:cursor-not-allowed"
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
        <template v-slot:sticky-div>
            <div class="w-full flex items-end px-3 md:px-5 py-2">
                <div class="w-full flex flex-row justify-between ml-2">
                    
                </div>
                <div class="flex flex-row">
                <button
                    @click="update"
                    class=" whitespace-nowrap w-full md:w-auto inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 focus:outline-none transition duration-150 ease-in-out disabled:opacity-50 disabled:cursor-not-allowed"
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
        <template v-slot:search>
            <div class="relative">
                <div
                    class="absolute inset-y-0 left-3 flex items-center pointer-events-none"
                >
                    <svg
                        class="w-5 h-5 text-gray-400"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                        />
                    </svg>
                </div>

                <input
                    @keydown.enter="submit"
                    v-model="query.search"
                    type="search"
                    id="content-search"
                    placeholder="Search"
                    class="w-full py-2.5 pl-11 pr-4 bg-white border border-gray-200 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors duration-200"
                />
            </div>
        </template>
        <template v-slot:options>
            <div class="flex gap-2">
                <button
                    @click="toggleFilterModal"
                    class="p-2.5 inline-flex items-center justify-center rounded-lg text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 transition-all duration-200 tooltipForActions"
                    data-tooltip="Filters"
                >
                    <svg
                        class="w-5 h-5"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"
                        />
                    </svg>
                    <span
                        class="absolute -top-8 left-1/2 -translate-x-1/2 px-2 py-1 bg-gray-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200"
                    >
                        Filters
                    </span>
                </button>

                <Link :href="`/ched/evaluation/${evaluation.id}/evaluate`">
                    <button
                        class="p-2.5 inline-flex items-center justify-center rounded-lg text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 transition-all duration-200 tooltipForActions"
                        data-tooltip="Refresh page"
                    >
                        <svg
                            class="w-5 h-5"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                            />
                        </svg>
                    </button>
                </Link>
            </div>
        </template>
        <template v-slot:main-content>
            <div class="p-3">
                <div
                    class="w-full p-2 md:p-8 my-3 md:shadow-lg md:border rounded-xl"
                >
                <div class="flex flex-col gap-6">
                    
                    <div class="grid md:grid-cols-2 gap-6">

                        <div class="flex items-center p-4 bg-gray-100 rounded-lg hover:bg-gray-200 transition-all">
                            <div class="w-full">
                                <p class="text-sm text-gray-500">HEI Name</p>
                                <div>
                                    {{ evaluation.institution_program.institution.name }}
                                </div>
                            </div>
                        </div>
            
                        
                        <div class="flex items-center p-4 bg-gray-100 rounded-lg hover:bg-gray-200 transition-all">
                            <div class="w-full">
                                <p class="text-sm text-gray-500">Program</p>
                                {{ evaluation.institution_program.program.program }}
                                <span v-if="evaluation.institution_program.program.major"> - {{ evaluation.institution_program.program.major }}</span>
                            </div>
                        </div>
                    </div>
            
                    <div class="grid md:grid-cols-2 gap-6">

                        <div class="flex items-center p-4 bg-gray-100 rounded-lg hover:bg-gray-200 transition-all">
                            <div class="w-full">
                                <p class="text-sm text-gray-500">Effectivity</p>
                                <div>
                                    AY: {{ evaluation.effectivity }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </div>

            <div class="p-3">
                <div class="w-full p-2 md:p-5 my-3 md:shadow-lg md:border rounded-xl">
            <content-table>
                <template v-slot:table-head>
                    <th class="p-2 text-sm bg-gray-300">Area/<br>Minimum Requirement</th>
                    <th class="p-2 text-sm bg-gray-300">Self-Evaluation Status/<br>Actual
                        Situation
                    </th>
                    <th class="p-2 text-sm bg-gray-300">Evidence</th>
                    <th class="p-2 text-sm bg-gray-300">Findings</th>
                    <th class="p-2 text-sm bg-gray-300">Comments/<br>Recommendations</th>
                    <th class="p-2 text-sm bg-gray-300">Evaluation Status</th>
                </template>

                <template v-slot:table-body>
                    <tr v-if="items.data.length == 0">
                        <no-search-result text="items" />
                    </tr>
                    <tr v-else v-for="(item, index) in items.data" :key="item.id" class="hover:bg-gray-200 align-top"
                        :class="{ 'bg-gray-100': index % 2 == 1 }">
                        <td class="p-2 text-justify max-w-[24rem]">
                            <div class="flex flex-col">
                                <div class="font-bold">
                                    {{ item.criteria.area }}
                                </div>
                                <div v-html="item.criteria.minimumRequirement"></div>
                            </div>
                        </td>
                        <td class="p-2 text-justify">
                            <status :text="item.selfEvaluationStatus" />
                            <div>
                                {{ item.actualSituation }}
                            </div>
                        </td>
                        <td class="h-auto p-2 flex flex-col justify-center">
                            <div class="flex flex-col"
                                :class="{ 'hidden': item.selfEvaluationStatus == 'Not applicable' }"
                                ref="evidenceFiles">
                                <div v-for="file in item.evidence" :key="file.id" class="w-full">
                                    <a class="px-1 text-blue-600 hover:text-blue-700 m-1 rounded" :href="file.url"
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
                        <td class="h-32 min-w-[16rem] p-2 relative group max-w-[16rem]">
                            <tip-tap-editor v-model="item.findings" @input="updateData"></tip-tap-editor>
                        </td>
                        <td class="h-32 min-w-[16rem] p-2 relative group">
                            <tip-tap-editor v-model="item.recommendations" @input="updateData"/>
                        </td>
                        <td class="p-3 min-w-5">
                            <div class="w-full h-full">
                                <select ref="evaluationStatus" v-model="item.evaluationStatus"
                                    :id="'evaluationStatus' + index" :name="'evaluationStatus' + index"
                                    @change="updateData" class="p-1 rounded w-32 border-gray-400 text-sm">
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
        </div>
        </div>
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
</template>

<script setup>

import { ref, onMounted, onUnmounted, reactive, computed, inject } from 'vue';
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
    if (hasUnsavedChanges.value) {
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


const evaluationStatus = ref([]);
const actionButtons = ref([]);
const evidenceFiles = ref([]);
const saveBtn = ref(null);

// Inject the reactive object
const hasUnsavedChanges = inject("hasUnsavedChanges");

// Function to update value
const updateData = () => {
    hasUnsavedChanges.value = true;
};


const refs = { evaluationStatus, actionButtons, evidenceFiles, saveBtn };



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
        onSuccess: () => {
            hasUnsavedChanges.value = false;
        },
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