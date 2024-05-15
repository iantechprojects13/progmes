<template>

    <Head title="Program Self-Evaluation" />
    <page-title title="Program Evaluation" />
    <content-container hasSearch="true" pageTitle="Monitoring / Evaluation" :hasNavigation="true" page="evaluation" :hasTopButton="true" :hasFilters="true" :data_list="complianceTools">
        <template v-slot:navigation>
            <div>
                <button class="select-none text-blue-500 h-10 mr-8 border-b-2 font-bold border-blue-500">
                    Evaluation
                </button>
                <Link :href="route('ched.evaluation.archive')">
                <button class="select-none text-gray-500 hover:text-black">
                    Archived
                </button>
                </Link>
            </div>
        </template>
        <template v-slot:search>
            <div class="w-full flex justify-end relative">
                <input @keydown.enter="submit" v-model="query.search" type="search" id="content-search"
                    placeholder="Search"
                    class="w-full rounded border border-gray-400 bg-slate-100 h-10 text-base placeholder-gray-400 pr-11 mr-2" />
                <button @click="submit"
                    class="text-gray-700 hover:text-black active:text-blue-500 h-10 w-10 rounded absolute right-2">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </template>
        <template v-slot:top-button>
            <select @change="submit" v-model="query.academicYear" id="academicYearSelect" class="h-10 rounded text-sm">
                <option value="2023-2024">A.Y. 2023-24</option>
                <option value="2024-2025">A.Y. 2024-25</option>
                <option value="2025-2026">A.Y. 2025-26</option>
                <option value="2026-2027">A.Y. 2026-27</option>
                <option value="2027-2028">A.Y. 2027-28</option>
                <option value="2028-2029">A.Y. 2028-29</option>
                <option value="2029-2030">A.Y. 2029-30</option>
            </select>
        </template>
        <template v-slot:options>
            <div class="mr-1">
                <Link href="/evaluation">
                <button
                    class="w-10 h-10 whitespace-nowrap rounded-full text-gray-700 hover:text-blue-500 active:text-white active:bg-blue-600 tooltipForActions"
                    data-tooltip="Refresh page">
                    <i class="fas fa-refresh"></i>
                </button>
                </Link>
            </div>
        </template>
        <template v-slot:main-content>
            <content-table>
                <template v-slot:table-head>
                    <th class="p-3 pl-5 border-b border-gray-400">Program/Institution</th>
                    <th class="p-3 border-b border-gray-400">Status</th>
                    <th class="p-3 border-b border-gray-400">Progress</th>
                    <th class="p-3 border-b border-gray-400 text-right">
                        <i class="fas fa-ellipsis-v"></i>
                    </th>
                </template>

                <template v-slot:table-body>
                    <tr v-if="complianceTools.data.length == 0">
                        <td colspan="3" class="p-3 py-10 text-center">No evaluation tool found</td>
                    </tr>
                    <tr v-else v-for="(item, index) in complianceTools.data" :key="item.id" class="hover:bg-slate-300"
                        :class="{'bg-slate-200': index % 2 ==0}">
                        <td class="p-3 pl-5">
                            <div>
                                {{ item.program }}
                            </div>
                            <div class="font-bold">
                                {{ item.institution }}
                            </div>
                        </td>
                        <td class="p-3">
                            <div v-if="
                                    item.status ==
                                    'Locked'
                                " class="bg-red-500 text-white w-fit px-1 py-0.5 rounded text-xs">
                                Locked
                            </div>
                            <div v-if="
                                    item.status ==
                                    'In progress'
                                " class="bg-blue-500 text-white w-fit px-1 py-0.5 rounded text-xs">
                                In Progress
                            </div>
                            <div v-if="item.status == 'Submitted'"
                                class="bg-emerald-500 text-white w-fit px-1 py-0.5 rounded text-xs">
                                Ready for visit
                            </div>
                        </td>
                        <td class="p-3">
                            <div class="font-bold text-sm px-1 py-0.5 rounded w-fit">
                                {{ item.progress }}%
                            </div>
                        </td>
                        <td class="p-3 text-right">
                            <button @click="view(item.id)"
                                class="select-none w-10 h-10 bg-green-600 hover:bg-green-700 text-white rounded mr-1 tooltipForActions"
                                data-tooltip="View">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button v-show="canEvaluate"
                             @click="evaluate(item.id)" 
                                class="select-none w-10 h-10 text-white bg-blue-500 hover:bg-blue-600 rounded mr-1 tooltipForActions"
                                data-tooltip="Evaluate">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button v-show="canEvaluate" v-if="item.isLocked" 
                             @click="setId(item.id); unlockModal = true;"
                                class="select-none w-10 h-10 text-white bg-red-500 hover:bg-red-600 rounded mr-1 tooltipForActions"
                                data-tooltip="Unlock">
                                <i class="fas fa-unlock"></i>
                            </button>
                            <button v-show="canEvaluate" v-else
                             @click="setId(item.id); lockModal = true;"
                                class="select-none w-10 h-10 text-white bg-red-500 hover:bg-red-600 rounded mr-1 tooltipForActions"
                                data-tooltip="Lock">
                                <i class="fas fa-lock"></i>
                            </button>
                            <button v-show="canEmail"
                             @click="setProgress(item.progress);  toggleEmailModal(); email.toolId = item.id;"
                                class="select-none w-10 h-10 text-white bg-orange-500 hover:bg-orange-600 rounded mr-1 tooltipForActions"
                                data-tooltip="Send Email">
                                <i class="fas fa-envelope"></i>
                            </button>
                            <button v-show="canEvaluate"
                             @click="setId(item.id); archiveModal = true;"
                                class="select-none w-10 h-10 text-white bg-gray-500 hover:bg-gray-600 rounded tooltipForActions"
                                data-tooltip="Archive">
                                <i class="fas fa-archive"></i>
                            </button>
                        </td>
                    </tr>
                </template>
            </content-table>
        </template>
        <template v-slot:show-item>
            <div class="mr-2">Items per page</div>
            <select v-model="query.show" id="showResultCount" @change="submit"
                class="select-none rounded h-8 w-20 p-1 text-sm">
                <option value="25">25</option>
                <option :value="50">50</option>
                <option :value="75">75</option>
                <option :value="100">100</option>
                <option :value="200">200</option>
            </select>
        </template>
    </content-container>

    <confirmation-modal :showModal="lockModal" @close="lockModal = false" title="Lock Evaluation Tool">
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
    </confirmation-modal>

    <confirmation-modal :showModal="unlockModal" @close="unlockModal = false" title="Unlock Evaluation Tool">
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
    </confirmation-modal>

    <confirmation-modal :showModal="archiveModal" @close="archiveModal = false" title="Archive Evaluation Tool">
        <template v-slot:message>
            Are you sure you want to archive this compliance evaluation tool?
        </template>
        <template v-slot:buttons>
            <button @click="archive" :disabled="archiving"
                class="select-none text-white h-10 w-20 rounded bg-blue-500">
                <span v-if="archiving"><i class="fas fa-spinner animate-spin"></i></span>
                <span v-else>Archive</span>
            </button>
        </template>
    </confirmation-modal>

    <modal :showModal="emailModal" title="Compose Email" @close="toggleEmailModal">
        <div>
            <label for="emailBody" class="font-bold mb-2">Message</label>
            <textarea class="w-full h-52 resize-none" placeholder="Compose email here" id="emailBody"
                v-model="email.body">
            </textarea>
        </div>
        <template v-slot:custom-button>
            <button @click="sendEmail" :disabled="sending"
                class="text-white bg-blue-600 hover:bg-blue-700 w-24 py-2 px-3 rounded border">
                <span v-if="sending"><i class="fas fa-spinner animate-spin"></i></span>
                <span v-else>Send</span>
            </button>
        </template>
    </modal>

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
    ]);
const toolId = ref(null);
const lockModal = ref(false);
const locking = ref(false);

const unlockModal = ref(false);
const unlocking = ref(false);

const archiveModal = ref(false);
const archiving = ref(false); 

    const query = useForm({
        academicYear: props.filters.academicYear,
        show: props.filters.show,
        search: props.filters.search,
    });

const emailModal = ref(false);
const sending = ref(false);
const prog = ref(0);

function toggleEmailModal() {
    emailModal.value = !emailModal.value;
}

function setProgress(val) {
    prog.value = val;
}

const str = computed(() => {
    return `We wanted to inform you of the current status of your program self-evaluation. As of now, your progress stands at ${prog.value}%. If you have any questions or need assistance, please feel free to contact us.`;
});

const email = reactive({
    toolId: null,
    body: str,
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
            preserveState: false,
        });
    }

    function view(tool) {
        router.get('/ched/evaluation/' + tool + '/view');
    }

    function submit() {
        query.get("/ched/evaluation", {
            preserveState: true,
            preserveScroll: true,
        });
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

    function archive(id) {
        router.post('/ched/evaluation/archive', { 'id': toolId.value }, {
            onStart: () => {
                archiving.value = true;
            },
            onFinish: () => {
                archiving.value = false;
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