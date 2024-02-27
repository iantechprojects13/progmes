<template>
    <Head title="Evaluation Form" />
    <page-title title="Program Self-Evaluation" />
    <div class="md:mx-8 mx-3 mt-7">
        <div class="flex flex-col w-full bg-white rounded p-3 py-5">
            <div class="w-auto flex flex-col md:flex-row">
                <label for="heiName"
                    class="mr-3 h-8 md:h-10 flex items-center justify-start md:justify-end align-middle text-gray-800 w-full md:w-24">HEI
                    Name
                </label>
                <input type="text" id="heiName" class="md:w-1/2 w-full rounded font-bold" disabled
                    :value="evaluation.institution_program.institution.name">
            </div>
            <div class="w-auto flex flex-col md:flex-row mt-2 md:mt-1">
                <label for="program"
                    class="mr-3 h-8 md:h-10 flex items-center justify-start md:justify-end align-middle text-gray-800 w-full md:w-24">
                    Program
                </label>
                <input type="text" id="program" class="md:w-1/2 w-full rounded font-bold" disabled
                    :value="evaluation.institution_program.program.program">
            </div>
            <div class="w-auto flex flex-col md:flex-row mt-2 md:mt-1">
                <label for="program"
                    class="mr-3 h-8 md:h-10 flex items-center justify-start md:justify-end align-middle text-gray-800 w-full md:w-24">
                    Program
                </label>
                <input type="text" id="program" class="md:w-1/2 w-full rounded font-bold" disabled
                    :value="'A.Y. ' + evaluation.effectivity">
            </div>
        </div>
    </div>
    <content-container :hasAction="true">
        <template v-slot:channel>

        </template>
        <template v-slot:actions>
            <!-- <div class="border h-10 flex flex-row items-center px-2">
                <button class="p-1 px-2 border border-gray-400" :class="{ 'bg-blue-200': itemsLayout == 'list' }"
                    @click="listLayout">
                    <i class="fas fa-list"></i>
                </button>
                <button class="p-1 px-2 border border-gray-400" :class="{ 'bg-blue-200': itemsLayout == 'grid' }"
                    @click="gridLayout">
                    <i class="fas fa-th-large"></i>
                </button>
            </div> -->
            <div class="italic text-gray-500 h-10 flex items-center mr-3">
                {{ $page.props.flash.message }}
            </div>
            <div>
                <button disabled class="lg:px-3 w-10 lg:w-auto text-gray-400 border border-gray-400 h-10 rounded mr-1">
                    <i class="fas fa-check lg:mr-2"></i>
                    <span class="hidden lg:inline-block">Ready for visit</span>
                </button>
                <button @click="submit" class="lg:px-3 w-10 lg:w-auto h-10 bg-blue-500 text-white rounded tooltipForActions"
                    data-tooltip="Save changes">
                    <i class="fas fa-floppy-disk lg:mr-2"></i>
                    <span class="hidden lg:inline-block">Save</span>
                </button>
            </div>
        </template>
        <template v-slot:content>
            <content-table v-show="itemsLayout === 'list'">
                <template v-slot:table-head>
                    <th class="p-3 border-t border-gray-400 max-w-8">Item</th>
                    <th class="p-3 border-t border-gray-400 max-w-xs">Area</th>
                    <th class="p-3 border-t border-gray-400 max-w-xs">Minimum Requirement</th>
                    <th class="p-3 border-t border-gray-400 w-16">Actual Situation</th>
                    <th class="p-3 border-t border-gray-400">Evidence</th>
                    <th class="p-3 border-t border-gray-400 whitespace-normal">Self-Evaluation Status</th>
                    <th class="p-3 border-t border-gray-400 text-right">
                        <i class="fas fa-ellipsis-v"></i>
                    </th>
                </template>
                <template v-slot:table-body>
                    <tr v-for="(item, index) in items" :key="item.id" class="align-top"
                        :class="{ 'bg-slate-200': index % 2 == 0 }">
                        <td class="p-3 max-w-6 font-bold">
                            {{ item.criteria.itemNo }}
                        </td>
                        <td class="p-3 whitespace-normal max-w-10">
                            <div class="flex flex-col">
                                <div class="font-bold">
                                    {{ item.criteria.area }}
                                </div>
                            </div>
                        </td>
                        <td class="p-3 whitespace-normal max-w-xs">
                            <div class="flex flex-col text-justify">
                                {{ item.criteria.minimumRequirement }}
                            </div>
                        </td>
                        <td class="h-32 max-w-lg p-3 relative group">
                            <textarea ref="actualSituationInput" v-model="form.items[index]['actualSituation']"
                                name="actualSituation"
                                class="w-full min-w-16 h-32 rounded border-gray-500 resize-none group custom-scrollbar"
                                :disabled="form.items[index]['selfEvaluationStatus'] == 'Not applicable'"
                                placeholder="Input actual situation here" @input="handleTextInput(index)">
                            </textarea>
                            <button @click="openEditor(index)"
                                class="absolute bottom-4 right-10 text-gray-500 hover:text-black group-focus-within:visible invisible tooltipForActions"
                                data-tooltip="Open editor">
                                <i class="fas fa-pen"></i>
                            </button>
                            <text-editor title="Actual Situation" :showModal="isEditorOpen && textEditorIndex == index"
                                @close="closeEditor">
                                <textarea ref="texteditor" v-model="form.items[index]['actualSituation']"
                                    name="actualSituationEditor"
                                    class="w-full h-32 rounded border-gray-500 resize-none group"
                                    placeholder="Input actual situation here" @focus="handleTextEditorInput(index)"
                                    @input="handleTextEditorInput(index)">
                                </textarea>
                            </text-editor>
                        </td>
                        <td class="h-auto p-3 max-w-12 min-w-8 flex flex-col justify-center">
                            <div :class="{ 'hidden': form.items[index]['selfEvaluationStatus'] == 'Not applicable' }"
                                ref="evidence">
                                <div class="flex flex-row overflow-hidden  whitespace-normal rounded-full mt-1 w-fit px-2">
                                    <a href="https://www.facebook.com" class="underline" target="_blank">
                                        Link 1
                                    </a>
                                </div>
                                <div class="flex flex-row overflow-hidden  whitespace-normal rounded-full mt-1 w-fit px-2">
                                    <a href="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR9PV1MClejGx2rwhYb5nFKBE1Q1XnkVWkO9A&usqp=CAU"
                                        class="underline" target="_blank">
                                        File 1
                                    </a>
                                </div>
                            </div>
                        </td>
                        <td class="h-32 p-3">
                            <div class="w-full h-full">
                                <select ref="selfEvaluationStatus" v-model="form.items[index]['selfEvaluationStatus']"
                                    @change="handleChangeStatus(index)" class="p-1 rounded w-36 border-gray-400">
                                    <option value="Complied">Complied</option>
                                    <option value="Not complied">Not complied</option>
                                    <option value="Not applicable">Not applicable</option>
                                </select>
                            </div>
                        </td>
                        <td class="p-3">
                            <div class="w-full text-right" ref="actionButtons"
                                :class="{ 'hidden': form.items[index]['selfEvaluationStatus'] == 'Not applicable' }">
                                <button @click="openEvidenceUploadModal"
                                    class="h-8 w-10 shadow shadow-gray-500 hover:bg-gray-300 rounded border border-gray-500 bg-white tooltipForActions"
                                    data-tooltip="Upload file"
                                    :disabled="form.items[index]['selfEvaluationStatus'] == 'Not applicable'">
                                    <i class="fas fa-file-upload"></i>
                                </button>
                                <button
                                    class="ml-1 h-8 w-10 shadow shadow-gray-500 hover:bg-gray-300 rounded border border-gray-500 bg-white tooltipForActions"
                                    data-tooltip="Link"
                                    :disabled="form.items[index]['selfEvaluationStatus'] == 'Not applicable'">
                                    <i class="fas fa-link"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </template>
            </content-table>


            <!-- ------ grid view ------- -->


            <!-- <div class="flex flex-col max-w-5xl mx-auto px-3 p-4" v-show="itemsLayout === 'grid'">
                <div v-for="(  item, index  ) in   items  " :key="item.id"
                    class="flex flex-col mb-5 p-2 m-2 bg-gray-200 rounded shadow-lg">
                    <div class="font-bold bg-gray-100 w-fit rounded-t p-2">
                        #{{ item.criteria.itemNo }} {{ item.criteria.area }}
                    </div>
                    <div class="bg-gray-100 p-2 px-3 rounded-b">
                        {{ item.criteria.minimumRequirement }}
                    </div>
                    <div class="bg-gray-200 p-0.5 pt-2">
                        <textarea class="w-full h-24 border-none " placeholder="Input actual situation here"></textarea>
                    </div>
                    <div class="w-full py-2 px-1">
                        <select class=" py-1 px-2 ml-1 rounded w-36 border-gray-300 bg-gray-100">
                            <option value="Not complied">Not complied</option>
                            <option value="Complied">Complied</option>
                            <option value="Not applicable">Not applicable</option>
                        </select>
                        <button class=" py-1 px-2 hover:bg-blue-600 bg-blue-500 text-white rounded">
                            <i class="fas fa-upload mr-2"></i>
                            Upload evidence
                        </button>
                    </div>
                </div>
            </div> -->


        </template>
    </content-container>
    <modal :showModal="uploadEvidenceFileModal" title="Upload Evidence" @close="closeEvidenceUploadModal" @upload="upload"
        type="uploadEvidenceFile">
        <div class="text-center pt-5">
            <input ref="uploadEvidenceFile" hidden type="file" @change="handleFileChange" id="evidence">
            <div v-if="evidenceFile" class="text-black text-left flex flex-col">
                <div class="p-2 rounded bg-gray-200">
                    <div>File name: {{ evidenceFile.name }}</div>
                    <div>Size: {{ (evidenceFile.size / 1024).toFixed(2) }} KB</div>
                </div>
                <div class="flex items-center w-full justify-center">
                    <button class="inline-block ml-4 py-1 px-2 border border-gray-400 rounded hover:bg-orange-400"
                        @click.prevent="$refs.uploadEvidenceFile.click()">
                        <i class="fas fa-refresh"></i>
                    </button>
                </div>
            </div>
            <button v-if="!evidenceFile" @click.prevent="$refs.uploadEvidenceFile.click()"
                class="hover:text-white border border-green-700 hover:bg-green-700 py-2 px-10 rounded"><i
                    class="fas fa-cloud-upload mr-5 text-xl"></i>
                Select file
            </button>
            <form-error-message :message="$page.props.errors.file" theme="dark" />
        </div>
    </modal>
    <Notification :message="$page.props.flash.success" />
</template>

<script setup>
import { ref, onMounted, onUnmounted, computed, reactive } from 'vue';
import { useForm, router } from '@inertiajs/vue3';

const handleKeyDown = (event) => {
    if (event.ctrlKey || event.metaKey) {
        if (event.key === 's' || event.key === 'S') {
            event.preventDefault();
            submit();
        }
    }
};


onMounted(() => {
    window.addEventListener('keydown', handleKeyDown);
    window.onbeforeunload = function () {
        return true;
    };
});

onUnmounted(() => {
    window.removeEventListener('keydown', handleKeyDown);
    window.onbeforeunload = null;
});

const uploadEvidenceFileModal = ref(false);
const uploadEvidenceLinkModal = ref(false);
const isEditorOpen = ref(false);
const textEditorIndex = ref('');

function openEditor(index) {
    isEditorOpen.value = true;
    textEditorIndex.value = index;
}

function closeEvidenceUploadModal(event) {
    uploadEvidenceFileModal.value = false;
    uploadEvidenceLinkModal.value = false;
    evidence.file = null;
    evidenceFile.value = null;
}

function openEvidenceUploadModal() {
    uploadEvidenceFileModal.value = true;
    uploadEvidenceLinkModal.value = true;
}

function closeEditor() {
    isEditorOpen.value = false;
}

const props = defineProps([
    'evaluation',
    'items',
]);

const itemsArray = computed(() => {
    return props.items.map(item => ({
        id: item.id,
        actualSituation: item.actualSituation,
        selfEvaluationStatus: item.selfEvaluationStatus,
    }));
});

const form = useForm({
    items: itemsArray,
});

const evidence = reactive({
    file: null,
});

const evidenceFile = ref(null)

const handleFileChange = (event) => {
    if (event.target.files[0] != null) {
        evidence.file = event.target.files[0];
        evidenceFile.value = event.target.files[0];
    }
};

function submit() {
    form.post('/hei/evaluation/update', form);
}

function upload() {
    router.post('/hei/evaluation/upload', evidence);
}




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

        handleTextInput(index) {
            let textarea = this.$refs.actualSituationInput[index];

            textarea.style.height = "128px";
            let newHeight = textarea.scrollHeight + "px";
            let maxHeight = 384;
            textarea.style.height = Math.min(maxHeight, Math.max(128, parseInt(newHeight))) + "px";
        },

        handleTextEditorInput(index) {
            let textEditor = this.$refs.texteditor[index];
            textEditor.style.height = "128px";
            let newHeight = textEditor.scrollHeight + "px";
            let maxHeight = 384;
            textEditor.style.height = Math.min(maxHeight, Math.max(128, parseInt(newHeight))) + "px";
        },

        handleChangeStatus(index) {
            let selfEvaluationStatus = this.$refs.selfEvaluationStatus[index];
            let actualSituationInput = this.$refs.actualSituationInput[index];
            let actionButtons = this.$refs.actionButtons[index];
            let evidence = this.$refs.evidence[index];

            if (selfEvaluationStatus.value == 'Not applicable') {
                actualSituationInput.disabled = true;
                actionButtons.classList.add('hidden');
                evidence.classList.add('hidden');

            } else {
                actualSituationInput.disabled = false;
                actionButtons.classList.remove('hidden');
                evidence.classList.remove('hidden');
            }
        },


    },

}
</script>