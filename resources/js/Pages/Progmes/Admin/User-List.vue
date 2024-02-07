<template>
    <Head title="Users List" />
    <AdminPanel />
    <content-container placeholder="Search User" :data_list="user_list">
        <template v-slot:channel>
            <button class="text-blue-500 font-bold underline mr-5">Active</button>
            <Link :href="route('admin.users.request')">
            <button class="text-gray-500 hover:text-gray-800">Request</button>
            </Link>
        </template>
        <template v-slot:content>
            <content-table>
                <template v-slot:table-head>
                    <th class="scope-col p-3 pl-0">Name</th>
                    <th class="scope-col p-3">Account Type</th>
                    <th class="scope-col p-3">Role</th>
                </template>
                <template v-slot:table-body>
                    <tr v-for="user in user_list.data" :key="user.id" class="border-b border-gray-300 hover:bg-stone-100">
                        <th scope="row" class="px-2 py-2 whitespace-nowrap flex items-center pr-10">
                            <img v-if="user.avatar" :src="user.avatar" width="35" class="rounded-full mr-3">
                            <img v-else src="/assets/user.png" width="35" class="rounded-full mr-3">
                            <div class="text-gray-600 items-center">
                                <span class="text-left block">{{ user.name }}</span>
                                <span class="font-normal">{{ user.email }}</span>
                            </div>
                        </th>
                        <td class="p-3">{{ user.type }}</td>
                        <td class="p-3">{{ user.role }}</td>
                    </tr>
                </template>
            </content-table>
        </template>
    </content-container>
</template>

<script setup>
const props = defineProps([
    'user_list',
]);
</script>

<script>
import Layout from '../Shared/Layout.vue';
export default {
    layout: Layout,
}
</script>
