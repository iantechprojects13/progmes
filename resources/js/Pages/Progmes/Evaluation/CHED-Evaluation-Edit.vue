<template>

    <Head title="Evaluation Form" />
    <page-title title="Program Evaluation" />

    <div class="md:mx-8 mx-3 mt-8 flex flex-row justify-between rounded relative">
        <div>
            <Link href="/evaluation">
            <button class="select-none w-24 h-10 border border-gray-500 rounded bg-white">
                <i class="fas fa-arrow-left mr-2"></i>
                Back
            </button>
            </Link>
        </div>
        <div>
            <Link :href="'/ched/evaluation/' + evaluation.id + '/deficiency-report'">
                <button class="select-none px-2 rounded bg-white border border-gray-500 hover:bg-gray-700 hover:text-white h-10">Deficiency Report</button>
            </Link>
        </div>
    </div>
    
    <div class="flex flex-col xl:flex-row items-center justify-between w-auto rounded border border-gray-400 bg-white mx-3 md:mx-8 mt-5 p-3">
        <div class="w-full text-left">
            <div class="w-auto flex flex-row items-center">
                <i class="fas fa-institution mr-3 text-blue-500"></i>
                <div>{{ evaluation.institution_program.institution.name }}</div>
            </div>
            <div class="w-auto flex flex-row items-center">
                <i class="fas fa-book mr-3 text-blue-500"></i>
                <div>
                    {{ evaluation.institution_program.program.program }}
                    <span></span>
                </div>
            </div>
            <div class="w-auto flex flex-row items-center">
                <i class="fas fa-calendar-check mr-3 text-blue-500"></i>
                <div>A.Y. {{ evaluation.effectivity }}</div>
            </div>
        </div>
        <!-- <div class="xl:w-auto w-full text-right mt-5 xl:mt-0">
            <div class="flex flex-col md:flex-row lg:mt-0 md:w-auto w-full">
                <div>Evaluation Progress</div>
                <div>
                    <compliance-progress>
                        
                    </compliance-progress>
                </div>
            </div>
        </div> -->
    </div>

    <content-container :hasTopButton="true">
        <template v-slot:top-button>
            <div class="flex">
                <button @click="update" ref="saveBtn"
                    class="select-none cursor-pointer bg-blue-500 text-white px-2 w-fit h-10 rounded">Save
                    Changes
                </button>
                <div class="flex flex-row justify-between ml-2">
                    <div class="text-gray-500 h-10 lg:ml-1 flex items-center mr-5">
                        <div v-if="hasUpdate" class="text-red-500">
                            *Changes unsaved.
                        </div>
                        <div v-else-if="$page.props.flash.updated">
                            <i class="fas fa-check-circle text-green-500 mr-1"></i>
                            {{ $page.props.flash.updated }}
                        </div>
                        <div v-else></div>
                    </div>
                </div>
            </div>
        </template>
        <template v-slot:main-content>
            <content-table>
                <template v-slot:table-head>
                    <th class="p-3 align-bottom whitespace-normal">Area/<br>Minimum Requirement</th>
                    <th class="p-3 align-bottom whitespace-normal">Self-Evaluation Status/<br>Actual
                        Situation
                    </th>
                    <th class="p-3 align-bottom">Evidence</th>
                    <th class="p-3 align-bottom whitespace-normal">Findings</th>
                    <th class="p-3 align-bottom whitespace-normal">Comments /<br>Recommendations</th>
                    <th class="p-3 align-bottom whitespace-normal">Evaluation Status</th>
                </template>

                <template v-slot:table-body>
                    <tr v-for="(item, index) in items" :key="item.id" class="align-top text-base"
                        :class="{ 'bg-slate-200': index % 2 == 0 }">
                        <td class="p-3 min-w-12 whitespace-normal text-justify">
                            <div class="flex flex-col">
                                <div class="font-bold">
                                    {{ item.area }}
                                </div>
                                <div>
                                    {{ item.minimumRequirement }}
                                </div>
                            </div>
                        </td>
                        <td class="p-3 min-w-12 align-top whitespace-normal text-justify">
                            <div class="bg-gray-600 text-white w-fit rounded px-2 mb-2"
                                :class="[{ 'bg-green-600': item.selfEvaluationStatus == 'Complied' }]">
                                {{ item.selfEvaluationStatus }}
                            </div>
                            <div>
                                {{ item.actualSituation }}
                            </div>
                        </td>
                        <td class="h-auto min-w-8 p-3 flex flex-col justify-center">
                            <div class="flex flex-col"
                                :class="{ 'hidden': item.selfEvaluationStatus == 'Not applicable' }"
                                ref="evidenceFiles">
                                <div v-for="file in item.evidence" :key="file.id" class="w-full">
                                    <a class="px-1 text-gray-600 hover:text-blue-500 m-1 rounded" :href="file.url"
                                        target="_blank">
                                        <span v-if="file.type == 'file'">
                                            File
                                        </span>
                                        <span v-else-if="file.type == 'link'">
                                            Link
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </td>
                        <td class="h-32 min-w-16 p-3 relative group">
                            <textarea ref="findingsInput" v-model="item.findings" :name="'findings' + index"
                                :id="'findings' + index"
                                class="w-full min-w-md h-32 rounded border-gray-500 resize-none group custom-scrollbar"
                                placeholder="Input findings here" @input="handleFindingsInput(index, item.id)">
                                            </textarea>
                            <button @click="openFindingsEditor(index)"
                                class="absolute bottom-4 right-10 text-gray-500 hover:text-black group-focus-within:visible invisible tooltipForActions"
                                data-tooltip="Open editor">
                                <i class="fas fa-pen"></i>
                            </button>
                            <text-editor title="Findings" :showModal="isFindingsEditorOpen && textEditorIndex == index"
                                @close="closeFindingsEditor">
                                <textarea ref="findingstexteditor" v-model="item.findings"
                                    :name="'findingsEditor' + index" :id="'findingsEditor' + index"
                                    class="w-full h-32 rounded border-gray-500 resize-none group"
                                    placeholder="Input findings here" @focus="handleFindingsEditorInput(index)"
                                    @input="handleFindingsEditorInput(index, item.id)">
                                                </textarea>
                            </text-editor>
                        </td>
                        <td class="h-32 min-w-16 p-3 relative group">
                            <textarea ref="recommendationsInput" v-model="item.recommendations"
                                :name="'recommendations' + index" :id="'recommendations' + index"
                                class="w-full h-32 rounded border-gray-500 resize-none group custom-scrollbar"
                                placeholder="Input comments here" @input="handleRecommendationsInput(index, item.id)">
                                            </textarea>
                            <button @click="openRecommendationsEditor(index)"
                                class="absolute bottom-4 right-10 text-gray-500 hover:text-black group-focus-within:visible invisible tooltipForActions"
                                data-tooltip="Open editor">
                                <i class="fas fa-pen"></i>
                            </button>
                            <text-editor title="Comments/Recommendations"
                                :showModal="isRecommendationsEditorOpen && textEditorIndex == index"
                                @close="closeRecommendationsEditor">
                                <textarea ref="recommendationstexteditor" v-model="item.findings"
                                    :name="'recommendationsEditor' + index" :id="'recommendationsEditor' + index"
                                    class="w-full h-32 rounded border-gray-500 resize-none group"
                                    placeholder="Input findings here" @focus="handleRecommendationEditorInput(index)"
                                    @input="handleRecommendationEditorInput(index, item.id)">
                                                </textarea>
                            </text-editor>
                        </td>
                        <td class="h-32 p-3 min-w-8">
                            <div class="w-full h-full">
                                <select ref="evaluationStatus" v-model="item.evaluationStatus"
                                    :id="'evaluationStatus' + index" :name="'evaluationStatus' + index"
                                    @change="handleChangeStatus(item.id)" class="p-1 rounded w-36 border-gray-400">
                                    <option :value="null">Select status</option>
                                    <option value="Complied">Complied</option>
                                    <option value="Not complied">Not complied</option>
                                    <option value="Not applicable">Not applicable</option>
                                </select>
                            </div>
                        </td>
                    </tr>
                </template>
            </content-table>
        </template>
    </content-container>
    <Notification :message="$page.props.flash.success" type="success" />
    <Notification :message="$page.props.flash.updated" type="success" />
    <Notification :message="$page.props.flash.deleted" type="success" />
    <Notification :message="$page.props.errors.link" type="failed" />
    <Notification v-show="saving" message="Saving..." type="processing" />
</template>

<script setup>
    // Imports
    import { ref, onMounted, onUnmounted, computed, reactive } from 'vue';
    import { router } from '@inertiajs/vue3';

    // Props
    const props = defineProps([
        'evaluation',
        'items',
        'deficiencyReport',
        'visitDate',
        'evaluatedBy',
        'reviewedBy',
        'notedBy',
        'auth',
    ]);

    // const deficiencyReport = reactive({
    //     id: ref(props.evaluation.id),
    //     visitDate: ref(props.visitDate),
    //     evaluatedBy: ref(props.evaluatedBy),
    //     reviewedBy: ref(props.reviewedBy),
    //     notedBy: ref(props.notedBy),
    // });

    const handleBeforeUnload = (event) => {
        if (hasUpdate.value) {
            event.returnValue = true;
        }
    };


    // Mount, Unmount
    onMounted(() => {
        window.addEventListener('keydown', handleKeyDown);
        window.addEventListener('beforeunload', handleBeforeUnload);
    });

    onUnmounted(() => {
        window.removeEventListener('keydown', handleKeyDown);
        window.removeEventListener('beforeunload', handleBeforeUnload);
    });


    // Variables
    const isFindingsEditorOpen = ref(false);
    const isRecommendationsEditorOpen = ref(false);
    const findingstexteditor = ref([]);
    const recommendationstexteditor = ref([]);
    const textEditorIndex = ref('');
    const reportModal = ref(false);

    const findingsInput = ref([]);
    const recommendationsInput = ref([]);
    const evaluationStatus = ref([]);
    const actionButtons = ref([]);
    const evidenceFiles = ref([]);
    const hasUpdate = ref(false);

    const saving = ref(false);
    // const generatingReport = ref(false);
    const saveBtn = ref(null);
    const updatedRows = ref([]);


    const refs = { findingsInput, evaluationStatus, actionButtons, evidenceFiles, saveBtn, findingstexteditor, recommendationstexteditor };

    // Functions
    function handleFindingsInput(index, id) {
        let textarea = findingsInput.value[index];
        textarea.style.height = "128px";
        let newHeight = textarea.scrollHeight + "px";
        let maxHeight = 384;
        textarea.style.height = Math.min(maxHeight, Math.max(128, parseInt(newHeight))) + "px";
        hasUpdate.value = true;
        getRowsWithUpdates(id);
    }

    function handleRecommendationsInput(index, id) {
        let textarea = recommendationsInput.value[index];
        textarea.style.height = "128px";
        let newHeight = textarea.scrollHeight + "px";
        let maxHeight = 384;
        textarea.style.height = Math.min(maxHeight, Math.max(128, parseInt(newHeight))) + "px";
        hasUpdate.value = true;
        getRowsWithUpdates(id);
    }

    function handleFindingsEditorInput(index, id) {
        let textEditor = refs.findingstexteditor.value[index];
        textEditor.style.height = "128px";
        let newHeight = textEditor.scrollHeight + "px";
        let maxHeight = 384;
        textEditor.style.height = Math.min(maxHeight, Math.max(128, parseInt(newHeight))) + "px";
        hasUpdate.value = true;
        getRowsWithUpdates(id);
    }

    function handleRecommendationEditorInput(index, id) {
        let textEditor = refs.recommendationstexteditor.value[index];
        textEditor.style.height = "128px";
        let newHeight = textEditor.scrollHeight + "px";
        let maxHeight = 384;
        textEditor.style.height = Math.min(maxHeight, Math.max(128, parseInt(newHeight))) + "px";
        hasUpdate.value = true;
        getRowsWithUpdates(id);
    }

    function getRowsWithUpdates(id) {
        if (!updatedRows.value.includes(id)) {
            updatedRows.value.push(id);
        }
    }

    function handleChangeStatus(id) {
        hasUpdate.value = true;
        getRowsWithUpdates(id);
    };



    function openFindingsEditor(index) {
        isFindingsEditorOpen.value = true;
        textEditorIndex.value = index;
    }

    function closeFindingsEditor() {
        isFindingsEditorOpen.value = false;
    }

    function openRecommendationsEditor(index) {
        isRecommendationsEditorOpen.value = true;
        textEditorIndex.value = index;
    }

    function closeRecommendationsEditor() {
        isRecommendationsEditorOpen.value = false;
    }

function closeReportModal() {
    reportModal.value = false;
    }

    // CTRL + S function
    const handleKeyDown = (event) => {
        if (event.ctrlKey || event.metaKey) {
            if (event.key === 's' || event.key === 'S') {
                event.preventDefault();
                refs.saveBtn.value.click();
            }
        }
    };


    function update() {
        router.post('/ched/evaluation/update', {
            id: props.evaluation.id,
            items: props.items,
            rows: updatedRows.value,
        }, {
            onStart: () => {
                saving.value = true;
            },
            onFinish: () => {
                saving.value = false;
            },
            preserveState: false,
            preserveScroll: true,
        });
}


// function generate() {
//     router.post('/report/deficiency/generate', deficiencyReport, {
//         onStart: () => {
//             generatingReport.value = true;
//         },
//         onFinish: () => {
//             generatingReport.value = false;
//         },
//         preserveScroll: true,
//         preserveState: true,
//     });
// }

</script>

<script>
    import Layout from '../Shared/Layout.vue'
    export default {
        layout: Layout,
        data() {
            return {
                itemsLayout: 'list',
            }
        },
        methods: {
            listLayout() {
                this.itemsLayout = 'list';
            },
            gridLayout() {
                this.itemsLayout = 'grid';
            },
        },

    }
</script>