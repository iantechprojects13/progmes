<template>

    <Head title="Users List" />
    <content-container :hasAdminPanel="true" :pageTitle="canEdit ? 'Active Users List' : 'Users List'" page="user"
        :hasNavigation="canEdit" :hasSearch="true" :hasFilters="true" :data_list="user_list">
        <template v-slot:navigation>
            <user-management-nav :requestCount="requestCount" :showInactive="showInactive" page="list">
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
                    class="w-10 h-10 hover:bg-gray-200 rounded-full text-gray-700 hover:text-blue-500 active:text-white active:bg-blue-600 tooltipForActions"
                    data-tooltip="Filters">
                    <i class="fas fa-filter"></i>
                </button>
                <Link href="/admin/users/list">
                <button
                    class="w-10 h-10 hover:bg-gray-200 rounded-full text-gray-700 hover:text-blue-500 active:text-white active:bg-blue-600 tooltipForActions"
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
                    <th v-show="canEdit" class="p-2 pr-5 text-right">
                        <i class="fas fa-ellipsis-v"></i>
                    </th>
                </template>
                <template v-slot:table-body>
                    <tr v-if="user_list.data.length==0">
                        <no-search-result text="users"/>
                    </tr>
                    <tr v-else v-for="(user, index) in user_list.data" :key="user.id"
                        class="hover:bg-gray-200 border-b align-top" :class="{ 'bg-gray-100': index % 2 == 1 }">
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
                        <td class="p-2 pr-5 text-right whitespace-nowrap">
                            <Link :href="'/admin/users/list/profile/' + user.id + '/view'">
                                <button class="select-none h-8 w-8 text-xl text-center rounded-full hover:bg-gray-300 text-green-500 hover:text-green-600 tooltipForActions"
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
                                class="select-none h-8 w-8 text-center text-xl hover:bg-gray-300 rounded-full text-red-500 hover:text-red-600 tooltipForActions"
                                data-tooltip="Deactivate User">
                                <i class="fas fa-lock"></i>
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
    
    <Confirmation :showModal="modal" @close="closeModal" :title="title" :modaltype="modaltype" :selected="selectedUser" width="lg" height="short" />
</template>

<script setup>
import { useForm, router } from "@inertiajs/vue3";
import { ref, computed } from "vue";

const props = defineProps([
    'user_list',
    'requestCount',
    'canEdit',
    'canFilter',
    'showRequest',
    'showInactive',
    'filters',
]);

const showFilterModal = ref(false);
const processing = ref(false);
const searchValue = ref(props.filters.search);

const query = useForm({
    show: props.filters.show != null ? props.filters.show : null,
    search: props.filters.search,
    type: props.filters.type != null ? props.filters.type : null,
});

function toggleFilterModal() {
    showFilterModal.value = !showFilterModal.value;
}

function submit() {
    query.get("/admin/users/list", {
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
    query.get("/admin/users/list", {
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
import Layout from "@/Shared/Layout.vue";
export default {
  components: { Layout },
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