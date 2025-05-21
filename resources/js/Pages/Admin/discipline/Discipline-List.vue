<template>
    <Head title="Discipline List" />
    <content-container
        :hasAdminPanel="true"
        pageTitle="Discipline List"
        page="discipline"
        :hasTopButton="canAdd"
        :hasNavigation="true"
        :hasSearch="true"
        :hasFilters="true"
        :data_list="discipline_list"
    >
        <template v-slot:top-button>
            <div class="w-full">
                <Link href="/admin/program/discipline/create">
                    <button
                        class="inline-flex w-full items-center justify-center px-4 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 focus:outline-none duration-200 ease-in-out shadow-sm"
                    >
                        &plus; Add Discipline
                    </button>
                </Link>
            </div>
        </template>
        <template v-slot:navigation>
            <main-content-nav
                btnText="Program"
                routeName="admin.program.list"
            />
            <main-content-nav
                btnText="Discipline"
                routeName="admin.discipline.list"
                :isActive="true"
            />
        </template>
        <template v-slot:search>
            <search-box v-model="query.search" @submit="filter" />
        </template>
        <template v-slot:options>
            <options
                @filter="toggleFilterModal"
                href="/admin/program/discipline"
            />
        </template>
        <template v-slot:main-content>
            <content-table>
                <template v-slot:table-head>
                    <th>Discipline</th>
                    <th><i class="fas fa-ellipsis-v text-lg"></i></th>
                </template>
                <template v-slot:table-body>
                    <tr v-if="discipline_list.data.length === 0">
                        <no-search-result text="discipline" />
                    </tr>

                    <tr
                        v-else
                        v-for="discipline in discipline_list.data"
                        :key="discipline.id"
                    >
                        <td>
                            {{ discipline.discipline }}
                        </td>
                        <td>
                            <action-button v-show="canEdit" type="edit" @click="edit(discipline.id)"/>
                            <action-button v-show="canDelete" type="delete" @click="toggleConfirmationModal(); selectedDiscipline = discipline;" />
                        </td>
                    </tr>
                </template>
            </content-table>
        </template>
    </content-container>

    <modal
        :showModal="showFilterModal"
        title="Filters"
        @close="toggleFilterModal"
        width="md"
    >
        <filter-page-items v-model="query.show"/>

        <template #custom-button>
            <modal-button text="Apply" color="green" @click="filter"/>
        </template>
    </modal>

    <Confirmation
        :showModal="confirmationModal"
        @close="toggleConfirmationModal"
        title="Delete Discipline"
        width="md"
    >
        <template #message>
            <div>
                Are you sure you want to delete
                <b>{{ selectedDiscipline.discipline }}</b
                >? This action can't be undone.
            </div>
        </template>
        <template #buttons>
            <modal-button text="Delete" color="red" @click="deleteDiscipline"/>
        </template>
    </Confirmation>
</template>

<script setup>
// -----------------------------------------------------------------------------------
import { ref } from "vue";
import { useForm, router } from "@inertiajs/vue3";
import Layout from "@/Shared/Layout.vue";
defineOptions({layout: Layout});

// -----------------------------------------------------------------------------------
const props = defineProps([
    "discipline_list",
    "canEdit",
    "canAdd",
    "canDelete",
    "filters",
]);

// -----------------------------------------------------------------------------------
const confirmationModal = ref(false);
const selectedDiscipline = ref("");
const showFilterModal = ref(false);

const query = useForm({
    show: props.filters.show != null ? props.filters.show : null,
    search: props.filters.search,
});

// -----------------------------------------------------------------------------------
function toggleConfirmationModal() {
    confirmationModal.value = !confirmationModal.value;
}

function toggleFilterModal() {
    showFilterModal.value = !showFilterModal.value;
}

function filter() {
    query.get("/admin/program/discipline", {
        preserveState: false,
        preserveScroll: true,
        replace: true,
    });
}

function edit(id) {
    router.get("/admin/program/discipline/" + id + "/edit");
}

function deleteDiscipline() {
    router.get('/admin/discipline/' + selectedDiscipline.value.id + '/delete');
}
</script>
