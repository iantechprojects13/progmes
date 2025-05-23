<template>
    <Head title="Library Self-Evaluation" />
    <page-title title="Library Self-Evaluation" />
    <content-container :hasBackButton="true">
        <template v-slot:content-title>
            <div class="w-full flex flex-col md:flex-row justify-between items-center">
                <div class="w-full flex flex-row items-center">
                    <div class="w-full flex flex-row items-center">
                        <div class="font-bold">Library Self-Evaluation Tool</div>
                    </div>
                </div>
                <div class="w-full md:w-auto" v-show="$page.props.auth.user.role == 'Vice-President for Academic Affairs' || $page.props.auth.user.role == 'Librarian'">
                    <dropdown-option position="right">
                        <template v-slot:button>
                            <button
                                class="flex whitespace-nowrap w-full md:w-auto items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg justify-center"
                            >
                                Options
                                <i class="fas fa-caret-down ml-2"></i>
                            </button>
                        </template>
                        <template v-slot:options>
                            <div class="w-64">
                                <div class="pb-3 border-b">
                                    <button @click="edit" :disabled="evaluation.status != 'In progress'"
                                        class="py-1.5 hover:bg-gray-200 w-full text-left indent-8"
                                        :class="{ 'grayscale text-gray-400 cursor-not-allowed': evaluation.status != 'In progress' }"
                                    >
                                        Edit
                                    </button>
                                </div>
                                <div class="pt-3">
                                    <div class="text-gray-600 font-semibold px-5 py-1">
                                        Monitoring Report
                                    </div>
                                    <button @click="previewFile" :disabled="evaluation.evaluationDate == null"
                                        class="py-1.5 hover:bg-gray-200 w-full text-left indent-8"
                                        :class="{ 'grayscale text-gray-400 cursor-not-allowed': evaluation.evaluationDate == null }"
                                    >
                                        Preview
                                    </button>
                                    <button @click="downloadFile" :disabled="evaluation.evaluationDate == null"
                                        class="py-1.5 hover:bg-gray-200 w-full text-left indent-8"
                                        :class="{ 'grayscale text-gray-400 cursor-not-allowed': evaluation.evaluationDate == null }"
                                    >
                                        Download
                                    </button>
                                </div>
                            </div>
                        </template>
                    </dropdown-option>
                </div>
            </div>
        </template>
        <template v-slot:main-content>
            <tool-info :data="{
                'HEI Name': evaluation.institution?.name,
                'Effectivity': evaluation.effectivity,
                'CMO': cmo,
            }"/>
            <tool-progress-display :data="progress" type="HEI" />
            <div class="p-3">
                <div
                    class="w-full p-2 md:p-5 md:shadow-lg md:border rounded-xl"
                >
                    <content-table>
                        <template v-slot:table-head>
                            <th>Area/<br />Minimum Requirement</th>
                            <th>Self-Evaluation Status/<br>Actual Situation</th>
                            <th>Evidence</th>
                            <th v-show="showEvaluation">
                                Findings
                            </th>
                            <th v-show="showEvaluation">
                                Comments/Recommendations
                            </th>
                            <th v-show="showEvaluation">
                                Evaluation Status
                            </th>
                        </template>
                        <template v-slot:table-body>
                            <tr v-if="evaluation.item.length == 0">
                                <no-search-result text="items"/>
                            </tr>
                            <tr
                                v-else
                                v-for="item in evaluation.item"
                                :key="item.id"
                            >
                                <td class="max-w-[24rem]">
                                    <div
                                        class="font-bold"
                                        v-html="item.criteria.area"
                                    ></div>
                                    <div
                                        v-html="
                                            item.criteria.minimumRequirement
                                        "
                                    ></div>
                                </td>
                                <td>
                                    <status :text="item.selfEvaluationStatus" />
                                    <div v-html="item.actualSituation"></div>
                                </td>
                                <td>
                                    <div
                                        v-for="link in item.evidence"
                                        :key="link.id"
                                        class="w-full"
                                    >
                                        <a
                                            class="px-1 text-blue-600 hover:underline m-1 rounded"
                                            :href="link.url"
                                            target="_blank"
                                        >
                                            Link
                                        </a>
                                    </div>
                                </td>
                                <td
                                    v-show="showEvaluation"
                                >
                                    <div v-html="item.findings">

                                    </div>
                                </td>
                                <td
                                    v-show="showEvaluation"
                                >
                                    <status :text="item.evaluationStatus" />
                                </td>
                                <td
                                    v-show="showEvaluation"
                                >
                                    <div v-html="item.recommendations">

                                    </div>
                                </td>
                            </tr>
                        </template>
                    </content-table>
                </div>
            </div>
        </template>
    </content-container>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';
import Layout from '@/Shared/Layout.vue';
import axios from 'axios';
defineOptions({ layout: Layout });
const props = defineProps([
    "evaluation",
    'cmo',
    "showEvaluation"
]);

const progress = ref([null]);

onMounted(() => {
    getProgress();
});


const getProgress = async () => {
    await axios.get(`/api/hei/library/evaluation/${props.evaluation.id}/progress`)
        .then(response => {
            progress.value = response.data;
        })
        .catch(error => {
            console.error('Error updating evaluation:', error);
        });
};

const submitReport = reactive({
    orientation: "landscape",
    id: ref(props.tool),
});

function edit() {
    router.get(`/hei/library/${props.evaluation.id}/edit`, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
}

function previewFile() {
    const url = `/report/library/monitoring/${props.evaluation.id}/${submitReport.orientation}/view`;
    window.open(url, "_blank");
}

function downloadFile() {
    const url = `/report/library/monitoring/${props.evaluation.id}/${submitReport.orientation}/download`;
    window.open(url, "_blank");
}

</script>
