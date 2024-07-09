<template>

    <Head title="Evaluation Forms" />
    <content-container :hasAdminPanel="true" :hasModal="true" pageTitle="Compliance Evaluation Tool" page="tool" :hasTopButton="canEdit" :hasSearch="true" :hasFilters="true" :data_list="institutionProgramList">
        <template v-slot:top-button>
            <div class="flex flex-row">
                <div class="flex md:flex-row flex-col">
                    <div class="w-full">
                        <button @click="toggleDeployModal"
                            class="select-none whitespace-nowrap px-3 rounded h-10 bg-blue-500 hover:bg-blue-600 text-white group">
                            <i class="fas fa-rocket mr-2"></i>
                            Deploy Tool
                        </button>
                    </div>
                </div>
                <select v-model="query.ay" id="academicYearDd" @change.prevent="changeAcademicYear"
                    class="select-none ml-2 text-gray-700 text-sm rounded border border-gray-400 hover:border-gray-500 cursor-pointer">
                    <option v-for="(item, index) in academicYearDropdown" :key="index" :value="item">A.Y. {{ item }}</option>
                </select>
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
                <Link href="/admin/tool">
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
                    <th class="p-2 pl-5">Institution</th>
                    <th class="p-2">Program</th>
                    <th class="p-2">Major</th>
                    <th class="p-2">CMO</th>
                    <th class="p-2">Status</th>
                </template>
                <template v-slot:table-body>
                    <tr v-if="institutionProgramList.data.length == 0">
                        <td class="text-center py-10" colspan="5">
                            No compliance evaluation tool found
                        </td>
                    </tr>
                    <tr v-else v-for="(program, index) in institutionProgramList.data" :key="program.id"
                        class="hover:bg-gray-200 border-b" :class="{ 'bg-gray-100': index % 2 == 1 }">
                        <td class="p-2 pl-5">
                            {{ program.institution?.name }}
                        </td>
                        <td class="p-2">
                            {{ program.program?.program }}
                        </td>
                        <td class="p-2">
                            {{ program.program?.major }}
                        </td>
                        <td class="p-2">
                            <div>
                                CMO No.{{ program.evaluation_form[0]?.cmo?.number }}
                                S. {{ program.evaluation_form[0]?.cmo?.series }},
                                Version {{ program.evaluation_form[0]?.cmo?.version }}
                            </div>
                        </td>
                        <td class="p-2 pr-5 text-left whitespace-nowrap">
                            <div v-if="program.evaluation_form[0]?.status == 'In progress' || program.evaluation_form[0]?.status == 'Deployed'"
                            class="bg-green-600 text-white w-fit px-1 rounded text-xs">
                                In Progress
                            </div>
                            <div v-else-if="program.evaluation_form[0]?.status == 'Submitted'"
                                class="bg-green-600 text-white w-fit px-1 rounded text-xs">
                                Ready for visit
                            </div>
                            <div v-else-if="program.evaluation_form[0]?.status == 'Monitored'"
                                class="bg-blue-500 text-white w-fit px-1 rounded text-xs">
                                Monitored
                            </div>
                        </td>
                    </tr>
                </template>
            </content-table>
        </template>
        <template v-slot:modal>
            <modal :showModal="deployToolModal" width="md" height="long" @close="toggleDeployModal" @submit="deploy" type="deploy"
                title="Deploy Compliance Tool">
                <div class="p-3">
                    <div>
                        <label class="font-bold text-gray-700" for="program">Program</label>
                        <select id="program" class="w-full h-10 text-sm rounded border-gray-400 my-0.5"
                            v-model="deployment.program">
                            <option :value="null">Select program</option>
                            <option v-for="program in program_list" :key="program.id" :value="program">
                                {{ program.program }}
                                <span v-if="program.major != null"> - {{ program.major }}</span>
                            </option>
                        </select>
                    </div>
                    <div class="mt-3">
                        <div :class="{
                                'grayscale opacity-50 pointer-events-none':
                                    deployment.program == null,
                            }">
                            <label class="font-bold text-gray-700" for="cmo">CHED Memorandum Order</label>
                            <input id="cmo" type="text" disabled class="w-full text-sm rounded border-gray-400 my-0.5" :value="
                                    deployment.program?.active_cmo != null
                                        ? 'CMO ' +
                                        deployment.program.active_cmo.number +
                                        ', S. ' +
                                        deployment.program.active_cmo.series +
                                        ', Version ' +
                                        deployment.program.active_cmo.version
                                        : 'No Active CMO'
                                " />
                        </div>
                    </div>
                    <div class="mt-3">
                        <label class="font-bold text-gray-700" for="toolEffectivity">Effective A.Y.</label>
                        <select v-model="deployment.effectivity" id="toolEffectivity"
                            class="w-full text-sm rounded border-gray-400 my-0.5">
                            <option v-for="(year, index) in academicYearDropdown" :key="index" :value="year">{{ year }}</option>
                        </select>
                    </div>
                </div>
                <template v-slot:custom-button>
                    <button 
                    :disabled="deployment.program == null || deployment.cmo == null" @click="toggleConfirmationModal" class="select-none text-white h-10 w-20 rounded border"
                    :class="[{'bg-gray-700':deployment.program == null || deployment.cmo == null}, {'bg-blue-600 hover:bg-blue-700':deployment.program != null && deployment.cmo != null}]"
                    >
                        <span v-if="deploying">
                            <i class="fas fa-spinner animate-spin"></i>
                        </span>
                        <span v-else>Deploy</span>
                    </button>
                </template>
            </modal>
        </template>
    </content-container>

    <modal :showModal="showFilterModal" @close="toggleFilterModal" width="sm" height="long" title="Filters">
        <div>
            <div class="flex flex-col">
                <label for="status">Status</label>
                <select v-model="query.status" id="status" class="rounded border-gray-400">
                    <option :value="null">All</option>
                    <option value="In progress">In Progress</option>
                    <option value="Submitted">Ready for visit</option>
                    <option value="Monitored">Monitored</option>
                </select>
            </div>
            <div class="mt-3 flex flex-col">
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

    <Confirmation :showModal="confirmationModal" width="md" height="short" @close="toggleConfirmationModal" title="Confirm Deployment">
        <template v-slot:message>
            <div>Are you sure you want to deploy evaluation compliance tool for <b>{{ deployment.program?.program }}<span v-if="deployment.program?.major"> - {{ deployment.program.major }}</span>
            </b>?</div>
        </template>
        <template v-slot:buttons>
            <button @click="deploy" class="select-none text-white bg-blue-600 hover:bg-blue-700 h-10 w-20 rounded border">
                <span v-if="deploying">
                    <i class="fas fa-spinner animate-spin"></i>
                </span>
                <span v-else>Confirm</span>
            </button>
        </template>
    </Confirmation>
    
    <Notification :message="$page.props.flash.success" type="success" />
    <Notification :message="$page.props.flash.failed" type="failed" />
    <Notification v-if="$page.props.errors.program" :message="$page.props.errors.program" type="failed" />
    <Notification v-else :message="$page.props.errors.cmo" type="failed" />
</template>

<script setup>
import { useForm, router } from "@inertiajs/vue3";
import { ref, watch } from "vue";

const props = defineProps([
    "institutionProgramList",
    "effectivity",
    "program_list",
    "academicYear",
    "canEdit",
    "filters",
]);

let deployToolModal = ref(false);
const deploying = ref(false);
const confirmationModal = ref(false);
const showFilterModal = ref(false);
const processing = ref(false);

function toggleDeployModal() {
    deployToolModal.value = !deployToolModal.value;
};

function toggleConfirmationModal() {
    if (confirmationModal.value == true) {
        setTimeout(() => {
                toggleDeployModal();
        }, 297);
    } else {
        toggleDeployModal();
    }

    if (confirmationModal.value == false) {
        setTimeout(() => {
            confirmationModal.value = !confirmationModal.value;
        }, 297);
    } else {
        confirmationModal.value = !confirmationModal.value;
    }

}

const deployment = useForm({
    program: null,
    cmo: null,
    effectivity: ref(props.effectivity),
});

watch(() => deployment.program?.active_cmo, (cmo_value) => {
    deployment.cmo = cmo_value;
});

function deploy() {
    if (deployment.program?.active_cmo) {
        deployment.cmo = deployment.program.active_cmo;
    } else {
        deployment.cmo = null;
    }

    router.post("/admin/form/deploy", deployment, {
        onStart: () => {
            deploying.value = true;
        },
        onFinish: () => {
            deploying.value = false;
        },
        preserveState: false,
    });
}

const query = useForm({
    ay: props.filters.academicYear,
    show: props.filters.show != null ? props.filters.show : null,
    search: props.filters.search,
    status: props.filters.status != null ? props.filters.status : null,
});

function toggleFilterModal() {
    showFilterModal.value = !showFilterModal.value;
}

function submit() {
    query.get("/admin/tool", {
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
    query.get("/admin/tool", {
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

function changeAcademicYear() {
    query.search = '';
    query.get("/admin/tool", {
        preserveScroll: true,
        preserveState: false,
    });
}


</script>

<script>
    import Layout from "../Shared/Layout.vue";
    import FormErrorMessage from "../Shared/FormErrorMessage.vue";
import Confirmation from '../Shared/Confirmation.vue';
    export default {
  components: { Confirmation },
        data() {
            return {
                openSelectPageDropdown: false,
                academicYearDropdown: [
                    '2023-2024',
                    '2024-2025',
                    '2025-2026',
                    '2026-2027',
                    '2027-2028',
                    '2028-2029',
                    '2029-2030',
                ],
            };
        },
        layout: Layout,
    };
</script>