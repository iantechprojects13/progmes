<template>

    <Head title="Dashboard" />
    <page-title title="Dashboard" />
    <div class="mx-3 md:mx-8 mt-5 border border-gray-300 rounded p-3 bg-white flex flex-col lg:flex-row justify-between items-center">
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
                    <select @change="selectAll" v-model="query.filter" id="filter" class="w-full lg:w-32 select-none h-10 py-0 rounded text-sm lg:mr-2 lg:mb-0 mb-2 border-gray-400 hover:border-gray-500 cursor-pointer">
                        <option :value="null">All</option>
                        <option value="hei">HEI</option>
                        <option value="program">Program</option>
                    </select>
                    <select v-show="query.filter == null" disabled="true" id="selectall" @change="selectHEI" class="w-full lg:w-40 border-gray-300 select-none h-10 py-0 rounded text-sm text-blue-500">
                        <option :value="null">N/A</option>
                    </select>
                    <select v-show="query.filter == 'hei'" v-model="query.hei" id="selectHEI" @change="selectHEI" class="w-full lg:w-40 select-none h-10 py-0 rounded text-sm text-blue-500 border-gray-400 hover:border-gray-500 cursor-pointer">
                        <option :value="null">Select HEI</option>
                        <option v-for="hei in hei_list" :key="hei.id" :value="hei.id">
                            {{ hei.name }}
                        </option>
                    </select>
                    <select v-show="query.filter == 'program'" v-model="query.program" @change="selectProgram" id="selectProgram" class="w-full lg:w-40 select-none h-10 py-0 rounded text-sm text-blue-500 border-gray-400 hover:border-gray-500 cursor-pointer">
                        <option :value="null">Select program</option>
                        <option v-for="program in program_list" :key="program.id" :value="program.id">
                            <span>{{ program.program }} </span> <span v-if="program.major != null"> - {{ program.major }}</span>
                        </option>
                    </select>
                </div>
            </div>
            <div class="flex flex-row mt-5 lg:mt-0">
                <select @change="submit" id="acadYearDropDown" class="select-none w-full lg:w-36 indent-2 h-10 px-2 py-0 rounded text-sm mr-2 border-gray-400 hover:border-gray-500 cursor-pointer" v-model="query.academicyear">
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
                            <button class="h-10 select-none whitespace-nowrap border border-gray-400 hover:border-gray-500 cursor-pointer rounded px-3">
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
    <div class="mx-3 md:mx-8 mt-8 flex flex-col lg:flex-row justify-between border border-gray-300 bg-white p-3 rounded">
        <div class=" font-bold uppercase text-gray-700">
            Progress
        </div>
    </div>
    <div class="mx-3 md:mx-8 mt-5 flex flex-col lg:flex-row justify-between">
        <div class="w-full lg:w-1/2 lg:mr-5">
            <div class="flex flex-col lg:flex-row">
                <div class="bg-white border border-gray-300 rounded p-5 mt-2 lg:mt-0 mr-5 w-full">
                    <div class="text-lg font-bold">{{ readyforvisit }}</div>
                    <div class="text-blue-500">Ready for visit</div>
                </div>
                <div class="bg-white border border-gray-300 rounded p-5 mt-2 lg:mt-0 w-full">
                    <div class="text-lg font-bold">{{ inprogress }}</div>
                    <div class="text-blue-500">In progress</div>
                </div>
            </div>
            <div class="flex flex-col lg:flex-row mt-0 lg:mt-3">
                <div class="bg-white border border-gray-300 rounded p-5 mt-2 lg:mt-0 mr-5 w-full">
                    <div class="text-lg font-bold">{{ forreevaluation }}</div>
                    <div class="text-blue-500">For re-evaluation</div>
                </div>
                <div class="bg-white border border-gray-300 rounded p-5 mt-2 lg:mt-0 w-full">
                    <div class="text-lg font-bold">{{ monitored }}</div>
                    <div class="text-blue-500">Monitored</div>
                </div>
            </div>
        </div>
        <div class="w-full lg:w-1/2 border border-gray-300 bg-white rounded mt-3 lg:mt-0">
            <div class="p-3 border-b border-gray-300"><b>Compliance Status</b></div>
            <div class="flex items-center text-center w-full h-36">
                <compliance-status v-if="readyforvisit != 0 || inprogress != 0 || forreevaluation != 0 || monitored != 0" :dataItem="[readyforvisit, inprogress, forreevaluation, monitored]" :color="['#03d', '#07f', '#09f', '#0cf']"
                :labels="['Ready for visit', 'In progress', 'For re-evaluation', 'Monitored']"
                >

                </compliance-status>
                <div v-else class="w-full text-gray-500">
                    No data to display
                </div>
            </div>
        </div>
    </div>
    <div class="mx-3 md:mx-8 mt-5 flex flex-col lg:flex-row mb-5">
        <div class="w-full border border-gray-300 rounded bg-white">
            <div class="p-3 border-b border-gray-300"><b>Compliance Progress</b></div>
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
    <div class="mx-3 md:mx-8 mt-12 flex flex-col lg:flex-row justify-between border border-gray-300 bg-white p-3 rounded">
        <div class=" font-bold uppercase text-gray-700">
            <span v-show="$page.props.auth.user.role != 'Education Supervisor'">Overall </span>Summary of Programs Monitored ({{ currentYear }})
        </div>
    </div>

    <div class="mx-3 md:mx-8 mt-5 flex flex-col lg:flex-row justify-between">
        <div class="w-full border border-gray-300 bg-white rounded mt-3 lg:mt-0 flex-col lg:flex-row">
            <div class="p-3 border-b border-gray-300"><b>Programs Monitored (by Quarter)</b></div>
            <div class="flex flex-col lg:flex-row px-3 py-5 lg:px-8 items-center">
                <div class="w-full lg:mr-5">
                        <div class="flex flex-col lg:flex-row">
                            <div class="bg-white border border-gray-300 rounded p-5 mt-2 lg:mt-0 mr-5 w-full">
                                <div class="text-lg font-bold">{{ quarter1 }}</div>
                                <div class="text-blue-500">1st Quarter</div>
                            </div>
                            <div class="bg-white border border-gray-300 rounded p-5 mt-2 lg:mt-0 w-full">
                                <div class="text-lg font-bold">{{ quarter2 }}</div>
                                <div class="text-blue-500">2nd Quarter</div>
                            </div>
                        </div>
                        <div class="flex flex-col lg:flex-row mt-0 lg:mt-3">
                            <div class="bg-white border border-gray-300 rounded p-5 mt-2 lg:mt-0 mr-5 w-full">
                                <div class="text-lg font-bold">{{ quarter3 }}</div>
                                <div class="text-blue-500">3rd Quarter</div>
                            </div>
                            <div class="bg-white border border-gray-300 rounded p-5 mt-2 lg:mt-0 w-full">
                                <div class="text-lg font-bold">{{ quarter4 }}</div>
                                <div class="text-blue-500">4th Quarter</div>
                            </div>
                        </div>
                        <div class="mt-0 lg:mt-3">
                            <div class="bg-white border border-gray-300 rounded p-5 mt-2 lg:mt-0 w-full">
                                <div class="text-lg font-bold">{{ evaluatedTotal }}</div>
                                <div class="text-blue-500">Total</div>
                            </div>
                        </div>
                    </div>
                <div class="flex items-center text-center w-full px-10 lg:px-24 mt-8 lg:mt-0">
                    <div v-if="evaluatedTotal == 0" class="w-full text-center">No data to display</div>
                    <pie-chart v-else :dataItem="[quarter1, quarter2, quarter3, quarter4]" :color="['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0']" :labels="['Q1', 'Q2', 'Q3', 'Q4']"></pie-chart>
                </div>
            </div>
        </div>
    </div>

    <charts-container :hasData="true" title="Programs Monitored Analysis" note="The line graph compares the number of programs monitored in each quarter over the last three years.">
        <template v-slot:data>
            <div class="w-full flex flex-col lg:flex-row items-center justify-center mb-5">
                <div class="w-full lg:w-1/5 flex flex-col mx-3 p-2 rounded lg:mt-0 mt-5 border border-gray-300 lg:border-none">
                    <div class="text-lg font-bold">{{ averageResult(0) }}</div>
                    <div class="text-gray-600">1st Quarter Average</div>
                    <div :class="[{'text-green-500': trend(0) == 'Inclined'}, {'text-red-500': trend(0) == 'Declined'}]">
                        <i class="fas" :class="[{'fa-arrow-circle-up mr-2': trend(0) == 'Inclined'}, {'fa-arrow-circle-down': trend(0) == 'Declined'}]"></i>
                        {{ change(0) }}%
                    </div>
                </div>
                <div class="w-full lg:w-1/5 flex flex-col mx-3 p-2 rounded lg:mt-0 mt-5 border border-gray-300 lg:border-none">
                    <div class="text-lg font-bold">{{ averageResult(1) }}</div>
                    <div class="text-gray-600">2nd Quarter Average</div>
                    <div :class="[{'text-green-500': trend(1) == 'Inclined'}, {'text-red-500': trend(1) == 'Declined'}]">
                        <i class="fas" :class="[{'fa-arrow-circle-up mr-2': trend(1) == 'Inclined'}, {'fa-arrow-circle-down': trend(1) == 'Declined'}]"></i>
                        {{ change(1) }}%
                    </div>
                </div>
                <div class="w-full lg:w-1/5 flex flex-col mx-3 p-2 rounded lg:mt-0 mt-5 border border-gray-300 lg:border-none">
                    <div class="text-lg font-bold">{{ averageResult(2) }}</div>
                    <div class="text-gray-600">3rd Quarter Average</div>
                    <div :class="[{'text-green-500': trend(2) == 'Inclined'}, {'text-red-500': trend(2) == 'Declined'}]">
                        <i class="fas" :class="[{'fa-arrow-circle-up mr-2': trend(2) == 'Inclined'}, {'fa-arrow-circle-down': trend(2) == 'Declined'}]"></i>
                        {{ change(2) }}%
                    </div>
                </div>
                <div class="w-full lg:w-1/5 flex flex-col mx-3 p-2 rounded lg:mt-0 mt-5 border border-gray-300 lg:border-none">
                    <div class="text-lg font-bold">{{ averageResult(3) }}</div>
                    <div class="text-gray-600">4th Quarter Average</div>
                    <div :class="[{'text-green-500': trend(3) == 'Inclined'}, {'text-red-500': trend(3) == 'Declined'}]">
                        <i class="fas" :class="[{'fa-arrow-circle-up mr-2': trend(3) == 'Inclined'}, {'fa-arrow-circle-down': trend(3) == 'Declined'}]"></i>
                        {{ change(3) }}%
                    </div>
                </div>
            </div>
        </template>
        <template v-slot:chart>
            <analytics-line-chart :thisYear="props.thisYear" :lastYear="props.lastYear" :twoYearsAgo="props.twoYearsAgo"></analytics-line-chart>
        </template>
    </charts-container>

    <charts-container :hasData="true" title="Programs Monitored This Year (by Month)" note="The bar graph compares the number of programs monitored in each month.">
        <template v-slot:data>
            <div v-show="evaluatedTotal != 0" class="w-full flex flex-col lg:flex-row items-center justify-center mb-5">
                <div class="w-full lg:w-1/5 flex flex-col mx-3 p-2 rounded lg:mt-0 mt-5 border border-gray-300 lg:border-none">
                    <div class="text-lg font-bold">{{ monthlyAverageResult() }}</div>
                    <div class="text-gray-600">Monthly Average</div>
                </div>
                <div class="w-full lg:w-1/5 flex flex-col mx-3 p-2 rounded lg:mt-0 mt-5 border border-gray-300 lg:border-none">
                    <div class="text-lg font-bold">{{ byMonthData[monthIndex] }}</div>
                    <div class="text-gray-600">This Month</div>
                    <div :class="[{'text-green-500': trendByMonth() == 'Inclined'}, {'text-red-500': trendByMonth() == 'Declined'}]">
                        <i class="fas" :class="[{'fa-arrow-circle-up mr-2': trendByMonth() == 'Inclined'}, {'fa-arrow-circle-down': trendByMonth() == 'Declined'}]"></i>
                        {{ changeByMonth() }}%
                    </div>
                </div>
            </div>
        </template>
        <template v-slot:chart>
            <div v-if="evaluatedTotal == 0" class="py-10 w-full text-center">No data to display</div>
            <bar-chart v-else :dataItem="byMonthData" :color="['#0af']" :labels="['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']"/>
        </template>
    </charts-container>

    <div v-show="$page.props.auth.user.role != 'Education Supervisor'" class="mx-3 md:mx-8 mt-12 flex flex-col lg:flex-row justify-between border border-gray-300 bg-white p-3 rounded">
        <div class=" font-bold uppercase text-gray-700">
            Overview
        </div>
    </div>

    <div v-show="$page.props.auth.user.role != 'Education Supervisor'" class="mx-3 md:mx-8 mt-3 flex flex-col xl:flex-row justify-between">
        <div class="flex flex-col md:flex-row w-full lg:mr-10 mr-0">
            <div class="border border-gray-300 bg-white px-5 py-3 w-full mt-3 mr-10 rounded flex flex-row justify-between items-center">
                <div>
                    <span class="font-bold text-lg mb-5">{{ userCount }}</span>
                    <br>Users
                    <br><span class="text-blue-500">Overall</span>
                </div>
                <div class="text-3xl text-blue-500">
                    <i class="fas fa-user"></i>
                </div>
            </div>
            <div class="border border-gray-300 bg-white px-5 py-3 w-full mt-3 rounded flex flex-row justify-between items-center">
                <div>
                    <span class="font-bold text-lg mb-5">{{ institutionCount }}</span>
                    <br>HEIs
                    <br><span class="text-blue-500">Overall</span>
                </div>
                <div class="text-3xl text-blue-500">
                    <i class="fas fa-institution"></i>
                </div>
            </div>
        </div>
        <div class="flex flex-col md:flex-row w-full lg:mt-3">
           <div class="border border-gray-300 bg-white px-5 py-3 w-full mt-3 lg:mt-0 mr-10 rounded flex flex-row justify-between items-center">
                <div>
                    <span class="font-bold text-lg mb-5">{{ programCount }}</span>
                    <br>Programs
                    <br><span class="text-blue-500">Overall</span>
                </div>
                <div class="text-3xl text-blue-500">
                    <i class="fas fa-book"></i>
                </div>
            </div>
            <div class="border border-gray-300 bg-white px-5 py-3 w-full mt-3 lg:mt-0 rounded flex flex-row justify-between items-center">
                <div>
                    <span class="font-bold text-lg mb-5">{{ disciplineCount }}</span>
                    <br>Discipline
                    <br><span class="text-blue-500">Overall</span>
                </div>
                <div class="text-3xl text-blue-500">
                    <i class="fas fa-book"></i>
                </div>
            </div> 
        </div>
    </div>

    <div v-show="$page.props.auth.user.role != 'Education Supervisor'" class="mx-3 md:mx-8 mt-5 flex flex-col xl:flex-row justify-between">
        <div class="w-full border border-gray-300 bg-white rounded mt-3 lg:mt-0">
            <div class="p-3 border-b border-gray-300"><b>User-type Distribution</b></div>
            <div class="flex items-center text-center w-full h-auto py-5">
                <distribution-chart :dataItem="usertype" :color="['#06d', '#09f']" :labels="['CHED', 'HEI']">
                    
                </distribution-chart>
            </div>
        </div>
    </div>
    

    <Notification :message="$page.props.flash.success" type="success"/>
    <Notification :message="$page.props.flash.failed" type="failed"/>
    <Notification :message="$page.props.flash.error" type="failed"/>
</template>

<script setup>
import { router, useForm } from '@inertiajs/vue3';
import { ref, watch, computed } from 'vue';

const props = defineProps([
    'complianceTool',
    'filters',
    'userCount',
    'institutionCount',
    'programCount',
    'disciplineCount',
    'usertype',
    'thisYear',
    'lastYear',
    'twoYearsAgo',
    'currentYear',
    'academicyear',
    'readyforvisit',
    'inprogress',
    'forreevaluation',
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
    'byMonthData',
    'monthIndex',
]);


const propx = {
    thisYear: [379, 371, 391, 384],
    lastYear: [321, 379, 440, 385],
    twoYearsAgo: [390, 455, 385, 295],
}

const averageResult = (index) => {
  const average = (props.thisYear[index] + props.lastYear[index] + props.twoYearsAgo[index]) / 3;
  return average.toFixed(2);
};

const monthlyAverageResult = () => {
    let total = 0;
    let monthly_average = 0;
    for (var i = 0; i < props.monthIndex; i++) {
        total = total + props.byMonthData[i];
    }
    monthly_average = total / props.monthIndex;
    return monthly_average.toFixed(2);
};

const averageLastTwoYears = (index) => {
  return (props.lastYear[index] + props.twoYearsAgo[index]) / 2;
};

const averageLastMonths = () => {

    let total = 0;
    let monthly_average = 0;
    for (var i = 0; i < props.monthIndex; i++) {
        total = total + props.byMonthData[i];
    }
    monthly_average = total / (props.monthIndex);
    return monthly_average;
};

const change = (index) => {
  const thisYearValue = props.thisYear[index];
  const lastTwoYearsAverage = averageLastTwoYears(index);
  const difference = thisYearValue - lastTwoYearsAverage;
  const percentageChange = (difference / lastTwoYearsAverage) * 100;
  return percentageChange.toFixed(2);
};

const trend = (index) => {
  const thisYearValue = props.thisYear[index];
  const lastTwoYearsAverage = averageLastTwoYears(index);
  return thisYearValue > lastTwoYearsAverage ? 'Inclined' : 'Declined';
};

const changeByMonth = () => {
  const thisMonthValue = props.byMonthData[props.monthIndex];
  const lastMonthsAverage = averageLastMonths();
  const difference = thisMonthValue - lastMonthsAverage;
  const percentageChange = (difference / lastMonthsAverage) * 100;
  return percentageChange.toFixed(2);
};

const trendByMonth = () => {
  const thisMonthValue = props.byMonthData[props.monthIndex];
  const lastMonthsAverage = averageLastMonths();
  return thisMonthValue > lastMonthsAverage ? 'Inclined' : 'Declined';
};



const query = useForm({
    academicyear: ref(props.academicyear),
    filter: ref(props.filters.filter) != null ? ref(props.filters.filter) : null,
    hei: ref(props.hei) != null ? ref(props.hei) : null,
    program: ref(props.program) != null ? ref(props.program) : null,
});

function submit() {
    query.get("/ched/dashboard", {
        preserveState: false,
        preserveScroll: true,
        replace: true,
    });
}

function selectAll() {
    if (query.filter == null) {
        query.hei = null;
        query.program = null;
        query.get("/ched/dashboard", {
            preserveState: false,
            preserveScroll: true,
            replace: true,
        });
    }
}

function selectHEI() {
    query.program = null;
    query.get("/ched/dashboard", {
        preserveState: false,
        preserveScroll: true,
        replace: true,
    });
}

function selectProgram() {
    query.hei = null;
    query.get("/ched/dashboard", {
        preserveState: false,
        preserveScroll: true,
        replace: true,
    });
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