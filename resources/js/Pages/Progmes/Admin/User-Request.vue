<template>
    <Head title="Users Request" />
    <AdminPanel />
    <content-container :hasAction="true" placeholder="Search User" :data_list="user_list">
        <template v-slot:channel>
            <dropdown-option position="left">
                <template v-slot:button>
                    <button class="px-3 rounded h-10 bg-green-700 text-white">Request<i
                            class="fas fa-caret-down ml-2"></i></button>
                </template>
                <template v-slot:options>
                    <div class="w-48">
                        <Link :href="route('admin.users.list')"
                            class="py-2 w-full px-2 text-left hover:bg-gray-200 rounded flex flex-row pl-4 text-gray-700">
                        <div class="w-6">
                        </div>
                        <div>Active Users</div>
                        </Link>
                        <Link :href="route('admin.users.request')"
                            class="py-2 w-full px-2 text-left hover:bg-gray-200 rounded flex flex-row pl-4 text-blue-500">
                        <div class="w-6">
                            <i class="fas fa-check"></i>
                        </div>
                        <div>Request</div>
                        </Link>
                    </div>
                </template>
            </dropdown-option>
        </template>
        <template v-slot:content>
            <content-table>
                <template v-slot:table-head>
                    <th class="scope-col p-3">Name/Email</th>
                    <th class="scope-col p-3">Account Type</th>
                    <th class="scope-col p-3">Role</th>
                    <th class="scope-col p-3 text-right"><i class="fas fa-ellipsis-v"></i></th>
                </template>
                <template v-slot:table-body>
                    <tr v-for="(user, index) in user_list.data" :key="user.id"
                        class="border-b border-gray-300 hover:bg-stone-100" :class="{ 'bg-gray-200': index % 2 == 0 }">
                        <th scope="row" class="p-2 whitespace-nowrap flex items-center pr-10">
                            <img v-if="user.avatar" :src="user.avatar" width="35" class="rounded-full mr-3">
                            <img v-else src="/assets/user.png" width="35" class="rounded-full mr-3">
                            <div class="text-gray-600 items-center">
                                <span class="text-left block">{{ user.name }}</span>
                                <span class="font-normal">{{ user.email }}</span>
                            </div>
                        </th>
                        <td class="p-2">{{ user.type }}</td>
                        <td class="p-2">{{ user.role }}</td>
                        <td class="p-2 text-right">
                            <button @click="toggleModal(user, 'reject', 'Reject User')"
                                class="hover:text-white shadow shadow-gray-500 hover:bg-red-600 rounded border-2 text-red-600 border-red-600 w-8 h-8 tooltipForActions"
                                data-tooltip="Reject">
                                <i class="text-lg fas fa-times"></i>
                            </button>
                            <button @click="toggleModal(user, 'accept', 'Accept User')"
                                class="hover:text-white shadow shadow-gray-500 hover:bg-blue-600 rounded border-2 text-blue-600 border-blue-600 w-8 h-8 tooltipForActions ml-1"
                                data-tooltip="Accept">
                                <i class="text-lg fas fa-check"></i>
                            </button>
                        </td>
                    </tr>
                </template>
            </content-table>
        </template>
    </content-container>
    <Notification :message="$page.props.flash.success" />
    <div v-if="modal">
        <Confirmation @close="closeModal" :title="title" :modaltype="modaltype" :selected="selectedUser" />
    </div>
</template>

<script setup>
const props = defineProps([
    'user_list',
]);
</script>

<script>
import Layout from '../Shared/Layout.vue';
export default {
    data() {
        return {
            modal: false,
            selectedUser: '',
            modaltype: '',
            title: '',
            openSelectPageDropdown: false,
        }
    },
    layout: Layout,

    methods: {
        toggleModal(id, type, title) {
            this.modal = !this.modal;
            this.selectedUser = id;
            this.modaltype = type;
            this.title = title;
        },

        closeModal() {
            this.modal = false;
        }
    },
}
</script>
