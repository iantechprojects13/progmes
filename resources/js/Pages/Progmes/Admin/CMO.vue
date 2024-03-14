<template>

    <Head title="CHED Memorandum Order List" />
    <AdminPanel />
    <content-container pageTitle="CHED Memorandum Order List" hasTopButton="true" hasSearch="true" hasFilters="true"
        hasNavigation="true" :data_list="cmo_list">
        <template v-slot:top-button>
            <input ref="uploadfile" hidden type="file" @change="evidence_file = $event.target.files[0]" />
            <button @click="$refs.uploadfile.click()" :disabled="importing"
                class="bg-blue-500 hover:bg-blue-600 h-10 px-2 rounded text-white">
                <span v-if="importing"><i class="fas fa-spinner animate-spin mr-2"></i>Importing...</span>
                <span v-else>Import CMO</span>
            </button>
        </template>
        <template v-slot:navigation>
            <div>
                <button class="text-blue-500 h-10 mr-8 border-b-2 font-bold border-blue-500">
                    CMO List
                </button>
                <Link :href="route('admin.cmo.draft')">
                <button class="text-gray-500 hover:text-black">
                    Draft
                </button>
                </Link>
            </div>
        </template>
        <template v-slot:search>
            <div class="w-full flex justify-end">
                <input @keydown.enter="submit" v-model="query.search" type="search" id="content-search"
                    placeholder="Search a CHED Memorandum Order"
                    class="rounded border-2 w-full border-gray-400 h-10 bg-white text-base placeholder-gray-400" />
                <button @click="submit"
                    class="hover:bg-gray-300 border-2 active:bg-blue-600 active:text-white h-10 w-12 border-gray-400 relative right-0.5 bg-white rounded-r">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </template>
        <template v-slot:options>
            <div class="mr-1">
                <Link href="/admin/CMOs">
                <button
                    class="px-2 border-2 w-12 whitespace-nowrap rounded h-10 text-gray-600 hover:text-black border-gray-500">
                    <i class="fas fa-refresh"></i>
                </button>
                </Link>
            </div>
        </template>
        <template v-slot:main-content>
            <content-table>
                <template v-slot:table-head>
                    <th class="py-2">CHED Memorandum Order</th>
                    <th class="py-2">Program</th>
                    <th class="py-2">Active Status</th>
                    <th v-show="canEdit" class="py-2 text-right">
                        <i class="fas fa-ellipsis-v"></i>
                    </th>
                </template>
                <template v-slot:table-body>
                    <tr v-for="(cmo, index) in cmo_list.data" :key="cmo.id" class="hover:bg-gray-100"
                        :class="{ 'bg-slate-200': index % 2 == 0 }">
                        <td class="p-2">
                            CMO No.{{ cmo.number }} Series of {{ cmo.series }},
                            Version {{ cmo.version }}
                        </td>
                        <td class="p-2">
                            {{ cmo.program?.program }}
                        </td>
                        <td class="p-2">
                            <div v-if="cmo.isActive" class="w-fit text-xs px-1 rounded bg-green-600 text-white">
                                Active
                            </div>
                            <div v-else class="w-fit text-xs px-1 rounded bg-gray-300">
                                Not Active
                            </div>
                        </td>
                        <td class="p-2 text-right">
                            <button @click="
                                    toggleConfirmationModal(
                                        cmo,
                                        'deleteCMO',
                                        'Delete Published CMO'
                                    )
                                " class="h-8 px-2 mr-1 rounded bg-red-500 hover:bg-red-600 text-white">
                                Delete
                            </button>
                            <button v-if="!cmo.isActive"
                                class="h-8 px-2 w-24 rounded bg-blue-500 hover:bg-blue-600 text-white" @click="
                                    toggleConfirmationModal(
                                        cmo,
                                        'activate',
                                        'Activate CMO'
                                    )
                                ">
                                Activate
                            </button>
                            <button v-else class="h-8 px-2 w-24 rounded bg-green-500 hover:bg-blue-600 text-white"
                                @click="
                                    toggleConfirmationModal(
                                        cmo,
                                        'deactivate',
                                        'Deactivate CMO'
                                    )
                                ">
                                Deactivate
                            </button>
                        </td>
                    </tr>
                    <tr v-if="cmo_list.data.length == 0">
                        <td class="text-center py-10" colspan="3">
                            No CMO found
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

    function viewValue() {
        console.log(refs.toggleBtn.value);
    }

    const props = defineProps(["cmo_list", "canEdit", "filters"]);

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