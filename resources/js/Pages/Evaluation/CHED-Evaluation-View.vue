<template>
    <Head title="Program Evaluation" />
    <page-title title="Program Evaluation" />
    <content-container :hasTopMainContent="true" :hasBackButton="true">
        <template v-slot:content-title>
            <div class="w-full flex flex-col md:flex-row justify-between items-center">
                <div class="w-full flex flex-row items-center">
                    <div class="w-full flex flex-row items-center">
                        <div class="font-bold">Program Evaluation Tool</div>
                    </div>
                </div>
                <div class="w-full md:w-auto">
                    <dropdown-option position="right" v-show="evaluation.evaluationDate != null || evaluation.status == 'Monitored'">
                        <template v-slot:button>
                            <button
                                class="flex whitespace-nowrap w-full md:w-auto items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg justify-center"
                            >
                                <i class="fas fa-file-pdf text-lg ml-2"></i>
                                <i class="fas fa-caret-down ml-2"></i>
                            </button>
                        </template>
                        <template v-slot:options>
                            <div class="w-64">
                                <div
                                        class="text-gray-600 font-semibold px-5 py-1"
                                    >
                                        Monitoring Report
                                    </div>
                                    <button
                                        @click="previewFileMonitoring"
                                        class="py-1.5 hover:bg-gray-200 w-full text-left indent-8"
                                    >
                                        Preview
                                    </button>
                                    <button
                                        @click="downloadFileMonitoring"
                                        class="py-1.5 hover:bg-gray-200 w-full text-left indent-8"
                                    >
                                        Download
                                    </button>
                            </div>
                        </template>
                    </dropdown-option>
                </div>
            </div>
        </template>
        <template v-slot:main-content>
            <tool-info
                :data="{
                    'HEI Name':
                        evaluation.institution_program?.institution?.name,
                    Program:
                        evaluation.institution_program?.program?.program +
                        (evaluation.institution_program?.program?.major
                            ? ' - ' +
                              evaluation.institution_program?.program?.major
                            : ''),
                    Effectivity: `A.Y. ${evaluation.effectivity}`,
                    CMO: `No.${evaluation.cmo?.number}, S.${evaluation.cmo?.series}`,
                }"
            />
            <tool-progress-display :data="progress" type="HEI"/>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8 p-2 md:px-4">
                <div class="p-4 rounded-lg shadow-md bg-green-100 text-center">
                    <p class="text-3xl font-bold text-green-600">
                        {{ progress[0] }}
                    </p>
                    <p class="text-green-700 font-semibold">Complied</p>
                </div>
                <div class="p-4 rounded-lg shadow-md bg-red-100 text-center">
                    <p class="text-3xl font-bold text-red-600">
                        {{ progress[1] }}
                    </p>
                    <p class="text-red-700 font-semibold">Not Complied</p>
                </div>
                <div class="p-4 rounded-lg shadow-md bg-gray-100 text-center">
                    <p class="text-3xl font-bold text-gray-600">
                        {{ progress[2] }}
                    </p>
                    <p class="text-gray-700 font-semibold">Not Applicable</p>
                </div>
                <div class="p-4 rounded-lg shadow-md bg-blue-100 text-center">
                    <p class="text-3xl font-bold text-blue-600">
                        {{ progress[3] }}%
                    </p>
                    <p class="text-blue-700 font-semibold">Progress</p>
                </div>
            </div>
            <div class="p-3">
                <div
                    class="w-full p-2 md:p-5 md:shadow-lg md:border rounded-xl"
                >
                    <content-table>
                        <template v-slot:table-head>
                            <th>Area/<br />Minimum Requirement</th>
                            <th>
                                Self-Evaluation Status/<br />Actual Situation
                            </th>
                            <th>Evidence</th>
                            <th v-show="showEvaluation">Findings</th>
                            <th v-show="showEvaluation">
                                Comments/<br />Recommendations
                            </th>
                            <th v-show="showEvaluation">Evaluation Status</th>
                        </template>
                        <template v-slot:table-body>
                            <tr v-if="evaluation.item.length == 0">
                                <no-search-result text="items"></no-search-result>
                            </tr>
                            <tr
                                v-else
                                v-for="item in evaluation.item"
                                :key="item.id"
                            >
                                <td class="max-w-[40rem]">
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
                                    v-html="item.findings"
                                ></td>
                                <td
                                    v-show="showEvaluation"
                                    v-html="item.recommendations"
                                ></td>
                                <td v-show="showEvaluation">
                                    <status :text="item.evaluationStatus" />
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
import { ref, reactive } from "vue";
import Layout from "@/Shared/Layout.vue";
defineOptions({ layout: Layout });

const props = defineProps(["evaluation", "progress", "showEvaluation"]);

//Monitoring Report
const submitReport = reactive({
    orientation: "landscape",
    id: ref(props.tool),
});

function previewFileMonitoring() {
    const url = `/report/monitoring/${props.evaluation.id}/${submitReport.orientation}/view`;
    window.open(url, "_blank");
}

function downloadFileMonitoring() {
    const url = `/report/monitoring/${props.evaluation.id}/${submitReport.orientation}/download`;
    window.open(url, "_blank");
}

function previewFileDeficiency() {
    const url = `/report/deficiency/${props.evaluation.id}/${submitReport.orientation}/view`;
    window.open(url, "_blank");
}

function downloadFileDeficiency() {
    const url = `/report/deficiency/${props.evaluation.id}/${submitReport.orientation}/download`;
    window.open(url, "_blank");
}

</script>

<style scoped>
a {
 color: rgb(47, 139, 251)   
}
</style>