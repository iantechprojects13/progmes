<template>

    <Head title="Program Self-Evaluation" />
    <page-title title="Program Evaluation" />
    <content-container hasSearch="true" pageTitle="Archived" page="archive" :hasNavigation="true" :hasTopButton="true" :hasFilters="true" :data_list="complianceTools">
        <template v-slot:navigation>
            <div>
                <Link href="/evaluation">
                <button class="select-none mr-8 text-gray-500 hover:text-black">
                    Evaluation
                </button>
                </Link>
                <button class="select-none text-blue-500 h-10 border-b-2 font-bold border-blue-500">
                    Archived
                </button>
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
        <template v-slot:top-button>
            <select @change="submit" v-model="query.academicYear" id="academicYearSelect" class="h-10 rounded text-sm">
                <option value="2023-2024">A.Y. 2023-24</option>
                <option value="2024-2025">A.Y. 2024-25</option>
                <option value="2025-2026">A.Y. 2025-26</option>
                <option value="2026-2027">A.Y. 2026-27</option>
                <option value="2027-2028">A.Y. 2027-28</option>
                <option value="2028-2029">A.Y. 2028-29</option>
                <option value="2029-2030">A.Y. 2029-30</option>
            </select>
        </template>
        <template v-slot:options>
            <div class="mr-1">
                <Link href="/ched/evaluation/archived">
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
                    <th class="p-3 pl-5 border-b border-gray-400">Program/Institution</th>
                    <th class="p-3 border-b border-gray-400">Archive Date</th>
                    <th class="p-3 border-b border-gray-400 text-right">
                        <i class="fas fa-ellipsis-v"></i>
                    </th>
                </template>

                <template v-slot:table-body>
                    <tr v-if="complianceTools.data.length == 0">
                        <td colspan="3" class="p-3 py-10 text-center">No evaluation tool found</td>
                    </tr>
                    <tr v-else v-for="(item, index) in complianceTools.data" :key="item.id" class="hover:bg-slate-300"
                        :class="{'bg-slate-200': index % 2 ==0}">
                        <td class="p-3 pl-5">
                            <div>
                                {{ item.program }}
                            </div>
                            <div class="font-bold">
                                {{ item.institution }}
                            </div>
                        </td>
                        <td class="p-3">
                            <div class="font-bold text-sm px-1 py-0.5 rounded w-fit">
                                {{ item.archivedDate }}
                            </div>
                        </td>
                        <td class="p-3 text-right">
                            <a :href="'/ched/evaluation/' + item.id + '/view'" target="_blank">
                                <button class="h-10 w-10 rounded text-white bg-green-600 hover:bg-green-700 tooltipForActions" data-tooltip="View"><i class="fas fa-eye"></i></button>
                            </a>
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
        'canEvaluate',
    ]);

    const query = useForm({
        academicYear: props.filters.academicYear,
        show: props.filters.show,
        search: props.filters.search,
    });

    function evaluate(tool) {
        router.get('/ched/evaluation/' + tool + '/evaluate');
    }

    function view(tool) {
        router.get('/ched/evaluation/' + tool + '/view');
    }

    function submit() {
        query.get("/ched/evaluation/archived", {
            preserveState: true,
            preserveScroll: true,
        });
    }
</script>

<script>
    import Layout from '../Shared/Layout.vue';

    export default {
        layout: Layout,
    }
</script>