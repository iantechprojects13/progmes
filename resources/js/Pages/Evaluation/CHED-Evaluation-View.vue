<template>

    <Head title="Program Evaluation" />
    <page-title title="Program Evaluation" />
    <content-container :hasTopMainContent="true">
        <template v-slot:content-title>
            <div class="w-full flex flex-col md:flex-row justify-between items-center">
                <div class="w-full flex flex-row items-center">
                    <div class="w-full flex flex-row items-center">
                        <Link :href="evaluation_tool.status != 'Monitored' ? '/evaluation' : '/ched/evaluation/monitored'">
                        <button class="w-8 h-8 rounded-full hover:bg-gray-200 tooltipForActions" data-tooltip="Back"><i class="fas fa-arrow-left"></i></button>
                        </Link>
                        <div class="ml-3 font-bold">Evaluate</div>
                    </div>
                </div>
            </div>
        </template>
        <template v-slot:top-main-content>
            <div class="flex flex-col xl:flex-row items-center justify-between w-auto rounded border border-gray-300 bg-white my-3 p-5">
                <div class="w-full text-left">
                    <div class="w-auto flex flex-row items-center">
                        <i class="fas fa-institution mr-3 text-blue-500"></i>
                        <div>{{ evaluation_tool.institution_program.institution?.name }}</div>
                    </div>
                    <div class="w-auto flex flex-row items-center">
                        <i class="fas fa-book mr-3 text-blue-500"></i>
                        <div>
                            {{ evaluation_tool.institution_program.program?.program }}
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
        </template>
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
                        class="hover:bg-gray-200 align-top" :class="{'bg-gray-100': index % 2 == 1 }">
                        <td class="p-3 whitespace-normal text-justify max-w-xl">
                            <div class="font-bold">{{ item.criteria.area }}</div>
                            <div v-html="item.criteria.minimumRequirement"></div>
                        </td>
                        <td class="p-3 whitespace-normal text-justify max-w-xl" v-html="item.actualSituation"></td>
                        <td class="p-3" v-html="item.selfEvaluationStatus"></td>
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
                        <td v-show="showEvaluation" class="p-3 whitespace-normal" v-html="item.findings">
                        </td>
                        <td v-show="showEvaluation" class="p-3 whitespace-normal" v-html="item.recommendations">
                        </td>
                        <td v-show="showEvaluation" class="p-3">
                            <div class="px-1 py-0.5 w-fit rounded text-white"
                                :class="[{ 'bg-blue-500': item.evaluationStatus == 'Complied' },
                                {'bg-red-500' : item.evaluationStatus == 'Not complied'},
                                {'bg-gray-600' :item.evaluationStatus == 'Not applicable'},
                                ]"
                                >{{ item.evaluationStatus }}
                            </div>
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
    import Layout from '@/Shared/Layout.vue';
    export default {
        layout: Layout,
    }
</script>