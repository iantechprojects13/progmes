<template>
    <Head title="Users List" />
    <AdminPanel />
    <content-container :hasAction="true" placeholder="Search User" :data_list="user_list">
        <template v-slot:channel>
            <dropdown-option position="left">
                <template v-slot:button>
                    <button class="px-3 rounded h-10 bg-green-700 text-white">Active users<i
                            class="fas fa-caret-down ml-2"></i></button>
                </template>
                <template v-slot:options>
                    <div class="w-48">
                        <Link :href="route('admin.users.list')"
                            class="w-full p-2 text-left hover:bg-gray-200 rounded flex flex-row pl-4 text-blue-500">
                        <div class="w-6">
                            <i class="fas fa-check"></i>
                        </div>
                        <div>Active Users</div>
                        </Link>
                        <Link :href="route('admin.users.request')"
                            class="w-full p-2 text-left hover:bg-gray-200 rounded flex flex-row pl-4 text-gray-700">
                        <div class="w-6">
                        </div>
                        <div>Request</div>
                        </Link>
                    </div>
                </template>
            </dropdown-option>
        </template>
        <template v-slot:actions>
            <dropdown-option>
                <template v-slot:button>
                    <button class="h-10 border border-gray-500 rounded px-2">
                        <i class="fas fa-filter mr-2"></i>
                        <i class="fas fa-caret-down"></i>
                    </button>
                </template>
                <template v-slot:options>
                    <div class="w-32 flex flex-col">
                        <Link>
                        HEI
                        </Link>
                        <Link>
                        CHED
                        </Link>
                    </div>
                </template>
            </dropdown-option>
        </template>
        <template v-slot:content>
            <content-table>
                <template v-slot:table-head>
                    <th class="scope-col p-3 border-b border-gray-400">Name/Email</th>
                    <th class="scope-col p-3 border-b border-gray-400">Role/HEI</th>
                    <th class="scope-col p-3 border-b border-gray-400">Program</th>
                    <th class="scope-col p-3 border-b border-gray-400">Discipline</th>
                </template>
                <template v-slot:table-body>
                    <tr v-for="(user, index) in user_list.data" :key="user.id"
                        class="border-b border-gray-300 hover:bg-stone-100 text-gray-700 align-top"
                        :class="{ 'bg-gray-200': index % 2 == 0 }">
                        <th scope="row" class="p-2 whitespace-nowrap flex items-center pr-10">
                            <img v-if="user.avatar" :src="user.avatar" width="35" class="rounded-full mr-3">
                            <img v-else src="/assets/user.png" width="35" class="rounded-full mr-3">
                            <div class="items-center">
                                <span class="text-left block">{{ user.name }}</span>
                                <span class="font-normal">{{ user.email }}</span>
                            </div>
                        </th>
                        <td class="p-2">
                            <div>
                                {{ user.role }}
                            </div>
                            <div v-for="role in user.user_role">
                                {{ role.institution?.name }}
                            </div>
                        </td>
                        <td class="p-2 whitespace-normal ">
                            <div v-for="role in user.user_role">
                                {{ role.program?.program }}
                            </div>
                        </td>
                        <td class="p-2 whitespace-normal">
                            <div v-for="role in user.user_role">
                                {{ role.discipline?.discipline }}
                            </div>
                        </td>
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
