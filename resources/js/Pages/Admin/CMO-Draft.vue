<template>

    <Head title="CHED Memorandum Order Draft List" />
    <content-container :hasAdminPanel="true" pageTitle="CMO Draft List" page="cmo" :hasTopButton="true" :hasSearch="true" :hasFilters="true"
        :hasNavigation="true" :data_list="cmo_list">
        <template v-slot:top-button>
            <div v-show="canEdit">
                <input ref="uploadfile" hidden type="file" @change="cmo_file = $event.target.files[0]">
                <button @click.prevent="$refs.uploadfile.click()" :disabled="importing"
                    class="select-none bg-blue-500 hover:bg-blue-600 h-10 px-3 rounded text-white whitespace-nowrap">
                    <span v-if="importing"><i class="fas fa-spinner animate-spin"></i></span>
                    <span v-else><i class="fas fa-file-import mr-2 text-base"></i>Import CMO</span>
                </button>
            </div>
        </template>
        <template v-slot:navigation>
            <div>
                <Link :href="route('admin.cmo.list')">
                <button class="select-none h-12 w-28 hover:bg-gray-100 text-gray-700 hover:text-black">
                    Published
                </button>
                </Link>
                <button class="select-none h-12 w-24 hover:bg-gray-100 text-blue-500 border-b-4 border-blue-500 relative font-bold">
                    Draft
                </button>
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
                <Link href="/admin/CMOs/draft">
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
                    <th class="p-2 pl-5">CMO</th>
                    <th class="p-2">Program</th>
                    <th class="p-2"
                        v-show="$page.props.auth.user.role == 'Super Admin' || $page.props.auth.user.role == 'Admin'">
                        Imported by</th>
                    <th v-show="canEdit" class="p-2 pr-5 text-right">
                        <i class="fas fa-ellipsis-v"></i>
                    </th>
                </template>
                <template v-slot:table-body>
                    <tr v-if="cmo_list.data.length == 0">
                        <no-search-result text="CMO" :search="props.filters.search"/>
                    </tr>
                    <tr v-else v-for="(cmo, index) in cmo_list.data" :key="cmo.id" class="hover:bg-gray-200 border-b"
                        :class="{'bg-gray-100': index % 2 == 1}">
                        <td class="p-2 pl-5 min-w-12">
                            <div v-if="cmo.number != null && cmo.series != null && cmo.version != null">
                                CMO No.{{ cmo.number }} Series of {{cmo.series}}, Version {{cmo.version}}
                            </div>
                            <div v-else>
                                -
                            </div>
                        </td>
                        <td class="p-2 min-w-12">
                            {{ cmo.program?.program }}
                            <span v-if="cmo.program?.major">
                                - {{ cmo.program?.major }}
                            </span>
                        </td>
                        <td class="p-2"
                            v-show="$page.props.auth.user.role == 'Super Admin'|| $page.props.auth.user.role == 'Admin'">
                            <div v-if="cmo.created_by?.name == null">
                                -
                            </div>
                            <div v-else-if="cmo.created_by?.name != $page.props.auth.user.name">
                                {{ cmo.created_by?.name }}
                            </div>
                            <div v-else>
                                Me
                            </div>
                        </td>
                        <td class="p-2 pr-5 text-right whitespace-nowrap">
                            <button @click="view(cmo.id)"
                                class="select-none h-8 w-8 text-xl text-center rounded-full hover:bg-gray-300 text-green-500 hover:text-green-600 tooltipForActions"
                                data-tooltip="View">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button @click="toggleConfirmationModal(cmo, 'publish', 'Publish CMO')"
                                class="select-none h-8 w-8 text-xl text-center rounded-full hover:bg-gray-300 text-blue-500 hover:text-blue-600 tooltipForActions"
                                data-tooltip="Publish">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                            <button @click="edit(cmo.id)"
                                class="select-none h-8 w-8 text-xl text-center rounded-full hover:bg-gray-300 text-blue-500 hover:text-blue-600 tooltipForActions"
                                data-tooltip="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button @click="toggleConfirmationModal(cmo, 'deleteCMO', 'Delete Draft')"
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

    <Confirmation :showModal="confirmationModal" @close="closeModal" :title="title" :modaltype="modaltype" :selected="selectedCMO" width="md" height="short" />
    
</template>

<script setup>
import { useForm, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps([
    'cmo_list',
    'canEdit',
    'filters'
]);

const cmo_file = ref(null);
const importing = ref(false);
const showFilterModal = ref(false);
const processing = ref(false);
const searchValue = ref(props.filters.search);

const form = useForm({
    cmo_file: null,
});


function edit(id) {
    router.get('/admin/CMOs/draft/' + id + '/edit');
}

function view(id) {
    router.get('/admin/CMOs/draft/' + id + '/view');
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



watch(cmo_file, value => {
    router.post('/admin/CMOs/create/import', { file: value }, {
        onStart: () => {
            importing.value = true;
        },
        onFinish: () => {
            importing.value = false;
        },
        preserveScroll: true,
        preserveState: false,
        replace: true,
    });
});

</script>


<script>
    import Layout from '@/Shared/Layout.vue'
    export default {
        data() {
            return {
                openCreateDropdown: false,
                openListOption: false,
                uploadModal: false,
                confirmationModal: false,
                selectedCMO: '',
                modaltype: '',
                title: '',
            }
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
            }
        },

        layout: Layout,
    }
</script>