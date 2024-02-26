<template>
    <Head title="Program Self-Evaluation" />
    <page-title title="Program Self-Evaluation" />
    <content-container :hasAction="true">
        <template v-slot:channel>
            <div class="flex flex-col w-fit">
                <div class="w-auto flex flex-col md:flex-row">
                    <div class="mr-3 font-bold text-gray-700">HEI Name</div>
                    <div class="bg-blue-200 rounded-full w-fit px-2 py-0">{{ program.institution.name }}</div>
                </div>
                <div class="w-auto flex flex-col md:flex-row md:text-center md:items-center mt-2">
                    <div class="mr-3 font-bold text-gray-700">Program</div>
                    <div class="bg-blue-200 rounded-full w-fit px-2 py-0">{{ program.program.program }}</div>
                </div>
            </div>
        </template>
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
                            <button @click="edit(item.id)" class="border rounded py-1 px-3 border-gray-400"
                                v-show="item.status == 'In progress'">
                                Edit
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