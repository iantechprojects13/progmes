<template>
    <Head title="Program Self-Evaluation" />
    <page-title title="Program Self-Evaluation" />
    <content-container :hasBackButton="true">
        <template v-slot:content-title>
            <div class="w-full flex flex-col md:flex-row text-left items-center justify-between">
                <div class="font-bold w-full">Self-Evaluation Tool</div>
                <div class="w-full md:w-auto">
                    <dropdown-option position="right" v-show="evaluation_tool.evaluationDate != null">
                        <template v-slot:button>
                            <button
                                class="flex whitespace-nowrap w-full md:w-auto items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg justify-center"
                            >
                                Monitoring Report
                                <i class="fas fa-caret-down ml-2"></i>
                            </button>
                        </template>
                        <template v-slot:options>
                            <div class="w-48">
                                <button @click="previewFile"
                                    class="py-1.5 hover:bg-gray-200 w-full text-left indent-7"
                                >
                                    Preview
                                </button>
                                <button @click="downloadFile"
                                    class="py-1.5 hover:bg-gray-200 w-full text-left indent-7"
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
            <div class="md:p-3">
                <div
                    class="w-full p-2 md:p-5 my-3 md:shadow-lg md:border rounded-xl"
                >
                    <div class="flex flex-col gap-6">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div
                                class="flex items-center p-4 bg-gray-100 rounded-lg hover:bg-gray-200 transition-all"
                            >
                                <div class="w-full">
                                    <p class="text-sm text-gray-500">
                                        HEI Name
                                    </p>
                                    <div>
                                        {{
                                            evaluation_tool.institution_program
                                                .institution?.name
                                        }}
                                    </div>
                                </div>
                            </div>

                            <div
                                class="flex items-center p-4 bg-gray-100 rounded-lg hover:bg-gray-200 transition-all"
                            >
                                <div class="w-full">
                                    <p class="text-sm text-gray-500">Program</p>
                                    {{
                                        evaluation_tool.institution_program
                                            .program?.program
                                    }}
                                    <span
                                        v-if="
                                            evaluation_tool.institution_program
                                                .program?.major
                                        "
                                    >
                                        -
                                        {{
                                            evaluation_tool.institution_program
                                                .program?.major
                                        }}</span
                                    >
                                </div>
                            </div>
                        </div>

                        <div class="grid md:grid-cols-2 gap-6">
                            <div
                                class="flex items-center p-4 bg-gray-100 rounded-lg hover:bg-gray-200 transition-all"
                            >
                                <div class="w-full">
                                    <p class="text-sm text-gray-500">
                                        Effectivity
                                    </p>
                                    <div>
                                        AY: {{ evaluation_tool.effectivity }}
                                    </div>
                                </div>
                            </div>
                            <div
                                class="flex items-center p-4 bg-gray-100 rounded-lg hover:bg-gray-200 transition-all"
                            >
                                <div class="w-full">
                                    <p class="text-sm text-gray-500">
                                        CMO
                                    </p>
                                    <div>No.{{ evaluation_tool.cmo?.number }}, S.{{ evaluation_tool.cmo?.series }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
                            <tr v-if="evaluation_tool.item.length == 0">
                                <no-search-result text="items"/>
                            </tr>
                            <tr
                                v-else
                                v-for="item in evaluation_tool.item"
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
                                    <div v-html="item.recommendations">

                                    </div>
                                </td>
                                <td
                                    v-show="showEvaluation"
                                >
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
import { ref, reactive } from 'vue';
import Layout from '@/Shared/Layout.vue';
defineOptions({ layout: Layout });
const props = defineProps(["evaluation_tool", "progress", "showEvaluation"]);

const submitReport = reactive({
    orientation: "landscape",
    id: ref(props.tool),
});

function previewFile() {
    const url = `/report/monitoring/${props.evaluation_tool.id}/${submitReport.orientation}/view`;
    window.open(url, "_blank");
}

function downloadFile() {
    const url = `/report/monitoring/${props.evaluation_tool.id}/${submitReport.orientation}/download`;
    window.open(url, "_blank");
}
</script>
