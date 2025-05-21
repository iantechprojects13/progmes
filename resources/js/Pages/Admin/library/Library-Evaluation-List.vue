<template>
    <Head title="Library Compliance Evaluation Tool" />
    <content-container
        :hasAdminPanel="true"
        :hasModal="true"
        pageTitle="Library Compliance Evaluation Tool"
        page="library"
        :hasTopButton="true"
        :hasSearch="true"
        :hasFilters="true"
        :data_list="evaluation_list"
    >
        <template v-slot:top-button>
            <div
                class="flex flex-col md:flex-row gap-3 w-full whitespace-nowrap"
            >
                <Link
                    :href="route('library.cmo.view')"
                    class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 focus:outline-none transition duration-150 ease-in-out disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <span class="flex items-center">
                        <svg
                            class="w-5 h-5 mr-2"
                            viewBox="0 0 24 24"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <!-- Document Shape -->
                            <path
                                d="M7 3H14L19 8V21C19 21.5523 18.5523 22 18 22H6C5.44772 22 5 21.5523 5 21V5C5 3.89543 5.89543 3 7 3Z"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                            <!-- Folded Corner -->
                            <path
                                d="M14 3V8H19"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                        </svg>

                        View CMO
                    </span>
                </Link>
            </div>
        </template>

        <template v-slot:search>
            <search-box v-model="query.search" @submit="filter" />
        </template>
        <template v-slot:options>
            <options @filter="toggleFilterModal" href="/admin/library" />
        </template>

        <template v-slot:main-content>
            <content-table>
                <template v-slot:table-head>
                    <th>HEI</th>
                    <th>Status</th>
                    <th>
                        <i class="fas fa-ellipsis-v text-lg"></i>
                    </th>
                </template>
                <template v-slot:table-body>
                    <tr v-if="evaluation_list.data.length == 0">
                        <no-search-result text="compliance evaluation tool" />
                    </tr>
                    <tr
                        v-else
                        v-for="item in evaluation_list.data"
                        :key="item.id"
                        >
                        <td>
                            {{ item.institution?.name }}
                        </td>
                        <td>
                            <status :text="item.status" />
                        </td>
                        <td>
                            <action-button
                                v-show="$page.props.auth.user.role == 'Super Admin'"
                                type="delete"
                                @click="toggleDeleteModal(item)"
                            />
                        </td>
                    </tr>
                </template>
            </content-table>
        </template>
    </content-container>

    <Confirmation
        :showModal="deleteModal"
        @close="toggleDeleteModal"
        title="Delete Tool"
        width="md"
    >
    <template v-slot:message>
        <div v-if="toolStartedAlready" style="display: flex; align-items: center; gap: 10px; color: #b91c1c;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="50" height="50">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                    d="M12 9v2m0 4h.01M10.29 3.86l-7.09 12.3A1 1 0 004 18h16a1 1 0 00.87-1.5l-7.09-12.3a1 1 0 00-1.74 0z" />
            </svg>

            <div>
              <strong>Are you sure you want to delete this program compliance evaluation tool?</strong><br>
              Any input and evidence will be deleted as well.
            </div>
        </div>
        <div v-else>
            Are you sure you want to delete this library compliance evaluation tool?
        </div>
    </template>
    <template v-slot:buttons>
        <modal-button text="Delete" color="red" @click="deleteTool"/>
    </template>
    </Confirmation>

    <modal
        :showModal="showFilterModal"
        @close="toggleFilterModal"
        width="md"
        title="Filters"
    >
        <div class="flex flex-col space-y-4">
            <filter-form-status v-model="query.status"/>
            <filter-academic-year v-model="query.ay"/>
            <filter-page-items v-model="query.show"/>
        </div>

        <template #custom-button>
            <modal-button text="Apply" color="green" @click="filter"/>
        </template>
    </modal>

</template>

<script setup>
// -------------------------------------------------------------------------
import { useForm, router } from "@inertiajs/vue3";
import { ref } from "vue";
import Layout from "@/Shared/Layout.vue";
defineOptions({ layout: Layout });

// -------------------------------------------------------------------------
const props = defineProps([
    "evaluation_list",
    "filters",
]);

// -------------------------------------------------------------------------
const showFilterModal = ref(false);
const deleteModal = ref(false);
const toolStartedAlready = ref(false);
const selectedTool = ref(null);

const query = useForm({
    ay: props.filters.academicYear,
    show: props.filters.show != null ? props.filters.show : null,
    search: props.filters.search,
    status: props.filters.status != null ? props.filters.status : null,
});

// -------------------------------------------------------------------------
function toggleDeleteModal(tool) {
    if (tool) {
        selectedTool.value = tool.id;

        if (tool.status != 'Deployed') {
            toolStartedAlready.value = true;
        } else {
            toolStartedAlready.value = false;
        }

    } 

    deleteModal.value = !deleteModal.value;
}

function toggleFilterModal() {
    showFilterModal.value = !showFilterModal.value;
}

function filter() {
    query.get("/admin/library", {
        preserveState: false,
        preserveScroll: true,
        replace: true,
    });
}

function changeAcademicYear() {
    query.search = "";
    query.get("/admin/library", {
        preserveState: false,
        preserveScroll: true,
        replace: true,
    });
}

function deleteTool() {
    router.get(`/ched/library/evaluation/${selectedTool.value}/delete`, {
        preserveScroll: true,
        preserveState: false,
        replace: true,
    })
}

</script>
