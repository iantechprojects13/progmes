<template>
    <Head title="Program List" />
    <content-container
        :hasAdminPanel="true"
        pageTitle="Program List"
        page="program"
        :hasTopButton="canAdd"
        :hasNavigation="true"
        :hasSearch="true"
        :hasFilters="true"
        :data_list="program_list"
    >
        <template v-slot:top-button>
            <div class="w-full">
                <Link href="/admin/program/create">
                    <button
                        class="inline-flex w-full items-center justify-center px-4 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 focus:outline-none duration-200 ease-in-out shadow-sm"
                    >
                        &plus; Add Program
                    </button>
                </Link>
            </div>
        </template>
        <template v-slot:navigation>
            <main-content-nav
                btnText="Program"
                routeName="admin.program.list"
                :isActive="true"
            />
            <main-content-nav
                btnText="Discipline"
                routeName="admin.discipline.list"
            />
        </template>
        <template v-slot:search>
            <search-box v-model="query.search" @submit="filter" />
        </template>
        <template v-slot:options>
            <options @filter="toggleFilterModal" href="/admin/program/" />
        </template>

        <template v-slot:main-content>
            <content-table>
                <template v-slot:table-head>
                    <th>Program</th>
                    <th>Major</th>
                    <th>Discipline</th>
                    <th>
                        <i class="fas fa-ellipsis-v text-lg"></i>
                    </th>
                </template>

                <template v-slot:table-body>
                    <tr v-if="program_list.data.length == 0">
                        <no-search-result text="program" />
                    </tr>

                    <tr
                        v-else
                        v-for="program in program_list.data"
                        :key="program.id"
                    >
                        <td>{{ program.program }}</td>
                        <td>{{ program.major }}</td>
                        <td>{{ program.discipline?.discipline }}</td>
                        <td>
                            <action-button v-show="canEdit" type="edit" @click="edit(program.id)"/>
                            <action-button v-show="canDelete" type="delete" @click="toggleConfirmationModal(); selectedProgram = program;"/>
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
        <div class="flex flex-col space-y-4">
            <filter-page-items v-model="query.show" />
            <filter-discipline v-model="query.discipline" :list="discipline_list"/>
        </div>

        <template #custom-button>
            <modal-button text="Apply" color="green" @click="filter" />
        </template>
    </modal>

    <Confirmation
        :showModal="confirmationModal"
        @close="closeModal"
        title="Delete Program"
        width="md"
    >
        <template #message>
            <div>
                Are you sure you want to delete
                <b
                    >{{ selectedProgram.program
                    }}<span v-if="selectedProgram.major != null">
                        - {{ selectedProgram.major }}</span
                    ></b
                >? This action can't be undone.
            </div>
        </template>
        <template #buttons>
            <modal-button text="Delete" color="red" @click="deleteProgram" />
        </template>
    </Confirmation>
</template>

<script setup>
// ----------------------------------------------------------------------------
import { ref } from "vue";
import { useForm, router } from "@inertiajs/vue3";
import Layout from "@/Shared/Layout.vue";
defineOptions({ layout: Layout });

// ----------------------------------------------------------------------------
const props = defineProps([
    "program_list",
    "canEdit",
    "canAdd",
    "canDelete",
    "filters",
    "discipline_list",
]);

// ----------------------------------------------------------------------------
const confirmationModal = ref(false);
const selectedProgram = ref("");
const showFilterModal = ref(false);

const query = useForm({
    show: props.filters.show != null ? props.filters.show : null,
    discipline: props.filters.discipline != null ? props.filters.discipline : null,
    search: props.filters.search,
});

// ----------------------------------------------------------------------------
function toggleConfirmationModal() {
    confirmationModal.value = !confirmationModal.value;
}

function closeModal() {
    confirmationModal.value = false;
}

function toggleFilterModal() {
    showFilterModal.value = !showFilterModal.value;
}

function filter() {
    query.get("/admin/program", {
        preserveState: false,
        preserveScroll: true,
        replace: true,
    });
}

function edit(id) {
    router.get("/admin/program/" + id + "/edit");
}

function deleteProgram() {
    router.get("/admin/program/" + selectedProgram.value.id + "/delete");
}
</script>
