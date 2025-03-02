<template>

    <Head title="Program Evaluation Monitored List" />
    <page-title title="Program Evaluation" />
    <content-container hasSearch="true" pageTitle="Monitored" page="monitored" :hasNavigation="true" :hasTopButton="true" :hasFilters="true" :data_list="complianceTools">
        <template v-slot:top-button>
            <select @change="submit" v-model="query.academicYear" id="academicYearSelect" class="h-10 border border-gray-400 hover:border-gray-500 cursor-pointer rounded text-sm">
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
            <Link href="/evaluation">
            <button class="select-none h-12 w-28 hover:bg-gray-100 text-gray-700 hover:text-black">
                Evaluation
            </button>
            </Link>
            <button class="select-none h-12 w-28 hover:bg-gray-100 text-blue-500 border-b-4 font-bold border-blue-500">
                Monitored
            </button>
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
                    class="w-10 h-10 whitespace-nowrap hover:bg-gray-200 rounded-full text-gray-700 hover:text-blue-500 active:text-white active:bg-blue-600 tooltipForActions"
                    data-tooltip="Filters">
                    <i class="fas fa-filter"></i>
                </button>
                <Link href="/ched/evaluation/monitored">
                <button
                    class="w-10 h-10 whitespace-nowrap hover:bg-gray-200 rounded-full text-gray-700 hover:text-blue-500 active:text-white active:bg-blue-600 tooltipForActions"
                    data-tooltip="Refresh page">
                    <i class="fas fa-refresh"></i>
                </button>
                </Link>
            </div>
        </template>
        <template v-slot:main-content>
            <content-table>
                <template v-slot:table-head>
                    <th class="p-2 pl-5">Program/Institution</th>
                    <th class="p-2">Monitoring/Evaluation Date</th>
                    <th class="p-2">Archive Date</th>
                    <th class="p-2 pr-5 text-right">
                        <i class="fas fa-ellipsis-v"></i>
                    </th>
                </template>

                <template v-slot:table-body>
                    <tr v-if="complianceTools.data.length == 0">
                        <td colspan="3" class="p-3 py-10 text-center">No evaluation tool found</td>
                    </tr>
                    <tr v-else v-for="(item, index) in complianceTools.data" :key="item.id" class="hover:bg-gray-200"
                        :class="{'bg-gray-100': index % 2 == 1}">
                        <td class="p-2 pl-5">
                            <div>
                                {{ item.program }}
                            </div>
                            <div class="font-bold">
                                {{ item.institution }}
                            </div>
                        </td>
                        <td class="p-2">
                            <div class="text-sm px-1 py-0.5 rounded w-fit">
                                {{ item.evaluationDate }}
                            </div>
                        </td>
                        <td class="p-2">
                            <div class="text-sm px-1 py-0.5 rounded w-fit">
                                {{ item.archivedDate }}
                            </div>
                        </td>
                        <td class="p-2 pr-5 text-right whitespace-nowrap">
                            <Link :href="'/ched/evaluation/' + item.id + '/view'">
                                <button class="select-none h-8 w-8 text-xl text-center rounded-full hover:bg-gray-300 text-green-500 hover:text-green-600 tooltipForActions" data-tooltip="View"><i class="fas fa-eye"></i></button>
                            </Link>
                        </td>
                    </tr>
                </template>
            </content-table>
        </template>
    </content-container>
    <modal :showModal="showFilterModal" @close="toggleFilterModal" width="sm" height="long" title="Filters">
        <div>
            <div class="flex flex-col">
                <label for="show">Items per page</label>
                <select v-model="query.show" id="show" class="rounded border-gray-400">
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="200">200</option>
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
import { router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps([
    'complianceTools',
    'filters',
    'canEvaluate',
]);

const showFilterModal = ref(false);
const processing = ref(false);

const query = useForm({
    show: props.filters.show != null ? props.filters.show : null,
    search: props.filters.search,
    academicYear: props.filters.academicYear != null ? props.filters.academicYear : null,
});

function toggleFilterModal() {
    showFilterModal.value = !showFilterModal.value;
}

function submit() {
    query.get("/ched/evaluation/monitored", {
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
    query.get("/ched/evaluation/monitored", {
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
    import Layout from '@/Shared/Layout.vue';

    export default {
        layout: Layout,
    }
</script>