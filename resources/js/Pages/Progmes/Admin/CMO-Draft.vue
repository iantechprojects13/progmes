<template>

    <Head title="CHED Memorandum Order List" />
    <AdminPanel />
    <content-container pageTitle="CMO Draft List" page="cmo" :hasTopButton="true" :hasSearch="true" :hasFilters="true"
        :hasNavigation="true" :data_list="cmo_list">
        <template v-slot:navigation>
            <div>
                <Link :href="route('admin.cmo.list')">
                <button class="select-none text-gray-500 h-10 mr-7  hover:text-black">
                    CMO
                </button>
                </Link>
                <button class="select-none text-blue-500 font-bold border-b-2 h-10 border-blue-500">
                    Draft
                </button>
            </div>
        </template>
        <template v-slot:search>
            <div class="w-full flex justify-end relative">
                <input @keydown.enter="submit" v-model="query.search" type="search" id="content-search"
                    placeholder="Search"
                    class="w-full rounded border border-gray-400 bg-slate-100 h-10 text-base placeholder-gray-400 pr-11 mr-2" />
                <button @click="submit"
                    class="text-gray-700 hover:text-black active:text-blue-500 h-10 w-10 rounded absolute right-2">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </template>
        <template v-slot:top-button>
            <div v-show="canEdit">
                <input ref="uploadfile" hidden type="file" @change="cmo_file = $event.target.files[0]">
                <button @click.prevent="$refs.uploadfile.click()" :disabled="importing"
                    class="select-none bg-blue-500 hover:bg-blue-600 h-10 w-32 rounded text-white text-sm whitespace-nowrap">
                    <span v-if="importing"><i class="fas fa-spinner animate-spin"></i></span>
                    <span v-else><i class="fas fa-file-import mr-2 text-base"></i>Import CMO</span>
                </button>
            </div>
        </template>
        <template v-slot:options>
            <div class="mr-1">
                <Link href="/admin/CMOs/draft">
                <button
                    class="w-10 h-10 whitespace-nowrap rounded-full text-gray-700 hover:text-blue-500 active:text-white active:bg-blue-600 tooltipForActions"
                    data-tooltip="Refresh page">
                    <i class="fas fa-refresh"></i>
                </button>
                </Link>
            </div>
        </template>
        <template v-slot:main-content>
            <content-table>
                <template v-slot:table-head>
                    <th class="p-3 pl-5">CMO</th>
                    <th class="p-3">Program</th>
                    <th class="p-3"
                        v-show="$page.props.auth.user.role == 'Super Admin' || $page.props.auth.user.role == 'Admin'">
                        Imported by</th>
                    <th v-show="canEdit" class="p-3 text-right">
                        <i class="fas fa-ellipsis-v"></i>
                    </th>
                </template>
                <template v-slot:table-body>
                    <tr v-if="cmo_list.data.length == 0">
                        <td class="text-center py-10" colspan="4">
                            No draft found
                        </td>
                    </tr>
                    <tr v-else v-for="(cmo, index) in cmo_list.data" :key="cmo.id" class="hover:bg-slate-300"
                        :class="{'bg-slate-200': index % 2 == 0}">
                        <td class="p-3 pl-5 whitespace-normal min-w-12">
                            <div v-if="cmo.number != null && cmo.series != null && cmo.version != null">
                                CMO No.{{ cmo.number }} Series of {{cmo.series}}, Version {{cmo.version}}
                            </div>
                            <div v-else>
                                -
                            </div>
                        </td>
                        <td class="p-3 whitespace-normal min-w-12">
                            {{ cmo.program?.program }}
                            <span v-if="cmo.program?.major">
                                - {{ cmo.program?.major }}
                            </span>
                        </td>
                        <td class="p-3 whitespace-normal"
                            v-show="$page.props.auth.user.role == 'Super Admin'|| $page.props.auth.user.role == 'Admin'">
                            {{ cmo.created_by?.name }}
                        </td>
                        <td class="p-3 text-right">
                            <button @click="toggleConfirmationModal(cmo, 'publish', 'Publish CMO')"
                                class="select-none h-10 w-10 mr-1 rounded bg-green-600 hover:bg-green-700 text-white tooltipForActions"
                                data-tooltip="Publish">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                            <button @click="edit(cmo.id)"
                                class="select-none h-10 w-10 mr-1 rounded bg-blue-500 hover:bg-blue-600 text-white tooltipForActions"
                                data-tooltip="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button @click="toggleConfirmationModal(cmo, 'deleteCMO', 'Delete Draft')"
                                class="select-none h-10 w-10 rounded bg-red-500 hover:bg-red-600 text-white tooltipForActions"
                                data-tooltip="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                </template>
            </content-table>
        </template>
        <template v-slot:show-item>
            <div class="mr-2">Items per page</div>
            <select v-model="query.show" id="showResultCount" @change="changeResultCount"
                class="select-none rounded h-8 w-20 p-1 text-sm">
                <option value="25">25</option>
                <option :value="50">50</option>
                <option :value="75">75</option>
                <option :value="100">100</option>
                <option :value="200">200</option>
            </select>
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
    import {
        useForm,
        router
    } from '@inertiajs/vue3';
    import { ref, watch } from 'vue';
    import { Inertia } from '@inertiajs/inertia'

    const form = useForm({
        cmo_file: null,
    });

    const cmo_file = ref(null);
    const importing = ref(false);

    function upload() {
        form.post('/admin/CMOs/create/import', form);
    }

    function edit(id) {
        router.get('/admin/CMOs/draft/' + id + '/edit');
    }

    const props = defineProps(['cmo_list', 'canEdit', 'filters']);

    const query = useForm({
        show: props.filters.show,
        search: props.filters.search,
    });

    function submit() {
        query.get("/admin/CMOs/draft", {
            preserveScroll: true,
        });

    }

    function changeResultCount() {
        query.get("/admin/CMOs/draft", {
            preserveScroll: false,
            preserveState: false,
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
        });
    });

</script>


<script>
    import Layout from '../Shared/Layout.vue'
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