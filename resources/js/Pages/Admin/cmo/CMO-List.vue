<template>
    <Head title="CHED Memorandum Order List" />
    <content-container
        :hasAdminPanel="true"
        pageTitle="CHED Memorandum Order List"
        page="cmo"
        :hasTopButton="true"
        :hasSearch="true"
        :hasFilters="true"
        :hasNavigation="canDraft"
        :data_list="cmo_list"
    >
        <template v-slot:top-button>
            <div v-show="canImport" class="w-full flex flex-col md:flex-row items-center gap-1">
                <input
                    ref="uploadfile"
                    type="file"
                    class="hidden"
                    @change="cmo_file = $event.target.files[0]"
                />

                <button
                    @click.prevent="$refs.uploadfile.click()"
                    :disabled="importing"
                    class="whitespace-nowrap inline-flex w-full items-center justify-center px-4 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 focus:outline-none transition duration-150 ease-in-out disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <span class="flex items-center">
                        <svg
                            class="w-5 h-5 mr-2"
                            viewBox="0 0 24 24"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                d="M12 4V16M12 16L8 12M12 16L16 12"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                            <path
                                d="M4 20H20"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                        </svg>
                        Import CMO
                    </span>
                </button>
            </div>
        </template>
        <template v-slot:navigation>
            <main-content-nav
                btnText="Published"
                routeName="admin.cmo.list"
                :isActive="true"
            />
            <main-content-nav
                btnText="Draft"
                routeName="admin.cmo.draft"
            />
        </template>

        <template v-slot:search>
            <search-box v-model="query.search" @submit="filter" />
        </template>
        <template v-slot:options>
            <options @filter="toggleFilterModal" href="/admin/CMOs" />
        </template>

        <template v-slot:main-content>
            <content-table>
                <template v-slot:table-head>
                    <th>CHED Memorandum Order</th>
                    <th>Program</th>
                    <th>Active Status</th>
                    <th>
                        <i class="fas fa-ellipsis-v text-lg"></i>
                    </th>
                </template>
                <template v-slot:table-body>
                    <tr v-if="cmo_list.data.length == 0">
                        <no-search-result text="CMO" />
                    </tr>
                    <tr v-else v-for="cmo in cmo_list.data" :key="cmo.id">
                        <td>
                            CMO No.{{ cmo.number }}, S.{{ cmo.series }}
                            <!-- , Version {{ cmo.version }} -->
                        </td>
                        <td>
                            {{ cmo.program?.program }}
                            <span v-if="cmo.program?.major">
                                - {{ cmo.program?.major }}
                            </span>
                        </td>
                        <td>
                            <status :text="cmo.isActive"></status>
                        </td>
                        <td>
                            <action-button type="view" @click="view(cmo.id)"/>
                            <action-button v-show="canEdit && $page.props.auth.user.role == 'Super Admin'" type="edit" @click="edit(cmo.id)" />
                            <action-button v-show="canEdit" v-if="!cmo.isActive" type="activateCMO" @click="toggleActivateModal(); selectedCMO = cmo;"/>
                            <action-button v-show="canEdit" v-else type="deactivateCMO" @click="toggleDeactivateModal(); selectedCMO = cmo;"/>
                            <action-button v-show="canEdit" type="delete" @click="toggleConfirmationModal(); selectedCMO = cmo;"/>
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
            <!-- Active Status Filter -->
            <div class="flex flex-col space-y-2">
                <label for="isActive" class="text-sm font-medium text-gray-700"
                    >Active Status</label
                >
                <select
                    v-model="query.isActive"
                    id="isActive"
                    class="block w-full px-3 py-2 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
                    <option :value="null">All</option>
                    <option :value="1">Active</option>
                    <option :value="0">Not Active</option>
                </select>
            </div>
            <filter-program v-model="query.program" :data="program_list"/>
            <filter-page-items v-model="query.show" />
        </div>

        <template #custom-button>
            <modal-button text="Apply" color="green" @click="filter"/>
        </template>
    </modal>

    <confirmation
        :showModal="activateModal"
        @close="toggleActivateModal"
        title="Activate CMO"
        width="md"
    >
        <template #message>
            <div>
                Are you sure you want to make
                <b
                    >CMO No.{{ selectedCMO?.number }} - Series of
                    {{ selectedCMO?.series }} - Version
                    {{ selectedCMO?.version }}</b
                >
                as active CMO for
                <b
                    >{{ selectedCMO?.program.program
                    }}<span v-if="selectedCMO?.program.major != null">
                        - {{ selectedCMO?.program.major }}</span
                    ></b
                >
                program?
            </div>
        </template>
        <template #buttons>
            <modal-button text="Activate" color="blue" @click="activate" />
        </template>
    </confirmation>

    <confirmation
        :showModal="deactivateModal"
        @close="toggleDeactivateModal"
        title="Deactivate CMO"
        width="md"
    >
        <template #message>
            <div>
                Are you sure you want to remove the activation from this CMO?
            </div>
        </template>
        <template #buttons>
            <modal-button text="Deactivate" color="red" @click="deactivate" />
        </template>
    </confirmation>

    <confirmation
        :showModal="confirmationModal"
        @close="toggleConfirmationModal"
        title="Delete CMO"
        width="md"
    >
        <template #message>
            <div>
                Are you sure you want to delete this CMO? This action can't be undone.
            </div>
        </template>
        <template #buttons>
            <modal-button text="Delete" color="red" @click="deleteCMO" />
        </template>
    </confirmation>
    

    <notification
        v-if="$page.props.errors.file"
        :message="$page.props.errors.file"
        type="failed"
    />
</template>

<script setup>
// -----------------------------------------------------------
import { ref, watch } from "vue";
import { useForm, router } from "@inertiajs/vue3";
import Layout from "@/Shared/Layout.vue";
defineOptions({ layout: Layout });

// -----------------------------------------------------------
const props = defineProps([
    "cmo_list",
    "program_list",
    "canEdit",
    "canImport",
    "canDraft",
    "filters",
]);

// -----------------------------------------------------------
const confirmationModal = ref(false);
const selectedCMO = ref(null);
const cmo_file = ref(null);
const importing = ref(false);
const showFilterModal = ref(false);
const activateModal = ref(false);
const deactivateModal = ref(false);

const query = useForm({
    search: props.filters.search,
    show: props.filters.show != null ? props.filters.show : null,
    isActive: props.filters.isActive != null ? props.filters.isActive : null,
    program: props.filters.program != null ? props.filters.program : null,
});

function toggleActivateModal() {
    activateModal.value = !activateModal.value;
}

function toggleDeactivateModal() {
    deactivateModal.value = !deactivateModal.value;
}

function toggleConfirmationModal() {
    confirmationModal.value = !confirmationModal.value;
}

function toggleFilterModal() {
    showFilterModal.value = !showFilterModal.value;
}

function filter() {
    query.get("/admin/CMOs", {
        preserveState: false,
        preserveScroll: true,
        replace: true,
    });
}

function view(id) {
    router.get("/admin/CMOs/" + id + "/view");
}

function edit(id) {
    router.get("/admin/CMOs/draft/" + id + "/edit");
}

function activate() {
    router.get(`/admin/CMOs/activate/${selectedCMO.value.id}`);
}

function deactivate() {
    router.get(`/admin/CMOs/deactivate/${selectedCMO.value.id}`);
}

function deleteCMO() {
    router.get(`/admin/CMOs/${selectedCMO.value.id}/delete`);
}


watch(cmo_file, (value) => {
    router.post(
        "/admin/CMOs/create/import",
        { file: value },
        {
            onStart: () => {
                importing.value = true;
            },
            onFinish: () => {
                importing.value = false;
            },
            preserveScroll: true,
            preserveState: false,
            replace: true,
        }
    );
});
</script>
