<template>
    <Head title="Evaluation" />
    <div class="text-xl font-bold w-full mb-5">Program Self-Evaluation</div>
    <div class="flex flex-col pb-4 mb-4 border-2 rounded border-gray-400 bg-gray-200 p-3">
        <div><b>Institution Name:</b> {{ role.institution.name }} </div>
        <div><b>Program:</b> {{ role.program.program }} </div>
        <div><b>Role:</b> {{ role.role }} </div>
    </div>
    <content-table>
        <template v-slot:table-head>
            <th class="px-2 py-3 border-b border-gray-300">Academic Year</th>
            <th class="px-2 py-3 border-b border-gray-300">Status</th>
        </template>
        <template v-slot:table-body>
            <tr v-for="item in evaluation.evaluation_form" :key="item.id" class="hover:bg-gray-100">
                <td class="px-2 py-3 border-b hover:underline cursor-pointer">
                    <Link :href="route('form.hei.edit', item)">
                    <i class="fas fa-file mr-2"></i>{{ item.effectivity }}
                    </Link>
                </td>
                <td class="px-2 py-3 border-b">
                    <div class="text-white text-xs">
                        <span class="px-2 py-1 bg-red-500 rounded" v-if="item.status === 'Locked'">
                            <i class="fas fa-lock mr-2"></i>{{ item.status }}
                        </span>
                        <span class="px-2 py-1 bg-blue-500 rounded" v-else-if="item.status === 'In progress'">
                            <i class="fas fa-unlock mr-2"></i>{{ item.status }}
                        </span>
                    </div>
                </td>
            </tr>
        </template>
    </content-table>
    <div v-if="$page.props.flash.failed">
        <Notification :message="$page.props.flash.failed" />
    </div>
</template>

<script setup>
defineProps([
    'role',
    'evaluation',
]);
</script>

<script>
import Layout from '../Shared/Layout.vue'
export default {
    layout: Layout,
}
</script>