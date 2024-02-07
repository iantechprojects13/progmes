<template>
    <Head title="Evaluation" />
    <div class="text-xl font-bold w-full mb-5">Program Evaluation</div>
    <div class="flex flex-col pb-4 mb-4 border-2 rounded border-gray-400 bg-gray-200 p-3">
        <div><b>Role:</b> {{ role[0].role }} </div>
    </div>
    <content-table>
        <template v-slot:table-head>
            <th class="px-2 py-3 border-b border-gray-300">Discipline</th>
        </template>
        <template v-slot:table-body>
            <tr v-for="item in role" :key="item.id" class="hover:bg-gray-100">
                <td class="px-2 py-3 border-b hover:underline cursor-pointer">
                    <!-- <Link :href="route('form.hei.edit', item)"> -->
                    <i class="fas fa-file mr-2"></i>{{ item.discipline.discipline }}
                    <!-- </Link> -->
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
]);
</script>

<script>
import Layout from '../Shared/Layout.vue'
export default {
    layout: Layout,
}
</script>