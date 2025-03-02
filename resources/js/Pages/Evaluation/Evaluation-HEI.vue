<template>
    <Head title="Evaluation" />
    <div class="text-xl font-bold w-full px-7 pt-5">Program Self-Evaluation</div>

    <content-container>
        <template v-slot:channel>
            <div class="flex flex-col">
                <div><b>Institution Name:</b> {{ role.institution.name }} </div>
                <div><b>Program:</b> {{ role.program.program }} </div>
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
                    <tr v-for="item in evaluation.evaluation_form" :key="item.id"
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
</template>

<script setup>
import { router } from '@inertiajs/vue3';

defineProps([
    'role',
    'evaluation',
]);

function edit(tool) {
    router.get('/hei/evaluation/' + tool + '/edit')
}

function createTool(item) {
    router.get('/hei/tool/create/' + item)
}
</script>

<script>
import Layout from '@/Shared/Layout.vue'
export default {
    layout: Layout,
}
</script>