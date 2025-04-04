<template>
    <Head title="Higher Education Institution List" />
    <content-container
        :hasAdminPanel="true"
        pageTitle="Higher Education Institution List"
        page="hei"
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
                            <button
                                @click="view(hei.id)"
                                class="inline-flex items-center justify-center rounded-full h-10 w-10 text-emerald-700 hover:bg-emerald-100 tooltipForActions"
                                data-tooltip="View"
                            >
                                <i class="fas fa-eye text-lg"></i>
                            </button>
                            <button
                                v-show="canEdit"
                                @click="edit(hei.id)"
                                class="inline-flex items-center justify-center rounded-full h-10 w-10 text-blue-700 hover:bg-blue-100 tooltipForActions"
                                data-tooltip="Edit"
                            >
                                <i class="fas fa-edit text-lg"></i>
                            </button>
                            <button
                                v-show="canDelete"
                                @click="
                                    toggleConfirmationModal(
                                        hei,
                                        'deleteHEI',
                                        'Delete HEI'
                                    )
                                "
                                class="inline-flex items-center justify-center rounded-full h-10 w-10 text-red-700 hover:bg-red-100 tooltipForActions"
                                data-tooltip="Delete"
                            >
                                <i class="fas fa-trash text-lg"></i>
                            </button>
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
        class="antialiased"
    >
        <div class="space-y-6">
            <div class="flex flex-col space-y-2">
                <label for="show" class="text-sm font-medium text-gray-700">
                    Items per page
                </label>
                <select
                    v-model="query.show"
                    id="show"
                    class="block w-full px-3 py-2 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="150">150</option>
                    <option value="200">200</option>
                    <option value="300">300</option>
                </select>
            </div>
        </div>

        <template #custom-button>
            <button
                @click="filter"
                class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 duration-200"
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
        :selected="selectedHEI"
        width="md"
    />
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
const modaltype = ref("");
const title = ref("");
const showFilterModal = ref(false);

const query = useForm({
    show: props.filters.show != null ? props.filters.show : null,
    search: props.filters.search,
});

// -----------------------------------------------------------------------------------------------------
function toggleConfirmationModal(id, type, titleValue) {
    confirmationModal.value = !confirmationModal.value;
    selectedHEI.value = id;
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
</script>
