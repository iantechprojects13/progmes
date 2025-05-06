<template>

    <Head title="Program Self-Evaluation" />
    <page-title title="Program Evaluation" />
    <content-container :hasNavigation="true" :pageTitle="institution" :hasSearch="true" :hasTopButton="true" :hasFilters="true" :data_list="complianceTools" :hideTopBorder="true">
        <template v-slot:top-button>
            <select @change.prevent="filter" v-model="query.academicYear" id="academicYearSelect" class="px-4 pr-10 py-2.5 text-sm bg-white border border-slate-200 text-slate-700 rounded-lg hover:border-slate-300 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 focus:outline-none duration-200 cursor-pointer appearance-none whitespace-nowrap">
                <option value="2022-2023">A.Y. 2022-23</option>
                <option value="2023-2024">A.Y. 2023-24</option>
                <option value="2024-2025">A.Y. 2024-25</option>
                <option value="2025-2026">A.Y. 2025-26</option>
                <option value="2026-2027">A.Y. 2026-27</option>
                <option value="2027-2028">A.Y. 2027-28</option>
                <option value="2028-2029">A.Y. 2028-29</option>
                <option value="2029-2030">A.Y. 2029-30</option>
            </select>
        </template>
        <template v-slot:navigation>
            <main-content-nav
                btnText="Evaluation"
                routeName="evaluation.hei"
                :isActive="true"
            />
            <main-content-nav
                btnText="Monitored"
                routeName="evaluation.hei.monitored"
            />
        </template>
        <template v-slot:search>
            <search-box v-model="query.search" @submit="filter"></search-box>
        </template>
        <template v-slot:options>
            <options @filter="toggleFilterModal" href="/hei/evaluation"/>
        </template>
        <template v-slot:main-content>
            <content-table>
                <template v-slot:table-head>
                    <th>Program</th>
                    <th>Status</th>
                    <th>Progress</th>
                    <th>
                        <i class="fas fa-ellipsis-v text-lg"></i>
                    </th>
                </template>

                <template v-slot:table-body>
                    <tr v-if="complianceTools.data.length == 0">
                        <no-search-result text="tool" />
                    </tr>
                    <tr v-else v-for="item in complianceTools.data" :key="item.id">
                        <td>
                            {{ item.program }}
                        </td>
                        <td>
                            <status :text="item.status" />
                        </td>
                        <td>
                            <evaluation-progress :percentage="item.progress"></evaluation-progress>
                        </td>
                        <td>
                            <button 
                                v-show="canEdit"
                                @click="edit(item.id)"
                                :disabled="item.status != 'In progress'"
                                :class="{'text-gray-400 hover:bg-gray-100': item.status != 'In progress', 'text-blue-700 hover:bg-blue-100': item.status == 'In progress'}"
                                class="inline-flex items-center justify-center rounded-full h-10 w-10 tooltipForActions disabled:cursor-not-allowed"
                                data-tooltip="Edit">
                                <i class="fas fa-edit text-lg"></i>
                            </button>
                            <button @click="view(item.id)"
                                class="inline-flex items-center justify-center rounded-full h-10 w-10 text-emerald-700 hover:bg-emerald-100 tooltipForActions"
                                data-tooltip="View">
                                <i class="fas fa-eye text-lg"></i>
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
import { ref } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import Layout from '@/Shared/Layout.vue';
defineOptions({ layout: Layout });

    const props = defineProps([
        'complianceTools',
        'filters',
        'institution',
        'canEdit',
    ]);

const showFilterModal = ref(false);

function toggleFilterModal() {
    showFilterModal.value = !showFilterModal.value;
}

    const query = useForm({
        academicYear: props.filters.academicYear,
        show: props.filters.show,
        search: props.filters.search,
        status: props.filters.status != null ? props.filters.status : null,
    });

    function view(tool) {
        router.get('/hei/evaluation/' + tool + '/view');
}

function edit(tool) {
        router.get('/hei/evaluation/' + tool + '/edit');
    }

    function filter() {
        query.get("/hei/evaluation", {
            preserveState: false,
            preserveScroll: true,
            replace: true,
        });
    }

</script>
