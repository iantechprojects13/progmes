<template>

    <Head title="Evaluation Form" />
    <page-title title="Program Self-Evaluation" />
    <div class="md:mx-8 mx-3 mt-8 flex flex-col lg:flex-row justify-between">
        <div class="flex flex-col w-auto rounded">
            <div class="w-auto flex flex-col md:flex-row">
                <div class="mr-2 font-bold">
                    HEI Name:
                </div>
                <div>
                    {{ evaluation.institution_program.institution.name }}
                </div>
            </div>
            <div class="w-auto flex flex-col md:flex-row mt-2 md:mt-1">
                <div class="w-auto flex flex-col md:flex-row">
                    <div class="mr-2 font-bold">
                        Program:
                    </div>
                    <div>
                        {{ evaluation.institution_program.program.program }}
                    </div>
                </div>
            </div>
            <div class="w-auto flex flex-col md:flex-row mt-2 md:mt-1">
                <div class="w-auto flex flex-col md:flex-row">
                    <div class="mr-2 font-bold">
                        Effectivity:
                    </div>
                    <div>
                        A.Y. {{ evaluation.effectivity }}
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-5 lg:mt-0">
            <button @click="readyForVisitModal = true; readyForVisitIndicator = $page.url"
                :disabled="!canSubmit || hasUpdate" class="px-3 w-fit text-white  h-10 rounded mr-1"
                :class="[{ 'bg-green-700 hover:bg-green-800': canSubmit && !hasUpdate }, { 'bg-red-400 hover:red-600': !canSubmit || hasUpdate }]">
                Ready for visit
            </button>
        </div>
    </div>
    <content-container hasTopButton="true" hasBackButton="true">
        <template v-slot:back-button>
            <Link href="/hei/evaluation">
            <button class="w-10 h-10 mr-2 rounded-full hover:bg-gray-300 tooltipForActions" data-tooltip="Back">
                <i class="fas fa-arrow-left"></i>
            </button>
            </Link>
        </template>
        <template v-slot:top-button>
            <div class="flex">
                <div class="text-gray-500 h-10 lg:ml-2 flex items-center mr-5">
                    <div v-if="saving">
                        <i class="fas fa-spinner animate-spin mr-2"></i>Saving
                    </div>
                    <div v-else-if="hasUpdate" class="text-red-500 italic">
                        *Changes unsaved
                    </div>
                    <div v-else-if="$page.props.flash.updated" class="italic">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        {{ $page.props.flash.updated }}
                    </div>

                    <div v-else></div>
                </div>
                <div>
                    <button @click="update" ref="saveBtn"
                        class="lg:px-3 w-10 lg:w-auto h-10 rounded text-gray-200 border bg-blue-500 border-blue-500 hover:bg-blue-600 hover:text-white"
                        :disabled="!hasUpdate">
                        <span class="hidden lg:inline-block">Save Changes</span>
                    </button>
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
                    <th class="p-3">Evidence</th>
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
                            <textarea ref="actualSituationInput" v-model="item.actualSituation"
                                :name="'actualSituation' + index" :id="'actualSituation' + index"
                                class="w-full min-w-16 h-32 rounded border-gray-500 resize-none group custom-scrollbar"
                                :disabled="item.selfEvaluationStatus == 'Not applicable'"
                                placeholder="Input actual situation here" @input="handleTextInput(index)">
                                        </textarea>
                            <button @click="openEditor(index)"
                                class="absolute bottom-4 right-10 text-gray-500 hover:text-black group-focus-within:visible invisible tooltipForActions"
                                data-tooltip="Open editor">
                                <i class="fas fa-pen"></i>
                            </button>
                            <text-editor title="Actual Situation" :showModal="isEditorOpen && textEditorIndex == index"
                                @close="closeEditor">
                                <textarea ref="texteditor" v-model="item.actualSituation"
                                    :name="'actualSituationEditor' + index" :id="'actualSituationEditor' + index"
                                    class="w-full h-32 rounded border-gray-500 resize-none group"
                                    placeholder="Input actual situation here" @focus="handleTextEditorInput(index)"
                                    @input="handleTextEditorInput(index)">
                                            </textarea>
                            </text-editor>
                        </td>
                        <td class="h-auto p-3 max-w-16 min-w-12 flex flex-col justify-center">
                            <div class="flex flex-col"
                                :class="{ 'hidden': item.selfEvaluationStatus == 'Not applicable' }"
                                ref="evidenceFiles">
                                <div v-for="(file, index) in item.evidence" :key="file.id" class="w-full">
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
                                    @change="handleChangeStatus(index)" class="p-1 rounded w-36 border-gray-400">
                                    <option value="Complied">Complied</option>
                                    <option value="Not complied">Not complied</option>
                                    <option value="Not applicable">Not applicable</option>
                                </select>
                            </div>
                        </td>
                        <td class="p-3">
                            <div class="w-full text-right visible" ref="actionButtons"
                                :class="{ 'invisible': item.selfEvaluationStatus == 'Not applicable' }">
                                <button @click=" fileModal = true; fileItem = item.id; $refs.inputEvidenceFile.click()"
                                    class="h-8 px-2 text-white bg-green-600 hover:bg-green-700 rounded tooltipForActions"
                                    :class="{ 'grayscale text-gray-500': hasUpdate }" data-tooltip="Upload file"
                                    :disabled="item.selfEvaluationStatus == 'Not applicable' || hasUpdate">
                                    <i class="fas fa-upload mr-2"></i>File
                                </button>
                                <button @click="linkModal = true; evidenceLink = null; linkItem = item.id"
                                    class="ml-1 h-8 px-2 bg-green-600 text-white hover:bg-green-700 rounded tooltipForActions"
                                    :class="{ 'grayscale text-gray-500': hasUpdate }" data-tooltip="Drop link"
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

    <modal :showModal="uploadEvidenceFileModal && $page.url == uploadFileIndicator" title="Upload Evidence File"
        @close="closeEvidenceUploadModal" @upload="upload" type="uploadEvidenceFile">
        <div class="text-center pt-5">
            <input ref="uploadEvidenceFile" hidden type="file" @change="handleFileChange" id="evidence">
            <div v-if="evidenceFile" class="text-black text-left flex flex-col">
                <div class="p-2 rounded bg-gray-200">
                    <div>File name: {{ evidenceFile.name }}</div>
                    <div>Size: {{ (evidenceFile.size / 1024).toFixed(2) }} KB</div>
                    <div class="w-full text-right">
                        <button
                            class="inline-block mr-1 py-1 px-2 border border-gray-400 rounded hover:text-orange-500 tooltipForActions"
                            data-tooltip="Change file" @click.prevent="$refs.uploadEvidenceFile.click()">
                            <i class="fas fa-refresh"></i>
                        </button>
                    </div>
                </div>
            </div>
            <button v-if="!evidenceFile" @click.prevent="$refs.uploadEvidenceFile.click()"
                class="hover:text-white border border-green-700 hover:bg-green-700 py-2 px-10 rounded"><i
                    class="fas fa-cloud-upload mr-5 text-xl"></i>
                Select file
            </button>
            <div class="mt-3 w-full text-left">
                <form-error-message :message="$page.props.errors['evidence.file']" theme="dark" />
            </div>
        </div>
    </modal>

    <confirmation @close="closeReadyForVisitConfirmation"
        v-show="readyForVisitModal && readyForVisitIndicator == $page.url" title="Ready For Visit">

        <template v-slot:message>
            Are you sure you want to submit this compliant tool? You won't be able to make changes after submitting.
        </template>

        <template v-slot:buttons>
            <button class=" text-gray-100 bg-blue-500 hover:text-white hover:bg-blue-600 p-2 px-3 rounded mr-1"
                @click="readyForVisit">
                Submit
            </button>
        </template>
    </confirmation>


    <!-- Select evidence file -->
    <div>
        <input type="file" ref="inputEvidenceFile" id="inputEvidenceFile"
            @change="evidenceFile = $event.target.files[0]">
    </div>

    <!-- Upload link modal -->
    <modal :showModal="linkModal" title="Evidence Link" @close="linkModal = false">
        <div class="text-center">
            <textarea class="w-full h-32 resize-none" placeholder="Input URL/Link here" id="link"
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
    <Notification :message="$page.props.flash.message" />
    <Notification :message="$page.props.flash.updated" />
    <Notification :message="$page.props.flash.deleted" />
    <Notification :message="$page.props.flash.uploaded" type="success" />
    <Notification :message="$page.props.errors.link" type="failed" />

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
        'canSubmit'
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

    const uploadEvidenceFileModal = ref(false);

    const isEditorOpen = ref(false);
    const textEditorIndex = ref('');
    const texteditor = ref([]);
    const readyForVisitModal = ref(false);



    const actualSituationInput = ref([]);
    const selfEvaluationStatus = ref([]);
    const actionButtons = ref([]);
    const evidenceFiles = ref([]);
    const hasUpdate = ref(false);

    const updateIndicator = ref(null);
    const uploadFileIndicator = ref(null);
    const submitLinkIndicator = ref(null);
    const readyForVisitIndicator = ref(null);
    const saveBtn = ref(null);

    const toUpload = ref(null);
    const saving = ref(false);
    const remountPage = ref(false);

    const refs = { actualSituationInput, selfEvaluationStatus, actionButtons, evidenceFiles, saveBtn, texteditor, inputEvidenceFile };

    // Functions
    function handleTextInput(index) {
        let textarea = actualSituationInput.value[index];
        textarea.style.height = "128px";
        let newHeight = textarea.scrollHeight + "px";
        let maxHeight = 384;
        textarea.style.height = Math.min(maxHeight, Math.max(128, parseInt(newHeight))) + "px";
        hasUpdate.value = true;
    }

    function handleTextEditorInput(index) {
        let textEditor = refs.texteditor.value[index];
        textEditor.style.height = "250px";
        let newHeight = textEditor.scrollHeight + "px";
        let maxHeight = 400;
        textEditor.style.height = Math.min(maxHeight, Math.max(128, parseInt(newHeight))) + "px";
        hasUpdate.value = true;
    }

    function handleChangeStatus(index) {
        let selfEvaluationStatus = refs.selfEvaluationStatus.value[index];
        let actualSituationInput = refs.actualSituationInput.value[index];
        let actionButtons = refs.actionButtons.value[index];
        let evidenceFiles = refs.evidenceFiles.value[index];
        hasUpdate.value = true;

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


    const handleFileChange = (event) => {
        evidenceUpload.file = event.target.files[0];
        evidenceFile.value = event.target.files[0];
    };

    const removeFile = () => {
        evidenceUpload.file = null;
        evidenceFile.value = null;
    };


    function openEditor(index) {
        isEditorOpen.value = true;
        textEditorIndex.value = index;
    }

    function closeEvidenceUploadModal() {
        removeFile();
        uploadEvidenceFileModal.value = false;
    }

    function closeEvidenceLinkModal() {
        removeLink();
        submitLinkIndicator.value = null;
        submitEvidenceLinkModal.value = false;
    }

    function closeReadyForVisitConfirmation() {
        readyForVisitModal.value = false;
    }

    function openEvidenceUploadModal(item) {
        uploadEvidenceFileModal.value = true;
        evidenceUpload.itemId = item.id;
    };

    function openEvidenceLinkModal(item) {
        evidenceLink.itemId = item.id;
        submitEvidenceLinkModal.value = true;
    };

    function closeEditor() {
        isEditorOpen.value = false;
    }

    function confirmDelete() {
        deleteEvidence.value = true;
    }

    function closeDeleteConfirmation() {
        deleteEvidence.value = false;
    }

    // CTRL + S function
    const handleKeyDown = (event) => {
        if (event.ctrlKey || event.metaKey) {
            if (event.key === 's' || event.key === 'S') {
                event.preventDefault();
                console.log('CLTR+S');
                refs.saveBtn.value.click();
            }
        }
    };

    const evidenceUpload = reactive({
        itemId: null,
        file: null,
    });

    function update() {
        router.post('/hei/evaluation/update', { 'id': props.evaluation.id, 'items': props.items }, {
            onStart: () => {
                saving.value = true;
            },
            onFinish: () => {
                saving.value = false;
            },
            preserveState: false,
        });
    }

    function upload() {
        router.post('/hei/evaluation/upload', { 'id': props.evaluation.id, 'itemId': evidenceUpload.itemId, 'file': evidenceUpload.file });
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
        });
    }

    function readyForVisit() {
        router.post('/hei/evaluation/submit', props.evaluation);
    }

    // watch(toUpload, value => {
    //     Inertia.post('/hei/evaluation/upload', { id: props.evidence.id, file: value }, {
    //         preserveScroll: true,
    //         preserveState: true,
    //     });
    // });


    watch(evidenceFile, value => {
        router.post('/hei/evaluation/upload', {
            id: props.evaluation.id,
            itemId: fileItem.value,
            file: evidenceFile.value,
        }, {
            preserveState: false,
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