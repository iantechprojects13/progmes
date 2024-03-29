<template>

    <Head title="View Program Self-Evaluation" />
    <page-title title="View Program Self-Evaluation" />
    <div class="md:mx-8 mx-3 mt-8 flex flex-col lg:flex-row justify-between rounded relative">
        <div>
            <Link href="/evaluation">
            <button class="w-24 h-10 border border-gray-500 rounded bg-white">
                <i class="fas fa-arrow-left mr-2"></i>
                Back
            </button>
            </Link>
        </div>
        <div class="flex flex-wrap mt-3 lg:mt-0">
            <div class=" flex-col lg:mt-0 mt-2 text-center mx-1 p-2 px-4 rounded border border-gray-500 bg-white">
                <div class="w-full font-bold text-blue-500">{{ progress[0] }}</div>
                <div class="font-bold text-gray-700 text-sm">Complied</div>
            </div>
            <div class=" flex-col lg:mt-0 mt-2 text-center mx-1 p-2 px-4 rounded border border-gray-500 bg-white">
                <div class="w-full font-bold text-blue-500">{{ progress[1] }}</div>
                <div class="font-bold text-gray-700 text-sm">Not Complied</div>
            </div>
            <div class=" flex-col lg:mt-0 mt-2 text-center mx-1 p-2 px-4 rounded border border-gray-500 bg-white">
                <div class="w-full font-bold text-blue-500">{{ progress[2] }}</div>
                <div class="font-bold text-gray-700 text-sm">Not Applicable</div>
            </div>
            <div class=" flex-col lg:mt-0 mt-2 text-center mx-1 p-2 px-4 rounded border border-gray-500 bg-white">
                <div class="w-full font-bold text-blue-500">{{ progress[3] }}%</div>
                <div class="font-bold text-gray-700 text-sm">Progress</div>
            </div>
        </div>
    </div>
    <div
        class="md:mx-8 mx-3 mt-5 flex flex-col lg:flex-row justify-between rounded bg-white p-8 border border-gray-400">
        <div class="flex flex-col w-auto rounded">
            <div class="w-auto flex flex-col md:flex-row">
                <div class="mr-2 font-bold">
                    HEI Name:
                </div>
                <div>
                    {{ evaluation_tool.institution_program.institution?.name }}
                </div>
            </div>
            <div class="w-auto flex flex-col md:flex-row mt-2 md:mt-1">
                <div class="w-auto flex flex-col md:flex-row">
                    <div class="mr-2 font-bold">
                        Program:
                    </div>
                    <div>
                        {{ evaluation_tool.institution_program.program?.program }}
                    </div>
                </div>
            </div>
            <div class="w-auto flex flex-col md:flex-row mt-2 md:mt-1">
                <div class="w-auto flex flex-col md:flex-row">
                    <div class="mr-2 font-bold">
                        Effectivity:
                    </div>
                    <div>
                        A.Y. {{ evaluation_tool.effectivity }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <content-container>
        <template v-slot:main-content>
            <content-table>
                <template v-slot:table-head>
                    <th class="p-3">Area</th>
                    <th class="p-3">Minimum Requirement</th>
                    <th class="p-3">Actual Situation</th>
                    <th class="p-3">Self-Evaluation Status</th>
                    <th class="p-3">Evidence</th>
                </template>
                <template v-slot:table-body>
                    <tr v-if="evaluation_tool.item.length == 0">
                        <td colspan="5">No Data</td>
                    </tr>
                    <tr v-else v-for="(item, index) in evaluation_tool.item" :key="item.id"
                        class="border-b border-gray-400 align-top" :class="{'bg-slate-200': index % 2 == 0 }">
                        <td class="p-3 whitespace-normal">{{ item.criteria.area }}</td>
                        <td class="p-3 whitespace-normal text-justify max-w-xl">{{ item.criteria.minimumRequirement }}
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
    ])
</script>

<script>
    import Layout from '../Shared/Layout.vue';
    export default {
        layout: Layout,
    }
</script>