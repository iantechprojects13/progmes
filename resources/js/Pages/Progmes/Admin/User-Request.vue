<template>
    <Head title="Users Request" />
    <AdminPanel />
    <content-container placeholder="Search User" :data_list="user_list">
        <template v-slot:channel>
            <div class="relative" @mouseleave="openSelectPageDropdown = false">
                <button class="px-3 rounded h-10 text-white bg-green-700"
                    @click.prevent="openSelectPageDropdown = !openSelectPageDropdown"><b>User
                        request </b><i class="fas fa-caret-down ml-2"></i></button>
                <div v-show="openSelectPageDropdown"
                    class="flex flex-col items-start absolute top-10 left-0 shadow border border-gray-300 bg-white rounded py-3 z-30 w-48"
                    @mouseover.prevent="openSelectPageDropdown = true">
                    <Link :href="route('admin.users.list')"
                        class="py-2 w-full px-2 text-left hover:bg-gray-200 rounded flex flex-row pl-4">
                    <div class="w-6">
                    </div>
                    <div>Active users</div>
                    </Link>
                    <button class="flex flex-row py-2 w-full px-2 text-left text-gray-500 pl-4" disabled>
                        <div class="w-6">
                            <i class="fas fa-check"></i>
                        </div>
                        <div>User request</div>
                    </button>
                </div>
            </div>
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
                        <th scope="row" class="p-3 whitespace-nowrap flex items-center pr-10">
                            <img v-if="user.avatar" :src="user.avatar" width="35" class="rounded-full mr-3">
                            <img v-else src="/assets/user.png" width="35" class="rounded-full mr-3">
                            <div class="text-gray-600 items-center">
                                <span class="text-left block">{{ user.name }}</span>
                                <span class="font-normal">{{ user.email }}</span>
                            </div>
                        </th>
                        <td class="p-3">{{ user.type }}</td>
                        <td class="p-3">{{ user.role }}</td>
                        <td class="p-3 text-right">
                            <button @click="toggleModal(user, 'reject', 'Reject User')"
                                class="text-sm h-7 w-16 hover:bg-red-500 hover:text-white border border-gray-400 rounded mr-1">
                                Reject
                            </button>
                            <button @click="toggleModal(user, 'accept', 'Accept User')"
                                class="text-sm h-7 w-16 bg-blue-500 hover:bg-blue-600 text-white rounded mr-1">
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
        }
    },
}
</script>
