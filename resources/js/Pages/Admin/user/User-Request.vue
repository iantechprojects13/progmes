<template>
    <Head title="Users Registration" />
    <content-container
        :hasAdminPanel="true"
        pageTitle="Request List"
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
                :isActive="true"
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
            <options @filter="toggleFilterModal" href="/admin/users/request" />
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
                    <tr v-for="user in user_list.data" :key="user.id">
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
                            <div v-for="role in user.user_role" :key="role.id" v-show="role.isActive == null">
                                {{ role.discipline?.discipline }}
                            </div>
                        </td>
                        <td>
                            <div v-for="role in user.user_role" :key="role.id" v-show="role.isActive == null">
                                {{ role.program?.program }}
                                <span v-if="role.program?.major != null">
                                    - {{ role.program?.major }}
                                </span>
                            </div>
                        </td>
                        <td>
                            <div v-for="role in user.user_role" :key="role.id" v-show="role.isActive == null">
                                {{ role.institution?.name }}
                            </div>
                        </td>
                        <td v-show="canEdit">
                            <action-button type="accept" @click="toggleAcceptConfirmationModal(); selectedUser = user, form.id = user.id" />
                            <action-button type="reject" @click="toggleRejectConfirmationModal(); selectedUser = user, form.id = user.id" />
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
        :showModal="rejectConfirmationModal"
        @close="toggleRejectConfirmationModal"
        title="Reject User"
        width="md"
    >
        <template #message>
            <div>
                Are you sure you want to reject <b>{{ selectedUser?.name }} </b>'s registration?
            </div>
            <div class="mt-3">
                <textarea v-model="form.reason" class="w-full h-[8rem] resize-none rounded-lg border-gray-400" placeholder="Enter reason"></textarea>
            </div>
        </template>
        <template #buttons>
            <modal-button text="Reject" color="red" @click="reject"/>
        </template>
    </confirmation>

    <confirmation
        :showModal="acceptConfirmationModal"
        @close="toggleAcceptConfirmationModal"
        title="Accept User"
        width="md"
    >
        <template #message>
            <div>
                Are you sure you want to accept <b>{{ selectedUser?.name }} </b>'s registration?
            </div>
        </template>
        <template #buttons>
            <modal-button text="Accept" color="blue" @click="accept"/>
        </template>
    </confirmation>
</template>

<script setup>
// --------------------------------------------------------------------
import { ref, reactive } from "vue";
import { useForm, router } from "@inertiajs/vue3";
import Layout from "@/Shared/Layout.vue";
defineOptions({ layout: Layout });

// --------------------------------------------------------------------
const props = defineProps([
    "user_list",
    "program_list",
    "institution_list",
    "requestCount",
    "showInactive",
    "canEdit",
    "canFilter",
    "filters",
]);

// --------------------------------------------------------------------
const selectedUser = ref(null);
const showFilterModal = ref(false);
const rejectConfirmationModal = ref(false);
const acceptConfirmationModal = ref(false);

// --------------------------------------------------------------------
const query = useForm({
    search: props.filters.search,
    show: props.filters.show != null ? props.filters.show : null,
    type: props.filters.type != null ? props.filters.type : null,
    role: props.filters.role != null ? props.filters.role : null,
    program: props.filters.program != null ? props.filters.program : null,
    institution: props.filters.institution != null ? props.filters.institution : null,
});

const form = reactive({
    id: null,
    reason: null,
})

// --------------------------------------------------------------------

function toggleFilterModal() {
    showFilterModal.value = !showFilterModal.value;
}

function toggleRejectConfirmationModal() {
    if (rejectConfirmationModal) {
        form.id = null;
        form.message = null;
    }
    rejectConfirmationModal.value = !rejectConfirmationModal.value;
}

function toggleAcceptConfirmationModal() {
    if (acceptConfirmationModal) {
        form.id = null;
    }
    acceptConfirmationModal.value = !acceptConfirmationModal.value;
}

function filter() {
    query.get("/admin/users/request", {
        preserveState: false,
        preserveScroll: true,
        replace: true,
    });
}

function reject() {
    router.post('/register/reject', form, {
        preserveState: false,
        preserveScroll: true,
        replace: true,
    });
}

function accept() {
    router.post('/register/accept', form, {
        preserveState: false,
        preserveScroll: true,
        replace: true,
    });
}
</script>
