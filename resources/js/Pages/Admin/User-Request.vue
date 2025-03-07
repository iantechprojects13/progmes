<template>

    <Head title="Users Registration" />
    <content-container :hasAdminPanel="true" pageTitle="Request List" page="user" :hasNavigation="true" :hasSearch="true" :hasFilters="true"
        :data_list="user_list">
        <template v-slot:navigation>
            <user-management-nav :requestCount="requestCount" :showInactive="showInactive" page="request">

            </user-management-nav>
        </template>
        <template v-slot:search>
            <div class="w-full flex flex-row relative items-center">
                <i class="fa fa-search text-gray-400 absolute left-5"></i>
                <input @keydown.enter="submit" v-model="query.search" type="search" id="content-search"
                    placeholder="Search"
                    class="w-full rounded-lg border border-gray-300 indent-10 h-10 text-base placeholder-gray-400" />
            </div>
        </template>
        <template v-slot:options>
            <div class="mr-1">
                
                <button @click="toggleFilterModal"
                    class="w-10 h-10 whitespace-nowrap hover:bg-gray-200 rounded-full text-gray-700 hover:text-blue-500 active:text-white active:bg-blue-600 tooltipForActions"
                    data-tooltip="Filters">
                    <i class="fas fa-filter"></i>
                </button>
                <Link href="/admin/users/request">
                <button
                    class="w-10 h-10 whitespace-nowrap hover:bg-gray-200 rounded-full text-gray-700 hover:text-blue-500 active:text-white active:bg-blue-600 tooltipForActions"
                    data-tooltip="Refresh page">
                    <i class="fas fa-refresh"></i>
                </button>
                </Link>
            </div>
        </template>
        <template v-slot:main-content>
            <content-table>
                <template v-slot:table-head>
                    <th class="p-2 pl-5">Name/Email</th>
                    <th class="p-2">Type</th>
                    <th class="p-2">Role</th>
                    <th class="p-2">Discipline</th>
                    <th class="p-2">Program</th>
                    <th class="p-2">HEI Name</th>
                    <th class="p-2 pr-5 text-right" v-show="canEdit">
                        <i class="fas fa-ellipsis-v"></i>
                    </th>
                </template>
                <template v-slot:table-body>
                    <tr v-if="user_list.data.length == 0">
                        <no-search-result text="users"/>
                    </tr>
                    <tr v-for="(user, index) in user_list.data" :key="user.id" class="hover:bg-gray-200 border-b align-top"
                        :class="{ 'bg-gray-100': index % 2 == 1 }">
                        <td class="p-2 pl-5">
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
                        <td class="p-2">
                            {{ user.type }}
                        </td>
                        <td class="p-2">
                            {{ user.role }}
                        </td>
                        <td class="p-2">
                            <div v-for="role in user.user_role" :key="role.id">
                                {{ role.discipline?.discipline || 'N/A'}}
                            </div>
                        </td>
                        <td class="p-2">
                            <div v-for="(role, programIndex) in user.user_role" :key="role.id">
                                {{ role.program?.program ||  programIndex == 0 ? 'N/A' : '' }}
                                <span v-if="role.program?.major != null">
                                    - {{ role.program?.major }}
                                </span>
                            </div>
                        </td>
                        <td class="p-2">
                            <div v-for="(role, institutionIndex) in user.user_role" :key="role.id">
                                {{ role.institution?.name || institutionIndex == 0 ? 'N/A' : '' }}
                            </div>
                        </td>
                        <td class="p-2 text-right pr-5 whitespace-nowrap" v-show="canEdit">
                            <button @click="toggleModal(user, 'accept', 'Accept User')"
                                class="select-none h-8 w-8 text-xl text-center rounded-full hover:bg-gray-300 text-blue-500 hover:text-blue-600 tooltipForActions"
                                data-tooltip="Accept User">
                                <i class="fas fa-check"></i>
                            </button>
                            <button @click="toggleModal(user, 'reject', 'Reject User')"
                                class="select-none h-8 w-8 text-xl text-center rounded-full hover:bg-gray-300 text-red-500 hover:text-red-600 tooltipForActions"
                                data-tooltip="Reject User">
                                <i class="fas fa-times"></i>
                            </button>
                        </td>
                    </tr>
                </template>
            </content-table>
        </template>
    </content-container>
    <modal :showModal="showFilterModal" @close="toggleFilterModal" width="sm" height="long" title="Filters">
        <div>
            <div class="flex flex-col">
                <label for="type">Account Type</label>
                <select v-model="query.type" id="type" class="rounded border-gray-400">
                    <option :value="null">All</option>
                    <option value="HEI">HEI</option>
                    <option value="CHED">CHED</option>
                </select>
            </div>
            <div class="mt-3 flex flex-col">
                <label for="show">Items per page</label>
                <select v-model="query.show" id="show" class="rounded border-gray-400">
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="200">200</option>
                </select>
            </div>
        </div>
        <template v-slot:custom-button>
            <button @click="filter" class="text-white bg-green-600 hover:bg-green-700 w-20 rounded h-10">
                <span v-if="processing">
                    <i class="fas fa-spinner animate-spin"></i>
                </span>
                <span v-else>Apply</span>
            </button>
        </template>
    </modal>
    <Confirmation :showModal="modal" @close="closeModal" :title="title" :modaltype="modaltype" :selected="selectedUser" width="md" height="short"/>
</template>

<script setup>
import { useForm, router } from "@inertiajs/vue3";
import { ref } from "vue";

const props = defineProps([
    'user_list',
    'requestCount',
    'showInactive',
    'canEdit',
    'canFilter',
    'filters',
]);


const showFilterModal = ref(false);
const processing = ref(false);

const query = useForm({
    show: props.filters.show != null ? props.filters.show : null,
    search: props.filters.search,
    type: props.filters.type != null ? props.filters.type : null,
});

function toggleFilterModal() {
    showFilterModal.value = !showFilterModal.value;
}

function submit() {
    query.get("/admin/users/request", {
        onStart: () => {
            processing.value = true;
        },
        onFinish: () => {
            processing.value = false;
        },
        preserveState: false,
        preserveScroll: true,
    });
}

function filter() {
    query.get("/admin/users/request", {
        onStart: () => {
            processing.value = true;
        },
        onFinish: () => {
            processing.value = false;
            toggleFilterModal();
        },
        preserveState: true,
        preserveScroll: true,
    });
}


</script>

<script>
    import Layout from '@/Shared/Layout.vue';
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