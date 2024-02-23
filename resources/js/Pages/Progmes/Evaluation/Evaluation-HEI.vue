<template>
    <Head title="Evaluation" />
    <div class="text-xl font-bold w-full mb-5 px-7 py-5">Program Self-Evaluation</div>
    <div class="flex flex-col pb-4 mx-7 mb-4 border-2 rounded border-gray-400 bg-gray-200 p-3">
        <div><b>Institution Name:</b> {{ role.institution.name }} </div>
        <div><b>Program:</b> {{ role.program.program }} </div>
        <div><b>Role:</b> {{ role.role }} </div>
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
                    <tr v-for="item in evaluation.evaluation_form" :key="item.id"
                        class="hover:bg-gray-100 border-b border-gray-300">
                        <td class="p-3">
                            <i class="fas fa-file mr-2"></i>{{ item.effectivity }}
                        </td>
                        <td class="p-3">
                            <div class="text-white text-xs">
                                <span class="px-2 py-1 bg-blue-500 rounded" v-show="item.status === 'deployed'">
                                    Deployed
                                </span>
                                <span class="px-2 py-1 bg-green-600 rounded" v-show="item.status === 'In progress'">
                                    In-progress
                                </span>
                                <span class="px-2 py-1 bg-red-500 rounded" v-show="item.status === 'locked'">
                                    Locked
                                </span>
                            </div>
                        </td>
                        <td class="p-3 text-right">
                            <button class="border rounded py-1 px-3 border-gray-400 text-white bg-green-600"
                                @click="createTool(item.id)" v-show="item.status === 'deployed'">
                                Start now
                            </button>
                            <button class="border rounded py-1 px-3 border-gray-400" v-show="item.status == 'In progress'">
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
    <div v-if="$page.props.flash.failed">
        <Notification :message="$page.props.flash.failed" />
    </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3';

defineProps([
    'role',
    'evaluation',
]);


function createTool(item) {
    router.get('/hei/tool/create/' + item)
}
</script>

<script>
import Layout from '../Shared/Layout.vue'
export default {
    layout: Layout,
}
</script>