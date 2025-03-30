<template>
    <Head title="Evaluation Forms" />
    <content-container
        :hasAdminPanel="true"
        :hasModal="true"
        pageTitle="Compliance Evaluation Tool"
        page="tool"
        :hasTopButton="canEdit"
        :hasSearch="true"
        :hasFilters="true"
        :data_list="institutionProgramList"
    >
        <template v-slot:top-button>
            <div
                class="flex flex-col md:flex-row gap-3 w-full whitespace-nowrap"
            >
                <button
                    @click="toggleDeployModal"
                    class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300 focus:outline-none duration-200 whitespace-nowrap"
                >
                    <svg
                        class="w-4 h-4 mr-2"
                        viewBox="0 0 24 24"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            d="M12 2L4 12H9V22H15V12H20L12 2Z"
                            fill="currentColor"
                        />
                    </svg>
                    Deploy Tool
                </button>

                <select
                    v-model="query.ay"
                    @change.prevent="changeAcademicYear"
                    class="px-4 pr-10 py-2.5 text-sm bg-white border border-slate-200 text-slate-700 rounded-lg hover:border-slate-300 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 focus:outline-none duration-200 cursor-pointer appearance-none whitespace-nowrap"
                >
                    <option
                        v-for="(item, index) in academicYearDropdown"
                        :key="index"
                        :value="item"
                    >
                        A.Y. {{ item }}
                    </option>
                </select>
            </div>
        </template>

        <template v-slot:search>
            <search-box v-model="query.search" @submit="filter" />
        </template>
        <template v-slot:options>
            <options @filter="toggleFilterModal" href="/admin/tool" />
        </template>

        <template v-slot:main-content>
            <content-table>
                <template v-slot:table-head>
                    <th>Institution</th>
                    <th>Program</th>
                    <th>Major</th>
                    <th>CMO</th>
                    <th>Status</th>
                </template>
                <template v-slot:table-body>
                    <tr v-if="institutionProgramList.data.length == 0">
                        <no-search-result text="compliance evaluation tool" />
                    </tr>
                    <tr
                        v-else
                        v-for="program in institutionProgramList.data"
                        :key="program.id"
                        >
                        <td>
                            {{ program.institution?.name }}
                        </td>
                        <td>
                            {{ program.program?.program }}
                        </td>
                        <td>
                            {{ program.program?.major }}
                        </td>
                        <td>
                            <div>
                                CMO No.{{
                                    program.evaluation_form[0]?.cmo?.number
                                }}
                                Series of
                                {{ program.evaluation_form[0]?.cmo?.series }}
                                <!-- Version {{ program.evaluation_form[0]?.cmo?.version }} -->
                            </div>
                        </td>
                        <td>
                            <status
                                :text="program.evaluation_form[0]?.status"
                            ></status>
                        </td>
                    </tr>
                </template>
            </content-table>
        </template>
        <template v-slot:modal>
            <modal
                :showModal="deployToolModal"
                width="md"
                height="long"
                @close="toggleDeployModal"
                @submit="deploy"
                type="deploy"
                title="Deploy Compliance Tool"
            >
                <div class="space-y-6">
                    <div class="flex flex-col space-y-2">
                        <label class="text-sm font-medium text-gray-700">
                            Program
                        </label>
                        <select
                            id="program"
                            class="w-full h-12 text-sm rounded border-gray-400"
                            v-model="deployment.program"
                        >
                            <option :value="null">Select program</option>
                            <option
                                v-for="program in program_list"
                                :key="program.id"
                                :value="program"
                            >
                                {{ program.program }}
                                <span v-if="program.major != null">
                                    - {{ program.major }}</span
                                >
                            </option>
                        </select>
                    </div>
                    <div>
                        <div
                            :class="{
                                'grayscale opacity-50 pointer-events-none':
                                    deployment.program == null,
                            }"
                        >
                            <label class="text-sm font-medium text-gray-700">
                                CHED Memorandum Order
                            </label>
                            <input
                                id="cmo"
                                type="text"
                                disabled
                                class="w-full text-sm rounded border-gray-400 h-12"
                                :value="
                                    deployment.program?.active_cmo != null
                                        ? 'CMO No.' +
                                          deployment.program.active_cmo.number +
                                          ', Series of ' +
                                          deployment.program.active_cmo.series
                                        : 'No Active CMO'
                                "
                            />
                        </div>
                    </div>
                    <div class="flex flex-col space-y-2">
                        <label class="text-sm font-medium text-gray-700">
                            Effective A.Y.
                        </label>
                        <select
                            v-model="deployment.effectivity"
                            id="toolEffectivity"
                            class="w-full text-sm rounded border-gray-400 h-12"
                        >
                            <option
                                v-for="(year, index) in academicYearDropdown"
                                :key="index"
                                :value="year"
                            >
                                {{ year }}
                            </option>
                        </select>
                    </div>
                </div>
                <template v-slot:custom-button>
                    <button
                        :disabled="
                            deployment.program == null || deployment.cmo == null
                        "
                        @click="toggleConfirmationModal"
                        class="px-3 py-2 text-sm font-medium text-white rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:text-sm"
                        :class="[
                            deployment.program == null || deployment.cmo == null
                                ? 'bg-gray-400 cursor-not-allowed'
                                : 'bg-blue-600 hover:bg-blue-700',
                        ]"
                    >
                        Deploy
                    </button>
                </template>
            </modal>
        </template>
    </content-container>

    <modal
        :showModal="showFilterModal"
        @close="toggleFilterModal"
        width="md"
        title="Filters"
        class="antialiased"
    >
        <div>
            <!-- Status Filter -->
            <div class="flex flex-col space-y-2 mb-6">
                <label for="status" class="text-sm font-medium text-gray-700">
                    Status
                </label>
                <select
                    v-model="query.status"
                    id="status"
                    class="block w-full px-3 py-2 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
                    <option :value="null">All</option>
                    <option value="In progress">In Progress</option>
                    <option value="Submitted">Ready for visit</option>
                    <option value="Monitored">Monitored</option>
                </select>
            </div>

            <!-- Items per page Filter -->
            <div class="flex flex-col space-y-2">
                <label for="show" class="text-sm font-medium text-gray-700">
                    Items per page
                </label>
                <select
                    v-model="query.show"
                    id="show"
                    class="block w-full px-3 py-2 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="150">150</option>
                    <option value="200">200</option>
                    <option value="300">300</option>
                </select>
            </div>
        </div>

        <template #custom-button>
            <button
                @click="filter"
                class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 duration-200 w-20 h-10"
            >
                <span v-if="processing">
                    <i class="fas fa-spinner animate-spin"></i>
                </span>
                <span v-else>Apply</span>
            </button>
        </template>
    </modal>

    <Confirmation
        :showModal="confirmationModal"
        width="md"
        @close="toggleConfirmationModal"
        title="Deploy Compliance Tool"
    >
        <template v-slot:message>
            <div>
                Are you sure you want to deploy evaluation compliance tool for
                <b
                    >{{ deployment.program?.program
                    }}<span v-if="deployment.program?.major">
                        - {{ deployment.program.major }}</span
                    > </b
                >?
            </div>
        </template>
        <template v-slot:buttons>
            <button
                @click="deploy"
                class="px-3 py-2 text-sm font-medium text-white rounded-md shadow-sm bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:text-sm"
            >
                Confirm
            </button>
        </template>
    </Confirmation>
</template>

<script setup>
// -------------------------------------------------------------------------
import { useForm, router } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import Layout from "@/Shared/Layout.vue";
defineOptions({ layout: Layout });

// -------------------------------------------------------------------------
const props = defineProps([
    "institutionProgramList",
    "effectivity",
    "program_list",
    "academicYear",
    "canEdit",
    "filters",
]);

// -------------------------------------------------------------------------
const deployToolModal = ref(false);
const confirmationModal = ref(false);
const showFilterModal = ref(false);
const processing = ref(false);
const academicYearDropdown = [
    "2022-2023",
    "2023-2024",
    "2024-2025",
    "2025-2026",
    "2026-2027",
    "2027-2028",
    "2028-2029",
    "2029-2030",
];

const deployment = useForm({
    program: null,
    cmo: null,
    effectivity: ref(props.effectivity),
});

const query = useForm({
    ay: props.filters.academicYear,
    show: props.filters.show != null ? props.filters.show : null,
    search: props.filters.search,
    status: props.filters.status != null ? props.filters.status : null,
});

// -------------------------------------------------------------------------
function toggleDeployModal() {
    deployToolModal.value = !deployToolModal.value;
}

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

function deploy() {
    if (deployment.program?.active_cmo) {
        deployment.cmo = deployment.program.active_cmo;
    } else {
        deployment.cmo = null;
    }

    router.post("/admin/form/deploy", deployment, {
        preserveState: false,
        replace: true,
    });
}

function toggleFilterModal() {
    showFilterModal.value = !showFilterModal.value;
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
        preserveState: false,
        preserveScroll: true,
        replace: true,
    });
}

function changeAcademicYear() {
    query.search = "";
    query.get("/admin/tool", {
        preserveScroll: true,
        preserveState: false,
        replace: true,
    });
}

watch(
    () => deployment.program?.active_cmo,
    (cmo_value) => {
        deployment.cmo = cmo_value;
    }
);
</script>
