<template>
    <Head title="Program Self-Evaluation" />
    <page-title title="Program Self-Evaluation" />
    <div class="md:mx-8 mx-3 mt-8">
        <div class="flex flex-col w-full rounded">
            <div class="w-auto flex flex-col md:flex-row">
                <div class="mr-2 font-bold">
                    HEI Name:
                </div>
                <div>
                    {{ program.institution.name }}
                </div>
            </div>
            <div class="w-auto flex flex-col md:flex-row mt-2 md:mt-1">
                <div class="w-auto flex flex-col md:flex-row">
                    <div class="mr-2 font-bold">
                        Program:
                    </div>
                    <div>
                        {{ program.program.program }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <content-container>
        <template v-slot:content>
            <content-table>
                <template v-slot:table-head>
                    <th class="p-3 border-b border-gray-400">Academic Year</th>
                    <th class="p-3 border-b border-gray-400">Status</th>
                    <th class="p-3 border-b border-gray-400 text-right">
                        <i class="fas fa-ellipsis-v"></i>
                    </th>
                </template>
                <template v-slot:table-body>
                    <tr v-for="item in program.evaluation_form" :key="item.id"
                        class="hover:bg-gray-100 border-b border-gray-300">
                        <td class="p-3 align-middle">
                            <i class="fas fa-file mr-2"></i>{{ item.effectivity }}
                        </td>
                        <td class="p-3 align-middle">
                            <div class="text-white text-sm">
                                <span class="p-1 bg-blue-500 rounded" v-show="item.status === 'deployed'">
                                    Deployed
                                </span>
                                <span class="p-1 bg-green-600 rounded" v-show="item.status === 'In progress'">
                                    In-progress
                                </span>
                                <span class="p-1 bg-red-500 rounded" v-show="item.status === 'locked'">
                                    Locked
                                </span>
                            </div>
                        </td>
                        <td class="p-3 text-right">
                            <button class="border rounded py-1 px-3 border-gray-400 text-white bg-green-600"
                                @click="createTool(item.id)" v-show="item.status === 'deployed'">
                                Start now
                            </button>
                            <button @click="edit(item.id)" ref="editButton"
                                class="border rounded h-10 w-10 border-gray-400 hover:bg-gray-50 shadow text-gray-600 hover:text-black shadow-gray-500 tooltipForActions"
                                data-tooltip="Edit evaluation">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="border rounded py-1 px-3 border-gray-400" v-show="item.status == 'locked'">
                                View
                            </button>
                        </td>
                    </tr>
                </template>
            </content-table>
        </template>
    </content-container>
    <Notification :message="$page.props.flash.failed" />
</template>

<script setup>
import { router } from '@inertiajs/vue3';

defineProps([
    'program',
]);

function edit(tool) {
    router.get('/hei/evaluation/' + tool);
}

function createTool(item) {
    router.get('/hei/tool/create/' + item);
}
</script>

<script>
import Layout from '../Shared/Layout.vue';
export default {
    layout: Layout,
}
</script>