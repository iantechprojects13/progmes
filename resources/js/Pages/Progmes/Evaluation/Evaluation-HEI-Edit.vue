<template>
    <Head title="Evaluation Form" />
    <div class="text-xl font-bold w-full mb-5 p-5">Program Self-Evaluation</div>
    <content-container placeholder="Search form">
        <template v-slot:channel>
            <Link :href="route('evaluation')" class="py-2 px-4 bg-gray-100 rounded-2xl hover:bg-gray-200">
            <i class="fas fa-arrow-left mr-2"></i>{{ evaluation.effectivity }}
            </Link>
        </template>
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
        </template>
        <template v-slot:content>
            <content-table v-show="itemsLayout === 'list'">
                <template v-slot:table-head>
                    <th class="max-w-8">Item</th>
                    <th class="px-3 py-2 max-w-10">Area</th>
                    <th class="px-3 py-2 max-w-16 ">Minimum Requirement</th>
                    <th class="px-3 py-2 min-w-16">Actual Situation</th>
                    <th class="px-3 py-2 min-w-16 max-w-18">Evidence</th>
                    <th class="px-3 py-2 w-32 whitespace-normal">Self-Evaluation Status</th>
                    <th class="px-3 py-2 text-right"><i class="fas fa-ellipsis-v"></i></th>
                </template>
                <template v-slot:table-body>
                    <tr v-for="(item, index) in items" :key="item.id" class="border-b-2 border-gray-300">
                        <td class="px-3 py-4 max-w-6 font-bold">
                            {{ item.criteria.itemNo }}
                        </td>
                        <td class="px-3 py-4 whitespace-normal max-w-10">
                            {{ item.criteria.area }}
                        </td>
                        <td class="px-3 py-4 whitespace-normal text-justify max-w-16">{{
                            item.criteria.minimumRequirement }}
                        </td>
                        <td class="h-32 w-72 px-2 py-4">
                            <textarea v-model="items[index].actualSituation"
                                class="w-full min-w-16 h-full text-sm rounded border-gray-300 resize-none"
                                placeholder="Input actual situation here">
                            </textarea>
                        </td>
                        <td class="h-32 min-w-12 max-w-16 px-2 py-3">
                            <div class="h-full text-sm relative flex">
                                <div class="h-full w-full py-4 px-2 pb-12 text-ellipsis flex flex-col justify-center">
                                    <div class="overflow-hidden text-ellipsis">Link:
                                        <span class="underline">linktopath.org/linktoevidencepathrandomlink</span>
                                    </div>
                                    <div class="overflow-hidden text-ellipsis">File:
                                        <span class="underline">filename.pdf</span>
                                    </div>
                                    <div v-if="index === 1" class="overflow-hidden text-ellipsis">File:
                                        <span class="underline">filename.pdf</span>
                                    </div>
                                    <div v-if="index === 1" class="overflow-hidden text-ellipsis">File:
                                        <span class="underline">filename.pdf</span>
                                    </div>
                                </div>
                                <div class="h-10 pt-1 px-1 absolute bottom-0 w-full flex-row items-center bg-gray-100">
                                    <button
                                        class="py-1 px-2 rounded hover:text-white border border-gray-400 hover:bg-blue-600 my-0.5 mx-0.5">
                                        <i class="fas fa-upload mr-2"></i>Link
                                    </button>
                                    <button
                                        class="py-1 px-2 rounded hover:text-white border border-gray-400 hover:bg-blue-600 my-0.5 mx-0.5">
                                        <i class="fas fa-upload mr-2"></i>File
                                    </button>
                                </div>
                            </div>
                        </td>
                        <td class="h-32 px-2 py-4">
                            <div class="w-full h-full text-sm flex items-end">
                                <select v-model="items[index].selfEvaluationStatus"
                                    class="p-1 text-sm rounded w-32 border-gray-400">
                                    <option value="Complied">Complied</option>
                                    <option value="Not complied">Not complied</option>
                                    <option value="Not applicable">Not applicable</option>
                                </select>
                            </div>
                        </td>
                        <td class="h-32 py-4">
                            <div class="w-full h-full text-sm flex items-end">
                                <button class="py-1 px-2 rounded hover:text-white border border-gray-400 hover:bg-green-600"
                                    @click="submit(item)" preserve-state>
                                    <i class="fas fa-save mr-2"></i>Save
                                </button>
                            </div>
                        </td>
                    </tr>
                </template>
            </content-table>


            <!-- ------ grid view ------- -->


            <div class="flex flex-col max-w-5xl mx-auto px-3 p-4" v-show="itemsLayout === 'grid'">
                <div v-for="(item, index) in items" :key="item.id"
                    class="flex flex-col mb-5 p-2 m-2 bg-gray-200 rounded shadow-lg">
                    <div class="font-bold bg-gray-100 w-fit rounded-t p-2">
                        #{{ item.criteria.itemNo }} {{ item.criteria.area }}
                    </div>
                    <div class="bg-gray-100 p-2 px-3 rounded-b">
                        {{ item.criteria.minimumRequirement }}
                    </div>
                    <div class="bg-gray-200 p-0.5 pt-2">
                        <textarea v-model="items[index].actualSituation" class="w-full h-24 border-none text-sm"
                            placeholder="Input actual situation here"></textarea>
                    </div>
                    <div class="w-full py-2 px-1">
                        <select class="text-sm py-1 px-2 ml-1 rounded w-36 border-gray-300 bg-gray-100">
                            <option value="">Not complied</option>
                            <option value="Complied">Complied</option>
                            <option value="Not Applicable">Not applicable</option>
                        </select>
                        <button class="text-sm py-1 px-2 hover:bg-blue-600 bg-blue-500 text-white rounded">
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

    mounted() {
        console.log('mounted');
    }
}
</script>