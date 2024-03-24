<template>

    <Head title="Users Registration" />
    <AdminPanel />
    <content-container @submit="submit" pageTitle="Request List" hasNavigation="true" hasSearch="true" hasFilters="true"
        :data_list="user_list">
        <template v-slot:navigation>
            <div>
                <Link :href="route('admin.users.list')">
                <button class="text-gray-500 hover:text-black h-10 mr-7">
                    Active
                </button>
                </Link>
                <button class="text-blue-500 h-10 border-b-2 font-bold border-blue-500 mr-7">
                    Request
                </button>
                <Link :href="route('admin.users.inactive')" v-show="showInactive">
                <button class="text-gray-500 hover:text-black h-10 mr-7">
                    Inactive
                </button>
                </Link>
            </div>
        </template>
        <template v-slot:search>
            <div class="w-full flex justify-end relative">
                <input @keydown.enter="submit" v-model="query.search" type="search" id="content-search"
                    placeholder="Search a user"
                    class="w-full rounded-full bg-slate-100 h-10 border-none indent-3 text-base placeholder-gray-400 pr-11 mr-2" />
                <button @click="submit"
                    class="hover:bg-gray-300 active:text-blue-500 h-10 w-10 rounded-full absolute right-3">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </template>
        <template v-slot:options>
            <div class="mr-1">
                <Link href="/admin/users/request">
                <button
                    class="px-2 border-2 w-12 whitespace-nowrap rounded h-10 text-gray-600 hover:text-black border-gray-500 tooltipForActions"
                    data-tooltip="Refresh page">
                    <i class="fas fa-refresh"></i>
                </button>
                </Link>
            </div>
            <div class="mr-1" v-show="canFilter">
                <dropdown-option position="right">
                    <template v-slot:button>
                        <button
                            class="flex justify-between items-center px-2 min-w-6 border-2 whitespace-nowrap rounded h-10 text-gray-600 hover:text-black border-gray-500">
                            <span v-if="props.filters.type == null">Type</span>
                            <span v-else-if="props.filters.type == 'CHED'">CHED</span>
                            <span v-else-if="props.filters.type == 'HEI'">HEI</span>
                            <span v-else>Type</span>
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
        </template>
        <template v-slot:main-content>
            <content-table>
                <template v-slot:table-head>
                    <th class="p-3">Name/Email</th>
                    <th class="p-3">Type</th>
                    <th class="p-3">Role</th>
                    <th class="p-3">Discipline</th>
                    <th class="p-3">Program</th>
                    <th class="p-3">HEI Name</th>
                    <th class="p-3 text-right" v-show="canEdit">
                        <i class="fas fa-ellipsis-v"></i>
                    </th>
                </template>
                <template v-slot:table-body>
                    <tr v-if="user_list.data.length == 0">
                        <td class="py-10 text-center" colspan="7">
                            No users found
                        </td>
                    </tr>
                    <tr v-for="(user, index) in user_list.data" :key="user.id"
                        :class="{ 'bg-slate-200': index % 2 == 0 }">
                        <td class="p-3">
                            <div class="flex flex-row">
                                <div class="mr-3 w-10">
                                    <img :src="user.avatar ? user.avatar : '/assets/user.png'" class="rounded-full" />
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
                        <td class="p-3">
                            {{ user.type }}
                        </td>
                        <td class="p-3 whitespace-normal">
                            {{ user.role }}
                        </td>
                        <td class="p-3 whitespace-normal">
                            <div v-for="(role, index) in user.user_role" :key="role.id">
                                {{ role.discipline?.discipline }}
                            </div>
                        </td>
                        <td class="p-3 whitespace-normal">
                            <div v-for="(role, index) in user.user_role" :key="role.id">
                                {{ role.program?.program }}
                            </div>
                        </td>
                        <td class="p-3 whitespace-normal">
                            <div v-for="(role, index) in user.user_role" :key="role.id">
                                {{ role.institution?.name }}
                            </div>
                        </td>
                        <td class="p-3 text-right px-3" v-show="canEdit">
                            <button @click="toggleModal(user, 'reject', 'Reject User')"
                                class="select-none h-10 w-16 text-white bg-red-500 hover:bg-red-600 rounded mr-1">
                                Reject
                            </button>
                            <button @click="toggleModal(user, 'accept', 'Accept User')"
                                class="select-none h-10 w-16 text-white bg-blue-500 hover:bg-blue-600 rounded">
                                Accept
                            </button>
                        </td>
                    </tr>
                </template>
            </content-table>
        </template>
    </content-container>
    <Notification :message="$page.props.flash.success" type="success" />
    <Notification :message="$page.props.flash.failed" type="failed" />
    <div v-if="modal">
        <Confirmation @close="closeModal" :title="title" :modaltype="modaltype" :selected="selectedUser" />
    </div>
</template>

<script setup>
    import { useForm, router } from "@inertiajs/vue3";
    import { ref } from "vue";

    const props = defineProps([
        'user_list',
        'showInactive',
        'canEdit',
        'canFilter',
        'filters',
    ]);

    const query = useForm({
        search: props.filters.search,
        type: props.filters.type,
    });

    function submit() {
        if (query.search == "" && query.filter == "") {
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