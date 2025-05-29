<template>
    <Head title="Library Compliance Evaluation" />
    <PageTitle title="Library Evaluation" />
    <content-container
        :hasModal="true"
        pageTitle="Library Compliance Evaluation"
        :hasTopButton="true"
        :hasSearch="true"
        :hasFilters="true"
        :data_list="evaluation_list"
    >
        
        <template v-slot:search>
            <search-box v-model="query.search" @submit="filter" />
        </template>
        <template v-slot:options>
            <options @filter="toggleFilterModal" href="/library" />
        </template>

        <template v-slot:main-content>
            <content-table>
                <template v-slot:table-head>
                    <th>HEI</th>
                    <th>Status</th>
                    <th>Progress</th>
                    <th>Compliance Rate</th>
                    <th>
                        <i class="fas fa-ellipsis-v text-lg"></i>
                    </th>
                </template>
                <template v-slot:table-body>
                    <tr v-if="evaluation_list.data.length == 0">
                        <no-search-result text="compliance evaluation tool" />
                    </tr>
                    <tr
                        v-else
                        v-for="item in evaluation_list.data"
                        :key="item.id"
                        >
                        <td>
                            {{ item.institution }}
                        </td>
                        <td>
                            <status :text="item.status" />
                        </td>
                        <td>
                            <evaluation-progress :percentage="item.progress" />
                        </td>
                        <td class="text-sm font-semibold">
                            {{ item.complianceRate }}%
                        </td>
                        <td>
                            <action-button type="view" @click="view(item.id) "/>
                            <action-button :isDisabled="item.status === 'Monitored' || item.status === 'In progress'" v-show="canEvaluate" type="evaluate" @click="evaluate(item.id)"/>

                            <action-button 
                                v-if="item.isLocked"
                                v-show="canEvaluate"
                                :isDisabled="item.status === 'Monitored'"
                                type="unlock"
                                @click="selectedTool = item.id; toggleLockConfirmation(); lockAction = 'unlock'; title = 'Unlock Tool'"
                            />
                            <action-button 
                                v-else
                                v-show="canEvaluate"
                                :isDisabled="item.status === 'Monitored'"
                                type="lock"
                                @click="selectedTool = item.id; toggleLockConfirmation(); lockAction = 'lock'; title = 'Lock Tool'"
                            />
                            <action-button
                                v-show="canEvaluate"
                                :isDisabled="item.status === 'Monitored' || item.status === 'In progress'"
                                type="monitored"
                                @click="selectedTool = item.id; toggleMonitoredModal();"
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
        title="Filters"
        width="md"
    >
        <div class="flex flex-col space-y-4">
            <filter-form-status v-model="query.status"/>
            <filter-academic-year v-model="query.academicyear"/>
        </div>

        <template #custom-button>
            <modal-button text="Apply" color="green" @click="filter"/>
        </template>
    </modal>

    <confirmation
        :showModal="lockModal"
        @close="toggleLockConfirmation"
        :title="title"
        width="md"
    >
        <template #message>
            <div v-if="lockAction == 'lock'">
                Are you sure you want to lock this library compliance evaluation tool? This action will prevent any further inputs from HEI.
            </div>
            <div v-else>
                Are you sure you want to unlock this library compliance evaluation tool? This action will enable inputs from HEI.
            </div>
        </template>
        <template #buttons>
            <modal-button v-if="lockAction == 'lock'" text="Lock" color="red" @click="lock"/>
            <modal-button v-else text="Unlock" color="blue" @click="unlock"/>
        </template>
    </confirmation>

    <confirmation :showModal="monitoredModal" @close="toggleMonitoredModal" title="Mark as Monitored"  width="md">
        <template v-slot:message>
            Are you sure you want to mark this library compliance evaluation tool as monitored? Once marked as monitored, it cannot be edited.
        </template>
        <template v-slot:buttons>
            <modal-button text="Confirm" color="blue" @click="monitored" />
        </template>
    </confirmation>
</template>

<script setup>
// -------------------------------------------------------------------------
import { useForm, router } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import Layout from "@/Shared/Layout.vue";
import PageTitle from "@/Shared/PageTitle.vue";
defineOptions({ layout: Layout });

// -------------------------------------------------------------------------
const props = defineProps([
    "evaluation_list",
    "filters",
    'canEvaluate',
]);

// -------------------------------------------------------------------------
const showFilterModal = ref(false);
const monitoredModal = ref(false);
const lockModal = ref(false);
const selectedTool = ref(null);
const lockAction = ref('');
const title = ref('');

const query = useForm({
    academicyear: props.filters.academicYear,
    show: props.filters.show != null ? props.filters.show : null,
    search: props.filters.search,
    status: props.filters.status != null ? props.filters.status : null,
    sort: props.filters.sort != null ? props.filters.sort: null,
    direction: props.filters.direction != null ? props.filters.direction : 'asc',
});

// -------------------------------------------------------------------------

function toggleFilterModal() {
    showFilterModal.value = !showFilterModal.value;
}

function toggleLockConfirmation() {
    lockModal.value = !lockModal.value;
}

function toggleMonitoredModal() {
    monitoredModal.value = !monitoredModal.value;
}

function filter() {
    query.get("/ched/library/evaluation", {
        preserveState: false,
        preserveScroll: true,
        replace: true,
    });
}

function evaluate(tool) {
    router.get('/ched/library/evaluation/' + tool + '/evaluate', {
        preserveScroll: true,
        preserveState: true,
    });
}

function view(tool) {
    router.get('/ched/library/evaluation/' + tool + '/view');
}

function lock() {
    router.post('/ched/library/evaluation/lock', { id: selectedTool.value }, {
        replace: true,
        preserveScroll: true,
        preserveState: false,
    })
}

function unlock() {
    router.post('/ched/library/evaluation/unlock', { id: selectedTool.value }, {
        replace: true,
        preserveScroll: true,
        preserveState: false,
    })
}

function monitored() {
    router.post('/ched/library/evaluation/monitored', { id: selectedTool.value }, {
        replace: true,
        preserveScroll: true,
        preserveState: false,
    });
}
</script>
