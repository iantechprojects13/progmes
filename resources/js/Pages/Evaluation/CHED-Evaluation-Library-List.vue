<template>
    <Head title="Library Compliance Evaluation" />
    <PageTitle title="Library Evaluation" />
    <content-container
        :hasModal="true"
        pageTitle="Library Compliance Evaluation"
        page="library"
        :hasTopButton="true"
        :hasSearch="true"
        :hasFilters="true"
        :data_list="evaluation_list"
    >
        <!-- <template v-slot:top-button>
            <div
                class="flex flex-col md:flex-row gap-3 w-full whitespace-nowrap"
            >

                <select
                    v-model="query.academicyear"
                    @change.prevent="changeAcademicYear"
                    class="px-4 pr-10 py-2.5 text-sm bg-white border border-slate-200 text-slate-700 rounded-lg hover:border-slate-300 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 focus:outline-none duration-200 cursor-pointer appearance-none whitespace-nowrap"
                >
                    <option
                        v-for="(item, index) in academicYearDropdown"
                        :key="index"
                        :value="item"
                    >
                        A.Y. {{ item }}
                    </option>
                </select>
            </div>
        </template> -->
        
        <template v-slot:search>
            <search-box v-model="query.search" @submit="filter" />
        </template>
        <template v-slot:options>
            <options @filter="toggleFilterModal" href="/library" />
        </template>

        <template v-slot:main-content>
            <content-table>
                <template v-slot:table-head>
                    <th>HEI</th>
                    <th>Status</th>
                    <th>Progress</th>
                    <th>Compliance Rate</th>
                    <th>
                        <i class="fas fa-ellipsis-v text-lg"></i>
                    </th>
                </template>
                <template v-slot:table-body>
                    <tr v-if="evaluation_list.data.length == 0">
                        <no-search-result text="compliance evaluation tool" />
                    </tr>
                    <tr
                        v-else
                        v-for="item in evaluation_list.data"
                        :key="item.id"
                        >
                        <td>
                            {{ item.institution }}
                        </td>
                        <td>
                            <status :text="item.status" />
                        </td>
                        <td>
                            <evaluation-progress :percentage="item.progress" />
                        </td>
                        <td class="text-sm font-semibold">
                            {{ item.complianceRate }}%
                        </td>
                        <td>
                            <!-- <button 
                                @click="view(item.id)"
                                class="inline-flex items-center justify-center rounded-full h-10 w-10 text-emerald-700 hover:bg-emerald-100 tooltipForActions"
                                data-tooltip="View">
                                <i class="fas fa-eye text-lg"></i>
                            </button> -->
                            <action-button type="view" @click="view(item.id) "/>
                            <action-button :isDisabled="item.status === 'In progress'" v-show="canEvaluate" type="evaluate" @click="evaluate(item.id)"/>

                            <!-- <button 
                                v-show="canEvaluate"
                                @click="evaluate(item.id)"
                                :disabled="item.status === 'In progress'"
                                :class="{'text-gray-400 hover:bg-gray-100': item.status === 'In progress', 'text-blue-700 hover:bg-blue-100': item.status !== 'In progress'}"
                                class="inline-flex items-center justify-center rounded-full h-10 w-10 tooltipForActions disabled:cursor-not-allowed"
                                data-tooltip="Evaluate">
                                <i class="fas fa-edit text-lg"></i>
                            </button> -->

                            <!-- <button 
                                v-show="canEvaluate" 
                                v-if="item.isLocked"
                                @click="setId(item.id); unlockModal = true;"
                                class="inline-flex items-center justify-center rounded-full h-10 w-10 text-gray-700 hover:bg-gray-100 tooltipForActions"
                                data-tooltip="Unlock">
                                <i class="fas fa-unlock text-lg"></i>
                            </button> -->

                            <!-- <button 
                                v-show="canEvaluate" 
                                v-else
                                @click="setId(item.id); lockModal = true;"
                                class="inline-flex items-center justify-center rounded-full h-10 w-10 text-red-700 hover:bg-red-100 tooltipForActions"
                                data-tooltip="Lock">
                                <i class="fas fa-lock text-lg"></i>
                            </button> -->

                            <action-button 
                                v-if="item.isLocked"
                                v-show="canEvaluate"
                                type="unlock"
                                @click="selectedTool = item.id; toggleLockConfirmation(); lockAction = 'unlock'; title = 'Unlock Tool'"
                            />
                            <action-button 
                                v-else
                                v-show="canEvaluate"
                                type="lock"
                                @click="selectedTool = item.id; toggleLockConfirmation(); lockAction = 'lock'; title = 'Lock Tool'"
                            />

                            <!-- <button 
                                v-show="canEvaluate"
                                @click="setId(item.id); monitoredModal = true;"
                                :disabled="item.status === 'In progress'"
                                :class="{'text-gray-400 hover:bg-gray-100': item.status === 'In progress', 'text-blue-700 hover:bg-blue-100': item.status !== 'In progress'}"
                                class="inline-flex items-center justify-center rounded-full h-10 w-10 tooltipForActions disabled:cursor-not-allowed"
                                data-tooltip="Mark as monitored">
                                <i class="fas fa-check text-lg"></i>
                            </button> -->
                        </td>
                    </tr>
                </template>
            </content-table>
        </template>
    </content-container>

    <modal
        :showModal="showFilterModal"
        @close="toggleFilterModal"
        width="md"
        title="Filters"
        class="antialiased"
    >
        <div class="flex flex-col space-y-4">
            <filter-form-status v-model="query.status"/>
            <filter-academic-year v-model="query.academicyear"/>
        </div>

        <template #custom-button>
            <modal-button text="Apply" color="green" @click="filter"/>
        </template>
    </modal>

    <confirmation
        :showModal="lockModal"
        @close="toggleLockConfirmation"
        :title="title"
        width="md"
        class="antialiased"
    >
        <template #message>
            <div v-if="lockAction == 'lock'">
                Are you sure you want to lock this library compliance evaluation tool? This action will prevent any further inputs from HEI.
            </div>
            <div v-else>
                Are you sure you want to unlock this compliance evaluation tool? This action will enable inputs from HEI.
            </div>
        </template>
        <template #buttons>
            <modal-button v-if="lockAction == 'lock'" text="Lock" color="red" @click="lock"/>
            <modal-button v-else text="Unlock" color="blue" @click="unlock"/>
        </template>
    </confirmation>
</template>

<script setup>
// -------------------------------------------------------------------------
import { useForm, router } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import Layout from "@/Shared/Layout.vue";
import PageTitle from "@/Shared/PageTitle.vue";
defineOptions({ layout: Layout });

// -------------------------------------------------------------------------
const props = defineProps([
    "evaluation_list",
    "filters",
    'canEvaluate',
]);

// -------------------------------------------------------------------------
const showFilterModal = ref(false);
const lockModal = ref(false);
const selectedTool = ref(null);
const lockAction = ref('');
const title = ref('');

// const academicYearDropdown = [
//     "2022-2023",
//     "2023-2024",
//     "2024-2025",
//     "2025-2026",
//     "2026-2027",
//     "2027-2028",
//     "2028-2029",
//     "2029-2030",
// ];

const query = useForm({
    academicyear: props.filters.academicYear,
    show: props.filters.show != null ? props.filters.show : null,
    search: props.filters.search,
    status: props.filters.status != null ? props.filters.status : null,
    sort: props.filters.sort != null ? props.filters.sort: null,
    direction: props.filters.direction != null ? props.filters.direction : 'asc',
});

// -------------------------------------------------------------------------

function toggleFilterModal() {
    showFilterModal.value = !showFilterModal.value;
}

function toggleLockConfirmation() {
    lockModal.value = !lockModal.value;
}

function filter() {
    query.get("/ched/library/evaluation", {
        preserveState: false,
        preserveScroll: true,
        replace: true,
    });
}

// function changeAcademicYear() {
//     query.search = "";
//     query.get("/ched/library/evaluation", {
//         preserveState: false,
//         preserveScroll: true,
//         replace: true,
//     });
// }

function evaluate(tool) {
    router.get('/ched/library/evaluation/' + tool + '/evaluate', {
        preserveScroll: true,
        preserveState: true,
    });
}

function view(tool) {
    router.get('/ched/library/evaluation/' + tool + '/view');
}

function lock() {
    router.post('/ched/library/evaluation/lock', { id: selectedTool.value }, {
        replace: true,
        preserveScroll: true,
        preserveState: false,
    })
}

function unlock() {
    router.post('/ched/library/evaluation/unlock', { id: selectedTool.value }, {
        replace: true,
        preserveScroll: true,
        preserveState: false,
    })
}
</script>
