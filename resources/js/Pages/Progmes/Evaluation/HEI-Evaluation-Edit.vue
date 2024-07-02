<template>

    <Head title="Program Self-Evaluation Tool" />
    <page-title title="Program Self-Evaluation" />
    <div class="md:mx-8 mx-3 mt-8 flex flex-row justify-between rounded relative">
        <div>
            <Link href="/evaluation">
            <button class="select-none w-24 h-10 border border-gray-500 rounded bg-white hover:bg-gray-700 hover:text-white">
                <i class="fas fa-arrow-left mr-2"></i>
                Back
            </button>
            </Link>
        </div>
        <div>
            <button @click="toggleResubmitModal" v-if="progress[3] == 100 && evaluation.evaluationDate != null"
                class="select-none px-3 w-fit text-white bg-blue-500 hover:bg-blue-600 h-10 rounded mr-1">
                Resubmit
            </button>
            <button @click="toggleReadyForVisitModal" v-else-if="progress[3] == 100 && evaluation.evaluationDate == null"
                class="select-none px-3 w-fit text-white bg-gray-700 hover:bg-gray-800 h-10 rounded mr-1">
                Ready for visit
            </button>
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
        <div class="xl:w-auto w-full text-right mt-5 xl:mt-0">
            <div class="flex flex-col md:flex-row lg:mt-0 md:w-auto w-full">
                <div class=" flex-col xl:mt-0 mt-2 text-center mx-1 p-2 px-3 md:w-fit w-full rounded border border-gray-500 bg-white">
                    <div class="w-full font-bold ">{{ progress[0] }}</div>
                    <div class="font-bold text-blue-500 whitespace-nowrap text-sm">Complied</div>
                </div>
                <div class=" flex-col xl:mt-0 mt-2 text-center mx-1 p-2 px-3 md:w-fit w-full rounded border border-gray-500 bg-white">
                    <div class="w-full font-bold">{{ progress[1] }}</div>
                    <div class="font-bold text-blue-500 whitespace-nowrap text-sm">Not Complied</div>
                </div>
                <div class=" flex-col xl:mt-0 mt-2 text-center mx-1 p-2 px-3 md:w-fit w-full rounded border border-gray-500 bg-white">
                    <div class="w-full font-bold">{{ progress[2] }}</div>
                    <div class="font-bold text-blue-500 whitespace-nowrap text-sm">Not Applicable</div>
                </div>
                <div class=" flex-col xl:mt-0 mt-2 text-center mx-1 p-2 px-3 md:w-fit w-full rounded border border-gray-500 bg-white">
                    <div class="w-full font-bold">{{ progress[3] }}%</div>
                    <div class="font-bold text-blue-500 whitespace-nowrap text-sm">Progress</div>
                </div>
            </div>
        </div>
    </div>

    <content-container :hasTopButton="true">
        <template v-slot:top-button>
            <div class="whitespace-normal mr-2">
                <button @click="update" ref="saveBtn"
                    class="select-none cursor-pointer bg-blue-500 hover:bg-blue-600 mr-2 text-white px-2 w-fit h-10 rounded">Save
                    Changes
                </button>
            </div>
            <div class="whitespace-normal">
                <div v-if="hasUpdate" class="text-red-500">
                    *Changes unsaved.
                </div>
                <div v-else-if="$page.props.flash.updated">
                    <i class="fas fa-check-circle text-green-500 mr-1"></i>
                    {{ $page.props.flash.updated }}
                </div>
            </div>
        </template>
        <template v-slot:main-content>
            <content-table v-show="itemsLayout === 'list'">
                <template v-slot:table-head>
                    <!-- <th class="p-3 max-w-8">Item</th> -->
                    <th class="p-3 max-w-xs">Area</th>
                    <th class="p-3 max-w-xs">Minimum Requirement</th>
                    <th class="p-3 w-16">Actual Situation</th>
                    <th class="p-3 w-16">Evidence</th>
                    <th class="p-3 whitespace-normal">Self-Evaluation Status</th>
                    <th class="p-3 text-right">
                        <i class="fas fa-ellipsis-v"></i>
                    </th>
                </template>
                <template v-slot:table-body>
                    <tr v-for="(item, index) in items" :key="item.id" class="align-top text-base"
                        :class="{ 'bg-slate-200': index % 2 == 0 }">
                        <!-- <td class="p-3 max-w-6 font-bold">
                            {{ item.itemNo }}
                        </td> -->
                        <td class="p-3 whitespace-normal max-w-10">
                            <div class="flex flex-col">
                                <div class="font-bold">
                                    {{ item.area }}
                                </div>
                            </div>
                        </td>
                        <td class="p-3 whitespace-normal max-w-xs">
                            <div class="flex flex-col text-justify">
                                {{ item.minimumRequirement }}
                            </div>
                        </td>
                        <td class="h-32 max-w-lg p-3 relative group">
                            <textarea maxlength="5000" ref="actualSituationInput" v-model="item.actualSituation"
                                :name="'actualSituation' + index" :id="'actualSituation' + index"
                                class="w-full min-w-16 h-32 rounded border-2 border-gray-500 resize-none group custom-scrollbar"
                                :disabled="item.selfEvaluationStatus == 'Not applicable'"
                                placeholder="Input actual situation here" @input="handleTextInput(index, item.id)">
                            </textarea>
                            <button @click="openEditor(index)"
                                class="absolute bottom-4 right-10 text-gray-500 hover:text-black group-focus-within:visible invisible tooltipForActions"
                                data-tooltip="Open editor">
                                <i class="fas fa-pen"></i>
                            </button>
                            <text-editor title="Actual Situation" :showModal="isEditorOpen && textEditorIndex == index"
                                @close="closeEditor">
                                <textarea maxlength="5000" ref="texteditor" v-model="item.actualSituation"
                                    :name="'actualSituationEditor' + index" :id="'actualSituationEditor' + index"
                                    class="w-full h-32 rounded border-2 border-gray-500 resize-none group"
                                    placeholder="Input actual situation here" @focus="handleTextEditorInput(index)"
                                    @input="handleTextEditorInput(index, item.id)">
                            </textarea>
                            </text-editor>
                        </td>
                        <td class="h-auto p-3 w-16 flex flex-col justify-center">
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
                                    <button @click="deleteModal = true; toDelete = file.id"
                                        class="text-gray-500 hover:text-red-500 tooltipForActions"
                                        :disabled="item.selfEvaluationStatus == 'Not applicable' || hasUpdate"
                                        :class="{ 'grayscale text-gray-500': hasUpdate }"
                                        :data-tooltip="file.type == 'file' ? 'Delete file' : 'Delete link'">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </td>
                        <td class="h-32 p-3">
                            <div class="w-full h-full">
                                <select ref="selfEvaluationStatus" v-model="item.selfEvaluationStatus"
                                    :id="'selfEvaluationStatus' + index" :name="'selfEvaluationStatus' + index"
                                    @change="handleChangeStatus(index, item.id)"
                                    class="p-1 rounded w-36 border-2 border-gray-500 select-none">
                                    <option :value="null">Select status</option>
                                    <option value="Complied">Complied</option>
                                    <option value="Not complied">Not complied</option>
                                    <option value="Not applicable">Not applicable</option>
                                </select>
                            </div>
                        </td>
                        <td class="p-3">
                            <div class="w-full text-right text-sm visible" ref="actionButtons"
                                :class="{ 'invisible': item.selfEvaluationStatus == 'Not applicable' }">
                                <button @click=" fileModal = true; fileItem = item.id; $refs.inputEvidenceFile.click()"
                                    class="rounded bg-white h-8 mr-1 px-2 border-2 tooltipForActions"
                                    :class="[{ 'grayscale text-blue-400 border-gray-400': hasUpdate }, {'hover:text-blue-500 border-gray-500' : !hasUpdate}]" data-tooltip="Upload file"
                                    :disabled="item.selfEvaluationStatus == 'Not applicable' || hasUpdate">
                                    <i class="fas fa-upload mr-2"></i>File
                                </button>
                                <button @click="linkModal = true; evidenceLink = null; linkItem = item.id"
                                    class="rounded bg-white h-8 px-2 border-2 tooltipForActions"
                                    :class="[{ 'grayscale text-blue-400 border-gray-400': hasUpdate }, {'hover:text-blue-500 border-gray-500' : !hasUpdate}]" data-tooltip="Drop link"
                                    :disabled="item.selfEvaluationStatus == 'Not applicable' || hasUpdate">
                                    <i class="fas fa-upload mr-2"></i>Link
                                </button>
                            </div>
                        </td>
                    </tr>
                </template>
            </content-table>
        </template>
    </content-container>

    <!-- Ready for visit confirmation -->
    <confirmation @close="toggleReadyForVisitModal" v-show="readyForVisitModal" title="Ready For Visit">

        <template v-slot:message>
            Are you sure you want to submit this compliance evaluation tool? You won't be able to make changes after
            submitting.
        </template>

        <template v-slot:buttons>
            <button class=" text-gray-100 bg-blue-500 hover:text-white hover:bg-blue-600 w-20 h-10 rounded mr-1"
                @click="submitTool">
                <span v-if="submitting"><i class="fas fa-spinner animate-spin"></i></span>
                <span v-else>Submit</span>
            </button>
        </template>
    </confirmation>

    <!-- Resubmit confirmation -->
    <confirmation @close="toggleResubmitModal" v-show="resubmitModal" title="Resubmit">

        <template v-slot:message>
            Are you sure you want to resubmit this compliance evaluation tool? You won't be able to make changes after
            resubmitting.
        </template>
        
        <template v-slot:buttons>
            <button class=" text-gray-100 bg-blue-500 hover:text-white hover:bg-blue-600 w-20 h-10 rounded mr-1"
                @click="submitTool">
                <span v-if="submitting"><i class="fas fa-spinner animate-spin"></i></span>
                <span v-else>Submit</span>
            </button>
        </template>
    </confirmation>

    <!-- Select evidence file -->
    <div>
        <input type="file" hidden ref="inputEvidenceFile" id="inputEvidenceFile"
            @change="evidenceFile = $event.target.files[0]">
    </div>

    <!-- Upload link modal -->
    <modal :showModal="linkModal" title="Evidence Link" @close="linkModal = false">
        <div class="text-center">
            <textarea maxlength="1000" class="w-full h-32 resize-none" placeholder="Input URL/Link here" id="link"
                v-model="evidenceLink">
            </textarea>
        </div>
        <template v-slot:custom-button>
            <button @click="submitLink" :disabled="uploadingLink"
                class="text-white bg-blue-600 hover:bg-blue-700 w-24 py-2 px-3 rounded border">
                <span v-if="uploadingLink"><i class="fas fa-spinner animate-spin"></i></span>
                <span v-else>Upload</span>
            </button>
        </template>
    </modal>

    <!-- Delete confirmation modal -->
    <confirmation-modal :showModal="deleteModal" @close="deleteModal = false" title="Delete file/link">
        <template v-slot:message>
            Are you sure you want to delete this evidence file/link? This action can't be undone.
        </template>
        <template v-slot:buttons>
            <button @click="deleteLink" :disabled="deleting"
                class="select-none text-white h-10 w-20 rounded bg-red-500">
                <span v-if="deleting"><i class="fas fa-spinner animate-spin"></i></span>
                <span v-else>Delete</span>
            </button>
        </template>
    </confirmation-modal>

    <!-- Notifications -->
    <Notification :message="$page.props.flash.success" type="success" />
    <Notification :message="$page.props.flash.failed" type="failed" />
    <Notification :message="$page.props.flash.updated" type="success" />
    <Notification :message="$page.props.flash.deleted" type="success" />
    <Notification :message="$page.props.flash.uploaded" type="success" />
    <Notification :message="$page.props.errors.link" type="failed" />
    <Notification v-show="saving" message="Saving..." type="processing" />
    <Notification v-show="uploadingFile" message="Uploading file..." type="processing" />
</template>

<script setup>
    // Imports
    import { ref, onMounted, onUnmounted, computed, reactive, watch } from 'vue';
    import { useForm, router } from '@inertiajs/vue3';
    import { Inertia } from '@inertiajs/inertia';

    // Props
    const props = defineProps([
        'evaluation',
        'items',
        'progress',
    ]);

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
    const deleteModal = ref(false);
    const deleting = ref(false);
    const toDelete = ref(null);

    const linkModal = ref(false);
    const uploadingLink = ref(false);
    const linkItem = ref(null);
    const evidenceLink = ref(null);

    const fileModal = ref(false);
    const uploadingFile = ref(false);
    const fileItem = ref(null);
    const evidenceFile = ref(null);
    const inputEvidenceFile = ref([]);

    const isEditorOpen = ref(false);
    const textEditorIndex = ref('');
    const texteditor = ref([]);
    const readyForVisitModal = ref(false);
    const resubmitModal = ref(false);

    const actualSituationInput = ref([]);
    const selfEvaluationStatus = ref([]);
    const actionButtons = ref([]);
    const evidenceFiles = ref([]);

    const hasUpdate = ref(false);
    const updatedRows = ref([]);
    const saveBtn = ref(null);
    const saving = ref(false);
const submitting = ref(false);
const updatingconforme = ref(false);

    const refs = { actualSituationInput, selfEvaluationStatus, actionButtons, evidenceFiles, saveBtn, texteditor, inputEvidenceFile };

    // Functions
    function handleTextInput(index, id) {
        let textarea = actualSituationInput.value[index];
        textarea.style.height = "128px";
        let newHeight = textarea.scrollHeight + "px";
        let maxHeight = 384;
        textarea.style.height = Math.min(maxHeight, Math.max(128, parseInt(newHeight))) + "px";
        hasUpdate.value = true;
        getRowsWithUpdates(id);
    }

    function handleTextEditorInput(index, id) {
        let textEditor = refs.texteditor.value[index];
        textEditor.style.height = "250px";
        let newHeight = textEditor.scrollHeight + "px";
        let maxHeight = 400;
        textEditor.style.height = Math.min(maxHeight, Math.max(128, parseInt(newHeight))) + "px";
        hasUpdate.value = true;
        getRowsWithUpdates(id);
    }

    function handleChangeStatus(index, id) {
        let selfEvaluationStatus = refs.selfEvaluationStatus.value[index];
        let actualSituationInput = refs.actualSituationInput.value[index];
        let actionButtons = refs.actionButtons.value[index];
        let evidenceFiles = refs.evidenceFiles.value[index];
        hasUpdate.value = true;
        getRowsWithUpdates(id);

        if (selfEvaluationStatus.value == 'Not applicable') {
            actualSituationInput.disabled = true;
            if (!actionButtons.classList.contains('hidden')) {
                actionButtons.classList.add('hidden');
            }
            if (!evidenceFiles.classList.contains('hidden')) {
                evidenceFiles.classList.add('hidden');
            }

        } else {
            actualSituationInput.disabled = false;
            if (actionButtons.classList.contains('hidden')) {
                actionButtons.classList.remove('hidden');
            }
            if (evidenceFiles.classList.contains('hidden')) {
                evidenceFiles.classList.remove('hidden');
            }
        }
    };

    function getRowsWithUpdates(id) {
        if (!updatedRows.value.includes(id)) {
            updatedRows.value.push(id);
        }
    }


    function openEditor(index) {
        isEditorOpen.value = true;
        textEditorIndex.value = index;
    }

    function closeEditor() {
        isEditorOpen.value = false;
    }

function toggleReadyForVisitModal() {
    readyForVisitModal.value = !readyForVisitModal.value;
}

function toggleResubmitModal() {
    resubmitModal.value = !resubmitModal.value;
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

const conforme = reactive({
        id: ref(props.evaluation.id),
        name: ref(props.evaluation.conforme),
        title: ref(props.evaluation.conformeTitle),
    });

function updateConforme() {
        router.post('/hei/evaluation/conforme', conforme, {
            onStart: () => {
                updatingconforme.value = true;
            },
            onFinish: () => {
                updatingconforme.value = false;
            },
            preserveScroll: true,
            preserveState: false,
        });
    }

    function update() {
        router.post('/hei/evaluation/update', {
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
            preserveScroll: true,
            preserveState: false,
        });
    }

    function submitLink() {
        router.post('/hei/evaluation/link', {
            id: linkItem.value,
            link: evidenceLink.value,
        }, {
            onStart: () => {
                uploadingLink.value = true;
            },
            onFinish: () => {
                uploadingLink.value = false;
            },
            preserveState: false,
            preserveScroll: true,
        });
    }


    function deleteLink() {
        router.post('/hei/evaluation/link/delete', {
            'id': props.evaluation.id,
            'item': toDelete.value
        }, {
            onStart: () => {
                deleting.value = true;
            },
            onFinish: () => {
                deleting.value = false;
            },
            preserveState: false,
            preserveScroll: true,
        });
    }

    function submitTool() {
        router.post('/hei/evaluation/submit', props.evaluation, {
            onStart: () => {
                submitting.value = true;
            },
            onFinish: () => {
                submitting.value = false;
            },
            preserveState: false,
            preserveScroll: true,
        });
    }



    watch(evidenceFile, value => {
        router.post('/hei/evaluation/upload', {
            id: props.evaluation.id,
            itemId: fileItem.value,
            file: evidenceFile.value,
        }, {
            onStart: () => {
                uploadingFile.value = true;
            },
            onFinish: () => {
                uploadingFile.value = false;
            },
            preserveState: false,
            preserveScroll: true,
        })
    });
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