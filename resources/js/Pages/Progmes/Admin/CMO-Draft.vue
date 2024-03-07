<template>

    <Head title="CHED Memorandum Order List" />
    <AdminPanel />
    <content-container pageTitle="CMO Draft List" hasTopButton="true" hasSearch="true" hasFilters="true"
        hasNavigation="true" :data_list="cmo_list">
        <template v-slot:top-button>
            <input ref="uploadfile" hidden type="file" @change="cmo_file = $event.target.files[0]">
            <button @click.prevent="$refs.uploadfile.click()"
                class="bg-blue-500 hover:bg-blue-600 h-10 px-2 rounded text-white">Import CMO</button>
        </template>
        <template v-slot:navigation>
            <div>
                <Link :href="route('admin.cmo.list')">
                <button class="text-gray-500 h-10 mr-8  hover:text-black">
                    CMO List
                </button>
                </Link>
                <button class="text-blue-500 font-bold border-b-2 h-10 border-blue-500">
                    Draft
                </button>
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
                <Link href="/admin/CMOs/draft">
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
                    <th v-show="canEdit" class="py-2 text-right">
                        <i class="fas fa-ellipsis-v"></i>
                    </th>
                </template>
                <template v-slot:table-body>
                    <tr v-for="(cmo, index) in cmo_list.data" :key="cmo.id" class="hover:bg-gray-100"
                        :class="{'bg-slate-200': index % 2 == 0}">
                        <td class="p-2">
                            CMO No.{{ cmo.number }} Series of {{cmo.series}}, Version {{cmo.version}}
                        </td>
                        <td class="p-2">
                            {{ cmo.program?.program }}
                        </td>
                        <td class="p-2 text-right">
                            <button @click="toggleConfirmationModal(cmo.id, 'deleteCMO', 'Delete Draft')"
                                class="h-8 px-2 mr-1 rounded bg-red-500 hover:bg-red-600 text-white">Delete
                            </button>
                            <button @click="edit(cmo.id)"
                                class="h-8 px-2 mr-1 rounded bg-yellow-500 hover:bg-yellow-600 text-white">Edit
                            </button>
                            <button @click="toggleConfirmationModal(cmo.id, 'publish', 'Publish CMO')"
                                class="h-8 px-2 rounded bg-blue-500 hover:bg-blue-600 text-white">Publish
                            </button>
                        </td>
                    </tr>
                    <tr v-if="cmo_list.data.length == 0">
                        <td class="text-center py-10" colspan="3">
                            No draft found
                        </td>
                    </tr>
                </template>
            </content-table>
        </template>
    </content-container>

    <div v-if="confirmationModal">
        <Confirmation @close="closeModal" :title="title" :modaltype="modaltype" :selected="selectedCMO" />
    </div>
    <div v-show="$page.props.flash.success">
        <Notification :message="$page.props.flash.success" />
    </div>
    <div v-show="$page.props.flash.failed">
        <Notification :message="$page.props.flash.failed" />
    </div>
    <Notification :message="$page.props.errors.file" />
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

    function upload() {
        form.post('/admin/CMOs/create/import', form);
    }

    function edit(id) {
        router.get('/admin/CMOs/draft/' + id + '/edit');
    }

    const props = defineProps(['cmo_list', 'canEdit', 'filters']);

    const query = useForm({
        search: props.filters.search,
    });

    function submit() {
        if (query.search == "") {
            router.get("/admin/CMOs/draft");
        } else {
            query.get("/admin/CMOs/draft", {
                preserveScroll: true,
            });
        }
    }

    watch(cmo_file, value => {
        Inertia.post('/admin/CMOs/create/import', { file: value }, {
            preserveScroll: true,
            preserveState: true,
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