<template>

    <Head title="Dashboard" />
    <page-title title="Dashboard" />
    <div class="mx-3 md:mx-8 py-8 lg:py-3 px-5 mt-8 border border-gray-300 rounded bg-white flex flex-col lg:flex-row justify-between items-center">
        <div class="mb-8 lg:mb-0 w-full">
            <div class="flex flex-row items-center">
                <i class="fas fa-book mr-3 text-blue-500"></i>
                <div v-if="!discipline & !program">
                    All
                </div>
                <div v-else>{{ disciplineName }}{{ programName }}
                    <span v-if="major != null">
                        - {{ major }}
                    </span>
                </div>
            </div>
            <div><i class="fas fa-institution mr-3 text-blue-500"></i>{{ heiName }}</div>
        </div>
        <div class="flex flex-col lg:flex-row w-full lg:w-auto">
            <div v-show="role != 'Program Head'" class="flex flex-col lg:flex-row items-center lg:whitespace-nowrap w-full lg:w-auto">
                <div class="lg:mr-2 w-full lg:w-auto">
                    <select @change="selectAll" v-model="query.filter" id="filter" class="w-full cursor-pointer lg:w-32 select-none h-10 py-0 rounded text-sm lg:mr-2 lg:mb-0 mb-2 border-gray-400 hover:border-gray-500">
                        <option :value="null">All</option>
                        <option v-show="role == 'Vice-President for Academic Affairs'" value="discipline">Discipline</option>
                        <option value="program">Program</option>
                    </select>
                    <select v-show="query.filter == null" disabled="true" id="selectall" @change="selectDiscipline" class="w-full lg:w-40 select-none h-10 py-0 rounded text-sm text-gray-500 border-gray-400">
                        <option :value="null">N/A</option>
                    </select>
                    <select v-show="query.filter == 'discipline'" v-model="query.discipline" id="selectDiscipline" @change="selectDiscipline" class="w-full border-gray-400 hover:border-gray-500 cursor-pointer lg:w-40 select-none h-10 py-0 rounded text-sm text-blue-500">
                        <option :value="null">Select Discipline</option>
                        <option v-for="discipline in discipline_list" :key="discipline.id" :value="discipline.id">
                            {{ discipline.discipline }}
                        </option>
                    </select>
                    <select v-show="query.filter == 'program'" v-model="query.program" @change="selectProgram" id="selectProgram" class="w-full border-gray-400 hover:border-gray-500 cursor-pointer lg:w-40 select-none h-10 py-0 rounded text-sm text-blue-500">
                        <option :value="null">Select program</option>
                        <option v-for="program in program_list" :key="program.id" :value="program.id">
                            <span>{{ program.program }} </span> <span v-if="program.major != null"> - {{ program.major }}</span>
                        </option>
                    </select>
                </div>
            </div>
            <div class="flex flex-row mt-5 lg:mt-0">
                <select @change="submit" id="acadYearDropDown" class="select-none cursor-pointer border-gray-400 hover:border-gray-500 w-full lg:w-32 h-10 px-2 py-0 rounded text-sm" v-model="query.academicyear">
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
    </div>
    <div class="mx-3 md:mx-8 mt-5 flex flex-col lg:flex-row justify-between">
        <div class="w-full lg:w-1/2 lg:mr-5">
            <div class="flex flex-col lg:flex-row">
                <div class="bg-white border border-gray-300 rounded p-5 mt-2 lg:mt-0 mr-5 w-full">
                    <div v-if="program">
                        <div class="text-lg font-bold">
                            <div v-if="complianceTool[0]">{{ complianceTool[0]?.complied }}</div>
                            <div v-else>-</div>
                        </div>
                        <div class="text-blue-500">Complied</div>
                    </div>
                    <div v-else>
                        <div class="text-lg font-bold">{{ readyforvisit }}</div>
                        <div class="text-blue-500">Ready for visit</div>
                    </div>
                </div>
                <div class="bg-white border border-gray-300 rounded p-5 mt-2 lg:mt-0 w-full">
                    <div v-if="program">
                        <div class="text-lg font-bold">
                            <div v-if="complianceTool[0]">{{ complianceTool[0]?.notcomplied }}</div>
                            <div v-else>-</div>
                        </div>
                        <div class="text-blue-500">Not complied</div>
                    </div>
                    <div v-else>
                        <div class="text-lg font-bold">{{ inprogress }}</div>
                        <div class="text-blue-500">In progress</div>
                    </div>
                </div>
            </div>
            <div class="flex flex-col lg:flex-row mt-0 lg:mt-3">
                <div class="bg-white border border-gray-300 rounded p-5 mt-2 lg:mt-0 mr-5 w-full">
                    <div v-if="program">
                        <div class="text-lg font-bold">
                            <div v-if="complianceTool[0]">{{ complianceTool[0]?.notapplicable }}</div>
                            <div v-else>-</div>
                        </div>
                        <div class="text-blue-500">Not applicable</div>
                    </div>
                    <div v-else>
                        <div class="text-lg font-bold">{{ forreevaluation }}</div>
                        <div class="text-blue-500">For re-evaluation</div>
                    </div>
                </div>
                <div class="bg-white border border-gray-300 rounded p-5 mt-2 lg:mt-0 w-full">
                    <div v-if="program">
                        <div class="text-lg font-bold">
                            <div v-if="complianceTool[0]">{{ complianceTool[0]?.nostatus }}</div>
                            <div v-else>-</div>
                        </div>
                        <div class="text-blue-500">No status</div>
                    </div>
                    <div v-else>
                        <div class="text-lg font-bold">{{ monitored }}</div>
                        <div class="text-blue-500">Monitored</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="w-full lg:w-1/2 border border-gray-300 bg-white rounded mt-3 lg:mt-0">
            <div class="p-3 border-b border-gray-300">
                <div v-if="program"><b>Self-Evaluation Status</b></div>
                <div v-else><b>Compliance Status</b></div>
            </div>
            <div v-if="program" class="flex items-center text-center w-full h-36">
                <compliance-status v-show="complianceTool[0] != null" :dataItem="[complianceTool[0]?.complied, complianceTool[0]?.notcomplied, complianceTool[0]?.notapplicable, complianceTool[0]?.nostatus]" :color="['#04f', '#07f', '#09f', '#0cf']"
                :labels="['Complied', 'Not complied', 'Not applicable', 'No status']"
                >

                </compliance-status>
                <div v-show="complianceTool[0] == null" class="w-full text-gray-500">
                    No data to display
                </div>
            </div>
            <div v-else class="flex items-center text-center w-full h-36">
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
    <div v-show="program" class="mx-3 md:mx-8 mt-5 mb-5">
        <div class="flex lg:flex-row flex-col w-full lg:w-1/2 lg:pr-2">
            <div class="w-full border border-gray-300 rounded bg-white p-5 mr-5">
                <div class="w-16">
                    <compliance-progress v-if="complianceTool[0]?.progress != null" :percentage="complianceTool[0]?.progress" :dataItem="[complianceTool[0]?.complied, complianceTool[0]?.notcomplied, complianceTool[0]?.notapplicable, complianceTool[0]?.nostatus]">

                    </compliance-progress>
                    <div v-else>-</div>
                </div>
                <div class="text-blue-500">Progress percentage</div>
            </div>
            <div class="w-full flex items-end border border-gray-300 rounded bg-white p-5 mt-3 lg:mt-0">
                <div>
                    <div class="text-lg font-bold">
                        <div class="text-base" v-if="complianceTool[0]?.status != null">{{complianceTool[0]?.status}}</div>
                        <div v-else>-</div>
                    </div>
                    <div class="text-blue-500">Evaluation status</div>
                </div>
            </div>
        </div>
    </div>
    <div class="mx-3 md:mx-8 mt-5 flex flex-col lg:flex-row mb-5" v-show="!program">
        <div class="w-full border border-gray-300 rounded bg-white">
            <div class="p-3 border-b border-gray-300"><b>Compliance Progress</b></div>
            <div class="px-3 max-h-screen overflow-y-auto">
                <table class="w-full text-left overflow-x-auto">
                    <thead>
                        <tr class="border-b sticky top-0 bg-white z-10">
                            <th class="py-2">Program</th>
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
                                <div class="flex flex-col whitespace-normal">
                                    <div v-show="discipline || props.filters.filter == null">{{ item.program }}</div>
                                    <div v-show="program || props.filters.filter == null">{{ item.discipline }}</div>
                                </div>
                            </td>
                            <td class="text-sm">
                                <div v-if="item.status == 'Deployed'" class="bg-gray-600 px-1 text-white w-fit rounded text-xs">
                                    Pending
                                </div>
                                <div v-else class="bg-green-500 px-1 w-fit rounded text-white text-xs">
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
                                <a :href="'/hei/evaluation/' + item.id + '/view'" target="_blank">
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
</template>

<script setup>
import { router, useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps([
    'complianceTool',
    'role',
    'filters',
    'academicyear',
    'readyforvisit',
    'inprogress',
    'forreevaluation',
    'monitored',
    'program_list',
    'program',
    'programName',
    'major',
    'discipline_list',
    'discipline',
    'disciplineName',
    'heiName',
    'tools',
]);

const query = useForm({
    academicyear: ref(props.academicyear),
    filter: ref(props.filters.filter) != null ? ref(props.filters.filter) : null,
    discipline: ref(props.discipline) != null ? ref(props.discipline) : null,
    program: ref(props.program) != null ? ref(props.program) : null,
});

function submit() {
    query.get("/hei/dashboard", {
        preserveState: false,
        preserveScroll: true,
    });
}

function selectAll() {
    if (query.filter == null) {
        query.discipline = null;
        query.program = null;
        router.get("/hei/dashboard");
    }
}

function selectDiscipline() {
    query.program = null;
    query.get("/hei/dashboard", {
        preserveState: false,
        preserveScroll: true,
    });
}

function selectProgram() {
    query.discipline = null;
    query.get("/hei/dashboard", {
        preserveState: false,
        preserveScroll: true,
    });
}

</script>

<script>
    import Layout from '@/Shared/Layout.vue';
import Notification from '@/Shared/Notification.vue';

    export default {
  components: { Notification },
        layout: Layout,
    }
</script>