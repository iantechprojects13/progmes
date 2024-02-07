<template>
    <Head title="CHED Memorandum Order List" />
    <AdminPanel />
    <content-container placeholder="Search CMO" :data_list="cmo_list">
        <template v-slot:channel>
            <button class="text-blue-500 font-bold underline mr-5">List</button>
            <Link :href="route('admin.cmo.create')">
            <button class="text-gray-500 hover:text-gray-800">Create</button>
            </Link>
        </template>
        <template v-slot:content>
            <content-table>
                <template v-slot:table-head>
                    <th class="scope-col p-3 pl-0">CHED Memorandum Order</th>
                    <th class="scope-col p-3">Version</th>
                    <th class="scope-col p-3">Discipline</th>
                    <th class="scope-col p-3">Program</th>
                </template>
                <template v-slot:table-body>
                    <tr v-for="item in cmo_list.data" :key="item.id" class="border-b border-gray-300">
                        <Link :href="route('admin.cmo.show', [item])" class="hover:bg-gray-300 bg-gray-200 p-1 rounded">
                        <th scope="row" class="p-3">
                            <i class="fas fa-file mr-3"></i>
                            No.{{ item.number }} Series of {{
                                item.series
                            }}
                        </th>
                        </Link>
                        <td class="p-3">{{ item.version }}</td>
                        <td class="p-3">{{ item.discipline }}</td>
                        <td class="p-3">{{ item.program }}</td>
                    </tr>
                    <tr v-show="cmo_list.data.length === 0">
                        <td colspan="4" class="h-24 text-center">No data</td>
                    </tr>
                </template>
            </content-table>
        </template>
    </content-container>
</template>

<script setup>
defineProps([
    'cmo_list',
]);
</script>

<script>
import Layout from '../Shared/Layout.vue'
export default {
    layout: Layout,
}
</script>

