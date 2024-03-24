<template>

    <Head title="CHED Memorandum Order List" />
    <AdminPanel />
    <content-container pageTitle="CHED Memorandum Order List" hasTopButton="true" hasSearch="true" hasFilters="true"
        :hasNavigation="canDraft" :data_list="cmo_list">
        <template v-slot:top-button>
            <div v-show="canImport">
                <input ref="uploadfile" hidden type="file" @change="evidence_file = $event.target.files[0]" />
                <button @click.prevent="$refs.uploadfile.click()" :disabled="importing"
                    class="select-none bg-blue-500 hover:bg-blue-600 h-10 w-32 rounded text-white text-sm">
                    <span v-if="importing"><i class="fas fa-spinner animate-spin"></i></span>
                    <span v-else><i class="fas fa-file-import mr-2 text-base"></i>Import CMO</span>
                </button>
            </div>
        </template>
        <template v-slot:navigation>
            <div>
                <button class="text-blue-500 h-10 mr-7 border-b-2 font-bold border-blue-500">
                    CMO
                </button>
                <Link :href="route('admin.cmo.draft')">
                <button class="text-gray-500 hover:text-black">
                    Draft
                </button>
                </Link>
            </div>
        </template>
        <template v-slot:search>
            <div class="w-full flex justify-end relative">
                <input @keydown.enter="submit" v-model="query.search" type="search" id="content-search"
                    placeholder="Search a CMO"
                    class="w-full rounded-full bg-slate-100 h-10 border-none indent-3 text-base placeholder-gray-400 pr-11 mr-2" />
                <button @click="submit"
                    class="hover:bg-gray-300 active:text-blue-500 h-10 w-10 rounded-full absolute right-3">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </template>
        <template v-slot:options>
            <div class="mr-1">
                <Link href="/admin/CMOs">
                <button
                    class="px-2 border-2 w-12 whitespace-nowrap rounded h-10 text-gray-600 hover:text-black border-gray-500 tooltipForActions"
                    data-tooltip="Refresh page">
                    <i class="fas fa-refresh"></i>
                </button>
                </Link>
            </div>
        </template>
        <template v-slot:main-content>
            <content-table>
                <template v-slot:table-head>
                    <th class="p-3">CHED Memorandum Order</th>
                    <th class="p-3">Program</th>
                    <th class="p-3">Active Status</th>
                    <th v-show="canEdit" class="p-3 text-right">
                        <i class="fas fa-ellipsis-v"></i>
                    </th>
                </template>
                <template v-slot:table-body>
                    <tr v-if="cmo_list.data.length == 0">
                        <td class="text-center py-10" colspan="3">
                            No CMO found
                        </td>
                    </tr>
                    <tr v-else v-for="(cmo, index) in cmo_list.data" :key="cmo.id" class="hover:bg-gray-100"
                        :class="{ 'bg-slate-200': index % 2 == 0 }">
                        <td class="p-3">
                            CMO No.{{ cmo.number }} Series of {{ cmo.series }},
                            Version {{ cmo.version }}
                        </td>
                        <td class="p-3">
                            {{ cmo.program?.program }}
                        </td>
                        <td class="p-3">
                            <div v-if="cmo.isActive" class="w-fit text-xs px-1 rounded bg-green-600 text-white">
                                Active
                            </div>
                            <div v-else class="w-fit text-xs px-1 rounded bg-gray-300">
                                Not Active
                            </div>
                        </td>
                        <td class="p-3 text-right">
                            <button @click="view(cmo.id)"
                                class="h-10 w-10 mr-1 rounded bg-green-600 hover:bg-green-700 text-white tooltipForActions"
                                data-tooltip="View">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button v-show="canEdit" v-if="!cmo.isActive"
                                @click="toggleConfirmationModal(cmo, 'activate', 'Activate CMO')"
                                class="h-10 w-10 mr-1 rounded bg-blue-500 hover:bg-blue-600 text-white tooltipForActions"
                                data-tooltip="Activate">
                                <i class="fas fa-toggle-on"></i>
                            </button>
                            <button v-show="canEdit" v-else
                                @click="toggleConfirmationModal(cmo, 'deactivate', 'Deactivate CMO')"
                                class="h-10 w-10 mr-1 rounded bg-blue-500 hover:bg-blue-600 text-white tooltipForActions"
                                data-tooltip="Deactivate">
                                <i class="fas fa-toggle-off"></i>
                            </button>
                            <button v-show="canEdit"
                                @click="toggleConfirmationModal(cmo, 'deleteCMO', 'Delete Published CMO')"
                                class="h-10 w-10 rounded bg-red-500 hover:bg-red-600 text-white tooltipForActions"
                                data-tooltip="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                </template>
            </content-table>
        </template>
    </content-container>
    <div v-if="confirmationModal">
        <Confirmation @close="closeModal" :title="title" :modaltype="modaltype" :selected="selectedCMO" />
    </div>
    <Notification :message="$page.props.flash.success" type="success" />
    <Notification :message="$page.props.flash.failed" type="failed" />
    <Notification :message="$page.props.errors.file" type="failed" />
</template>

<script setup>
    import { useForm, router } from "@inertiajs/vue3";
    import { ref, watch } from "vue";
    import { Inertia } from "@inertiajs/inertia";

    const evidence_file = ref(null);
    const toggleBtn = ref([]);
    const importing = ref(false);

    const refs = { toggleBtn };

    function upload() {
        form.post("/admin/CMOs/create/import", form);
    }

    function edit(id) {
        router.get("/admin/CMOs/draft/" + id + "/edit");
    }

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
        search: props.filters.search,
    });

    function submit() {
        if (query.search == "") {
            router.get("/admin/CMOs");
        } else {
            query.get("/admin/CMOs", {
                preserveScroll: true,
            });
        }
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
            }
        );
    });
</script>

<script>
    import Layout from "../Shared/Layout.vue";
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