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
            <div class="flex flex-col md:flex-row gap-3 w-full md:w-auto">
                <button
                    @click="toggleDeployModal"
                    class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300 focus:outline-none transition-colors duration-200 whitespace-nowrap"
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
                    class="px-4 pr-10 py-2.5 text-sm bg-white border border-slate-200 text-slate-700 rounded-lg hover:border-slate-300 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 focus:outline-none transition-colors duration-200 cursor-pointer appearance-none whitespace-nowrap"
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
            <div class="relative">
                <div
                    class="absolute inset-y-0 left-3 flex items-center pointer-events-none"
                >
                    <svg
                        class="w-5 h-5 text-gray-400"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                        />
                    </svg>
                </div>

                <input
                    @keydown.enter="submit"
                    v-model="query.search"
                    type="search"
                    id="content-search"
                    placeholder="Search"
                    class="w-full py-2.5 pl-11 pr-4 bg-white border border-gray-200 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors duration-200"
                />
            </div>
        </template>
        <template v-slot:options>
            <div class="flex gap-2">
                <button
                    @click="toggleFilterModal"
                    class="p-2.5 inline-flex items-center justify-center rounded-lg text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 transition-all duration-200 tooltipForActions"
                    data-tooltip="Filters"
                >
                    <svg
                        class="w-5 h-5"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"
                        />
                    </svg>
                    <span
                        class="absolute -top-8 left-1/2 -translate-x-1/2 px-2 py-1 bg-gray-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200"
                    >
                        Filters
                    </span>
                </button>

                <Link href="/admin/tool">
                    <button
                        class="p-2.5 inline-flex items-center justify-center rounded-lg text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 transition-all duration-200 tooltipForActions"
                        data-tooltip="Refresh page"
                    >
                        <svg
                            class="w-5 h-5"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                            />
                        </svg>
                    </button>
                </Link>
            </div>
        </template>
        <template v-slot:main-content>
            <content-table>
                <template v-slot:table-head>
                    <th
                        class="px-6 py-4 text-left text-base font-bold text-gray-800"
                    >
                        Institution
                    </th>
                    <th
                        class="px-6 py-4 text-left text-base font-bold text-gray-800"
                    >
                        Program
                    </th>
                    <th
                        class="px-6 py-4 text-left text-base font-bold text-gray-800"
                    >
                        Major
                    </th>
                    <th
                        class="px-6 py-4 text-left text-base font-bold text-gray-800"
                    >
                        CMO
                    </th>
                    <th
                        class="px-6 py-4 text-left text-base font-bold text-gray-800"
                    >
                        Status
                    </th>
                </template>
                <template v-slot:table-body>
                    <tr v-if="institutionProgramList.data.length == 0">
                        <no-search-result text="compliance evaluation tool" />
                    </tr>
                    <tr
                        v-else
                        v-for="(program, index) in institutionProgramList.data"
                        :key="program.id"
                        class="transition-colors hover:bg-blue-200 border-b border-gray-200"
                        :class="{ 'bg-slate-200': index % 2 === 1 }"
                    >
                        <td class="px-6 py-2 text-gray-900 align-top">
                            {{ program.institution?.name }}
                        </td>
                        <td class="px-6 py-2 text-gray-900 align-top">
                            {{ program.program?.program }}
                        </td>
                        <td class="px-6 py-2 text-gray-900 align-top">
                            {{ program.program?.major || "N/A" }}
                        </td>
                        <td class="px-6 py-2 text-gray-900 align-top">
                            <div>
                                CMO No.{{
                                    program.evaluation_form[0]?.cmo?.number
                                }}
                                Series of
                                {{ program.evaluation_form[0]?.cmo?.series }}
                                <!-- Version {{ program.evaluation_form[0]?.cmo?.version }} -->
                            </div>
                        </td>
                        <td class="px-6 py-2 align-top whitespace-nowrap">
                            <status :text="program.evaluation_form[0]?.status"></status>
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
                <div class="p-3">
                    <div>
                        <label class="font-bold text-gray-700" for="program"
                            >Program</label
                        >
                        <select
                            id="program"
                            class="w-full h-10 text-sm rounded border-gray-400 my-0.5"
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
                    <div class="mt-3">
                        <div
                            :class="{
                                'grayscale opacity-50 pointer-events-none':
                                    deployment.program == null,
                            }"
                        >
                            <label class="font-bold text-gray-700" for="cmo"
                                >CHED Memorandum Order</label
                            >
                            <input
                                id="cmo"
                                type="text"
                                disabled
                                class="w-full text-sm rounded border-gray-400 my-0.5"
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
                    <div class="mt-3">
                        <label
                            class="font-bold text-gray-700"
                            for="toolEffectivity"
                            >Effective A.Y.</label
                        >
                        <select
                            v-model="deployment.effectivity"
                            id="toolEffectivity"
                            class="w-full text-sm rounded border-gray-400 my-0.5"
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
                        class="select-none text-white h-10 w-20 rounded border"
                        :class="[
                            {
                                'bg-gray-700':
                                    deployment.program == null ||
                                    deployment.cmo == null,
                            },
                            {
                                'bg-blue-600 hover:bg-blue-700':
                                    deployment.program != null &&
                                    deployment.cmo != null,
                            },
                        ]"
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

    <modal
        :showModal="showFilterModal"
        @close="toggleFilterModal"
        width="sm"
        height="long"
        title="Filters"
        class="antialiased"
    >
        <div>
            <!-- Status Filter -->
            <div
                class="flex flex-col space-y-2 mb-6"
            >
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
                class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200 w-20 h-10"
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
        height="short"
        @close="toggleConfirmationModal"
        title="Confirm Deployment"
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
                class="select-none text-white bg-blue-600 hover:bg-blue-700 h-10 w-20 rounded border"
            >
                <span v-if="deploying">
                    <i class="fas fa-spinner animate-spin"></i>
                </span>
                <span v-else>Confirm</span>
            </button>
        </template>
    </Confirmation>
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

const deployment = useForm({
    program: null,
    cmo: null,
    effectivity: ref(props.effectivity),
});

watch(
    () => deployment.program?.active_cmo,
    (cmo_value) => {
        deployment.cmo = cmo_value;
    }
);

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
    query.search = "";
    query.get("/admin/tool", {
        preserveScroll: true,
        preserveState: false,
    });
}
</script>

<script>
import Layout from "@/Shared/Layout.vue";
import FormErrorMessage from "@/Shared/Component/FormErrorMessage.vue";
import Confirmation from "@/Shared/Confirmation.vue";
export default {
    components: { Confirmation },
    data() {
        return {
            openSelectPageDropdown: false,
            academicYearDropdown: [
                "2023-2024",
                "2024-2025",
                "2025-2026",
                "2026-2027",
                "2027-2028",
                "2028-2029",
                "2029-2030",
            ],
        };
    },
    layout: Layout,
};
</script>
