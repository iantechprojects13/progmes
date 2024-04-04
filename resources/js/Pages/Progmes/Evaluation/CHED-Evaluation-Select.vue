<template>

    <Head title="Program Self-Evaluation" />
    <page-title title="Program Evaluation" />
    <content-container hasSearch="true" :hasTopButton="true" hasFilters="true" :data_list="complianceTools">
        <template v-slot:search>
            <div class="w-full flex justify-end relative">
                <input @keydown.enter="submit" v-model="query.search" type="search" id="content-search"
                    placeholder="Search by HEI or program"
                    class="w-full rounded-full bg-slate-100 h-10 border-none indent-3 text-base placeholder-gray-400 pr-11 mr-2" />
                <button @click="submit"
                    class="hover:bg-gray-300 active:text-blue-500 h-10 w-10 rounded-full absolute right-3">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </template>
        <template v-slot:top-button>
            <select @change="changeAcademicYear" v-model="query.academicYear" id="academicYearSelect" class="h-10 rounded text-sm">
                <option value="2023-2024">A.Y. 2023-2024</option>
                <option value="2024-2025">A.Y. 2024-2025</option>
                <option value="2025-2026">A.Y. 2025-2026</option>
                <option value="2026-2027">A.Y. 2026-2027</option>
                <option value="2027-2028">A.Y. 2027-2028</option>
                <option value="2028-2029">A.Y. 2028-2029</option>
                <option value="2029-2030">A.Y. 2029-2030</option>
            </select>
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
                    <th class="p-3 pl-5 border-b border-gray-400">Program/Institution</th>
                    <th class="p-3 border-b border-gray-400">Status</th>
                    <th class="p-3 border-b border-gray-400">Progress</th>
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
                            <div v-if="
                                    item.status ==
                                    'Locked'
                                " class="bg-red-500 text-white w-fit px-2 py-0.5 text-xs">
                                Locked
                            </div>
                            <div v-if="
                                    item.status ==
                                    'In progress'
                                " class="bg-blue-500 text-white w-fit px-2 py-0.5 text-xs">
                                In Progress
                            </div>
                            <div v-if="item.status == 'Submitted'"
                                class="bg-emerald-500 text-white w-fit px-2 py-0.5 text-xs">
                                Ready for visit
                            </div>
                        </td>
                        <td class="p-3">
                            <div class="bg-gray-700 text-white font-bold text-sm px-2 py-0.5 w-fit">
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
            <select v-model="query.show" id="showResultCount" @change="changeResultCount"
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

    function edit(tool) {
        router.get('/ched/evaluation/' + tool + '/evaluate');
    }

    function view(tool) {
        router.get('/ched/evaluation/' + tool + '/view');
    }

    function submit() {
        query.get("/ched/evaluation", {
            preserveState: true,
            preserveScroll: true,
        });
    }

    function changeResultCount() {
        query.get("/ched/evaluation", {
            preserveScroll: true,
            preserveState: true,
        });
    }


    function changeAcademicYear() {
        query.get("/ched/evaluation", {
            preserveScroll: true,
            preserveState: true,
        });
    }
</script>

<script>
    import Layout from '../Shared/Layout.vue';

    export default {
        layout: Layout,
    }
</script>