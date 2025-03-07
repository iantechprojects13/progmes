<template>

    <Head title="Dashboard" />
    <div class="bg-gradient-to-r from-blue-600 to-indigo-800 p-8">
        <h1 class="text-3xl font-bold text-white mb-2">Dashboard</h1>
       
    </div>
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
                <div class="w-full lg:w-auto">
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
        </div>
    </div>
    <div class="mx-3 md:mx-8 mt-12 flex flex-col lg:flex-row lg:items-center justify-between border border-gray-300 bg-white p-3 rounded">
        <div class=" font-bold uppercase text-gray-700">
            Progress
        </div>
        <div class="lg:mt-0 mt-2">
            <div class="flex flex-col lg:flex-row w-full lg:w-auto">
                <div class="flex flex-col lg:flex-row items-center lg:whitespace-nowrap w-full lg:w-auto">
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
    <div class="mx-3 md:mx-8 my-12">
        <div class="h-0.5 w-full bg-stone-200">

        </div>
    </div>
    <div class="mx-3 md:mx-8 mt-12 flex flex-col lg:flex-row lg:items-center justify-between border border-gray-300 bg-white p-3 rounded">
        <div class=" font-bold uppercase text-gray-700">
            <span v-show="$page.props.auth.user.role != 'Education Supervisor'">Overall </span>Summary of Programs monitored
        </div>
        <div class="lg:mt-0 mt-2">
            <select @change="submit" v-model="query.year" class="w-full lg:w-32 select-none h-10 py-0 rounded text-sm lg:mr-2 lg:mb-0 mb-2 border-gray-400 hover:border-gray-500 cursor-pointer">
                <option value="2021">2021</option>
                <option value="2022">2022</option>
                <option value="2023">2023</option>
                <option value="2024">2024</option>
                <option value="2025">2025</option>
                <option value="2026">2026</option>
                <option value="2027">2027</option>
                <option value="2028">2028</option>
            </select>
        </div>
    </div>

    <div class="mx-3 md:mx-8 mt-5 flex flex-col lg:flex-row justify-between">
        <div class="w-full border border-gray-300 bg-white rounded mt-3 lg:mt-0 flex-col lg:flex-row">
            <div class="p-3 border-b border-gray-300"><b>Programs Monitored by Quarter ({{ filters.year }})</b></div>
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
                        <div class="flex flex-col lg:flex-row mt-0 lg:mt-3">
                            <div class="bg-white border border-gray-300 rounded p-5 mt-2 lg:mt-0 mr-5 w-full">
                                <div class="text-lg font-bold">{{ evaluatedTotal }}</div>
                                <div class="text-blue-500">Total</div>
                            </div>
                            <div class="bg-white border border-gray-300 rounded p-5 mt-2 lg:mt-0 w-full">
                                <div v-if="performance()" class="font-bold">{{ performance() }}</div>
                                <div v-else class="font-bold">-</div>
                                <div class="text-blue-500">Monitoring Performance <i @click="toggleScaleModal" class="fas fa-question-circle cursor-pointer" title="Show Performance Criteria"></i></div>
                            </div>
                        </div>
                    </div>
                <div class="flex items-center text-center w-full mt-8 lg:mt-0">
                    <div v-if="evaluatedTotal == 0" class="w-full text-center">No data to display</div>
                    <pie-chart
                    v-else :dataItem="[quarter1, quarter2, quarter3, quarter4]" :color="['#36A2EB', '#4BC0C0', '#FFCE56', '#FF6384']" :labels="['Q1', 'Q2', 'Q3', 'Q4']" :tooltip="[byQuarterData(quarter1, '1st'), byQuarterData(quarter2, '2nd'), byQuarterData(quarter3, '3rd'), byQuarterData(quarter4, '4th')]" :filter="heiName != null ? 'HEI' : programName != null ? 'Program' : 'Overall'">

                    </pie-chart>
                </div>
            </div>
            <div class="p-5" v-show="evaluatedTotal != 0">
                <div class="p-3 border rounded bg-blue-100 text-sm">
                    <p v-html="monitoredPercentage($page.props.auth.user.role, monitored, totalPrograms, filters.year, isCurrentYear)">

                    </p>
                </div>
            </div>
        </div>
    </div>
    
    
    <charts-container :hasData="true" title="Programs Monitored Analysis" :note="displayAnalyticsData ? 'The line graph compares the number of programs monitored in each quarter during '+analyticsLabel[0]+ ', ' +analyticsLabel[1]+ ', and '+analyticsLabel[2]+'.' : null ">
        <template v-slot:data>
            <div v-show="displayAnalyticsData" class="my-3 p-3 rounded border bg-blue-100 text-sm text-left">
                {{ conclusion }}
            </div>
        </template>
        <template v-slot:chart>
            <analytics-line-chart v-if="displayAnalyticsData" :thisYear="props.thisYear" :lastYear="props.lastYear" :twoYearsAgo="props.twoYearsAgo" :labels="analyticsLabel" :filter="heiName != null ? 'HEI' : programName != null ? 'Program' : 'Overall'" :growthRate="quarterlyGrowth" :isCurrentYear="isCurrentYear" :currentQuarter="currentQuarter"></analytics-line-chart>
            <div v-else class="py-10">
                No data to display
            </div>
        </template>
    </charts-container>

    <charts-container :hasData="true" :title="'Programs Monitored by Month ('+filters.year+')'" :note="evaluatedTotal != 0 ? 'The bar graph compares the number of programs monitored in each month.': null">
        <template v-slot:data>
            <div v-show="evaluatedTotal != 0 && isCurrentYear" class="w-full flex flex-col lg:flex-row items-center justify-center mb-5">
                <div class="w-full lg:w-1/5 flex flex-col mx-3 p-3 rounded lg:mt-0 mt-5 border border-gray-300">
                    <div class="text-lg font-bold">{{ monthlyAverageResult() }}</div>
                    <div class="text-blue-500">Monthly Average</div>                </div>
                <div class="w-full lg:w-1/5 flex flex-col mx-3 p-3 rounded lg:mt-0 mt-5 border border-gray-300">
                    <div class="text-lg font-bold">{{ byMonthData[monthIndex] }}</div>
                    <div class="text-blue-500">This Month</div>
                </div>
            </div>
        </template>
        <template v-slot:chart>
            <div v-if="evaluatedTotal == 0" class="py-10 w-full text-center">No data to display</div>
            <bar-chart v-else :dataItem="byMonthData" :color="['#0af']" :labels="['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']"/>
        </template>
    </charts-container>

    <div v-show="$page.props.auth.user.role != 'Education Supervisor'" class="mx-3 md:mx-8 my-12">
        <div class="h-0.5 w-full bg-stone-200">

        </div>
    </div>
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
    <modal :showModal="showScaleModal" @close="toggleScaleModal" width="sm" height="long" title="Monitoring Performance Scale">
        <div>
            <div class="flex flex-col">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b">
                            <th class="p-2">Percentage Range</th>
                            <th class="p-2">Performance Level</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b">
                            <td class="p-2">0% - 20%</td>
                            <td class="p-2 text-red-500">Very Low</td>
                        </tr>
                        <tr class="border-b">
                            <td class="p-2">21% - 40%</td>
                            <td class="p-2 text-red-500">Low</td>
                        </tr>
                        <tr class="border-b">
                            <td class="p-2">41% - 60%</td>
                            <td class="p-2 text-green-600">Moderate</td>
                        </tr>
                        <tr class="border-b">
                            <td class="p-2">61% - 80%</td>
                            <td class="p-2 text-blue-500">High</td>
                        </tr>
                        <tr class="border-b">
                            <td class="p-2">81% - 100% or above</td>
                            <td class="p-2 text-blue-500">Very High</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </modal>
    
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
    'isCurrentYear',
    'analyticsLabel',
    'displayAnalyticsData',
    'totalPrograms',
    'currentQuarter',
]);

const showScaleModal = ref(false);

const query = useForm({
    academicyear: ref(props.academicyear),
    filter: ref(props.filters.filter) != null ? ref(props.filters.filter) : null,
    hei: ref(props.hei) != null ? ref(props.hei) : null,
    program: ref(props.program) != null ? ref(props.program) : null,
    year: ref(props.filters.year) != null ? ref(props.filters.year) : null,
});

function toggleScaleModal() {
    showScaleModal.value = !showScaleModal.value;
}

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




//============================================================================
// Monthly

const monthlyAverageResult = () => {
    let total = 0;
    let monthly_average = 0;
    for (var i = 0; i < props.monthIndex; i++) {
        total = total + props.byMonthData[i];
    }
    monthly_average = total / props.monthIndex;
    return monthly_average.toFixed(0);
};


//===========================================================================
// Quarterly

const byQuarterData = (quarterData, quarter) => {
    let arr = [];
    let programName = props.programName != null ? props.programName : '';
    let percentage = quarterData / props.evaluatedTotal * 100;
    let heiName = props.heiName != null ? props.heiName : '';

    arr.push(quarter);
    arr.push(props.filters.year);
    arr.push(quarterData);
    arr.push(props.evaluatedTotal);
    arr.push(percentage.toFixed(2));
    arr.push(heiName);
    arr.push(programName);
    arr.push(byQuarterPercentage(quarterData));
    arr.push(props.totalPrograms);
    return arr;
}

const byQuarterPercentage = (quarterData) => {
    let percentage = 0;

    if (quarterData) {
        percentage = (quarterData / props.totalPrograms) * 100;
        return percentage.toFixed(2);
    } else {
        percentage = null;
        return percentage;
    }
}

const performance = () => {
    let percentage = 0;

    percentage = (props.evaluatedTotal / props.totalPrograms) * 100;

    if (percentage <= 20) {
        return "Very Low";
    } else if (percentage > 20 && percentage <= 40) {
        return "Low";
    } else if (percentage > 40 && percentage <= 60) {
        return "Moderate";
    } else if (percentage > 60 && percentage <= 80) {
        return "High";
    } else if (percentage > 80) {
        return "Very High";
    }
}

const monitoredPercentage = (role) => {

    let progOrHei = props.programName ? " in " + props.programName : props.heiName ? " in " + props.heiName : "";
    let percentage = 0;
    let performance = "";
    let advice = "";
    let description = "";
    let currentQuarter = props.currentQuarter == 1 ? "Q1" : props.currentQuarter == 2 ? "Q2" : props.currentQuarter == 3 ? "Q3" : props.currentQuarter == 4 ? "Q4" : null;
    let threshold = props.currentQuarter == 1 ? 25 : props.currentQuarter == 2 ? 50 : props.currentQuarter == 3 ? 75 : props.currentQuarter == 4 ? 100 : null;
    let targetThreshold = 0;
    let thresholdPercentage = threshold / 100;
    let overallPercentage = 0; 


    percentage = (props.evaluatedTotal / props.totalPrograms) * 100;
    
    
    if (props.isCurrentYear) {
        percentage = (percentage / threshold) * 100;
        
        targetThreshold = props.totalPrograms * thresholdPercentage;
        overallPercentage = ((props.evaluatedTotal / props.totalPrograms) * 100).toFixed(2);
    }
    


    let percentageFloat = parseFloat(percentage);
    let excess = percentageFloat - 100;


    if (!props.isCurrentYear) {
        if (percentage <= 20) {
            performance = "This indicates <b class='text-red-500'>very low monitoring performance</b>, which means that only a small fraction of programs are monitored. ";
        } else if (percentage > 20 && percentage <= 40) {
            performance = "This indicates <b class='text-red-500'>low monitoring performance</b>, which means that less than half of the programs" + progOrHei + " are monitored. ";
        } else if (percentage > 40 && percentage <= 60) {
            performance = "This indicates <b class='text-green-600'>moderate monitoring performance</b>, which means that about half of the programs are monitored. ";
        } else if (percentage > 60 && percentage <= 80) {
            performance = "This indicates <b class='text-blue-500'>high monitoring performance</b>, which means that the majority of programs are monitored. ";
        } else if (percentage > 80 && percentage <= 100) {
            performance = "This indicates <b class='text-blue-500'>very high monitoring performance</b>, which means that (nearly) all programs are monitored. ";
        } else if (percentage > 100) {
            performance = "This <b class='text-blue-500'>very high monitoring performance</b> indicates that not only were all programs monitored" + progOrHei + ", but there was also an additional " + excess.toFixed(2) + "% of coverage beyond the total number of programs. ";
        }  
    } else {
        if (percentage <= 20) {
            performance = "This indicates <b class='text-red-500'>very low monitoring performance</b>, which means that only a small fraction of programs are monitored. ";
            advice = "Immediate intervention is needed to significantly increase the monitoring rate and address the large gap in performance.";
        } else if (percentage > 20 && percentage <= 40) {
            performance = "This indicates <b class='text-red-500'>low monitoring performance</b>, which means that less than half of the programs" + progOrHei + " are monitored. ";
            advice = "Immediate action is necessary to improve the monitoring rate and bring it closer to the expected threshold."
        } else if (percentage > 40 && percentage <= 60) {
            performance = "This indicates <b class='text-green-600'>moderate monitoring performance</b>, which means that about half of the programs are monitored. ";
            advice = "Continued effort is required to reach the target."
        } else if (percentage > 60 && percentage <= 80) {
            performance = "This indicates <b class='text-blue-500'>high monitoring performance</b>, which means that the majority of programs are monitored. ";
            advice = "Continued effort is essential to maintain momentum and meet the full target.";
        } else if (percentage > 80 && percentage <= 100) {
            performance = "This indicates <b class='text-blue-500'>very high monitoring performance</b>, which means that (nearly) all programs are monitored. ";
            advice = "Maintain the current level of performance with continued diligence.";
        } else if (percentage > 100) {
            performance = "This <b class='text-blue-500'>very high performance</b> indicates that not only were all programs monitored" + progOrHei + ", but there was also an additional " + excess.toFixed(2) + "% of coverage beyond the total number of programs. ";
            advice = "Maintain the current level of performance with continued diligence.";
        }  
    }
    


    
    if (props.program) {
        if (props.isCurrentYear) {
            description = "As of " + currentQuarter + " " + props.filters.year + ", " + props.evaluatedTotal + " programs were monitored " + progOrHei + ", which is " + percentage.toFixed(2) + "% of the target threshold ("+ targetThreshold.toFixed(0) + " programs) for " + currentQuarter + ". "+ performance + advice;
        } else {
            description = "In " + props.filters.year + ", " + props.evaluatedTotal + " "+ props.programName +" programs were monitored, which is " + percentage.toFixed(2) + "% of the " + props.totalPrograms + " programs available. " + performance;
        }
    } else if (props.hei) {
        if (props.isCurrentYear) {
            description = "As of " + currentQuarter + " " + props.filters.year + ", " + props.evaluatedTotal + " programs were monitored " + progOrHei + ", which is " + percentage.toFixed(2) + "% of the target threshold ("+ targetThreshold.toFixed(0) + " programs) for " + currentQuarter + ". " + performance + advice;
        } else {
            description = "In " + props.filters.year + ", " + props.evaluatedTotal + " programs in the "+ props.heiName +" were monitored, which is " + percentage.toFixed(2) + "% of the " + props.totalPrograms + " programs available. " + performance;
        }
    } else {
        if (props.isCurrentYear) {
            description = "As of " + currentQuarter + " " + props.filters.year + ", " + props.evaluatedTotal + " programs were monitored, which is " + percentage.toFixed(2) + "% of the target threshold ("+ targetThreshold.toFixed(0) + " programs) for " + currentQuarter + ". "+ performance + advice;
        } else {
            description = "In " + props.filters.year + ", " + props.evaluatedTotal + " programs were monitored, which is " + percentage.toFixed(2) + "% of the " + props.totalPrograms + " programs available. " + performance;
        }
    }

    return description;
    
}

//============================================================================================================
// Analytics (Line Graph)

const getQuarterlyGrowth = (index, data) => {

    if (index == 0 || data[index] == 0 || data[index-1] == 0) {
        return 0;
    }
    
    let current = data[index];
    let previous = data[index - 1];
    
    let rate = ((current - previous) / previous) * 100;

    return rate.toFixed(2);
}

// Reactive Data: Cumulative totals and quarterly growth for each year
const cumulativeTotals = ref({
  twoYearsAgo: props.twoYearsAgo[0] + props.twoYearsAgo[1] + props.twoYearsAgo[2] + props.twoYearsAgo[3],
  lastYear: props.lastYear[0] + props.lastYear[1] + props.lastYear[2] + props.lastYear[3],
  thisYear: props.thisYear[0] + props.thisYear[1] + props.thisYear[2] + props.thisYear[3],
});

const quarterlyGrowth = ref({
  twoYearsAgo: [0, parseFloat(getQuarterlyGrowth(1, props.twoYearsAgo)), parseFloat(getQuarterlyGrowth(2, props.twoYearsAgo)), parseFloat(getQuarterlyGrowth(3, props.twoYearsAgo))],
  lastYear: [0, parseFloat(getQuarterlyGrowth(1, props.lastYear)), parseFloat(getQuarterlyGrowth(2, props.lastYear)), parseFloat(getQuarterlyGrowth(3, props.lastYear))],
  thisYear: [0, parseFloat(getQuarterlyGrowth(1, props.thisYear)), parseFloat(getQuarterlyGrowth(2, props.thisYear)), parseFloat(getQuarterlyGrowth(3, props.thisYear))],
});


// Computed Conclusion based on the data
const conclusion = computed(() => {
  let conclusion = "";

    // Cumulative Totals comparison
    
    if (cumulativeTotals.value['thisYear'] > cumulativeTotals.value['lastYear'] && cumulativeTotals.value['thisYear'] > cumulativeTotals.value['twoYearsAgo']) {
        // Highest cumulative growth
        conclusion = props.filters.year + " has shown the highest overall cumulative growth compared to previous years.";
    } else if (cumulativeTotals.value['thisYear'] < cumulativeTotals.value['lastYear'] && cumulativeTotals.value['thisYear'] < cumulativeTotals.value['twoYearsAgo']) {
        // Lowest cumulative growth
        conclusion = props.filters.year + " has the lowest overall cumulative growth compared to previous years, indicating a decline.";
    } else {
        // Moderate cumulative growth
        conclusion = props.filters.year + "'s cumulative growth is moderate compared to previous years.";
    }


    // New condition: Check for steady or volatile growth

    if (quarterlyGrowth.value['thisYear'].every(q => q > 0)) {
        // If all quarters have positive growth
        conclusion += " Additionally, the growth in " + props.filters.year + " was steady, indicating a consistent upward trend.";
    } else if (quarterlyGrowth.value['thisYear'].some(q => q < 0) && quarterlyGrowth.value['thisYear'].some(q => q > 0)) {
        // Mixed growth (some quarters positive, some negative)
        conclusion += " However, " + props.filters.year + " experienced a mix of growth and decline, indicating some volatility in program monitoring.";
    } else if (quarterlyGrowth.value['thisYear'].every(q => q < 0)) {
        // All quarters have negative growth
        conclusion += " Unfortunately, " + props.filters.year + " faced consistent declines throughout the year.";
    }




    // Additional quarter-specific analysis (e.g., if Q3 was particularly strong or weak)
    if ((props.currentQuarter > 2 && props.isCurrentYear) || (!props.isCurrentYear)) {
        if (quarterlyGrowth.value['thisYear'][2] > quarterlyGrowth.value['thisYear'][1]) {
            // Q3 stronger than Q2
            conclusion += " The third quarter of " + props.filters.year + " showed significant improvement compared to the second quarter.";
        } else if (quarterlyGrowth.value['thisYear'][2] < quarterlyGrowth.value['thisYear'][1]) {
            // Q3 weaker than Q2
            conclusion += " The third quarter of " + props.filters.year + " saw a decline in performance compared to the second quarter.";
        } else if (quarterlyGrowth.value['thisYear'][2] == quarterlyGrowth.value['thisYear'][1]) {
            // Q3 balanced with Q2
            conclusion += " The third quarter of " + props.filters.year + " remained consistent with the second quarter, showing no significant change in performance.";
        }
    }
    
    // Check if Q4 shows recovery or decline

    if ((props.currentQuarter > 3 && props.isCurrentYear) || (!props.isCurrentYear)) {
        if (props.thisYear[3] > props.thisYear[4]) {
        conclusion += " However, there was a recovery in the fourth quarter, showing signs of improvement.";
        } else {
            conclusion += " Unfortunately, the fourth quarter also showed signs of decline, suggesting challenges in maintaining growth.";
        }
    }

    return conclusion;
    
});

</script>

<script>
import Layout from '@/Shared/Layout.vue';

    export default {
        layout: Layout,
    }
</script>

<style scoped>
.tooltipForScale {
    position: relative;
    cursor: pointer;
}

.tooltipForScale::before {
    content: attr(data-tooltip);
    position: absolute;
    right: 0px;
    bottom: 45px;
    font-size: 13px;
    font-weight: normal;
    width: fit-content;
    height: fit-content;
    white-space: nowrap;
    background-color: #333;
    color: #eee;
    padding: 10px;
    border-radius: 3px;
    display: none;
    z-index: 10000;
}

.tooltipForScale:hover::before {
    display: block;
}
</style>