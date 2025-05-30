<template>
    <Head title="Higher Education Institution List" />
    <content-container
        :hasAdminPanel="true"
        pageTitle="Higher Education Institution List"
        :hasTopButton="canAdd"
        :hasSearch="true"
        :hasFilters="true"
        :data_list="institution_list"
    >
        <template v-slot:top-button>
            <div class="w-full md:w-auto">
                <Link href="/admin/higher-education-institutions/create">
                    <button
                        class="inline-flex w-full items-center justify-center px-4 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 focus:outline-none duration-200 ease-in-out shadow-sm"
                    >
                        &plus; Add HEI
                    </button>
                </Link>
            </div>
        </template>
        <template v-slot:search>
            <search-box v-model="query.search" @submit="filter" />
        </template>
        <template v-slot:options>
            <options
                @filter="toggleFilterModal"
                href="/admin/higher-education-institutions"
            />
        </template>
        <template v-slot:main-content>
            <content-table>
                <template v-slot:table-head>
                    <th>HEI Name</th>
                    <th><i class="fas fa-ellipsis-v text-lg"></i></th>
                </template>
                <template v-slot:table-body>
                    <tr v-if="institution_list.data.length == 0">
                        <no-search-result text="HEI" />
                    </tr>
                    <tr
                        v-else
                        v-for="hei in institution_list.data"
                        :key="hei.id"
                    >
                        <td>{{ hei.name }}</td>
                        <td>
                            <action-button type="view" @click="view(hei.id)"/>
                            <action-button v-show="canEdit" type="edit" @click="edit(hei.id)"/>
                            <action-button v-show="canDelete" type="delete" @click="toggleConfirmationModal(); selectedHEI = hei;"/>
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
        <div class="space-y-4">
            <filter-page-items v-model="query.show"/>
        </div>

        <template #custom-button>
            <modal-button text="Apply" color="green" @click="filter"/>
        </template>
    </modal>

    <Confirmation
        :showModal="confirmationModal"
        @close="toggleConfirmationModal"
        title="Delete HEI"
        width="md"
    >
        <template #message>
            <div>
                Are you sure you want to delete <b>{{ selectedHEI.name }}</b
                >? This action can't be undone.
            </div>
        </template>
        <template #buttons>
            <modal-button text="Delete" color="red" @click="deleteHEI"/>
        </template>
    </Confirmation>
</template>

<script setup>
// -----------------------------------------------------------------------------------------------------
import { ref } from "vue";
import { router, useForm } from "@inertiajs/vue3";
import Layout from "@/Shared/Layout.vue";
defineOptions({ layout: Layout });

// -----------------------------------------------------------------------------------------------------
const props = defineProps([
    "institution_list",
    "canEdit",
    "canAdd",
    "canDelete",
    "filters",
]);

// -----------------------------------------------------------------------------------------------------
const confirmationModal = ref(false);
const selectedHEI = ref("");
const showFilterModal = ref(false);

const query = useForm({
    show: props.filters.show != null ? props.filters.show : null,
    search: props.filters.search,
});

// -----------------------------------------------------------------------------------------------------
function toggleConfirmationModal() {
    confirmationModal.value = !confirmationModal.value;
}

function toggleFilterModal() {
    showFilterModal.value = !showFilterModal.value;
}

function filter() {
    query.get("/admin/higher-education-institutions", {
        preserveState: false,
        preserveScroll: true,
        replace: true,
    });
}

function edit(id) {
    router.get("/admin/higher-education-institutions/" + id + "/edit");
}

function view(id) {
    router.get("/admin/higher-education-institutions/" + id + "/view");
}

function deleteHEI() {
    router.get(`/admin/higher-education-institutions/${selectedHEI.value.id}/delete`);
}
</script>
