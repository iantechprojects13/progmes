<template>
    <Head title="Program List" />
    <AdminPanel />
    <content-container placeholder="Search program" :data_list="program_list">
        <template v-slot:channel>
            <button class="text-blue-500 underline font-bold mr-5">List</button>
            <Link :href="route('admin.program.create')">
            <button class="text-gray-500 hover:text-gray-800">Create</button>
            </Link>
        </template>
        <template v-slot:content>
            <content-table>
                <template v-slot:table-head>
                    <th scope="col" class="px-2 py-2">
                        Program
                    </th>
                    <th scope="col" class="px-2 py-2">
                        Discipline
                    </th>
                    <th scope="col" class="px-2 py-2">
                        Major
                    </th>
                    <th scope="col" class="px-3 py-2 text-right">
                        <i class="fas fa-ellipsis-v"></i>
                    </th>
                </template>
                <template v-slot:table-body>
                    <tr v-for="program in program_list?.data" :key="program.id" class="border-b border-gray-400">
                        <th scope="row" class="p-3">
                            {{ program.program }}
                        </th>
                        <td class="p-3">
                            {{ program.discipline }}
                        </td>
                        <td class="p-3">
                            {{ program.major }}
                        </td>
                    </tr>
                </template>
            </content-table>
        </template>
    </content-container>
    <div v-if="$page.props.flash.success">
        <Notification :message="this.$page.props.flash.success" />
    </div>
</template>

<script setup>


const props = defineProps([
    'program_list',
]);
</script>

<script>
import Layout from '../Shared/Layout.vue';
export default {
    layout: Layout,
}
</script>