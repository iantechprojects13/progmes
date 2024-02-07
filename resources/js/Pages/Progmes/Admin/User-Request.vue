<template>
    <Head title="Users Request" />
    <AdminPanel />
    <content-container placeholder="Search User" :data_list="user_list">
        <template v-slot:channel>
            <Link :href="route('admin.users.list')">
            <button class="text-gray-500 hover:text-gray-800 mr-5">Active</button>
            </Link>
            <button class="text-blue-500 font-bold underline mr-5">Request</button>
        </template>
        <template v-slot:content>
            <content-table>
                <template v-slot:table-head>
                    <th class="scope-col p-3">Account Type</th>
                    <th class="scope-col p-3">Type</th>
                    <th class="scope-col p-3">Role</th>
                    <th class="scope-col p-3 text-right"><i class="fas fa-ellipsis-v"></i></th>
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
                        <td class="px-3 py-4 text-right">
                            <button @click="toggleModal(user, 'reject', 'Reject User')"
                                class=" px-2 py-1 hover:bg-red-500 hover:text-white border border-gray-400 rounded mr-1">
                                Reject
                            </button>
                            <button @click="toggleModal(user, 'accept', 'Accept User')"
                                class="px-2 py-1 bg-blue-500 hover:bg-blue-600 text-white rounded mr-1">
                                Accept
                            </button>
                        </td>
                    </tr>
                </template>
            </content-table>
        </template>
    </content-container>
    <div v-if="$page.props.flash.success">
        <Notification :message="$page.props.flash.success" />
    </div>
    <div v-if="modal">
        <Confirmation @close="toggleModal" :title="title" :modaltype="modaltype" :selected="selectedUser" />
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
        }
    },
    layout: Layout,

    methods: {
        toggleModal(id, type, title) {
            this.modal = !this.modal;
            this.selectedUser = id;
            this.modaltype = type;
            this.title = title;
        }
    },
}
</script>
