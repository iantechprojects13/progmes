<template>
    <Head title="Users Registration" />
    <content-container
        :hasAdminPanel="true"
        pageTitle="Request List"
        page="user"
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
                            <div class="flex items-center gap-0">
                                <button
                                    @click="
                                        toggleConfirmationModal(
                                            user,
                                            'accept',
                                            'Accept'
                                        )
                                    "
                                    class="inline-flex items-center justify-center rounded-full h-10 w-10 text-blue-700 hover:bg-blue-100 tooltipForActions"
                                    data-tooltip="Accept"
                                >
                                    <i class="fas fa-check text-lg"></i>
                                </button>
                                <action-button type="reject" @click="toggleRejectConfirmationModal(); selectedUser = user, form.id = user.id" />
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
        height="long"
        title="Filters"
        class="antialiased"
    >
        <div class="flex flex-col space-y-4">
            <!-- Account Type Filter -->
            <div
                class="flex flex-col space-y-1"
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
    >
    <template #custom-button>

    </template>
    </Confirmation>

    <confirmation
        :showModal="rejectConfirmationModal"
        @close="toggleRejectConfirmationModal"
        title="Reject User"
        width="md"
        class="antialiased"
    >
        <template #message>
            <div>
                Are you sure you want to reject <b>{{ selectedUser.name }} </b>'s registration?
            </div>
            <div class="mt-3">
                <textarea v-model="form.reason" class="w-full h-[8rem] resize-none rounded-lg border-gray-400" placeholder="Enter reason"></textarea>
            </div>
        </template>
        <template #buttons>
            <modal-button text="Reject" color="red" @click="reject"/>
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
    "requestCount",
    "showInactive",
    "canEdit",
    "canFilter",
    "filters",
]);

// --------------------------------------------------------------------
const confirmationModal = ref(false);
const selectedUser = ref("");
const modaltype = ref("");
const title = ref("");
const showFilterModal = ref(false);
const rejectConfirmationModal = ref(false);

// --------------------------------------------------------------------
const query = useForm({
    show: props.filters.show != null ? props.filters.show : null,
    search: props.filters.search,
    type: props.filters.type != null ? props.filters.type : null,
});

const form = reactive({
    id: null,
    reason: null,
})

// --------------------------------------------------------------------
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

function toggleRejectConfirmationModal() {
    if (rejectConfirmationModal) {
        form.id = null;
        form.message = null;
    }
    rejectConfirmationModal.value = !rejectConfirmationModal.value;
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
</script>
