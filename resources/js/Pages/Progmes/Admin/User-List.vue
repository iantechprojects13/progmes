<template>

    <Head title="Users List" />
    <AdminPanel />
    <content-container @submit="submit" :pageTitle="canEdit ? 'Active Users List' : 'Users List'" page="user"
        :hasNavigation="canEdit" :hasSearch="true" :hasFilters="true" :data_list="user_list">
        <template v-slot:navigation>
            <div>
                <button class="select-none text-blue-500 h-10 mr-7 border-b-2 relative font-bold border-blue-500">
                    Active
                </button>
                <Link :href="route('admin.users.request')">
                <button class="mr-7 select-none text-gray-700 hover:text-black">
                    Request
                </button>
                </Link>
                <Link :href="route('admin.users.inactive')" v-show="showInactive">
                <button class="select-none text-gray-700 hover:text-black mr-7">
                    Inactive
                </button>
                </Link>
            </div>
        </template>
        <template v-slot:search>
            <div class="w-full flex justify-end relative">
                <input @keydown.enter="submit" v-model="query.search" type="search" id="content-search"
                    placeholder="Search"
                    class="w-full rounded border border-gray-400 bg-slate-100 h-10 text-base placeholder-gray-400 pr-11 mr-2" />
                <button @click="submit"
                    class="text-gray-700 hover:text-black active:text-blue-500 h-10 w-10 rounded absolute right-2">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </template>
        <template v-slot:options>
            <div class="mr-1">
                <Link href="/admin/users/list">
                <button
                    class="w-10 h-10 whitespace-nowrap rounded-full text-gray-700 hover:text-blue-500 active:text-white active:bg-blue-600 tooltipForActions"
                    data-tooltip="Refresh page">
                    <i class="fas fa-refresh"></i>
                </button>
                </Link>
            </div>
            <div v-show="canFilter" class="mr-1">
                <dropdown-option position="right">
                    <template v-slot:button>
                        <button
                            class="flex justify-between items-center px-2 min-w-6 border whitespace-nowrap rounded h-10 text-gray-600 hover:text-black border-gray-500">
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
                    <th class="p-3 pl-5">Name/Email</th>
                    <th class="p-3">Type</th>
                    <th class="p-3">Role</th>
                    <th class="p-3">Discipline</th>
                    <th class="p-3">Program</th>
                    <th class="p-3">HEI Name</th>
                    <th v-show="canEdit" class="p-3 text-right">
                        <i class="fas fa-ellipsis-v"></i>
                    </th>
                </template>
                <template v-slot:table-body>
                    <tr v-if="user_list.data.length==0">
                        <td class="py-10 text-center" colspan="7">
                            No users found
                        </td>
                    </tr>
                    <tr v-else v-for="(user, index) in user_list.data" :key="user.id"
                        class="hover:bg-slate-300 align-top" :class="{ 'bg-slate-200': index % 2 == 0 }">
                        <td class="p-3 pl-5">
                            <div class="flex flex-row">
                                <div class="mr-3 w-10">
                                    <img :src="
                                            user.avatar
                                                ? user.avatar
                                                : '/assets/user.png'
                                        " alt="user" class="rounded-full" />
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
                            <div v-for="role in user.user_role" :key="role.id">
                                {{ role.discipline?.discipline }}
                            </div>
                        </td>
                        <td class="p-3 whitespace-normal">
                            <div v-for="role in user.user_role" :key="role.id">
                                {{ role.program?.program }}
                                <span v-if="role.program?.major != null">
                                    - {{ role.program?.major }}
                                </span>
                            </div>
                        </td>
                        <td class="p-3 whitespace-normal">
                            <div v-for="role in user.user_role" :key="role.id">
                                {{ role.institution?.name }}
                            </div>
                        </td>
                        <td class="p-3 text-right">
                            <Link :href="'/admin/users/list/profile/' + user.id + '/view'">
                                <button class="select-none h-10 w-10 text-center text-white bg-green-600 hover:bg-green-700 rounded mr-1 tooltipForActions"
                                data-tooltip="View profile"
                                >
                                    <i class="fas fa-eye"></i>
                                </button>
                            </Link>
                            <button v-show="canEdit" @click="
                                    toggleModal(
                                        user,
                                        'deactivateUser',
                                        'Deactivate User'
                                    )
                                "
                                class="select-none h-10 w-10 text-center text-white bg-red-500 hover:bg-red-600 rounded mr-1 tooltipForActions"
                                data-tooltip="Deactivate">
                                <i class="fas fa-lock"></i>
                            </button>
                        </td>
                    </tr>
                </template>
            </content-table>
        </template>
        <template v-slot:show-item>
            <div class="mr-2">Items per page</div>
            <select v-model="query.show" id="showResultCount" @change="changeResultCount"
                class="select-none rounded h-8 w-20 p-1 text-sm">
                <option value="25">25</option>
                <option :value="50">50</option>
                <option :value="75">75</option>
                <option :value="100">100</option>
                <option :value="200">200</option>
            </select>
        </template>
    </content-container>
    <Notification :message="$page.props.flash.success" type="success" />
    <Notification :message="$page.props.flash.failed" type="failed" />
    <Notification :message="$page.props.flash.error" type="failed" />
    <div v-if="modal">
        <Confirmation @close="closeModal" :title="title" :modaltype="modaltype" :selected="selectedUser" />
    </div>
</template>

<script setup>
    import { useForm, router } from "@inertiajs/vue3";
    import { ref, computed } from "vue";

    const props = defineProps([
        'user_list',
        'canEdit',
        'canFilter',
        'showRequest',
        'showInactive',
        'filters',
    ]);

    const query = useForm({
        show: props.filters.show,
        search: props.filters.search,
        type: props.filters.type,
    });

    const getDiscipline = (index) => {
        let discipline = props.user_list[index];
        return discipline;
    }

    function submit() {
        query.get("/admin/users/list", {
            preserveState: true,
            preserveScroll: true,
        });
    }

    function changeResultCount() {
        query.get("/admin/users/list", {
            preserveScroll: false,
            preserveState: false,
        });
    }
</script>

<script>
    import Layout from "../Shared/Layout.vue";
    export default {
        data() {
            return {
                modal: false,
                selectedUser: "",
                modaltype: "",
                title: "",
                openSelectPageDropdown: false,
            };
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
            },
        },
    };
</script>