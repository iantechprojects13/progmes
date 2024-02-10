<template>
    <Head title="Users List" />
    <AdminPanel />
    <content-container placeholder="Search User" :data_list="user_list">
        <template v-slot:channel>
            <div class="relative" @mouseleave="openSelectPageDropdown = false">
                <button class="px-3 rounded h-10 text-white bg-green-700"
                    @click.prevent="openSelectPageDropdown = !openSelectPageDropdown"><b>Active users </b><i
                        class="fas fa-caret-down ml-2"></i></button>
                <div v-show="openSelectPageDropdown"
                    class="flex flex-col items-start absolute top-10 left-0 shadow border border-gray-300 bg-white rounded px-3 py-3 z-30 w-48"
                    @mouseover.prevent="openSelectPageDropdown = true">
                    <button class="flex flex-row py-2 w-full px-2 text-left text-gray-500" disabled>
                        <div class="w-6">
                            <i class="fas fa-check"></i>
                        </div>
                        <div>Active users</div>
                    </button>
                    <Link :href="route('admin.users.request')"
                        class="py-2 w-full px-2 text-left hover:bg-gray-200 rounded flex flex-row">
                    <div class="w-6">
                    </div>
                    <div>User request</div>
                    </Link>
                </div>
            </div>
        </template>
        <template v-slot:content>
            <content-table>
                <template v-slot:table-head>
                    <th class="scope-col p-3 border-b border-gray-400">Name/Email</th>
                    <th class="scope-col p-3 border-b border-gray-400">Account Type</th>
                    <th class="scope-col p-3 border-b border-gray-400">Role</th>
                </template>
                <template v-slot:table-body>
                    <tr v-for="(user, index) in user_list.data" :key="user.id"
                        class="border-b border-gray-300 hover:bg-stone-100" :class="{ 'bg-gray-200': index % 2 == 0 }">
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
    data() {
        return {
            openSelectPageDropdown: false,
        }
    },

    methods: {
        selectOption(option) {
            this.selectedOption = option;
            this.isOpen = false;
        },
    }
}
</script>
