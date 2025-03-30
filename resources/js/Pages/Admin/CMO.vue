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
            <div v-show="canImport" class="w-full">
                <input
                    ref="uploadfile"
                    type="file"
                    class="hidden"
                    @change="cmo_file = $event.target.files[0]"
                />

                <button
                    @click.prevent="$refs.uploadfile.click()"
                    :disabled="importing"
                    class="inline-flex w-full items-center justify-center px-4 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 focus:outline-none transition duration-150 ease-in-out disabled:opacity-50 disabled:cursor-not-allowed"
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
            <main-content-nav page="published" managementType="cmo">
            </main-content-nav>
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
                    <th v-show="canEdit">
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
                            <div class="flex justify-end gap-0">
                                <button
                                    @click="view(cmo.id)"
                                    class="inline-flex items-center justify-center rounded-full h-10 w-10 text-emerald-700 hover:bg-emerald-100 tooltipForActions"
                                    data-tooltip="View"
                                >
                                    <i class="fas fa-eye text-lg"></i>
                                </button>

                                <button
                                    v-show="canEdit"
                                    v-if="!cmo.isActive"
                                    @click="
                                        toggleConfirmationModal(
                                            cmo,
                                            'activate',
                                            'Activate CMO'
                                        )
                                    "
                                    class="inline-flex items-center justify-center rounded-full h-10 w-10 text-blue-700 hover:bg-blue-100 tooltipForActions"
                                    data-tooltip="Activate"
                                >
                                    <i class="fas fa-toggle-off text-lg"></i>
                                </button>

                                <button
                                    v-show="canEdit"
                                    v-else
                                    @click="
                                        toggleConfirmationModal(
                                            cmo,
                                            'deactivate',
                                            'Deactivate CMO'
                                        )
                                    "
                                    class="inline-flex items-center justify-center rounded-full h-10 w-10 text-blue-700 hover:bg-blue-100 tooltipForActions"
                                    data-tooltip="Deactivate"
                                >
                                    <i class="fas fa-toggle-on text-lg"></i>
                                </button>

                                <button
                                    v-show="canEdit"
                                    @click="
                                        toggleConfirmationModal(
                                            cmo,
                                            'deleteCMO',
                                            'Delete Published CMO'
                                        )
                                    "
                                    class="inline-flex items-center justify-center rounded-full h-10 w-10 text-red-700 hover:bg-red-100 tooltipForActions"
                                    data-tooltip="Delete"
                                >
                                    <i class="fas fa-trash text-lg"></i>
                                </button>
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
        title="Filters"
        class="antialiased"
    >
        <div>
            <!-- Active Status Filter -->
            <div class="flex flex-col space-y-2 mb-6">
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

            <!-- Items per page Filter -->
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
        :selected="selectedCMO"
        width="md"
    />

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
    "canEdit",
    "canImport",
    "canDraft",
    "filters",
]);

// -----------------------------------------------------------
const confirmationModal = ref(false);
const selectedCMO = ref("");
const modaltype = ref("");
const title = ref("");
const cmo_file = ref(null);
const importing = ref(false);
const showFilterModal = ref(false);

const query = useForm({
    show: props.filters.show != null ? props.filters.show : null,
    search: props.filters.search,
    isActive: props.filters.isActive != null ? props.filters.isActive : null,
});

// -----------------------------------------------------------
function toggleConfirmationModal(id, type, titleValue) {
    confirmationModal.value = !confirmationModal.value;
    selectedCMO.value = id;
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
    query.get("/admin/CMOs", {
        preserveState: false,
        preserveScroll: true,
        replace: true,
    });
}

function view(id) {
    router.get("/admin/CMOs/" + id + "/view");
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
