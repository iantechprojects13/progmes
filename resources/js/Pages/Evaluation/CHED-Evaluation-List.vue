<template>

    <Head title="Program Evaluation" />
    <page-title title="Program Evaluation" />
    <content-container hasSearch="true" pageTitle="Program Monitoring / Evaluation" :hasNavigation="true" page="evaluation" :hasTopButton="true" :hasFilters="true" :data_list="complianceTools">
        <template v-slot:top-button>
            <select
                    v-model="query.academicYear"
                    @change.prevent="filter"
                    class="px-4 pr-10 py-2.5 text-sm bg-white border border-gray-300 text-slate-700 rounded-lg hover:border-gray-400 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 focus:outline-none duration-200 cursor-pointer appearance-none whitespace-nowrap"
                >
                    <option value="2021-2022">A.Y. 2021-22</option>
                    <option value="2022-2023">A.Y. 2022-23</option>
                    <option value="2023-2024">A.Y. 2023-24</option>
                    <option value="2024-2025">A.Y. 2024-25</option>
                    <option value="2025-2026">A.Y. 2025-26</option>
                    <option value="2026-2027">A.Y. 2026-27</option>
                    <option value="2027-2028">A.Y. 2027-28</option>
                    <option value="2028-2029">A.Y. 2028-29</option>
                    <option value="2029-2030">A.Y. 2029-30</option>
                </select>
        </template>
        <template v-slot:navigation>
            <main-content-nav page="evaluation" managementType="evaluation"></main-content-nav>
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
                            <button 
                                @click="view(item.id)"
                                class="inline-flex items-center justify-center rounded-full h-10 w-10 text-emerald-700 hover:bg-emerald-100 tooltipForActions"
                                data-tooltip="View">
                                <i class="fas fa-eye text-lg"></i>
                            </button>

                            <button 
                                v-show="canEvaluate"
                                @click="evaluate(item.id)"
                                :disabled="item.status === 'In progress'"
                                :class="{'text-gray-400 hover:bg-gray-100': item.status === 'In progress', 'text-blue-700 hover:bg-blue-100': item.status !== 'In progress'}"
                                class="inline-flex items-center justify-center rounded-full h-10 w-10 tooltipForActions disabled:cursor-not-allowed"
                                data-tooltip="Evaluate">
                                <i class="fas fa-edit text-lg"></i>
                            </button>

                            <button 
                                v-show="canEvaluate" 
                                v-if="item.isLocked"
                                @click="setId(item.id); unlockModal = true;"
                                class="inline-flex items-center justify-center rounded-full h-10 w-10 text-gray-700 hover:bg-gray-100 tooltipForActions"
                                data-tooltip="Unlock">
                                <i class="fas fa-unlock text-lg"></i>
                            </button>

                            <button 
                                v-show="canEvaluate" 
                                v-else
                                @click="setId(item.id); lockModal = true;"
                                class="inline-flex items-center justify-center rounded-full h-10 w-10 text-red-700 hover:bg-red-100 tooltipForActions"
                                data-tooltip="Lock">
                                <i class="fas fa-lock text-lg"></i>
                            </button>

                            <!-- <button 
                                v-show="canEmail"
                                @click="setProgress(item.progress); toggleEmailModal(); email.toolId = item.id;"
                                class="inline-flex items-center justify-center rounded-full h-10 w-10 text-orange-700 hover:bg-orange-100 tooltipForActions"
                                data-tooltip="Send Email">
                                <i class="fas fa-envelope text-lg"></i>
                            </button> -->

                            <button 
                                v-show="canEvaluate"
                                @click="setId(item.id); monitoredModal = true;"
                                :disabled="item.status === 'In progress'"
                                :class="{'text-gray-400 hover:bg-gray-100': item.status === 'In progress', 'text-blue-700 hover:bg-blue-100': item.status !== 'In progress'}"
                                class="inline-flex items-center justify-center rounded-full h-10 w-10 tooltipForActions disabled:cursor-not-allowed"
                                data-tooltip="Mark as monitored">
                                <i class="fas fa-check text-lg"></i>
                            </button>
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
        class="antialiased"
    >
        <div>
            <!-- Active Status Filter -->
            <div class="flex flex-col space-y-2 mb-6">
                <label for="isActive" class="text-sm font-medium text-gray-700"
                    >Status</label
                >
                <select
                    v-model="query.status"
                    id="isActive"
                    class="block w-full px-3 py-2 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
                    <option :value="null">All</option>
                    <option value="In progress">In Progress</option>
                    <option value="Submitted">Completed</option>
                    <option value="For Re-evaluation">For Re-evaluation</option>
                </select>
            </div>

            <div class="flex flex-col space-y-2 mb-6">
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
                Apply
            </button>
        </template>
    </modal>

    <modal :showModal="emailModal" title="Compose Email" @close="toggleEmailModal" width="xl" height="long">
        <div>
            <label for="emailBody" class="font-bold mb-2">Message</label>
            <textarea class="w-full h-52 resize-none" placeholder="Compose email here" id="emailBody"
                v-model="email.body">
            </textarea>
            <div class="mt-3">
                <label for="emailBody" class="font-bold mb-2">Recipient</label>
                <input type="text" v-model="email.recipient" class="h-10 w-full">
            </div>
        </div>
        <template v-slot:custom-button>
            <button @click="sendEmail" :disabled="sending || email.body == ''"
                class="text-white bg-blue-600 hover:bg-blue-700 w-24 py-2 px-3 rounded border"
                :class="[{'bg-gray-600 hover:bg-gray-700': email.body == ''}]"
                >
                <span v-if="sending"><i class="fas fa-spinner animate-spin"></i></span>
                <span v-else>Send</span>
            </button>
        </template>
    </modal>

    <confirmation :showModal="lockModal" @close="lockModal = false" title="Lock Evaluation Tool" width="md" height="short">
        <template v-slot:message>
            Are you sure you want to lock this compliance evaluation tool? This action will prevent any further inputs from HEI.
        </template>
        <template v-slot:buttons>
            <button @click="lock" :disabled="locking"
                class="select-none text-white h-10 w-20 rounded-lg text-sm bg-red-600 hover:bg-red-700">
                Lock
            </button>
        </template>
    </confirmation>

    <confirmation :showModal="unlockModal" @close="unlockModal = false" title="Unlock Evaluation Tool" width="md" height="short">
        <template v-slot:message>
            Are you sure you want to unlock this compliance evaluation tool? This action will enable inputs from HEI.
        </template>
        <template v-slot:buttons>
            <button @click="unlock" :disabled="unlocking"
                class="select-none text-white h-10 w-20 rounded bg-blue-500">
                <span v-if="unlocking"><i class="fas fa-spinner animate-spin"></i></span>
                <span v-else>Unlock</span>
            </button>
        </template>
    </confirmation>

    <confirmation :showModal="monitoredModal" @close="monitoredModal = false" title="Mark as Monitored"  width="md" height="short">
        <template v-slot:message>
            Are you sure you want to mark this compliance evaluation tool as monitored? Once marked as monitored, it cannot be edited.
        </template>
        <template v-slot:buttons>
            <button @click="monitored" :disabled="markingAsMonitored"
                class="select-none text-white h-10 w-20 rounded bg-blue-500">
                <span v-if="markingAsMonitored"><i class="fas fa-spinner animate-spin"></i></span>
                <span v-else>Confirm</span>
            </button>
        </template>
    </confirmation>
</template>

<script setup>
// ------------------------------------------------------------
import { reactive, ref, computed } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import Layout from "@/Shared/Layout.vue";
defineOptions({ layout: Layout });

// ------------------------------------------------------------
const props = defineProps([
    'complianceTools',
    'filters',
    'canEvaluate',
    'canEmail',
    'programHeadEmail',
]);

// ------------------------------------------------------------
const emailModal = ref(false);
const sending = ref(false);
const prog = ref(0);

const toolId = ref(null);
const lockModal = ref(false);
const locking = ref(false);

const unlockModal = ref(false);
const unlocking = ref(false);

const monitoredModal = ref(false);
const markingAsMonitored = ref(false);
const sort = ref(null);

const showFilterModal = ref(false);
const processing = ref(false);


const email = reactive({
    toolId: null,
    body: null,
    recipient: ref(props.programHeadEmail),
});

const query = useForm({
    show: props.filters.show != null ? props.filters.show : null,
    search: props.filters.search,
    academicYear: props.filters.academicYear != null ? props.filters.academicYear : null,
    status: props.filters.status != null ? props.filters.status : null,
    sort: props.filters.sort != null ? props.filters.sort: null,
    direction: props.filters.direction != null ? props.filters.direction : 'asc',
});

// ------------------------------------------------------------


function toggleFilterModal() {
    showFilterModal.value = !showFilterModal.value;
}

function filter() {
    query.get("/ched/evaluation", {
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

function toggleEmailModal() {
    emailModal.value = !emailModal.value;
}

function sendEmail() {
    router.post('/evaluation/notify', email, {
        onStart: () => {
            sending.value = true;
        },
        onFinish: () => {
            sending.value = false;
        },
        preserveState: false,
        preserveScroll: true,
    })
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
        onStart: () => {
            markingAsMonitored.value = true;
        },
        onFinish: () => {
            markingAsMonitored.value = false;
        },
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