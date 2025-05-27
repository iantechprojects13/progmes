<template>
    <Head title="Inactive Users List" />
    <content-container
        :hasAdminPanel="true"
        pageTitle="Inactive Users List"
        :hasNavigation="true"
        :hasSearch="true"
        :hasFilters="true"
        :data_list="user_list"
    >
        <template v-slot:navigation>
            <main-content-nav
                btnText="Active"
                routeName="admin.users.list"
            />
            <main-content-nav
                btnText="Request"
                routeName="admin.users.request"
                :requestCount="requestCount"
            />
            <main-content-nav
                btnText="Inactive"
                routeName="admin.users.inactive"
                :isActive="true"
            />
        </template>
        <template v-slot:search>
            <search-box v-model="query.search" @submit="filter" />
        </template>
        <template v-slot:options>
            <options @filter="toggleFilterModal" href="/admin/users/inactive" />
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
                        <td v-show="canEdit">
                            <action-button type="activateUser" @click="toggleActivateModal(); selectedUser = user" />
                            <action-button type="delete" @click="toggleDeleteModal(); selectedUser = user" />
                        </td>
                    </tr>
                </template>
            </content-table>
        </template>
    </content-container>

    <modal
        :showModal="showFilterModal"
        @close="toggleFilterModal"
        title="Filters"
        width="md"
    >
        <div class="flex flex-col space-y-4">
            <filter-user-type v-show="$page.props.auth.user.role == 'Super Admin'" v-model="query.type" />
            <filter-user-role v-model="query.role" :type="$page.props.auth.user.role == 'Super Admin' ? 'All' : 'HEI' " />
            <filter-program :data="program_list" v-model="query.program"/>
            <filter-institution :data="institution_list" v-model="query.institution"/>
            <filter-page-items v-model="query.show"/>
        </div>

        <template #custom-button>
            <modal-button text="Apply" color="green" @click="filter"/>
        </template>
    </modal>
    
    <confirmation
        :showModal="activateModal"
        @close="toggleActivateModal"
        title="Activate User"
        width="md"
    >
        <template #message>
            <div>
                Are you sure you want to activate
                <b>{{ selectedUser?.name }} </b>'s account?
            </div>
        </template>
        <template #buttons>
            <modal-button text="Activate" color="blue" @click="activate" />
        </template>
    </confirmation>

    <confirmation
        :showModal="deleteModal"
        @close="toggleDeleteModal"
        title="Delete User"
        width="md"
    >
        <template #message>
            <div>
                Are you sure you want to delete
                <b>{{ selectedUser?.name }} </b>'s account?
            </div>
        </template>
        <template #buttons>
            <modal-button text="Delete" color="red" @click="deleteUser" />
        </template>
    </confirmation>

</template>

<script setup>
// -----------------------------------------------------------------------------
import { ref } from "vue";
import { useForm, router } from "@inertiajs/vue3";
import Layout from "@/Shared/Layout.vue";
defineOptions({ layout: Layout });

// -----------------------------------------------------------------------------
const props = defineProps([
    "user_list",
    "program_list",
    "institution_list",
    "requestCount",
    "page",
    "canEdit",
    "canFilter",
    "filters",
]);

// -----------------------------------------------------------------------------
const selectedUser = ref(null);
const showFilterModal = ref(false);
const activateModal = ref(false);
const deleteModal = ref(false);

const query = useForm({
    search: props.filters.search,
    show: props.filters.show != null ? props.filters.show : null,
    type: props.filters.type != null ? props.filters.type : null,
    role: props.filters.role != null ? props.filters.role : null,
    program: props.filters.program != null ? props.filters.program : null,
    institution: props.filters.institution != null ? props.filters.institution : null,
});

// -----------------------------------------------------------------------------
function toggleFilterModal() {
    showFilterModal.value = !showFilterModal.value;
}

function toggleActivateModal() {
    activateModal.value = !activateModal.value;
}

function toggleDeleteModal() {
    deleteModal.value = !deleteModal.value;
}

function filter() {
    query.get("/admin/users/inactive", {
        preserveState: false,
        preserveScroll: true,
        replace: true,
    });
}

function activate() {
    router.get(`/register/${selectedUser.value.id}/activate`)
}

function deleteUser() {
    router.get(`/register/${selectedUser.value.id}/delete`)
}

</script>
