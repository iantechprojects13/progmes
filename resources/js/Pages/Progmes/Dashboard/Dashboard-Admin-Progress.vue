<template>

    <Head title="Dashboard" />
    <page-title title="Dashboard" />
    <div class="mx-3 md:mx-8 mt-5 border-b-2 border-gray-400">
        <Link href="/admin/dashboard/progress" class="mr-7">
            <button class="h-10 border-b-2 border-blue-500 text-blue-500 font-bold">
                Progress
            </button>
        </Link>
        <Link href="/admin/dashboard/overview">
            <button class="h-10 text-gray-700 hover:text-black">
                Overview
            </button>
        </Link>
    </div>
    <div class="mx-3 md:mx-8 mt-5 border border-gray-400 rounded p-3 bg-white flex flex-row justify-between items-center">
        <div><i class="fas fa-tasks mr-3 text-blue-500"></i>Progress</div>
        <div class="flex flex-row">
            <select @change="submit" id="acadYearDropDown" class="select-none h-8 py-0 rounded text-sm mr-2" v-model="query.academicyear">
                <option value="2023-2024">A.Y. 2023-2024</option>
                <option value="2024-2025">A.Y. 2024-2025</option>
                <option value="2025-2026">A.Y. 2025-2026</option>
                <option value="2026-2027">A.Y. 2026-2027</option>
                <option value="2027-2028">A.Y. 2027-2028</option>
                <option value="2028-2029">A.Y. 2028-2029</option>
                <option value="2029-2030">A.Y. 2029-2030</option>
            </select>
            <div v-show="$page.props.auth.user.role == 'Super Admin'">
                <dropdown-option position="right">
                    <template v-slot:button>
                        <button class="h-8 select-none whitespace-nowrap border border-gray-500 rounded px-2">
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
            <div class="flex flex-col lg:flex-row mt-3">
                <div class="bg-white border border-gray-400 rounded p-5 mt-2 lg:mt-0 mr-5 w-full">
                    <div class="text-lg font-bold">{{ pending }}</div>
                    <div class="text-blue-500">Pending</div>
                </div>
                <div class="bg-white border border-gray-400 rounded p-5 mt-2 lg:mt-0 w-full">
                    <div class="text-lg font-bold">{{ archived }}</div>
                    <div class="text-blue-500">Archived</div>
                </div>
            </div>
        </div>
        <div class="w-full lg:w-1/2 border border-gray-400 bg-white rounded mt-3 lg:mt-0">
            <div class="p-3 border-b border-gray-400"><b>Compliance Status</b></div>
            <div class="flex items-center text-center w-full h-36">
                <compliance-status :dataItem="[readyforvisit, inprogress, pending, archived]" :color="['#05f', '#07f', '#09f', '#0bf']"
                :labels="['Ready for Visit', 'In progress', 'Pending', 'Completed/Archived']"
                >

                </compliance-status>
            </div>
        </div>
    </div>
    <div class="mx-3 md:mx-8 mt-5 flex flex-col lg:flex-row mb-5">
        <div class="w-full lg:w-2/3 mr-5 border border-gray-400 rounded bg-white">
            <div class="p-3 border-b border-gray-400"><b>Compliance Progress</b></div>
            <div class="p-3 items-start flex flex-col lg:flex-row mt-5 justify-between">
                <div class="flex flex-col lg:flex-row items-center">
                    <span class="mr-2 text-sm font-bold whitespace-nowrap">View by:</span>
                    <select v-model="query.filter" id="filter" class="w-full lg:w-64 select-none h-8 py-0 rounded text-sm lg:mr-2 lg:mb-0 mb-2">
                        <option value="hei">HEI</option>
                        <option value="program">Program</option>
                    </select>
                    <select v-show="query.filter == 'hei'" v-model="query.hei" id="selectHEI" @change="selectHEI" class="w-full select-none h-8 py-0 rounded text-sm text-blue-500">
                        <option :value="null">Select HEI</option>
                        <option v-for="hei in hei_list" :key="hei.id" :value="hei.id">
                            {{ hei.name }}
                        </option>
                    </select>
                    <select v-show="query.filter == 'program'" v-model="query.program" @change="selectProgram" id="selectProgram" class="w-full select-none h-8 py-0 rounded text-sm text-blue-500">
                        <option :value="null">Select program</option>
                        <option v-for="program in program_list" :key="program.id" :value="program.id">
                            <span>{{ program.program }} </span> <span v-if="program.major != null"> - {{ program.major }}</span>
                        </option>
                    </select>
                </div>
            </div>
            <div class="p-3 mt-5 max-h-screen overflow-y-auto">
                <table class="w-full text-left overflow-x-auto">
                    <thead>
                        <tr class="border-b">
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
                                    <div>{{ item.program }}</div>
                                    <div>{{ item.institution }}</div>
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
                                <button class="hover:bg-gray-200 w-10 h-10 rounded-full text-blue-500">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="h-fit w-full lg:w-1/3 border border-gray-400 rounded p-3 bg-white lg:mt-0 mt-3">
            Updates
        </div>
    </div>
    <Notification :message="$page.props.flash.success" type="success"/>
    <Notification :message="$page.props.flash.failed" type="failed"/>
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
    'archived',
    'program_list',
    'program',
    'hei_list',
    'hei',
]);

const query = useForm({
    academicyear: ref(props.academicyear),
    filter: ref(props.filters.filter),
    hei: ref(props.hei) != null ? ref(props.hei) : null,
    program: props.program != null ? props.program : null,
});

function submit() {
    query.get("/admin/dashboard/progress", {
        preserveState: false,
        preserveScroll: true,
    });
}

function selectHEI() {
    query.program = null;
    query.get("/admin/dashboard/progress", {
        preserveState: false,
        preserveScroll: true,
    });
}

function selectProgram() {
    query.hei = null;
    query.get("/admin/dashboard/progress", {
        preserveState: false,
        preserveScroll: true,
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