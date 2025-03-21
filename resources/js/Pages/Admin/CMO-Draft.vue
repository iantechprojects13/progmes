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
            <div v-show="canEdit">
                <input
                    ref="uploadfile"
                    type="file"
                    class="hidden"
                    @change="cmo_file = $event.target.files[0]"
                />

                <button
                    @click.prevent="$refs.uploadfile.click()"
                    :disabled="importing"
                    class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 focus:outline-none transition duration-150 ease-in-out disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <span v-if="importing" class="flex items-center">
                        <svg
                            class="animate-spin h-5 w-5 mr-2"
                            fill="none"
                            viewBox="0 0 24 24"
                        >
                            <circle
                                class="opacity-25"
                                cx="12"
                                cy="12"
                                r="10"
                                stroke="currentColor"
                                stroke-width="4"
                            ></circle>
                            <path
                                class="opacity-75"
                                fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                            ></path>
                        </svg>
                        Importing...
                    </span>

                    <span v-else class="flex items-center">
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
            <main-content-nav page="draft" managementType="cmo">
            </main-content-nav>
        </template>
        <template v-slot:search>
            <div class="relative">
                <div
                    class="absolute inset-y-0 left-3 flex items-center pointer-events-none"
                >
                    <svg
                        class="w-5 h-5 text-gray-400"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                        />
                    </svg>
                </div>

                <input
                    @keydown.enter="submit"
                    v-model="query.search"
                    type="search"
                    id="content-search"
                    placeholder="Search"
                    class="w-full py-2.5 pl-11 pr-4 bg-white border border-gray-200 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors duration-200"
                />
            </div>
        </template>
        <template v-slot:options>
            <div class="flex gap-2">
                <button
                    @click="toggleFilterModal"
                    class="p-2.5 inline-flex items-center justify-center rounded-lg text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 transition-all duration-200 tooltipForActions"
                    data-tooltip="Filters"
                >
                    <svg
                        class="w-5 h-5"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"
                        />
                    </svg>
                    <span
                        class="absolute -top-8 left-1/2 -translate-x-1/2 px-2 py-1 bg-gray-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200"
                    >
                        Filters
                    </span>
                </button>

                <Link href="/admin/CMOs/draft">
                    <button
                        class="p-2.5 inline-flex items-center justify-center rounded-lg text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 transition-all duration-200 tooltipForActions"
                        data-tooltip="Refresh page"
                    >
                        <svg
                            class="w-5 h-5"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                            />
                        </svg>
                    </button>
                </Link>
            </div>
        </template>
        <template v-slot:main-content>
            <content-table>
                <template v-slot:table-head>
                    <th
                        class="px-6 py-4 text-left text-base font-bold text-gray-800"
                    >
                        CMO
                    </th>
                    <th
                        class="px-6 py-4 text-left text-base font-bold text-gray-800"
                    >
                        Program
                    </th>
                    <th
                        class="px-6 py-4 text-left text-base font-bold text-gray-800"
                        v-show="
                            $page.props.auth.user.role == 'Super Admin' ||
                            $page.props.auth.user.role == 'Admin'
                        "
                    >
                        Imported by
                    </th>
                    <th v-show="canEdit" class="px-6 py-4 text-right w-24">
                        <span class="text-gray-800">
                            <i class="fas fa-ellipsis-v text-lg"></i>
                        </span>
                    </th>
                </template>
                <template v-slot:table-body>
                    <tr v-if="cmo_list.data.length == 0">
                        <no-search-result
                            text="CMO"
                            :search="props.filters.search"
                        />
                    </tr>
                    <tr
                        v-else
                        v-for="(cmo, index) in cmo_list.data"
                        :key="cmo.id"
                        class="transition-colors hover:bg-blue-200 border-b border-gray-200"
                        :class="{ 'bg-slate-200': index % 2 === 1 }"
                    >
                        <td class="px-6 py-2 text-gray-900 align-top">
                            <div
                                v-if="
                                    cmo.number != null &&
                                    cmo.series != null &&
                                    cmo.version != null
                                "
                            >
                                CMO No.{{ cmo.number }} Series of
                                {{ cmo.series }}, Version {{ cmo.version }}
                            </div>
                            <div v-else>-</div>
                        </td>
                        <td class="px-6 py-2 text-gray-900 align-top min-w-12">
                            {{ cmo.program?.program }}
                            <span v-if="cmo.program?.major">
                                - {{ cmo.program?.major }}
                            </span>
                        </td>
                        <td
                            class="px-6 py-2 text-gray-900 align-top"
                            v-show="
                                $page.props.auth.user.role == 'Super Admin' ||
                                $page.props.auth.user.role == 'Admin'
                            "
                        >
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
                        <td class="px-6 py-2 align-top whitespace-nowrap">
                            <button
                                @click="view(cmo.id)"
                                class="inline-flex items-center justify-center rounded-full h-10 w-10 text-emerald-700 hover:bg-emerald-100 transition-colors tooltipForActions"
                                data-tooltip="View"
                            >
                                <i class="fas fa-eye text-lg"></i>
                            </button>
                            <button
                                @click="edit(cmo.id)"
                                class="inline-flex items-center justify-center rounded-full h-10 w-10 text-blue-700 hover:bg-blue-100 transition-colors tooltipForActions"
                                data-tooltip="Edit"
                            >
                                <i class="fas fa-edit text-lg"></i>
                            </button>
                            <button
                                @click="
                                    toggleConfirmationModal(
                                        cmo,
                                        'publish',
                                        'Publish CMO'
                                    )
                                "
                                class="inline-flex items-center justify-center rounded-full h-10 w-10 text-blue-700 hover:bg-blue-100 transition-colors tooltipForActions"
                                data-tooltip="Publish"
                            >
                                <i class="fas fa-paper-plane text-lg"></i>
                            </button>
                            <button
                                @click="
                                    toggleConfirmationModal(
                                        cmo,
                                        'deleteCMO',
                                        'Delete Draft'
                                    )
                                "
                                class="inline-flex items-center justify-center rounded-full h-10 w-10 text-red-700 hover:bg-red-100 transition-colors tooltipForActions"
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
        @close="toggleFilterModal"
        width="sm"
        height="long"
        title="Filters"
        class="antialiased"
    >
        <div>
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
                class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200 w-20 h-10"
            >
                <span v-if="processing">
                    <i class="fas fa-spinner animate-spin"></i>
                </span>
                <span v-else>Apply</span>
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
        height="short"
    />
</template>

<script setup>
import { useForm, router } from "@inertiajs/vue3";
import { ref, watch } from "vue";

const props = defineProps(["cmo_list", "canEdit", "filters"]);

const cmo_file = ref(null);
const importing = ref(false);
const showFilterModal = ref(false);
const processing = ref(false);
const searchValue = ref(props.filters.search);

const form = useForm({
    cmo_file: null,
});

function edit(id) {
    router.get("/admin/CMOs/draft/" + id + "/edit");
}

function view(id) {
    router.get("/admin/CMOs/draft/" + id + "/view");
}

const query = useForm({
    show: props.filters.show != null ? props.filters.show : null,
    search: props.filters.search,
});

function toggleFilterModal() {
    showFilterModal.value = !showFilterModal.value;
}

function submit() {
    query.get("/admin/CMOs/draft", {
        onStart: () => {
            processing.value = true;
        },
        onFinish: () => {
            processing.value = false;
        },
        preserveState: false,
        preserveScroll: true,
    });
}

function filter() {
    query.get("/admin/CMOs/draft", {
        onStart: () => {
            processing.value = true;
        },
        onFinish: () => {
            processing.value = false;
            toggleFilterModal();
        },
        preserveState: true,
        preserveScroll: true,
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

<script>
import Layout from "@/Shared/Layout.vue";
export default {
    data() {
        return {
            openCreateDropdown: false,
            openListOption: false,
            uploadModal: false,
            confirmationModal: false,
            selectedCMO: "",
            modaltype: "",
            title: "",
        };
    },

    methods: {
        openUploadModal() {
            this.uploadModal = !this.uploadModal;
        },

        toggleConfirmationModal(id, type, title) {
            this.confirmationModal = !this.confirmationModal;
            this.selectedCMO = id;
            this.modaltype = type;
            this.title = title;
        },

        closeModal() {
            this.confirmationModal = false;
            this.uploadModal = false;
        },
    },

    layout: Layout,
};
</script>
