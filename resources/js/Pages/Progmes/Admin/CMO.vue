<template>

    <Head title="CHED Memorandum Order List" />
    <AdminPanel />
    <content-container pageTitle="CHED Memorandum Order List" hasTopButton="true" hasSearch="true" hasFilters="true"
        hasNavigation="true" :data_list="cmo_list">
        <template v-slot:top-button>
            <input ref="uploadfile" hidden type="file" @change="cmo_file = $event.target.files[0]">
            <button @click.prevent="$refs.uploadfile.click()"
                class="bg-blue-500 hover:bg-blue-600 h-10 px-2 rounded text-white">Import CMO</button>
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
                        :class="{'bg-slate-200': index % 2 == 0}">
                        <td class="p-2">
                            CMO No.{{ cmo.number }} Series of {{cmo.series}}, Version {{cmo.version}}
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
                            <button @click="toggleConfirmationModal(cmo.id, 'deleteCMO', 'Delete Published CMO')"
                                class="h-8 px-2 mr-1 rounded bg-red-500 hover:bg-red-600 text-white">Delete</button>
                            <button v-if="!cmo.isActive"
                                class="h-8 px-2 w-24 rounded bg-blue-500 hover:bg-blue-600 text-white"
                                @click="toggleConfirmationModal(cmo, 'activate', 'Activate CMO')">Activate
                            </button>
                            <button v-else class="h-8 px-2 w-24 rounded bg-green-500 hover:bg-blue-600 text-white"
                                @click="toggleConfirmationModal(cmo.id, 'deactivate', 'Deactivate CMO')">Deactivate
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
    <!-- <content-container :hasAction="true" placeholder="Search CMO" :data_list="cmo_list">
        <template v-slot:channel>
            <dropdown-option>
                <template v-slot:button>
                    <button class="bg-green-700 rounded h-10 text-white px-3">
                        <span v-show="listItems == 'published'">Published</span>
                        <span v-show="listItems == 'draft'">Draft</span>
                        <i class="fas fa-caret-down ml-3"></i>
                    </button>
                </template>

                <template v-slot:options>
                    <div class="w-40">
                        <Link class="w-full" :href="route('admin.cmo.list')">
                        <button class=" w-full flex flex-row text-left indent-2 py-2 hover:bg-gray-300"
                            :class="{ 'text-blue-500': listItems == 'published' }">
                            <div class="w-8">
                                <i v-show="listItems == 'published'" class="fas fa-check"></i>
                            </div>
                            Published
                        </button>
                        </Link>
                        <Link class="relative w-full" :href="route('admin.cmo.list', { list: 'draft' })">
                        <button class="w-full flex flex-row text-left indent-2 py-2 hover:bg-gray-300"
                            :class="{ 'text-blue-500': listItems == 'draft' }">
                            <div class="w-8">
                                <i v-show="listItems == 'draft'" class="fas fa-check"></i>
                            </div>
                            Draft
                        </button>
                        </Link>
                    </div>
                </template>
            </dropdown-option>
        </template>

        <template v-slot:actions>
            <button class="h-10 w-full text-base bg-blue-500 hover:bg-blue-600 text-white px-3 rounded"
                @click="openUploadModal">
                    <i class="fas fa-file-import mr-2"></i>
                    Import file
            </button>
        </template>

        <template v-slot:content>
            <content-table>
                <template v-slot:table-head>
                    <th class="p-3">CHED Memorandum Order</th>
                    <th class="p-3">Discipline</th>
                    <th class="p-3">Program</th>
                    <th v-show="listItems == 'published'" class="p-3">CMO Status</th>
                    <th v-show="listItems == 'draft'" class="p-3">Last Modified</th>
                    <th class="p-3 text-right">Action</th>
                </template>

                <template v-slot:table-body>
                    <tr v-for="( item, index ) in  cmo_list.data " :key="item.id"
                        class="border-b border-gray-300 text-gray-700 hover:bg-gray-300 hover:text-black cursor-pointer"
                        :class="{ 'bg-gray-200': index % 2 == 0 }">
                        <th scope="row" class="p-3">
                            <i class="fas fa-file text-lg mr-2 text-stone-600"></i>
                            <span v-if="item.number && item.series">
                                No. {{ item.number }} -
                                Series of {{ item.series }} -
                                Version {{ item.version }}
                            </span>
                            <span v-else>
                                -
                            </span>
                        </th>
                        <td class="p-3">{{ item.discipline }}</td>
                        <td class="p-3">{{ item.program }}</td>
                        <td v-show="listItems == 'published'" class="p-3">
                            <div v-if="item.active" class="py-0.5 px-1 text-xs rounded text-white bg-green-600 w-fit">
                                Active
                            </div>
                            <div v-else class="py-0.5 px-1 text-xs rounded text-white bg-red-600 w-fit">Not active</div>
                        </td>
                        <td v-show="listItems == 'draft'" class="p-3">
                            {{ item.modified }}
                        </td>
                        <td class="text-right p-3 w-20 relative text-gray-500">
                            <div v-show="listItems == 'published'">
                                <button
                                    @click="item.active ? toggleConfirmationModal(item.id, 'deactivate', 'Deactivate CMO') : toggleConfirmationModal(item, 'activate', 'Activate CMO')"
                                    class="hover:text-blue-600 hover:bg-gray-200 hover:border border-gray-400 w-8 h-8 rounded-full tooltipForActions"
                                    :data-tooltip="!item.active ? 'Set as Active' : 'Remove activation'">
                                    <i class="text-lg fas fa-heart"></i>
                                </button>
                                <Link :href="'/admin/CMOs/' + item.id + '/view'">
                                <button
                                    class="hover:text-blue-600 hover:bg-gray-200 hover:border border-gray-400 w-8 h-8 rounded-full tooltipForActions"
                                    data-tooltip="View">
                                    <i class="text-lg fas fa-eye"></i>
                                </button>
                                </Link>

                            </div>
                            <div v-show="listItems == 'draft'">
                                <button @click="toggleConfirmationModal(item.id, 'publish', 'Publish Draft')"
                                    class="text-gray-500 hover:text-blue-600 hover:bg-gray-200 rounded-full border-gray-400 w-8 h-8 tooltipForActions"
                                    data-tooltip="Publish">
                                    <i class="text-lg fas fa-paper-plane"></i>
                                </button>
                                <button @click="edit(item.id)"
                                    class="text-gray-500 hover:text-orange-600 hover:bg-gray-200 rounded-full border-gray-400 w-8 h-8 tooltipForActions"
                                    data-tooltip="Edit">
                                    <i class="text-lg fas fa-edit"></i>
                                </button>
                                <button @click="toggleConfirmationModal(item.id, 'delete', 'Delete Draft')"
                                    class="text-gray-500 hover:text-red-600 hover:bg-gray-200 rounded-full border-gray-400 w-8 h-8 tooltipForActions"
                                    data-tooltip="Delete">
                                    <i class="text-lg fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr v-show="cmo_list.data.length === 0">
                        <td v-if="listItems == 'published'" colspan="7" class="h-24 text-center align-middle">Empty</td>
                        <td v-else colspan="6" class="h-24 text-center align-middle">Empty</td>
                    </tr>
                </template>
            </content-table>
        </template>
    </content-container> -->
    <!-- <modal :showModal="uploadModal" title="Import excel file" @close="closeModal" @upload="upload" type="import">
        <form @submit.prevent="upload">
            <div class="text-center pt-5">
                <input ref="uploadfile" hidden type="file" @change="form.cmo_file = $event.target.files[0]" id="excel">
                <div v-if="form.cmo_file" class="text-black underline">
                    {{ form.cmo_file.name }} <button
                        class="inline-block ml-4 py-1 px-2 border border-gray-400 rounded hover:bg-orange-400"
                        @click.prevent="$refs.uploadfile.click()"><i class="fas fa-pencil"></i></button>
                </div>
                <button v-if="!form.cmo_file" @click.prevent="$refs.uploadfile.click()"
                    class="hover:text-white border border-green-700 hover:bg-green-700 py-2 px-10 rounded"><i
                        class="fas fa-cloud-upload mr-5 text-xl"></i>Select
                    file</button>
                <div v-if="$page.props.errors" class="text-sm text-red-500 mt-3">
                    {{ $page.props.errors.cmo_file }}
                </div>
            </div>
        </form>
    </modal> -->
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
            router.get("/admin/CMOs");
        } else {
            query.get("/admin/CMOs", {
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