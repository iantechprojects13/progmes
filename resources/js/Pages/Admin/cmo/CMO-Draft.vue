<template>
    <Head title="CHED Memorandum Order Draft List" />
    <content-container
        :hasAdminPanel="true"
        pageTitle="CMO Draft List"
        page="cmo"
        :hasTopButton="true"
        :hasSearch="true"
        :hasFilters="true"
        :hasNavigation="true"
        :data_list="cmo_list"
    >
        <template v-slot:top-button>
            <div v-show="canEdit" class="w-full flex flex-col md:flex-row items-center gap-1">
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
            />
            <main-content-nav
                btnText="Draft"
                routeName="admin.cmo.draft"
                :isActive="true"
            />
        </template>
        <template v-slot:search>
            <search-box v-model="query.search" @submit="filter" />
        </template>
        <template v-slot:options>
            <options @filter="toggleFilterModal" href="/admin/CMOs/draft" />
        </template>

        <template v-slot:main-content>
            <content-table>
                <template v-slot:table-head>
                    <th>CMO</th>
                    <th>Program</th>
                    <th
                        v-show="
                            $page.props.auth.user.role == 'Super Admin' ||
                            $page.props.auth.user.role == 'Admin' ||
                            $page.props.auth.user.role == 'Education Supervisor'
                        "
                    >
                        Imported by
                    </th>
                    <th v-show="canEdit">
                        <i class="fas fa-ellipsis-v text-lg"></i>
                    </th>
                </template>
                <template v-slot:table-body>
                    <tr v-if="cmo_list.data.length == 0">
                        <no-search-result
                            text="CMO"
                            :search="props.filters.search"
                        />
                    </tr>
                    <tr v-else v-for="cmo in cmo_list.data" :key="cmo.id">
                        <td>
                            <div>
                                CMO No.{{ cmo.number ? cmo.number : '*' }}, S.{{ cmo.series ? cmo.series : '*' }}
                            </div>
                        </td>
                        <td>
                            <div v-if="cmo.program">
                                {{ cmo.program?.program }}
                            <span v-if="cmo.program?.major">
                                - {{ cmo.program?.major }}
                            </span>
                            </div>
                            <div v-else>
                                -
                            </div>
                        </td>
                        <td>
                            <div v-if="cmo.created_by?.name == null">-</div>
                            <div
                                v-else-if="
                                    cmo.created_by?.name !=
                                    $page.props.auth.user.name
                                "
                            >
                                {{ cmo.created_by?.name }}
                            </div>
                            <div v-else>Me</div>
                        </td>
                        <td>
                            <div class="flex justify-end gap-0">
                                <action-button type="view" @click="view(cmo.id)" />
                                <action-button type="edit" @click="edit(cmo.id)" />
                                <action-button type="publish" @click="togglePublishModal(); selectedCMO = cmo;"/>
                                <action-button type="delete" @click="toggleDeleteModal(); selectedCMO = cmo;" />
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
        width="sm"
        title="Filters"
    >
        <div class="flex flex-col space-y-4">
            <filter-program v-model="query.program" :data="program_list"/>
            <filter-page-items v-model="query.show"/>
        </div>

        <template #custom-button>
            <modal-button text="Apply" color="green" @click="filter"/>
        </template>
    </modal>

    <confirmation
        :showModal="deleteModal"
        @close="toggleDeleteModal"
        title="Delete CMO"
        width="md"
    >
        <template #message>
            <div>
                Are you sure you want to delete this CMO? This action can't
                be undone.
            </div>
        </template>
        <template #buttons>
            <modal-button text="Delete" color="red" @click="deleteCMO"/>
        </template>
    </confirmation>

    <confirmation
        :showModal="publishModal"
        @close="togglePublishModal"
        title="Publish CMO"
        width="md"
    >
        <template #message>
            <div>
                Are you sure you want to publish this CMO? Once published, the document cannot be edited further.
            </div>
        </template>
        <template #buttons>
            <modal-button text="Publish" color="blue" @click="publishCMO"/>
        </template>
    </confirmation>
    
    <notification
        v-if="$page.props.errors.file"
        :message="$page.props.errors.file"
        type="failed"
    />
</template>

<script setup>
// -----------------------------------------------------------------------------------------------------------------
import { ref, watch } from "vue";
import { useForm, router } from "@inertiajs/vue3";
import Layout from "@/Shared/Layout.vue";
defineOptions({ layout: Layout });

// -----------------------------------------------------------------------------------------------------------------
const props = defineProps(["cmo_list", "canEdit", "filters", "program_list"]);

// -----------------------------------------------------------------------------------------------------------------
const confirmationModal = ref(false);
const selectedCMO = ref("");
const modaltype = ref("");
const title = ref("");
const cmo_file = ref(null);
const importing = ref(false);
const showFilterModal = ref(false);
const deleteModal = ref(false);
const publishModal = ref(false);

const query = useForm({
    show: props.filters.show != null ? props.filters.show : null,
    search: props.filters.search,
    program: props.filters.program != null ? props.filters.program : null,
});

// ------------------------------------------------------------------------------------------------------------------
function toggleConfirmationModal(id, type, titleValue) {
    confirmationModal.value = !confirmationModal.value;
    selectedCMO.value = id;
    modaltype.value = type;
    title.value = titleValue;
}

function toggleDeleteModal() {
    deleteModal.value = !deleteModal.value;
}

function togglePublishModal() {
    publishModal.value = !publishModal.value;
}

function toggleFilterModal() {
    showFilterModal.value = !showFilterModal.value;
}

function filter() {
    query.get("/admin/CMOs/draft", {
        preserveState: false,
        preserveScroll: true,
        replace: true,
    });
}

function edit(id) {
    router.get("/admin/CMOs/draft/" + id + "/edit");
}

function view(id) {
    router.get("/admin/CMOs/draft/" + id + "/view");
}

function deleteCMO() {
    router.get(`/admin/CMOs/${selectedCMO.value.id}/delete`, {
        preserveScroll: true,
        preserveState: false,
        replace: true,
    });
}

function publishCMO() {
    router.get(`/admin/CMOs/${selectedCMO.value.id}/publish`, {
        preserveScroll: true,
        preserveState: false,
        replace: true,
    });
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
