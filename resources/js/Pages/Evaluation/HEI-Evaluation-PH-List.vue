<template>
    <Head title="Program Self-Evaluation" />
    <page-title title="Program Self-Evaluation" />
    <div class="md:mx-8 mx-3 mt-8">
        <div class="w-full p-2 md:p-5 my-3 md:shadow-lg md:border rounded-xl">
            <div class="flex flex-col gap-6">
                <div class="grid md:grid-cols-2 gap-6">
                    <div
                        class="flex items-center p-4 bg-gray-100 rounded-lg hover:bg-gray-200 transition-all"
                    >
                        <div class="w-full">
                            <p class="text-sm text-gray-500">HEI Name</p>
                            <div>
                                {{ program?.institution?.name }}
                            </div>
                        </div>
                    </div>

                    <div
                        class="flex items-center p-4 bg-gray-100 rounded-lg hover:bg-gray-200 transition-all"
                    >
                        <div class="w-full">
                            <p class="text-sm text-gray-500">Program</p>
                            {{ program?.program?.program }}
                            <span v-if="program?.program?.major">
                                -
                                {{ program?.program?.major }}</span
                            >
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <content-container :hasSearch="true" :hasFilters="true" :hideTopBorder="true" :data_list="evaluationTools">
        <template v-slot:search>
            <search-box v-model="query.search" @submit="filter" />
        </template>
        <template v-slot:options>
            <options @filter="toggleFilterModal" href="/hei/ph/evaluation" />
        </template>
        <template v-slot:main-content>
            <content-table>
                <template v-slot:table-head>
                    <th>Program/Academic Year</th>
                    <th>Status</th>
                    <th>
                        <i class="fas fa-ellipsis-v text-lg"></i>
                    </th>
                </template>
                <template v-slot:table-body>
                    <tr v-if="evaluationTools.data.length == 0">
                        <no-search-result text="compliance evaluation tool" />
                    </tr>
                    <tr
                        v-else 
                        v-for="item in evaluationTools.data"
                        :key="item.id"
                    >
                        <td class="max-w-[64]">
                            {{ item.program }}
                            <span v-if="item.major">
                                - {{ item.major }}
                            </span>
                        </td>
                        <td>
                            <status :text="item.status" />
                        </td>
                        <td>
                            <div class="flex justify-end gap-0">
                                <button
                                    :disabled="starting"
                                    class="inline-flex items-center justify-center rounded-full h-10 w-24 text-white bg-blue-600 hover:bg-blue-700 tooltipForActions"
                                    @click="createTool(item.id)"
                                    v-show="item.status === 'Deployed'"
                                >
                                    Start now
                                </button>
                                <button
                                    v-show="item.status === 'In progress'"
                                    @click="edit(item.id)"
                                    class="inline-flex items-center justify-center rounded-full h-10 w-10 text-blue-700 hover:bg-blue-100 tooltipForActions"
                                    data-tooltip="Edit"
                                >
                                    <i class="fas fa-edit text-lg"></i>
                                </button>
                                <button
                                    @click="view(item.id)"
                                    class="inline-flex items-center justify-center rounded-full h-10 w-10 text-green-700 hover:bg-green-100 tooltipForActions"
                                    data-tooltip="View"
                                    v-show="item.status != 'Deployed'"
                                >
                                    <i class="fas fa-eye text-lg"></i>
                                </button>
                            </div>
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
            <!-- Active Status Filter -->
            <div class="flex flex-col space-y-2 mb-6">
                <label for="isActive" class="text-sm font-medium text-gray-700"
                    >Status</label
                >
                <select
                    v-model="query.status"
                    id="isActive"
                    class="block w-full px-3 py-2 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
                    <option :value="null">All</option>
                    <option value="In progress">In Progress</option>
                    <option value="Submitted">Completed</option>
                    <option value="For Re-evaluation">For Re-evaluation</option>
                </select>
            </div>
            <div class="flex flex-col space-y-2 mb-6">
                <label for="isActive" class="text-sm font-medium text-gray-700"
                    >Academic Year</label
                >
                <select
                    v-model="query.academicYear"
                    id="isActive"
                    class="block w-full px-3 py-2 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
                    <option :value="null">All</option>
                    <option value="2023-2024">2023-2024</option>
                    <option value="2024-2025">2024-2025</option>
                    <option value="2025-2026">2025-2026</option>
                    <option value="2026-2027">2026-2027</option>
                    <option value="2027-2028">2027-2028</option>
                    <option value="2028-2029">2028-2029</option>
                    <option value="2029-2030">2029-2030</option>
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
                Apply
            </button>
        </template>
    </modal>
</template>

<script setup>
import { ref } from "vue";
import { router, useForm } from "@inertiajs/vue3";
import Layout from "@/Shared/Layout.vue";
defineOptions({ layout: Layout });

const props = defineProps(["program", "evaluationTools", "filters"]);

const starting = ref(false);
const showFilterModal = ref(false);

function toggleFilterModal() {
    showFilterModal.value = !showFilterModal.value;
}


const query = useForm({
    show: props.filters.show != null ? props.filters.show : null,
    search: props.filters.search,
    academicYear: props.filters.academicYear != null ? props.filters.academicYear : null,
    status: props.filters.status != null ? props.filters.status : null,
});

function edit(tool) {
    router.get("/hei/evaluation/" + tool + "/edit");
}

function view(tool) {
    router.get("/hei/evaluation/" + tool + "/view");
}

function createTool(item) {
    router.post(
        "/hei/tool/create",
        { id: item },
        {
            onStart: () => {
                starting.value = true;
            },
            onFinish: () => {
                starting.value = false;
            },
            preserveState: false,
            preserveScroll: true,
            replace: true,
        }
    );
}

function filter() {
    query.get("/hei/ph/evaluation", {
        preserveState: false,
        preserveScroll: true,
        replace: true,
    });
}
</script>
