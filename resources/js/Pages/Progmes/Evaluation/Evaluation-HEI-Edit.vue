<template>
    <Head title="Evaluation Form" />
    <page-title title="Program Self-Evaluation" />
    <content-container :hasAction="true">
        <template v-slot:actions>
            <div class="border h-10 flex flex-row items-center px-2">
                <button class="p-1 px-2 border border-gray-400" :class="{ 'bg-blue-200': itemsLayout == 'list' }"
                    @click="listLayout">
                    <i class="fas fa-list"></i>
                </button>
                <button class="p-1 px-2 border border-gray-400" :class="{ 'bg-blue-200': itemsLayout == 'grid' }"
                    @click="gridLayout">
                    <i class="fas fa-th-large"></i>
                </button>
            </div>
            <div>
                Save
            </div>
        </template>
        <template v-slot:content-head>
            <div class="px-3 bg-white">
                <div class="w-full p-3 bg-white flex flex-col lg:flex-row border-b border-t px-5">
                    <div class="flex flex-col w-fit">
                        <div class="w-auto flex flex-col md:flex-row">
                            <div class="mr-3 font-bold text-gray-700">HEI Name</div>
                            <div class="bg-stone-500 text-white rounded-full w-fit px-4 lg:px-2 py-0">{{
                                evaluation.institution_program.institution.name
                            }}</div>
                        </div>
                        <div class="w-auto flex flex-col md:flex-row md:text-center md:items-center mt-2">
                            <div class="mr-3 font-bold text-gray-700">Program</div>
                            <div class="bg-stone-500 text-white rounded-full w-fit px-4 lg:px-2 py-0">{{
                                evaluation.institution_program.program.program
                            }}</div>
                        </div>
                        <div class="w-auto flex flex-col md:flex-row md:text-center md:items-center mt-2">
                            <div class="mr-3 font-bold text-gray-700">Effectivity</div>
                            <div class="bg-stone-500 text-white rounded-full w-fit px-4 lg:px-2 py-0">{{
                                evaluation.effectivity
                            }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
        <template v-slot:content>
            <content-table v-show="itemsLayout === 'list'">
                <template v-slot:table-head>
                    <th class="p-3 max-w-8">Item</th>
                    <th class="p-3 max-w-xs">Area</th>
                    <th class="p-3 max-w-xs">Minimum Requirement</th>
                    <th class="p-3 min-w-16">Actual Situation</th>
                    <th class="p-3 min-w-16 max-w-18">Evidence</th>
                    <th class="p-3 w-48 whitespace-normal">Self-Evaluation Status</th>
                </template>
                <template v-slot:table-body>
                    <tr v-for="( item, index ) in  items " :key="item.id" class="align-top"
                        :class="[{ 'bg-slate-200': index % 2 == 0 }, { 'bg-red-200': items[index].selfEvaluationStatus == 'Not applicable' }]">
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
                        <td class="h-32 max-w-lg p-3 relative group ">
                            <textarea v-model="items[index].actualSituation"
                                :disabled="items[index].selfEvaluationStatus == 'Not applicable'"
                                class="w-full min-w-16 h-full  rounded border-gray-300 resize-none group"
                                placeholder="Input actual situation here">
                            </textarea>
                            <button
                                class="absolute bottom-4 right-7 text-gray-500 hover:text-black group-focus-within:visible invisible tooltipForActions"
                                data-tooltip="Open editor">
                                <i class="fas fa-pen"></i>
                            </button>
                        </td>
                        <td class="h-auto min-w-12 max-w-16 p-3">
                            <div class="h-full">
                                <div class="h-10 pt-1 px-1 w-full text-right">
                                    <button
                                        class="h-8 w-10 shadow shadow-gray-500 hover:bg-gray-300 rounded border border-gray-500 bg-white tooltipForActions"
                                        data-tooltip="Upload file"
                                        :disabled="items[index].selfEvaluationStatus == 'Not applicable'">
                                        <i class="fas fa-file-upload"></i>
                                    </button>
                                    <button
                                        class="ml-1 h-8 w-10 shadow shadow-gray-500 hover:bg-gray-300 rounded border border-gray-500 bg-white tooltipForActions"
                                        data-tooltip="Link"
                                        :disabled="items[index].selfEvaluationStatus == 'Not applicable'">
                                        <i class="fas fa-link"></i>
                                    </button>
                                </div>
                                <div class="h-full w-full py-4 px-2 pb-12 text-ellipsis flex flex-col justify-center">
                                    <div class="overflow-hidden text-ellipsis">Link:
                                        <span class="underline">linktopath.org/linktoevidencepathrandomlink</span>
                                    </div>
                                    <div class="overflow-hidden text-ellipsis">File:
                                        <span class="underline">filename.pdf</span>
                                    </div>
                                    <div v-if="index % 3 == 1" class="overflow-hidden text-ellipsis">File:
                                        <span class="underline">filename.pdf</span>
                                    </div>
                                </div>

                            </div>
                        </td>
                        <td class="h-32 p-3">
                            <div class="w-full h-full">
                                <select v-model="items[index].selfEvaluationStatus"
                                    class="p-1  rounded w-full border-gray-400">
                                    <option value="Complied">Complied</option>
                                    <option value="Not complied">Not complied</option>
                                    <option value="Not applicable">Not applicable</option>
                                </select>
                            </div>
                        </td>
                    </tr>
                </template>
            </content-table>


            <!-- ------ grid view ------- -->


            <div class="flex flex-col max-w-5xl mx-auto px-3 p-4" v-show="itemsLayout === 'grid'">
                <div v-for="( item, index ) in  items " :key="item.id"
                    class="flex flex-col mb-5 p-2 m-2 bg-gray-200 rounded shadow-lg">
                    <div class="font-bold bg-gray-100 w-fit rounded-t p-2">
                        #{{ item.criteria.itemNo }} {{ item.criteria.area }}
                    </div>
                    <div class="bg-gray-100 p-2 px-3 rounded-b">
                        {{ item.criteria.minimumRequirement }}
                    </div>
                    <div class="bg-gray-200 p-0.5 pt-2">
                        <textarea v-model="items[index].actualSituation" class="w-full h-24 border-none "
                            placeholder="Input actual situation here"></textarea>
                    </div>
                    <div class="w-full py-2 px-1">
                        <select class=" py-1 px-2 ml-1 rounded w-36 border-gray-300 bg-gray-100">
                            <option value="">Not complied</option>
                            <option value="Complied">Complied</option>
                            <option value="Not Applicable">Not applicable</option>
                        </select>
                        <button class=" py-1 px-2 hover:bg-blue-600 bg-blue-500 text-white rounded">
                            <i class="fas fa-upload mr-2"></i>
                            Upload evidence
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </content-container>
    <div v-if="$page.props.flash.success">
        <notification :message="$page.props.flash.success" />
    </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3';

defineProps([
    'evaluation',
    'items',
]);


function submit(item) {
    router.post('/hei/evaluation/update', item);
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
        }
    },

}
</script>