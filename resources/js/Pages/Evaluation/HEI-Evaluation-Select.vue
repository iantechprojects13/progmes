<template>

    <Head title="Program Self-Evaluation" />
    <page-title title="Program Evaluation" />
    <div class="mx-3 md:mx-8 mt-8 border border-gray-300 bg-white rounded p-3 flex flex-col lg:flex-row items-center text-left justify-between">
        <div class="text-left w-full"><i class="fas fa-institution mr-3 text-blue-500"></i>{{ institution }}</div>
        <div class="mt-5 lg:mt-0 w-full lg:w-auto">
            <select @change="submit" v-model="query.academicYear" id="academicYearSelect" class="w-full border-gray-400 hover:border-gray-500 cursor-pointer lg:w-40 h-10 rounded text-sm">
                <option value="2024-2025">A.Y. 2024-25</option>
                <option value="2025-2026">A.Y. 2025-26</option>
                <option value="2026-2027">A.Y. 2026-27</option>
                <option value="2027-2028">A.Y. 2027-28</option>
                <option value="2028-2029">A.Y. 2028-29</option>
                <option value="2029-2030">A.Y. 2029-30</option>
            </select>
        </div>
    </div>
    <content-container :hasNavigation="true" :hasSearch="true" :hasFilters="true" :data_list="complianceTools" :hideTopBorder="true">
        <template v-slot:navigation>
            <div>
                <button class="select-none h-12 w-28 hover:bg-gray-100 text-blue-500 border-b-4 relative font-bold border-blue-500">
                    Evaluation
                </button>
                <Link :href="route('evaluation.hei.monitored')">
                <button class="select-none h-12 w-28 hover:bg-gray-100 text-gray-700 hover:text-black">
                    Monitored
                </button>
                </Link>
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
                    class="w-10 h-10 whitespace-nowrap hover:bg-gray-200 rounded-full text-gray-700 hover:text-blue-500 active:text-white active:bg-blue-600 tooltipForActions"
                    data-tooltip="Filters">
                    <i class="fas fa-filter"></i>
                </button>
                <Link href="/evaluation">
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
                    <th class="p-2 pl-5">Program</th>
                    <th class="p-2">Status</th>
                    <th class="p-2">Progress</th>
                    <th class="p-2 text-right">
                        <i class="fas fa-ellipsis-v"></i>
                    </th>
                </template>

                <template v-slot:table-body>
                    <tr v-if="complianceTools.data.length == 0">
                        <td colspan="4" class="p-2 py-10 text-center">No data</td>
                    </tr>
                    <tr v-else v-for="(item, index) in complianceTools.data" :key="item.id" class="border-b hover:bg-gray-200"
                        :class="{'bg-gray-100': index % 2 == 1}">
                        <td class="p-2 pl-5">
                            {{ item.program }}
                        </td>
                        <td class="p-2">
                            <div v-if="
                                    item.status ==
                                    'Locked'
                                " class="bg-red-500 text-white w-fit px-1 py-0.5 text-xs rounded whitespace-nowrap">
                                Locked
                            </div>
                            <div v-if="
                                    item.status ==
                                    'In progress'
                                " class="bg-blue-500 text-white w-fit px-1 py-0.5 text-xs rounded whitespace-nowrap">
                                In Progress
                            </div>
                            <div v-if="item.status == 'Submitted'"
                                class="bg-green-500 text-white w-fit px-1 py-0.5 text-xs rounded whitespace-nowrap">
                                Ready for visit
                            </div>
                            <div v-if="item.status == 'For re-evaluation'"
                                class="bg-green-500 text-white w-fit px-1 py-0.5 text-xs rounded whitespace-nowrap">
                                For re-evaluation
                            </div>
                        </td>
                        <td class="p-2">
                            <div class="font-bold text-sm px-1 py-0.5 w-fit">
                                {{ item.progress }}%
                            </div>
                        </td>
                        <td class="p-2 pr-5 text-right whitespace-nowrap">
                            <button v-show="canEvaluate && item.status == 'Submitted'" @click="edit(item.id)"
                                class="select-none h-8 w-8 text-xl text-center rounded-full hover:bg-gray-300 text-blue-500 hover:text-blue-600 tooltipForActions"
                                data-tooltip="Evaluate">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button @click="view(item.id)"
                                class="select-none h-8 w-8 text-xl text-center rounded-full hover:bg-gray-300 text-green-500 hover:text-green-600 tooltipForActions"
                                data-tooltip="View">
                                <i class="fas fa-eye"></i>
                            </button>
                        </td>
                    </tr>
                </template>
            </content-table>
        </template>
    </content-container>
</template>

<script setup>
    import { router, useForm } from '@inertiajs/vue3';
    import { ref } from 'vue';

    const props = defineProps([
        'complianceTools',
        'filters',
        'institution',
    ]);

    const query = useForm({
        academicYear: props.filters.academicYear,
        show: props.filters.show,
        search: props.filters.search,
    });

    function view(tool) {
        router.get('/hei/evaluation/' + tool + '/view');
    }

    function submit() {
        query.get("/hei/evaluation", {
            preserveState: true,
            preserveScroll: true,
        });
    }

    // function changeResultCount() {
    //     query.get("/ched/evaluation", {
    //         preserveScroll: true,
    //         preserveState: true,
    //     });
    // }


    // function changeAcademicYear() {
    //     query.get("/ched/evaluation", {
    //         preserveScroll: true,
    //         preserveState: true,
    //     });
    // }
</script>

<script>
    import Layout from '@/Shared/Layout.vue';

    export default {
        layout: Layout,
    }
</script>