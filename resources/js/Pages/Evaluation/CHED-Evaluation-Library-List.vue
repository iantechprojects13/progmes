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
        <template v-slot:top-button>
            <div
                class="flex flex-col md:flex-row gap-3 w-full whitespace-nowrap"
            >

                <select
                    v-model="query.ay"
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
        </template>

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
                            {{ item.institution?.name }}
                        </td>
                        <td>
                            <status :text="item.status" />
                        </td>
                        <td>
                            <button 
                                @click="view(item.id)"
                                class="inline-flex items-center justify-center rounded-full h-10 w-10 text-emerald-700 hover:bg-emerald-100 tooltipForActions"
                                data-tooltip="View">
                                <i class="fas fa-eye text-lg"></i>
                            </button>

                            <button 
                                v-show="canEvaluate"
                                @click="evaluate(item.id)"
                                :disabled="item.status === 'In progress'"
                                :class="{'text-gray-400 hover:bg-gray-100': item.status === 'In progress', 'text-blue-700 hover:bg-blue-100': item.status !== 'In progress'}"
                                class="inline-flex items-center justify-center rounded-full h-10 w-10 tooltipForActions disabled:cursor-not-allowed"
                                data-tooltip="Evaluate">
                                <i class="fas fa-edit text-lg"></i>
                            </button>

                            <button 
                                v-show="canEvaluate" 
                                v-if="item.isLocked"
                                @click="setId(item.id); unlockModal = true;"
                                class="inline-flex items-center justify-center rounded-full h-10 w-10 text-gray-700 hover:bg-gray-100 tooltipForActions"
                                data-tooltip="Unlock">
                                <i class="fas fa-unlock text-lg"></i>
                            </button>

                            <button 
                                v-show="canEvaluate" 
                                v-else
                                @click="setId(item.id); lockModal = true;"
                                class="inline-flex items-center justify-center rounded-full h-10 w-10 text-red-700 hover:bg-red-100 tooltipForActions"
                                data-tooltip="Lock">
                                <i class="fas fa-lock text-lg"></i>
                            </button>

                            <!-- <button 
                                v-show="canEmail"
                                @click="setProgress(item.progress); toggleEmailModal(); email.toolId = item.id;"
                                class="inline-flex items-center justify-center rounded-full h-10 w-10 text-orange-700 hover:bg-orange-100 tooltipForActions"
                                data-tooltip="Send Email">
                                <i class="fas fa-envelope text-lg"></i>
                            </button> -->

                            <button 
                                v-show="canEvaluate"
                                @click="setId(item.id); monitoredModal = true;"
                                :disabled="item.status === 'In progress'"
                                :class="{'text-gray-400 hover:bg-gray-100': item.status === 'In progress', 'text-blue-700 hover:bg-blue-100': item.status !== 'In progress'}"
                                class="inline-flex items-center justify-center rounded-full h-10 w-10 tooltipForActions disabled:cursor-not-allowed"
                                data-tooltip="Mark as monitored">
                                <i class="fas fa-check text-lg"></i>
                            </button>
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
        <div>
            <!-- Status Filter -->
            <div class="flex flex-col space-y-2 mb-6">
                <label for="status" class="text-sm font-medium text-gray-700">
                    Status
                </label>
                <select
                    v-model="query.status"
                    id="status"
                    class="block w-full px-3 py-2 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
                    <option :value="null">All</option>
                    <option value="In progress">In Progress</option>
                    <option value="Submitted">Completed</option>
                    <option value="Monitored">Monitored</option>
                </select>
            </div>

            <!-- Items per page Filter -->
            <div class="flex flex-col space-y-2">
                <label for="show" class="text-sm font-medium text-gray-700">
                    Items per page
                </label>
                <select
                    v-model="query.show"
                    id="show"
                    class="block w-full px-3 py-2 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="150">150</option>
                    <option value="200">200</option>
                    <option value="300">300</option>
                </select>
            </div>
        </div>

        <template #custom-button>
            <button
                @click="filter"
                class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 duration-200 w-20 h-10"
            >
                <span v-if="processing">
                    <i class="fas fa-spinner animate-spin"></i>
                </span>
                <span v-else>Apply</span>
            </button>
        </template>
    </modal>

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
const processing = ref(false);
const academicYearDropdown = [
    "2022-2023",
    "2023-2024",
    "2024-2025",
    "2025-2026",
    "2026-2027",
    "2027-2028",
    "2028-2029",
    "2029-2030",
];


const query = useForm({
    ay: props.filters.academicYear,
    show: props.filters.show != null ? props.filters.show : null,
    search: props.filters.search,
    status: props.filters.status != null ? props.filters.status : null,
});

// -------------------------------------------------------------------------

function toggleFilterModal() {
    showFilterModal.value = !showFilterModal.value;
}

function filter() {
    query.get("/ched/library/evaluation", {
        preserveState: false,
        preserveScroll: true,
        replace: true,
    });
}

function changeAcademicYear() {
    query.search = "";
    query.get("/ched/library/evaluation", {
        preserveState: false,
        preserveScroll: true,
        replace: true,
    });
}

function evaluate(tool) {
    router.get('/ched/library/evaluation/' + tool + '/evaluate', {
        preserveScroll: true,
        preserveState: true,
    });
}

function view(tool) {
    router.get('/ched/library/evaluation/' + tool + '/view');
}

</script>
