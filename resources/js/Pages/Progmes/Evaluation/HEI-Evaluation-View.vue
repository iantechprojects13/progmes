<template>

    <Head title="Program Self-Evaluation" />
    <page-title title="Program Self-Evaluation" />
    <div class="md:mx-8 mx-3 mt-8 flex flex-col lg:flex-row justify-between rounded bg-white p-8">
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
    <content-container hasBackButton="true">
        <template v-slot:back-button>
            <button class="h-10 w-10 rounded-full hover:bg-gray-300"><i class="fas fa-arrow-left"></i></button>
        </template>
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
                    <tr v-if="evaluation_items.length == 0">
                        <td colspan="5">No Data</td>
                    </tr>
                    <tr v-else v-for="(item, index) in evaluation_items" :key="item.id"
                        class="border-b border-gray-400 align-top" :class="{'bg-slate-200': index % 2 == 0 }">
                        <td class="p-3 whitespace-normal">{{ item.criteria.area }}</td>
                        <td class="p-3 whitespace-normal text-justify max-w-xl">{{ item.criteria.minimumRequirement }}
                        </td>
                        <td class="p-3 whitespace-normal text-justify max-w-xl">{{ item.actualSituation }}</td>
                        <td class="p-3">{{ item.selfEvaluationStatus }}</td>
                        <td class="p-3">
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
        'evaluation_items',
    ])
</script>

<script>
    import Layout from '../Shared/Layout.vue';
    export default {
        layout: Layout,
    }
</script>