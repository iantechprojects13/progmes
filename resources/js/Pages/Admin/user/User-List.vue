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
                            <action-button type="view" @click="view(user.id)"/>
                            <action-button type="deactivateUser" @click="toggleConfirmationModal(); selectedUser = user; form.id = user.id"/>
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
        :showModal="confirmationModal"
        @close="toggleConfirmationModal"
        title="Deactivate User"
        width="md"
    >
        <template #message>
            <div>
                Are you sure you want to deactivate
                <b>{{ selectedUser.name }} </b>'s account?
            </div>
        </template>
        <template #buttons>
            <modal-button text="Deactivate" color="red" @click="deactivateUser"/>
        </template>
    </confirmation>
</template>

<script setup>
// -----------------------------------------------------------------------------------------------------
import { ref } from "vue";
import { useForm, router } from "@inertiajs/vue3";
import Layout from "@/Shared/Layout.vue";
defineOptions({ layout: Layout });

// -----------------------------------------------------------------------------------------------------
const props = defineProps([
    "user_list",
    "program_list",
    "institution_list",
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
const showFilterModal = ref(false);

const query = useForm({
    search: props.filters.search,
    show: props.filters.show != null ? props.filters.show : null,
    type: props.filters.type != null ? props.filters.type : null,
    role: props.filters.role != null ? props.filters.role : null,
    program: props.filters.program != null ? props.filters.program : null,
    institution: props.filters.institution != null ? props.filters.institution : null,
});

const form = useForm({
    id: null,
})

// -----------------------------------------------------------------------------------------------------

function toggleConfirmationModal() {
    confirmationModal.value = !confirmationModal.value;
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

function view(id) {
    router.get(`/admin/users/list/profile/${id}/view`, {
        replace: true,
    })
}

function deactivateUser() {
    router.get(`/register/${selectedUser.value.id}/deactivate`);
}
</script>
