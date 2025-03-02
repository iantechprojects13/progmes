<template>
    <Head title="CHED Memorandum Order List" />
    <content-container :hasAdminPanel="true" pageTitle="CHED Memorandum Order List" page="cmo" :hasTopButton="true" :hasSearch="true" :hasFilters="true"
        :hasNavigation="canDraft" :data_list="cmo_list">
        <template v-slot:top-button>
            <div v-show="canImport">
                <input ref="uploadfile" hidden type="file" @change="evidence_file = $event.target.files[0]" />
                <button @click.prevent="$refs.uploadfile.click()" :disabled="importing"
                    class="select-none bg-blue-500 hover:bg-blue-600 h-10 px-3 rounded text-white text whitespace-nowrap">
                    <span v-if="importing"><i class="fas fa-spinner animate-spin"></i></span>
                    <span v-else><i class="fas fa-file-import mr-2 text-base"></i>Import CMO</span>
                </button>
            </div>
        </template>
        <template v-slot:navigation>
            <div>
                <button class="select-none h-12 w-28 hover:bg-gray-100 text-blue-500 border-b-4 relative font-bold border-blue-500">
                    Published
                </button>
                <Link :href="route('admin.cmo.draft')">
                <button class="select-none h-12 w-24 hover:bg-gray-100 text-gray-700 hover:text-black">
                    Draft
                </button>
                </Link>
            </div>
        </template>
        <template v-slot:search>
            <div class="w-full flex flex-row relative items-center">
                <i class="fa fa-search text-gray-400 absolute left-5"></i>
                <input @keydown.enter="submit" v-model="query.search" type="search" id="content-search"
                    placeholder="Search"
                    class="w-full rounded-lg border border-gray-300 indent-10 h-10 text-base placeholder-gray-400" />
            </div>
        </template>
        <template v-slot:options>
            <div class="mr-1">
                
                <button @click="toggleFilterModal"
                    class="w-10 h-10 whitespace-nowrap hover:bg-gray-200 rounded-full text-gray-700 hover:text-blue-500 active:text-white active:bg-blue-600 tooltipForActions"
                    data-tooltip="Filters">
                    <i class="fas fa-filter"></i>
                </button>
                <Link href="/admin/CMOs">
                <button
                    class="w-10 h-10 whitespace-nowrap hover:bg-gray-200 rounded-full text-gray-700 hover:text-blue-500 active:text-white active:bg-blue-600 tooltipForActions"
                    data-tooltip="Refresh page">
                    <i class="fas fa-refresh"></i>
                </button>
                </Link>
            </div>
        </template>
        <template v-slot:main-content>
            <content-table>
                <template v-slot:table-head>
                    <th class="p-2 pl-5">CHED Memorandum Order</th>
                    <th class="p-2">Program</th>
                    <th class="p-2">Active Status</th>
                    <th v-show="canEdit" class="p-2 pr-5 text-right">
                        <i class="fas fa-ellipsis-v"></i>
                    </th>
                </template>
                <template v-slot:table-body>
                    <tr v-if="cmo_list.data.length == 0">
                        <td class="text-center py-10" colspan="3">
                            No CMO found
                        </td>
                    </tr>
                    <tr v-else v-for="(cmo, index) in cmo_list.data" :key="cmo.id" class="hover:bg-gray-200 border-b"
                        :class="{ 'bg-gray-100': index % 2 == 1 }">
                        <td class="p-2 pl-5">
                            CMO No.{{ cmo.number }} S. {{ cmo.series }},
                            Version {{ cmo.version }}
                        </td>
                        <td class="p-2">
                            {{ cmo.program?.program }}
                            <span v-if="cmo.program?.major">
                                - {{ cmo.program?.major }}
                            </span>
                        </td>
                        <td class="p-2">
                            <div v-if="cmo.isActive" class="w-fit text-xs px-1 rounded bg-green-500 text-white whitespace-nowrap">
                                Active
                            </div>
                            <div v-else class="w-fit text-xs px-1 rounded text-white bg-gray-500 whitespace-nowrap">
                                Not Active
                            </div>
                        </td>
                        <td class="p-2 pr-5 text-right whitespace-nowrap">
                            <button @click="view(cmo.id)"
                                class="select-none h-8 w-8 text-xl text-center rounded-full hover:bg-gray-300 text-green-500 hover:text-green-600 tooltipForActions"
                                data-tooltip="View">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button v-show="canEdit" v-if="!cmo.isActive"
                                @click="toggleConfirmationModal(cmo, 'activate', 'Activate CMO')"
                                class="select-none h-8 w-8 text-xl text-center rounded-full hover:bg-gray-300 text-gray-500 hover:text-gray-600 tooltipForActions"
                                data-tooltip="Activate">
                                <i class="fas fa-toggle-off"></i>
                            </button>
                            <button v-show="canEdit" v-else
                                @click="toggleConfirmationModal(cmo, 'deactivate', 'Deactivate CMO')"
                                class="select-none h-8 w-8 text-xl text-center rounded-full hover:bg-gray-300 text-blue-500 hover:text-blue-600 tooltipForActions"
                                data-tooltip="Deactivate">
                                <i class="fas fa-toggle-on"></i>
                            </button>
                            <button v-show="canEdit"
                                @click="toggleConfirmationModal(cmo, 'deleteCMO', 'Delete Published CMO')"
                                class="select-none h-8 w-8 text-xl text-center rounded-full hover:bg-gray-300 text-red-500 hover:text-red-600 tooltipForActions"
                                data-tooltip="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                </template>
            </content-table>
        </template>
    </content-container>
    <modal :showModal="showFilterModal" @close="toggleFilterModal" width="sm" height="long" title="Filters">
        <div>
            <div class="flex flex-col">
                <label for="isActive">Active Status</label>
                <select v-model="query.isActive" id="isActive" class="rounded border-gray-400">
                    <option :value="null">All</option>
                    <option :value="1">Active</option>
                    <option :value="0">Not Active</option>
                </select>
            </div>
            <div class="mt-3 flex flex-col">
                <label for="show">Items per page</label>
                <select v-model="query.show" id="show" class="rounded border-gray-400">
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="200">200</option>
                </select>
            </div>
        </div>
        <template v-slot:custom-button>
            <button @click="filter" class="text-white bg-green-600 hover:bg-green-700 w-20 rounded h-10">
                <span v-if="processing">
                    <i class="fas fa-spinner animate-spin"></i>
                </span>
                <span v-else>Apply</span>
            </button>
        </template>
    </modal>
   
    <Confirmation :showModal="confirmationModal" @close="closeModal" :title="title" :modaltype="modaltype" :selected="selectedCMO" width="md" height="short"/>
</template>

<script setup>
import { useForm, router } from "@inertiajs/vue3";
import { ref, watch } from "vue";

const evidence_file = ref(null);
const importing = ref(false);


const showFilterModal = ref(false);
const processing = ref(false);

function view(id) {
    router.get('/admin/CMOs/' + id + '/view');
}

const props = defineProps([
    'cmo_list',
    'canEdit',
    'canImport',
    'canDraft',
    'filters',
]);

const query = useForm({
    show: props.filters.show != null ? props.filters.show : null,
    search: props.filters.search,
    isActive: props.filters.isActive != null ? props.filters.isActive : null,
});

function toggleFilterModal() {
    showFilterModal.value = !showFilterModal.value;
}

function submit() {
    query.get("/admin/CMOs", {
        onStart: () => {
            processing.value = true;
        },
        onFinish: () => {
            processing.value = false;
        },
        preserveState: false,
        preserveScroll: true,
        replace: true,
    });
}

function filter() {
    query.get("/admin/CMOs", {
        onStart: () => {
            processing.value = true;
        },
        onFinish: () => {
            processing.value = false;
            toggleFilterModal();
        },
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
}

watch(evidence_file, (value) => {
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

            toggleConfirmationModal(cmo, type, title) {
                this.confirmationModal = !this.confirmationModal;
                this.selectedCMO = cmo;
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