<template>

    <Head title="Users Request" />
    <AdminPanel />
    <content-container @submit="submit" pageTitle="Request List" hasNavigation="true" hasSearch="true" hasFilters="true"
        :data_list="user_list">
        <template v-slot:navigation>
            <div>
                <Link :href="route('admin.users.list')">
                <button class="text-gray-500 hover:text-blackh-10 mr-8">
                    Active users
                </button>
                </Link>
                <button class="text-blue-500 h-10 border-b-2 font-bold border-blue-500">
                    Request
                </button>
            </div>
        </template>
        <template v-slot:search>
            <div class="w-full flex justify-end">
                <input @keydown.enter="submit" v-model="query.search" type="search" id="content-search"
                    placeholder="Search a user"
                    class="rounded border-2 w-full border-gray-400 h-10 bg-white text-base placeholder-gray-400" />
                <button @click="submit"
                    class="hover:bg-gray-300 border-2 active:bg-blue-600 active:text-white h-10 w-12 border-gray-400 relative right-0.5 bg-white rounded-r">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </template>
        <template v-slot:options>
            <div class="mr-1">
                <Link href="/admin/users/request">
                <button
                    class="px-2 border-2 w-12 whitespace-nowrap rounded h-10 text-gray-600 hover:text-black border-gray-500">
                    <i class="fas fa-refresh"></i>
                </button>
                </Link>
            </div>
            <div class="mr-1">
                <dropdown-option position="right">
                    <template v-slot:button>
                        <button
                            class="px-2 border-2 whitespace-nowrap rounded h-8 text-gray-600 hover:text-black border-gray-500">
                            Type
                            <i class="fas fa-caret-down ml-2"></i>
                        </button>
                    </template>
                    <template v-slot:options>
                        <div class="w-52">
                            <div class="px-3 text-gray-600 text-sm font-bold tracking-tight">
                                TYPE
                            </div>
                            <button preserve-state @click="
                                        query.type = '';
                                        submit();
                                    " class="w-full py-0.5 text-left indent-7 hover:bg-gray-200" :class="{
                                        'bg-gray-300': props.filters.type == null,
                                    }">
                                All
                            </button>
                            <button @click="
                                        query.type = 'CHED';
                                        submit();
                                    " class="w-full py-0.5 text-left indent-7 hover:bg-gray-200" :class="{
                                        'bg-gray-300': props.filters.type == 'CHED',
                                    }">
                                CHED
                            </button>
                            <button @click="
                                        query.type = 'HEI';
                                        submit();
                                    " class="w-full py-0.5 text-left indent-7 hover:bg-gray-200" :class="{
                                        'bg-gray-300': props.filters.type == 'HEI',
                                    }">
                                HEI
                            </button>
                        </div>
                    </template>
                </dropdown-option>
            </div>
            <div>
                <dropdown-option position="right">
                    <template v-slot:button>
                        <button
                            class="px-2 border-2 whitespace-nowrap rounded h-8 text-gray-600 hover:text-black border-gray-500">
                            Sort by

                            <i class="fas fa-caret-down ml-2"></i>
                        </button>
                    </template>
                    <template v-slot:options>
                        <div class="w-40">
                            <button preserve-state @click="
                                        query.sort = 'name';
                                        submit();
                                    " class="w-full py-1 text-left indent-5 hover:bg-gray-200" :class="{
                                        'bg-gray-300': props.filters.sort == 'name',
                                    }">
                                Name
                            </button>
                            <button @click="
                                        query.sort = 'type';
                                        submit();
                                    " class="w-full py-1 text-left indent-5 hover:bg-gray-200" :class="{
                                        'bg-gray-300': props.filters.sort == 'type',
                                    }">
                                Type
                            </button>
                            <button @click="
                                        query.sort = 'role';
                                        submit();
                                    " class="w-full py-1 text-left indent-5 hover:bg-gray-200" :class="{
                                        'bg-gray-300': props.filters.sort == 'role',
                                    }">
                                Role
                            </button>
                        </div>
                    </template>
                </dropdown-option>
            </div>
        </template>
        <template v-slot:main-content>
            <content-table>
                <template v-slot:table-head>
                    <th class="py-2">Name/Email</th>
                    <th class="py-2">Type</th>
                    <th class="py-2">Role</th>
                    <th class="py-2">Discipline/Program</th>
                </template>
                <template v-slot:table-body>
                    <tr v-for="(user, index) in user_list.data" :key="user.id"
                        :class="{ 'bg-slate-200': index % 2 == 0 }">
                        <td class="py-2">
                            <div class="flex flex-row">
                                <div class="mx-3">
                                    <img :src="
                                                user.avatar
                                                    ? user.avatar
                                                    : '/assets/user.png'
                                            " width="40" alt="user" class="rounded-full" />
                                </div>
                                <div class="flex flex-col">
                                    <div class="font-bold">
                                        {{ user.name }}
                                    </div>
                                    <div>
                                        {{ user.email }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="py-2">
                            {{ user.type }}
                        </td>
                        <td class="py-2">
                            {{ user.role }}
                        </td>
                        <td class="py-2">
                            -
                        </td>
                        <td class="py-2 text-right px-3" v-if="canEdit">
                            <button @click="toggleModal(user, 'reject', 'Reject User')"
                                class="h-8 px-2 text-white bg-red-500 hover:bg-red-600 rounded mr-1">
                                Reject
                            </button>
                            <button @click="toggleModal(user, 'accept', 'Accept User')"
                                class="h-8 px-2 text-white bg-blue-500 hover:bg-blue-600 rounded">
                                Accept
                            </button>
                        </td>
                    </tr>
                    <tr v-if="user_list.data.length == 0">
                        <td class="py-10 text-center" colspan="4">
                            No users found
                        </td>
                    </tr>
                </template>
            </content-table>
        </template>
    </content-container>
    <!-- <content-container :hasAction="true" placeholder="Search User" :data_list="user_list">
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
                                class="hover:text-white shadow shadow-gray-500 hover:bg-red-600 rounded border text-red-600 border-gray-500 px-2 h-8">
                                Reject
                            </button>
                            <button @click="toggleModal(user, 'accept', 'Accept User')"
                                class="hover:text-white shadow shadow-gray-500 hover:bg-blue-600 rounded border text-blue-600 border-gray-500 px-2 h-8 ml-1">
                                Accept
                            </button>
                        </td>
                    </tr>
                </template>
            </content-table>
        </template>
    </content-container> -->
    <Notification :message="$page.props.flash.success" />
    <div v-if="modal">
        <Confirmation @close="closeModal" :title="title" :modaltype="modaltype" :selected="selectedUser" />
    </div>
</template>

<script setup>
    import { useForm, router } from "@inertiajs/vue3";
    import { ref } from "vue";

    const props = defineProps(["user_list", "canEdit", "filters"]);

    const query = useForm({
        search: props.filters.search,
        sort: props.filters.sort,
        type: props.filters.type,
    });

    function submit() {
        if (query.search == "" && query.sort == "" && query.filter == "") {
            router.get("/admin/users/request");
        } else {
            query.get("/admin/users/request", {
                preserveState: true,
                preserveScroll: true,
            });
        }
    }


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