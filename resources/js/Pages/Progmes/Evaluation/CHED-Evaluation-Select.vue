<template>

    <Head title="Program Evaluation" />
    <page-title title="Program Evaluation" />
    <content-container hasSearch="true" pageTitle="Evaluation" :hasNavigation="true" page="evaluation" :hasTopButton="true" :hasFilters="true" :data_list="complianceTools">
        <template v-slot:top-button>
            <select @change="submit" v-model="query.academicYear" id="academicYearSelect" class="h-10 border-gray-400 hover:border-gray-500 cursor-pointer rounded text-sm">
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
            <button class="select-none h-12 w-28 hover:bg-gray-100 text-blue-500 border-b-4 relative font-bold border-blue-500">
                Evaluation
            </button>
            <Link :href="route('ched.evaluation.monitored')">
            <button class="select-none h-12 w-28 hover:bg-gray-100 text-gray-700 hover:text-black">
                Monitored
            </button>
            </Link>
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
                <Link href="/evaluation">
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
                    <th class="p-2 pl-5">Program/Institution</th>
                    <th class="p-2">Status</th>
                    <th class="p-2">Progress</th>
                    <th class="p-2 pr-5 text-right">
                        <i class="fas fa-ellipsis-v"></i>
                    </th>
                </template>

                <template v-slot:table-body>
                    <tr v-if="complianceTools.data.length == 0">
                        <td colspan="3" class="p-2 py-10 text-center">No evaluation tool found</td>
                    </tr>
                    <tr v-else v-for="(item, index) in complianceTools.data" :key="item.id" class="hover:bg-gray-200"
                        :class="{'bg-gray-100': index % 2 == 1}">
                        <td class="p-2 pl-5">
                            <div class="font-bold">
                                {{ item.program }}
                            </div>
                            <div>
                                {{ item.institution }}
                            </div>
                        </td>
                        <td class="p-2">
                            <div v-if="
                                    item.status ==
                                    'Locked'
                                " class="bg-red-500 text-white w-fit px-1 py-0.5 rounded text-xs whitespace-nowrap">
                                Locked
                            </div>
                            <div v-if="
                                    item.status ==
                                    'In progress'
                                " class="bg-blue-500 text-white w-fit px-1 py-0.5 rounded text-xs whitespace-nowrap">
                                In Progress
                            </div>
                            <div v-if="item.status == 'Submitted'"
                                class="bg-emerald-500 text-white w-fit px-1 py-0.5 rounded text-xs whitespace-nowrap">
                                Ready for visit
                            </div>
                            <div v-if="item.status == 'For re-evaluation'"
                                class="bg-emerald-500 text-white w-fit px-1 py-0.5 rounded text-xs whitespace-nowrap">
                                For re-evaluation
                            </div>
                        </td>
                        <td class="p-2">
                            <div class="font-bold text-sm px-1 py-0.5 rounded w-fit">
                                {{ item.progress }}%
                            </div>
                        </td>
                        <td class="p-2 pr-5 text-right whitespace-nowrap">
                            <button @click="view(item.id)"
                                class="select-none h-8 w-8 text-center text-xl hover:bg-gray-300 rounded-full text-green-500 hover:text-green-600 tooltipForActions"
                                data-tooltip="View">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button v-show="canEvaluate"
                             @click="evaluate(item.id)" 
                                class="select-none h-8 w-8 text-center text-xl hover:bg-gray-300 rounded-full text-blue-500 hover:text-blue-600 tooltipForActions"
                                data-tooltip="Evaluate">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button v-show="canEvaluate" v-if="item.isLocked" 
                             @click="setId(item.id); unlockModal = true;"
                                class="select-none h-8 w-8 text-center text-xl hover:bg-gray-300 rounded-full text-gray-500 hover:text-gray-600 tooltipForActions"
                                data-tooltip="Unlock">
                                <i class="fas fa-unlock"></i>
                            </button>
                            <button v-show="canEvaluate" v-else
                             @click="setId(item.id); lockModal = true;"
                                class="select-none h-8 w-8 text-center text-xl hover:bg-gray-300 rounded-full text-red-500 hover:text-red-600 tooltipForActions"
                                data-tooltip="Lock">
                                <i class="fas fa-lock"></i>
                            </button>
                            <button v-show="canEmail"
                             @click="setProgress(item.progress);  toggleEmailModal(); email.toolId = item.id;"
                                class="select-none h-8 w-8 text-center text-xl hover:bg-gray-300 rounded-full text-orange-500 hover:text-orange-600 tooltipForActions"
                                data-tooltip="Send Email">
                                <i class="fas fa-envelope"></i>
                            </button>
                            <button v-show="canEvaluate"
                             @click="setId(item.id); monitoredModal = true;"
                                class="select-none h-8 w-8 text-center text-xl hover:bg-gray-300 rounded-full text-blue-500 hover:text-blue-600 tooltipForActions"
                                data-tooltip="Mark as monitored">
                                <i class="fas fa-check"></i>
                            </button>
                        </td>
                    </tr>
                </template>
            </content-table>
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
                class="select-none text-white h-10 w-20 rounded bg-red-500">
                <span v-if="locking"><i class="fas fa-spinner animate-spin"></i></span>
                <span v-else>Lock</span>
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

    <Notification :message="$page.props.flash.success" type="success" />
    <Notification :message="$page.props.flash.failed" type="failed"/>
</template>

<script setup>
import { router, useForm } from '@inertiajs/vue3';
import { reactive, ref, computed } from 'vue';

const props = defineProps([
    'complianceTools',
    'filters',
    'canEvaluate',
    'canEmail',
    'programHeadEmail',
]);

const toolId = ref(null);
const lockModal = ref(false);
const locking = ref(false);

const unlockModal = ref(false);
const unlocking = ref(false);

const monitoredModal = ref(false);
const markingAsMonitored = ref(false); 

const showFilterModal = ref(false);
const processing = ref(false);


const query = useForm({
    show: props.filters.show != null ? props.filters.show : null,
    search: props.filters.search,
    academicYear: props.filters.academicYear != null ? props.filters.academicYear : null,
    status: props.filters.status != null ? props.filters.status : null,
});

function toggleFilterModal() {
    showFilterModal.value = !showFilterModal.value;
}

function submit() {
    query.get("/ched/evaluation", {
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
    query.get("/ched/evaluation", {
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


const emailModal = ref(false);
const sending = ref(false);
const prog = ref(0);

function toggleEmailModal() {
    emailModal.value = !emailModal.value;
}

function setProgress(val) {
    prog.value = val;
}


const email = reactive({
    toolId: null,
    body: null,
    recipient: ref(props.programHeadEmail),
});

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

<script>
    import Layout from '../Shared/Layout.vue';

    export default {
        layout: Layout,
    }
</script>