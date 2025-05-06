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
                    <th>Program/Institution</th>
                    <th>CMO</th>
                    <th>Status</th>
                    <th v-show="canEdit">
                        <i class="fas fa-ellipsis-v text-lg"></i>
                    </th>
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
                            <div>
                                {{ program.program?.program }}
                                 
                                <span v-if="program.program?.major != null">
                                    - {{ program.program?.major }}
                                </span>
                            </div>
                            <div class="text-sm text-gray-600">
                                {{ program.institution?.name }}
                            </div>
                        </td>
                        <td>
                            <div>
                                CMO No.{{
                                    program.evaluation_form[0]?.cmo?.number
                                }},
                                S.{{ program.evaluation_form[0]?.cmo?.series }}
                            </div>
                        </td>
                        <td>
                            <status
                                :text="program.evaluation_form[0]?.status"
                            ></status>
                        </td>
                        <td v-show="canEdit">
                            <button
                                v-show="canDelete"
                                @click="
                                    toggleDeleteModal(
                                        program.evaluation_form[0],
                                        'deleteEvaluationTool',
                                        'Delete Compliance Tool'
                                    )
                                "
                                class="inline-flex items-center justify-center rounded-full h-10 w-10 text-red-700 hover:bg-red-100 tooltipForActions"
                                data-tooltip="Delete"
                            >
                                <i class="fas fa-trash text-lg"></i>
                            </button>
                        </td>
                    </tr>
                </template>
            </content-table>
        </template>
        <template v-slot:modal>
            <modal
                :showModal="deployToolModal"
                width="md"
                @close="toggleDeployModal"
                @submit="deploy"
                title="Deploy Compliance Tool"
            >
                <div class="space-y-4">
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
                    <modal-button
                        @click="toggleConfirmationModal"
                        :class="[
                            deployment.program == null || deployment.cmo == null
                                ? 'bg-gray-400 hover:bg-gray-400 cursor-not-allowed'
                                : 'bg-blue-600 hover:bg-blue-700',
                            ]"
                        :disabled="deployment.program == null || deployment.cmo == null"
                        text="Deploy"
                    />
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
        <div class="flex flex-col space-y-5">
            <filter-form-status v-model="query.status"/>
            <filter-institution v-model="query.institution" :data="institution_list"/>
            <filter-program v-model="query.program" :data="program_list"/>
            <filter-academic-year v-model="query.ay"/>
            <filter-page-items v-model="query.show"/>
        </div>

        <template #custom-button>
            <modal-button text="Apply" color="green" @click="filter"/>
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
            <modal-button text="Confirm" color="blue" @click="deploy"/>
        </template>
    </Confirmation>

    <Confirmation
        :showModal="deleteModal"
        @close="toggleDeleteModal"
        width="md"
        class="antialiased"
    />
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
    "institution_list",
    "academicYear",
    "canEdit",
    'canDelete',
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


const deleteModal = ref(false);
const selectedTool = ref("");

function toggleDeleteModal() {
    deleteModal.value = !deleteModal.value;
}

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
    program: props.filters.program != null ? props.filters.program : null,
    institution: props.filters.institution != null ? props.filters.institution : null,
    
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

function deleteTool() {
    router.get("");
}

watch(
    () => deployment.program?.active_cmo,
    (cmo_value) => {
        deployment.cmo = cmo_value;
    }
);
</script>
