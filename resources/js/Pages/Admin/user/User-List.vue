<template>
    <Head title="Users List" />
    <content-container
        :hasAdminPanel="true"
        :pageTitle="canEdit ? 'Users List' : 'Users List'"
        page="user"
        :hasNavigation="canEdit"
        :hasSearch="true"
        :hasFilters="true"
        :data_list="user_list"
    >
        <template v-slot:navigation>
            <main-content-nav
                btnText="Active"
                routeName="admin.users.list"
                :isActive="true"
            />
            <main-content-nav
                btnText="Request"
                routeName="admin.users.request"
                :requestCount="requestCount"
            />
            <main-content-nav
                v-show="showInactive"
                btnText="Inactive"
                routeName="admin.users.inactive"
            />
        </template>
        <template v-slot:search>
            <search-box v-model="query.search" @submit="filter" />
        </template>
        <template v-slot:options>
            <options @filter="toggleFilterModal" href="/admin/users/list" />
        </template>

        <template v-slot:main-content>
            <content-table>
                <template v-slot:table-head>
                    <th>Name/Email</th>
                    <th>Type</th>
                    <th>Role</th>
                    <th>Discipline</th>
                    <th>Program</th>
                    <th>HEI Name</th>
                    <th v-show="canEdit">
                        <i class="fas fa-ellipsis-v text-lg"></i>
                    </th>
                </template>
                <template v-slot:table-body>
                    <tr v-if="user_list.data.length == 0">
                        <no-search-result text="users" />
                    </tr>
                    <tr v-else v-for="user in user_list.data" :key="user.id">
                        <td>
                            <div class="flex flex-row">
                                <div class="mr-3 w-10">
                                    <img
                                        :src="
                                            user.avatar
                                                ? user.avatar
                                                : '/assets/user.png'
                                        "
                                        alt="user"
                                        class="rounded-full"
                                    />
                                </div>
                                <div class="flex flex-col">
                                    <div>
                                        {{ user.name }}
                                    </div>
                                    <div class="text-sm text-gray-600">
                                        {{ user.email }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            {{ user.type }}
                        </td>
                        <td>
                            <p v-if="user.role == 'Vice-President for Academic Affairs'">
                                VPAA/Dean of Multiple Discipline
                            </p>
                            <p v-else>
                                {{ user.role }}
                            </p>
                        </td>
                        <td>
                            <div v-for="role in user.user_role" :key="role.id">
                                {{ role.discipline?.discipline }}
                            </div>
                        </td>
                        <td>
                            <div v-for="role in user.user_role" :key="role.id">
                                {{ role.program?.program }}
                                <span v-if="role.program?.major != null">
                                    - {{ role.program?.major }}
                                </span>
                            </div>
                        </td>
                        <td>
                            <div v-for="role in user.user_role" :key="role.id">
                                {{ role.institution?.name }}
                            </div>
                        </td>
                        <td>
                            <div class="flex items-center gap-0">
                                <Link
                                    :href="
                                        '/admin/users/list/profile/' +
                                        user.id +
                                        '/view'
                                    "
                                >
                                    <button
                                        class="inline-flex items-center justify-center rounded-full h-10 w-10 text-emerald-700 hover:bg-emerald-100 tooltipForActions"
                                        data-tooltip="View"
                                    >
                                        <i class="fas fa-eye text-lg"></i>
                                    </button>
                                </Link>

                                <button
                                    v-show="canEdit"
                                    @click="
                                        toggleConfirmationModal(
                                            user,
                                            'deactivateUser',
                                            'Deactivate'
                                        )
                                    "
                                    class="inline-flex items-center justify-center rounded-full h-10 w-10 text-red-700 hover:bg-red-100 tooltipForActions"
                                    data-tooltip="Deactivate"
                                >
                                    <i class="fas fa-lock text-lg"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </template>
            </content-table>
        </template>
    </content-container>

    <modal
        :showModal="showFilterModal"
        @close="toggleFilterModal"
        width="md"
        title="Filters"
        class="antialiased"
    >
        <div>
            <!-- Account Type Filter -->
            <div
                class="flex flex-col space-y-2 mb-6"
                v-show="$page.props.auth.user.role == 'Super Admin'"
            >
                <label for="type" class="text-sm font-medium text-gray-700">
                    Account Type
                </label>
                <select
                    v-model="query.type"
                    id="type"
                    class="block w-full px-3 py-2 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
                    <option :value="null">All</option>
                    <option value="HEI">HEI</option>
                    <option value="CHED">CHED</option>
                </select>
            </div>
            <filter-page-items v-model="query.show"/>
        </div>

        <template #custom-button>
            <button
                @click="filter"
                class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 duration-200 w-20 h-10"
            >
                Apply
            </button>
        </template>
    </modal>

    <Confirmation
        :showModal="confirmationModal"
        @close="closeModal"
        :title="title"
        :modaltype="modaltype"
        :selected="selectedUser"
        width="md"
    />
</template>

<script setup>
// -----------------------------------------------------------------------------------------------------
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import Layout from "@/Shared/Layout.vue";
defineOptions({ layout: Layout });

// -----------------------------------------------------------------------------------------------------
const props = defineProps([
    "user_list",
    "requestCount",
    "canEdit",
    "canFilter",
    "showRequest",
    "showInactive",
    "filters",
]);

// -----------------------------------------------------------------------------------------------------
const confirmationModal = ref(false);
const selectedUser = ref("");
const modaltype = ref("");
const title = ref("");
const showFilterModal = ref(false);

const query = useForm({
    show: props.filters.show != null ? props.filters.show : null,
    search: props.filters.search,
    type: props.filters.type != null ? props.filters.type : null,
});

// -----------------------------------------------------------------------------------------------------

function toggleConfirmationModal(id, type, titleValue) {
    confirmationModal.value = !confirmationModal.value;
    selectedUser.value = id;
    modaltype.value = type;
    title.value = titleValue;
}

function closeModal() {
    confirmationModal.value = false;
}

function toggleFilterModal() {
    showFilterModal.value = !showFilterModal.value;
}

function filter() {
    query.get("/admin/users/list", {
        preserveState: false,
        preserveScroll: true,
        replace: true,
    });
}
</script>
