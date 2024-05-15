<template>

    <Head title="Program Self-Evaluation" />
    <page-title title="Program Evaluation" />
    <div class="mx-3 md:mx-8 mt-8 border border-gray-400 bg-white rounded p-3 flex flex-col lg:flex-row items-center text-left justify-between">
        <div class="text-left w-full"><i class="fas fa-institution mr-3 text-blue-500"></i>{{ institution }}</div>
        <div class="mt-5 lg:mt-0 w-full lg:w-auto">
            <select @change="submit" v-model="query.academicYear" id="academicYearSelect" class="w-full lg:w-40 h-10 rounded text-sm">
                <option value="2023-2024">A.Y. 2023-24</option>
                <option value="2024-2025">A.Y. 2024-25</option>
                <option value="2025-2026">A.Y. 2025-26</option>
                <option value="2026-2027">A.Y. 2026-27</option>
                <option value="2027-2028">A.Y. 2027-28</option>
                <option value="2028-2029">A.Y. 2028-29</option>
                <option value="2029-2030">A.Y. 2029-30</option>
            </select>
        </div>
    </div>
    <content-container :hasNavigation="true" :hasSearch="true" :hasFilters="true" :data_list="complianceTools">
        <template v-slot:navigation>
            <div class="mt-3">
                <button class="select-none text-blue-500 h-10 mr-8 border-b-2 font-bold border-blue-500">
                    Evaluation
                </button>
                <Link :href="route('evaluation.hei.archive')">
                <button class="select-none text-gray-500 hover:text-black">
                    Archived
                </button>
                </Link>
            </div>
        </template>
        <template v-slot:search>
            <div class="w-full flex justify-end relative">
                <input @keydown.enter="submit" v-model="query.search" type="search" id="content-search"
                    placeholder="Search"
                    class="w-full rounded border border-gray-400 bg-slate-100 h-10 text-base placeholder-gray-400 pr-11 mr-2" />
                <button @click="submit"
                    class="text-gray-700 hover:text-black active:text-blue-500 h-10 w-10 rounded absolute right-2">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </template>
        <template v-slot:options>
            <div class="mr-1">
                <Link href="/evaluation">
                <button
                    class="w-10 h-10 whitespace-nowrap rounded-full text-gray-700 hover:text-blue-500 active:text-white active:bg-blue-600 tooltipForActions"
                    data-tooltip="Refresh page">
                    <i class="fas fa-refresh"></i>
                </button>
                </Link>
            </div>
        </template>
        <template v-slot:main-content>
            <content-table>
                <template v-slot:table-head>
                    <th class="p-3 pl-5 border-b border-gray-400">Program</th>
                    <th class="p-3 border-b border-gray-400">Status</th>
                    <th class="p-3 border-b border-gray-400">Progress</th>
                    <th class="p-3 border-b border-gray-400 text-right">
                        <i class="fas fa-ellipsis-v"></i>
                    </th>
                </template>

                <template v-slot:table-body>
                    <tr v-if="complianceTools.data.length == 0">
                        <td colspan="3" class="p-3 py-10 text-center">No compliance evaluation tool found</td>
                    </tr>
                    <tr v-else v-for="(item, index) in complianceTools.data" :key="item.id" class="hover:bg-slate-300"
                        :class="{'bg-slate-200': index % 2 ==0}">
                        <td class="p-3 pl-5">
                            {{ item.program }}
                        </td>
                        <td class="p-3">
                            <div v-if="
                                    item.status ==
                                    'Locked'
                                " class="bg-red-500 text-white w-fit px-1 py-0.5 text-xs rounded">
                                Locked
                            </div>
                            <div v-if="
                                    item.status ==
                                    'In progress'
                                " class="bg-blue-500 text-white w-fit px-1 py-0.5 text-xs rounded">
                                In Progress
                            </div>
                            <div v-if="item.status == 'Submitted'"
                                class="bg-emerald-500 text-white w-fit px-1 py-0.5 text-xs rounded">
                                Ready for visit
                            </div>
                        </td>
                        <td class="p-3">
                            <div class="font-bold text-sm px-1 py-0.5 w-fit">
                                {{ item.progress }}%
                            </div>
                        </td>
                        <td class="p-3 text-right">
                            <button v-show="canEvaluate && item.status == 'Submitted'" @click="edit(item.id)"
                                class="select-none w-10 h-10 text-white bg-blue-500 hover:bg-blue-600 rounded mr-1 tooltipForActions"
                                data-tooltip="Evaluate">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button @click="view(item.id)"
                                class="select-none w-10 h-10 bg-green-600 hover:bg-green-700 text-white rounded tooltipForActions"
                                data-tooltip="View">
                                <i class="fas fa-eye mr-1"></i>
                            </button>
                        </td>
                    </tr>
                </template>
            </content-table>
            <Notification :message="$page.props.flash.success" type="success" />
        </template>
        <template v-slot:show-item>
            <div class="mr-2">Items per page</div>
            <select v-model="query.show" id="showResultCount" @change="submit"
                class="select-none rounded h-8 w-20 p-1 text-sm">
                <option value="25">25</option>
                <option :value="50">50</option>
                <option :value="75">75</option>
                <option :value="100">100</option>
                <option :value="200">200</option>
            </select>
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
    import Layout from '../Shared/Layout.vue';

    export default {
        layout: Layout,
    }
</script>