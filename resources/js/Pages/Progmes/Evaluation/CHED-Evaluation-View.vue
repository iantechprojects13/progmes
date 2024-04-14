<template>

    <Head title="Program Evaluation" />
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
    </div>

    <div class="flex flex-col xl:flex-row items-center justify-between w-auto rounded border border-gray-400 bg-white mx-3 md:mx-8 mt-5 p-3">
        <div class="w-full text-left">
            <div class="w-auto flex flex-row items-center">
                <i class="fas fa-institution mr-3 text-blue-500"></i>
                <div>{{ evaluation_tool.institution_program.institution?.name }}</div>
            </div>
            <div class="w-auto flex flex-row items-center">
                <i class="fas fa-book mr-3 text-blue-500"></i>
                <div>
                    {{ evaluation_tool.institution_program.program?.program }}
                    <span></span>
                </div>
            </div>
            <div class="w-auto flex flex-row items-center">
                <i class="fas fa-calendar-check mr-3 text-blue-500"></i>
                <div>A.Y. {{ evaluation_tool.effectivity }}</div>
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
    <content-container>
        <template v-slot:main-content>
            <content-table>
                <template v-slot:table-head>
                    <th class="p-3">Area/<br>Minimum Requirement</th>
                    <th class="p-3">Actual Situation</th>
                    <th class="p-3">Self-Evaluation Status</th>
                    <th class="p-3">Evidence</th>
                    <th class="p-3" v-show="showEvaluation">Findings</th>
                    <th class="p-3" v-show="showEvaluation">Comments/Recommendations</th>
                    <th class="p-3" v-show="showEvaluation">Evaluation Status</th>
                </template>
                <template v-slot:table-body>
                    <tr v-if="evaluation_tool.item.length == 0">
                        <td colspan="5">No Data</td>
                    </tr>
                    <tr v-else v-for="(item, index) in evaluation_tool.item" :key="item.id"
                        class="border-b border-gray-400 align-top" :class="{'bg-slate-200': index % 2 == 0 }">
                        <td class="p-3 whitespace-normal text-justify max-w-xl">
                            <div class="font-bold">{{ item.criteria.area }}</div>
                            <div>{{ item.criteria.minimumRequirement }}</div>
                        </td>
                        <td class="p-3 whitespace-normal text-justify max-w-xl">{{ item.actualSituation }}</td>
                        <td class="p-3">{{ item.selfEvaluationStatus }}</td>
                        <td class="p-3">
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
                        </td>
                        <td v-show="showEvaluation" class="p-3 whitespace-normal">
                            {{ item.findings }}
                        </td>
                        <td v-show="showEvaluation" class="p-3 whitespace-normal">
                            {{ item.recommendations }}
                        </td>
                        <td v-show="showEvaluation" class="p-3">
                            <div class="px-1 py-0.5 w-fit rounded text-white"
                            :class="[{'bg-blue-600' : item.evaluationStatus == 'Complied'}]"
                            >{{ item.evaluationStatus }}</div>
                        </td>
                    </tr>
                </template>
            </content-table>
        </template>
    </content-container>
</template>

<script setup>
    defineProps([
        'evaluation_tool',
        'progress',
        'showEvaluation',
    ])
</script>

<script>
    import Layout from '../Shared/Layout.vue';
    export default {
        layout: Layout,
    }
</script>