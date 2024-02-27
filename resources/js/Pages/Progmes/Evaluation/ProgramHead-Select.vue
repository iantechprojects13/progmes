<template>
    <Head title="Program Self-Evaluation" />
    <page-title title="Program Self-Evaluation" />
    <div class="md:mx-8 mx-3 mt-7">
        <div class="flex flex-col w-full bg-white rounded p-3 py-5">
            <div class="w-auto flex flex-col md:flex-row">
                <label for="heiName"
                    class="mr-3 h-8 md:h-10 flex items-center justify-start md:justify-end align-middle text-gray-800 w-full md:w-24">HEI
                    Name
                </label>
                <input type="text" id="heiName" class="md:w-1/2 w-full rounded font-bold" disabled
                    :value="program.institution.name">
            </div>
            <div class="w-auto flex flex-col md:flex-row mt-2 md:mt-1">
                <label for="program"
                    class="mr-3 h-8 md:h-10 flex items-center justify-start md:justify-end align-middle text-gray-800 w-full md:w-24">
                    Program
                </label>
                <input type="text" id="program" class="md:w-1/2 w-full rounded font-bold" disabled
                    :value="program.program.program">
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
                            <button @click="edit(item.id)"
                                class="border rounded h-10 w-10 border-gray-400 hover:bg-gray-300 shadow transition-all duration-200 hover:text-xl shadow-gray-500 tooltipForActions"
                                data-tooltip="Edit Evaluation" v-show="item.status == 'In progress'">
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
    router.get('/hei/evaluation/' + tool + '/edit')
}

function createTool(item) {
    router.get('/hei/tool/create/' + item)
}
</script>

<script>
import Layout from '../Shared/Layout.vue';
export default {
    layout: Layout,
}
</script>