<template>

    <Head title="Program Evaluation" />
    <page-title title="Program Evaluation" />
    <content-container hasSearch="true" pageTitle="Program Monitoring / Evaluation" :hasNavigation="true" page="evaluation" :hasFilters="true" :data_list="complianceTools">
        <template v-slot:navigation>
            <main-content-nav
                btnText="Evaluation"
                routeName="evaluation.ched"
                :isActive="true"
            />
            <main-content-nav
                btnText="Monitored"
                routeName="evaluation.ched.monitored"
            />
        </template>
        <template v-slot:search>
            <search-box v-model="query.search" @submit="filter" />
        </template>
        <template v-slot:options>
            <options @filter="toggleFilterModal" href="/ched/evaluation" />
        </template>
        <template v-slot:main-content>
            <content-table>
                <template v-slot:table-head>
                    <th>Program/HEI</th>
                    <th>Status</th>
                    <th>Progress</th>
                    <th>Compliance Rate</th>
                    <th>
                        <i class="fas fa-ellipsis-v text-lg"></i>
                    </th>
                </template>

                <template v-slot:table-body>
                    <tr v-if="complianceTools.data.length == 0">
                        <no-search-result text="evaluation tool"/>
                    </tr>
                    <tr v-else v-for="item in complianceTools.data"
                    :key="item.id"
                    >
                        <td class="flex flex-col">
                            <div>
                                {{ item.program }}
                                <span v-if="item.major != null">
                                    - {{ item.major }}
                                </span>
                            </div>
                            <div class="text-sm text-gray-600">
                                {{ item.institution }}
                            </div>
                        </td>
                        <td>
                            <status :text="item.status"></status>
                        </td>
                        <td>
                            <evaluation-progress :percentage="item.progress"></evaluation-progress>
                        </td>
                        <td>
                            <div class="text-sm font-semibold">
                                {{ item.complianceRate }}%
                            </div>
                        </td>
                        <td>
                            <action-button type="view" @click="view(item.id)" />
                            <action-button 
                                v-show="canEvaluate"
                                :isDisabled="item.status === 'In progress'"
                                type="evaluate"
                                @click="evaluate(item.id)"
                            />
                            <action-button
                                v-show="canEvaluate" 
                                v-if="item.isLocked"
                                type="unlock"
                                @click="setId(item.id); unlockModal = true;"
                            />
                            <action-button
                                v-else
                                v-show="canEvaluate"
                                type="lock"
                                @click="setId(item.id); lockModal = true;"
                            />

                            <action-button 
                                v-show="canEvaluate"
                                type="monitored"
                                @click="setId(item.id); monitoredModal = true;"
                                :isDisabled="item.status === 'In progress'"
                            />
                        </td>
                    </tr>
                </template>
            </content-table>
        </template>
    </content-container>

    <modal
        :showModal="showFilterModal"
        @close="toggleFilterModal"
        width="md"
        title="Filters"
    >
        <div class="flex flex-col space-y-4">
            <filter-form-status v-model="query.status" />
            <filter-institution v-model="query.institution" :data="institution_list" />
            <filter-program v-model="query.program" :data="program_list" />
            <div class="flex flex-col space-y-1">
                <label for="sort" class="text-sm font-medium text-gray-700"
                    >Sort by</label
                >
                <div class="flex flex-row gap-2">
                    <select
                    v-model="query.sort"
                    id="sort"
                    class="block w-3/5 px-3 py-2 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
                    <option :value="null">Default</option>
                    <option value="progress">Progress</option>
                    <option value="complianceRate">Compliance Rate</option>
                </select>
                <select
                    v-model="query.direction"
                    id="sort"
                    class="block w-2/5 px-3 py-2 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
                    <option value="desc">High to Low</option>
                    <option value="asc">Low to High</option>
                </select>
                </div>
            </div>
            <filter-academic-year v-model="query.academicYear" />
            <filter-page-items v-model="query.show"/>
        </div>

        <template #custom-button>
            <modal-button text="Apply" color="green" @click="filter"/>
        </template>
    </modal>

    <confirmation :showModal="lockModal" @close="lockModal = false" title="Lock Evaluation Tool" width="md">
        <template v-slot:message>
            Are you sure you want to lock this compliance evaluation tool? This action will prevent any further inputs from HEI.
        </template>
        <template v-slot:buttons>
            <modal-button text="Lock" color="red" @click="lock" />
        </template>
    </confirmation>

    <confirmation :showModal="unlockModal" @close="unlockModal = false" title="Unlock Evaluation Tool" width="md">
        <template v-slot:message>
            Are you sure you want to unlock this compliance evaluation tool? This action will enable inputs from HEI.
        </template>
        <template v-slot:buttons>
            <modal-button text="Unlock" color="blue" @click="unlock" />
        </template>
    </confirmation>

    <confirmation :showModal="monitoredModal" @close="monitoredModal = false" title="Mark as Monitored"  width="md">
        <template v-slot:message>
            Are you sure you want to mark this compliance evaluation tool as monitored? Once marked as monitored, it cannot be edited.
        </template>
        <template v-slot:buttons>
            <modal-button text="Confirm" color="blue" @click="monitored" />
        </template>
    </confirmation>
</template>

<script setup>
// ------------------------------------------------------------
import { ref } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import Layout from "@/Shared/Layout.vue";
defineOptions({ layout: Layout });

// ------------------------------------------------------------
const props = defineProps([
    'complianceTools',
    'institution_list',
    'program_list',
    'filters',
    'canEvaluate',
]);

// ------------------------------------------------------------
const sending = ref(false);
const prog = ref(0);

const toolId = ref(null);
const lockModal = ref(false);
const locking = ref(false);

const unlockModal = ref(false);
const unlocking = ref(false);

const monitoredModal = ref(false);
const showFilterModal = ref(false);


const query = useForm({
    show: props.filters.show != null ? props.filters.show : null,
    search: props.filters.search,
    academicYear: props.filters.academicYear != null ? props.filters.academicYear : null,
    status: props.filters.status != null ? props.filters.status : null,
    sort: props.filters.sort != null ? props.filters.sort: null,
    direction: props.filters.direction != null ? props.filters.direction : 'asc',
    institution: props.filters.institution != null ? props.filters.institution: null,
    program: props.filters.program != null ? props.filters.program: null,

});

// ------------------------------------------------------------


function toggleFilterModal() {
    showFilterModal.value = !showFilterModal.value;
}

function filter() {
    query.get("/ched/evaluation", {
        onFinish: () => {
            toggleFilterModal();
        },
        preserveState: false,
        preserveScroll: true,
        replace: true,
    });
}

function evaluate(tool) {
    router.get('/ched/evaluation/' + tool + '/evaluate', {
        preserveScroll: true,
        preserveState: true,
    });
}

function view(tool) {
    router.get('/ched/evaluation/' + tool + '/view');
}

function setId(id) {
    toolId.value = id;
}

function lock() {
    router.post('/ched/evaluation/lock', { 'id': toolId.value }, {
        onStart: () => {
            locking.value = true;
        },
        onFinish: () => {
            locking.value = false;
        },
        preserveState: false,
        preserveScroll: true,
    });
}

function unlock() {
    router.post('/ched/evaluation/unlock', { 'id': toolId.value }, {
        onStart: () => {
            unlocking.value = true;
        },
        onFinish: () => {
            unlocking.value = false;
        },
        preserveState: false,
        preserveScroll: true,
    });
}

function monitored(id) {
    router.post('/ched/evaluation/monitored', { 'id': toolId.value }, {
        preserveState: false,
        preserveScroll: true,
    });
}
</script>

<style scoped>
:deep(td:has(button)) {
    border-bottom: 1px solid #d6d8e1 !important;
  }
</style>