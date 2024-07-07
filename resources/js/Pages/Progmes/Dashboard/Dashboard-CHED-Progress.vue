<template>

    <Head title="Dashboard" />
    <page-title title="Dashboard" />
    <div class="mx-3 md:mx-8 mt-5 border-b-2 border-gray-400" v-show="$page.props.auth.user.role != 'Education Supervisor'">
        <Link href="/ched/dashboard/progress" class="mr-7">
            <button class="select-none h-10 border-b-2 border-blue-500 text-blue-500 font-bold">
                Progress
            </button>
        </Link>
        <Link href="/ched/dashboard/overview">
            <button class="select-none h-10 text-gray-700 hover:text-black">
                Overview
            </button>
        </Link>
    </div>
    <div class="mx-3 md:mx-8 mt-5 border border-gray-400 rounded p-3 bg-white flex flex-col lg:flex-row justify-between items-center">
        <div class="mb-3 lg:mb-0 w-full pr-5">
            <div v-if="!hei & !program">
                <div class="flex flex-row items-center">
                    <i class="fas fa-book mr-3 text-blue-500"></i>
                    <div>Overall</div>
                </div>
            </div>
            <div v-show="heiName">
                <div class="flex flex-row items-center">
                    <i class="fas fa-institution mr-3 text-blue-500"></i>
                    <div>{{ heiName }}</div>
                </div>
            </div>
            <div v-show="programName">
                <div class="flex flex-row items-center">
                    <i class="fas fa-book mr-3 text-blue-500"></i>
                    <div>{{ programName }}
                        <span v-if="major != null">
                            - {{ major }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex flex-col lg:flex-row w-full lg:w-auto">
            <div class="flex flex-col lg:flex-row items-center lg:whitespace-nowrap w-full lg:w-auto">
                <div class="lg:mr-2 w-full lg:w-auto">
                    <select @change="selectAll" v-model="query.filter" id="filter" class="w-full lg:w-32 select-none h-10 py-0 rounded text-sm lg:mr-2 lg:mb-0 mb-2">
                        <option :value="null">All</option>
                        <option value="hei">HEI</option>
                        <option value="program">Program</option>
                    </select>
                    <select v-show="query.filter == null" disabled="true" id="selectall" @change="selectHEI" class="w-full lg:w-40 select-none h-10 py-0 rounded text-sm text-blue-500">
                        <option :value="null">N/A</option>
                    </select>
                    <select v-show="query.filter == 'hei'" v-model="query.hei" id="selectHEI" @change="selectHEI" class="w-full lg:w-40 select-none h-10 py-0 rounded text-sm text-blue-500">
                        <option :value="null">Select HEI</option>
                        <option v-for="hei in hei_list" :key="hei.id" :value="hei.id">
                            {{ hei.name }}
                        </option>
                    </select>
                    <select v-show="query.filter == 'program'" v-model="query.program" @change="selectProgram" id="selectProgram" class="w-full lg:w-40 select-none h-10 py-0 rounded text-sm text-blue-500">
                        <option :value="null">Select program</option>
                        <option v-for="program in program_list" :key="program.id" :value="program.id">
                            <span>{{ program.program }} </span> <span v-if="program.major != null"> - {{ program.major }}</span>
                        </option>
                    </select>
                </div>
            </div>
            <div class="flex flex-row mt-5 lg:mt-0">
                <select @change="submit" id="acadYearDropDown" class="select-none w-full lg:w-32 h-10 px-2 py-0 rounded text-sm mr-2" v-model="query.academicyear">
                    <option value="2023-2024">A.Y. 2023-24</option>
                    <option value="2024-2025">A.Y. 2024-25</option>
                    <option value="2025-2026">A.Y. 2025-26</option>
                    <option value="2026-2027">A.Y. 2026-27</option>
                    <option value="2027-2028">A.Y. 2027-28</option>
                    <option value="2028-2029">A.Y. 2028-29</option>
                    <option value="2029-2030">A.Y. 2029-30</option>
                </select>
                <div v-show="$page.props.auth.user.role == 'Super Admin'">
                    <dropdown-option position="right">
                        <template v-slot:button>
                            <button class="h-10 select-none whitespace-nowrap border border-gray-500 rounded px-3">
                                <i class="fas fa-cog mr-1"></i><i class="fas fa-caret-down"></i>
                            </button>
                        </template>
                        <template v-slot:options>
                            <div class="w-40 text-center py-5">
                                <button @click="setAcadYear" class="whitespace-nowrap text-sm bg-blue-500 text-white hover:bg-blue-600 rounded px-2 h-8">Set Default Year</button>
                            </div>
                        </template>
                    </dropdown-option>
                </div>
            </div>
        </div>
    </div>
    <div class="mx-3 md:mx-8 mt-5 flex flex-col lg:flex-row justify-between">
        <div class="w-full lg:w-1/2 lg:mr-5">
            <div class="flex flex-col lg:flex-row">
                <div class="bg-white border border-gray-400 rounded p-5 mt-2 lg:mt-0 mr-5 w-full">
                    <div class="text-lg font-bold">{{ readyforvisit }}</div>
                    <div class="text-blue-500">Ready for visit</div>
                </div>
                <div class="bg-white border border-gray-400 rounded p-5 mt-2 lg:mt-0 w-full">
                    <div class="text-lg font-bold">{{ inprogress }}</div>
                    <div class="text-blue-500">In progress</div>
                </div>
            </div>
            <div class="flex flex-col lg:flex-row mt-0 lg:mt-3">
                <div class="bg-white border border-gray-400 rounded p-5 mt-2 lg:mt-0 mr-5 w-full">
                    <div class="text-lg font-bold">{{ pending }}</div>
                    <div class="text-blue-500">Pending</div>
                </div>
                <div class="bg-white border border-gray-400 rounded p-5 mt-2 lg:mt-0 w-full">
                    <div class="text-lg font-bold">{{ monitored }}</div>
                    <div class="text-blue-500">Monitored</div>
                </div>
            </div>
        </div>
        <div class="w-full lg:w-1/2 border border-gray-400 bg-white rounded mt-3 lg:mt-0">
            <div class="p-3 border-b border-gray-400"><b>Compliance Status</b></div>
            <div class="flex items-center text-center w-full h-36">
                <compliance-status v-if="readyforvisit != 0 || inprogress != 0 || pending != 0 || monitored != 0" :dataItem="[readyforvisit, inprogress, pending, monitored]" :color="['#03d', '#07f', '#09f', '#0cf']"
                :labels="['Ready for visit', 'In progress', 'Pending', 'Monitored']"
                >

                </compliance-status>
                <div v-else class="w-full text-gray-500">
                    No data to display
                </div>
            </div>
        </div>
    </div>
    <div class="mx-3 md:mx-8 mt-5 flex flex-col lg:flex-row mb-5">
        <div class="w-full border border-gray-400 rounded bg-white">
            <div class="p-3 border-b border-gray-400"><b>Compliance Progress</b></div>
            <div class="px-3 max-h-screen overflow-y-auto">
                <table class="w-full text-left overflow-x-auto">
                    <thead>
                        <tr class="border-b sticky top-0 bg-white z-10">
                            <th class="py-2">Program/HEI</th>
                            <th class="py-2">Status</th>
                            <th class="py-2">Progress</th>
                            <th class="py-2 text-right">
                                <i class="fas fa-ellipsis-v"></i>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="complianceTool.length == 0">
                            <td colspan="4" class="py-10 text-center">
                                No compliance tool found
                            </td>
                        </tr>
                        <tr v-for="item in complianceTool" :key="item.id"
                        class="border-b"
                        >
                            <td>
                                <div class="flex flex-col">
                                    <div v-show="hei || props.filters.filter == null">{{ item.program }}</div>
                                    <div v-show="program || props.filters.filter == null">{{ item.institution }}</div>
                                </div>
                            </td>
                            <td class="text-sm px-2">
                                <div v-if="item.status == 'Deployed'" class="bg-gray-600 px-1 text-white w-fit rounded text-xs">
                                    Pending
                                </div>
                                <div v-else class="bg-green-500 px-1 w-fit rounded text-white text-xs whitespace-nowrap">
                                    {{ item.status }}
                                </div>
                            </td>
                            <td class="text-sm py-2">
                                <div class="w-16">
                                    <compliance-progress :percentage="item.progress" :dataItem="[item.complied, item.notcomplied, item.notapplicable, item.nostatus]">

                                    </compliance-progress>
                                </div>
                            </td>
                            <td class="text-right">
                                <a :href="'/ched/evaluation/' + item.id + '/view'" target="_blank">
                                    <button class="hover:bg-gray-200 w-10 h-10 rounded-full text-blue-500">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="mx-3 md:mx-8 mt-12 font-bold">
        SUMMARY OF PROGRAMS MONITORED
    </div>
    <div class="mx-3 md:mx-8 mt-5 flex flex-col lg:flex-row justify-between">
        <div class="w-full lg:w-1/2 lg:mr-5">
            <div class="py-3 font-bold">No. of Programs Monitored (by Quarter)</div>
            <div class="flex flex-col lg:flex-row">
                <div class="bg-white border border-gray-400 rounded p-5 mt-2 lg:mt-0 mr-5 w-full">
                    <div class="text-lg font-bold">{{ quarter1 }}</div>
                    <div class="text-blue-500">1st Quarter</div>
                </div>
                <div class="bg-white border border-gray-400 rounded p-5 mt-2 lg:mt-0 w-full">
                    <div class="text-lg font-bold">{{ quarter2 }}</div>
                    <div class="text-blue-500">2nd Quarter</div>
                </div>
            </div>
            <div class="flex flex-col lg:flex-row mt-0 lg:mt-3">
                <div class="bg-white border border-gray-400 rounded p-5 mt-2 lg:mt-0 mr-5 w-full">
                    <div class="text-lg font-bold">{{ quarter3 }}</div>
                    <div class="text-blue-500">3rd Quarter</div>
                </div>
                <div class="bg-white border border-gray-400 rounded p-5 mt-2 lg:mt-0 w-full">
                    <div class="text-lg font-bold">{{ quarter4 }}</div>
                    <div class="text-blue-500">4th Quarter</div>
                </div>
            </div>
            <div class="mt-0 lg:mt-3">
                <div class="bg-white border border-gray-400 rounded p-5 mt-2 lg:mt-0 w-full">
                    <div class="text-lg font-bold">{{ evaluatedTotal }}</div>
                    <div class="text-blue-500">Total</div>
                </div>
            </div>
        </div>
        <div class="w-full lg:w-1/2 border border-gray-400 bg-white rounded mt-3 lg:mt-0">
            <div class="p-3 border-b border-gray-400"><b>Monitored Programs Distribution</b></div>
            <div class="flex items-center text-center w-full h-auto py-5">
                <bar-chart :dataItem="[quarter1, quarter2, quarter3, quarter4]" :color="['#0af']" :labels="['Q1', 'Q2', 'Q3', 'Q4']"></bar-chart>
            </div>
        </div>
    </div>

    <div class="mx-3 md:mx-8 mt-5 flex flex-col xl:flex-row justify-between">
        <div class="w-full border border-gray-400 bg-white rounded mt-3 lg:mt-0">
            <div class="p-3 border-b border-gray-400"><b>Programs Monitored by Month</b></div>
            <div class="flex items-center text-center w-full h-auto p-5 md:px-20">
                <line-chart :lineChartDataItem="lineChartDataItem"></line-chart>
            </div>
        </div>
    </div>
    

    <Notification :message="$page.props.flash.success" type="success"/>
    <Notification :message="$page.props.flash.failed" type="failed"/>
    <Notification :message="$page.props.flash.error" type="failed"/>
</template>

<script setup>
import { router, useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps([
    'complianceTool',
    'filters',
    'academicyear',
    'readyforvisit',
    'inprogress',
    'pending',
    'monitored',
    'program_list',
    'program',
    'major',
    'hei_list',
    'hei',
    'heiName',
    'programName',
    'quarter1',
    'quarter2',
    'quarter3',
    'quarter4',
    'evaluatedTotal',
    'lineChartDataItem',
]);

const query = useForm({
    academicyear: ref(props.academicyear),
    filter: ref(props.filters.filter) != null ? ref(props.filters.filter) : null,
    hei: ref(props.hei) != null ? ref(props.hei) : null,
    program: ref(props.program) != null ? ref(props.program) : null,
});

function submit() {
    query.get("/ched/dashboard/progress", {
        preserveState: false,
        preserveScroll: true,
        replace: true,
    });
}

function selectAll() {
    if (query.filter == null) {
        query.hei = null;
        query.program = null;
        query.get("/ched/dashboard/progress", {
            preserveState: false,
            preserveScroll: true,
            replace: true,
        });
    }
}

function selectHEI() {
    query.program = null;
    query.get("/ched/dashboard/progress", {
        preserveState: false,
        preserveScroll: true,
        replace: true,
    });
}

function selectProgram() {
    query.hei = null;
    query.get("/ched/dashboard/progress", {
        preserveState: false,
        preserveScroll: true,
        replace: true,
    });
}

function viewTool(id) {
    router.get('/ched/evaluation/' + id + '/view');
}

function setAcadYear() {
    router.post('/set-academic-year', [props.academicyear]);
}

</script>

<script>
    import Layout from '../Shared/Layout.vue';
import Notification from '../Shared/Notification.vue';

    export default {
  components: { Notification },
        layout: Layout,
    }
</script>