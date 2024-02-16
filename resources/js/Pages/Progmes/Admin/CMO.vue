<template>
    <Head title="CHED Memorandum Order List" />
    <AdminPanel />
    <content-container placeholder="Search CMO" :data_list="cmo_list" :key="componentKey">
        <template v-slot:channel>
            <div class="relative z-30" @mouseleave="openListOption = false">
                <button class="bg-green-700 rounded h-10 text-white px-3" @click="openListOption = !openListOption">
                    <span v-show="listItems == 'published'">Published</span>
                    <span v-show="listItems == 'draft'">Draft</span>
                    <i class="fas fa-caret-down ml-3"></i>
                </button>
                <div v-show="openListOption"
                    class="absolute top-10 w-48 rounded py-3 border border-gray-400 shadow-lg bg-white">
                    <Link class="w-full" :href="route('admin.cmo.list')">
                    <button class=" w-full flex flex-row text-left indent-2 py-2 hover:bg-gray-300"
                        :class="{ 'bg-gray-200': listItems == 'published' }">
                        <div class="w-8">
                            <i v-show="listItems == 'published'" class="fas fa-check"></i>
                        </div>
                        Published
                    </button>
                    </Link>
                    <Link class="relative w-full" :href="route('admin.cmo.list', { list: 'draft' })">
                    <button class="w-full flex flex-row text-left indent-2 py-2 hover:bg-gray-300"
                        :class="{ 'bg-gray-200': listItems == 'draft' }">
                        <div class="w-8">
                            <i v-show="listItems == 'draft'" class="fas fa-check"></i>
                        </div>
                        Draft
                    </button>
                    </Link>
                </div>
            </div>
        </template>
        <template v-slot:actions>
            <div class="relative text-gray-600" @mouseleave="openCreateDropdown = false">
                <button class="px-3 border rounded h-10 text-white bg-blue-500 hover:bg-blue-600"
                    @click.prevent="openCreateDropdown = !openCreateDropdown">
                    <i class="fas fa-plus"></i>
                    <i class="fas fa-caret-down ml-3"></i></button>
                <div v-show="openCreateDropdown"
                    class="flex flex-col items-start absolute top-10 right-0 shadow border border-gray-300 bg-white rounded py-3 z-30 w-48"
                    @mouseover.prevent="openCreateDropdown = true">
                    <button class="py-2 indent-2 flex flex-row w-full text-left hover:bg-gray-200 hover:text-black">
                        <div class="w-8">
                            <i class="fas fa-plus"></i>
                        </div>
                        <div>Create new</div>
                    </button>
                    <button class="flex flex-row py-2 indent-2 w-full text-left hover:bg-gray-200 hover:text-black"
                        @click="openUploadModal">
                        <div class="w-8">
                            <i class="fas fa-file-import"></i>
                        </div>
                        <div>Import file</div>
                    </button>
                </div>
            </div>
        </template>
        <template v-slot:content>
            <content-table>
                <template v-slot:table-head>
                    <th class="scope-col p-3">CHED Memorandum Order</th>
                    <th class="scope-col p-3">Discipline</th>
                    <th class="scope-col p-3">Program</th>
                    <!-- <th v-show="listItems == 'published'" class="scope-col p-3">Active Status</th> -->
                    <th v-show="listItems == 'draft'" class="scope-col p-3">Last Modified</th>
                    <th class="scope-col p-3 text-right">Action</th>
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
                        <!-- <td v-show="listItems == 'published'" class="p-3">
                            <div v-if="item.active" class="text-white text-xs p-0.5 px-1 bg-green-700 w-fit rounded">Active
                            </div>
                            <div v-else class="text-white text-xs p-0.5 px-1 bg-red-500 w-fit rounded">Inactive</div>
                        </td> -->
                        <td v-show="listItems == 'draft'" class="p-3">
                            {{ item.modified }}
                        </td>
                        <td class="text-right p-3 w-20 relative text-gray-500">
                            <div v-show="listItems == 'published'">
                                <button
                                    @click="item.active ? toggleConfirmationModal(item.id, 'deactivate', 'Deactivate CMO') : toggleConfirmationModal(item, 'activate', 'Activate CMO')"
                                    class="hover:text-blue-600 hover:bg-gray-200 hover:border border-gray-400 w-8 h-8 rounded-full tooltipForActions"
                                    :class="{ 'text-blue-500': item.active }"
                                    :data-tooltip="!item.active ? 'Set as Active' : 'Remove activation'">
                                    <i class="text-lg fas fa-star"></i>
                                </button>
                            </div>
                            <div v-show="listItems == 'draft'">
                                <button @click="toggleConfirmationModal(item.id, 'publish', 'Publish draft')"
                                    class="text-blue-500 hover:text-blue-600 hover:bg-gray-200 hover:border border-gray-400 w-8 h-8 rounded-full tooltipForActions"
                                    data-tooltip="Publish">
                                    <i class="text-lg fas fa-paper-plane"></i>
                                </button>
                                <button @click="edit(item.id)"
                                    class="text-orange-500 hover:text-orange-600 hover:bg-gray-200 hover:border border-gray-400 w-8 h-8 rounded-full tooltipForActions"
                                    data-tooltip="Edit">
                                    <i class="text-lg fas fa-edit"></i>
                                </button>
                                <button @click="toggleConfirmationModal(item.id, 'delete', 'Delete draft')"
                                    class="text-red-500 hover:text-red-600 hover:bg-gray-200 hover:border border-gray-400 w-8 h-8 rounded-full tooltipForActions"
                                    data-tooltip="Delete">
                                    <i class="text-lg fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr v-show="cmo_list.data.length === 0">
                        <td colspan="6" class="h-24 text-center">Empty</td>
                    </tr>
                </template>
            </content-table>
        </template>
    </content-container>
    <modal v-if="uploadModal" title="Import excel file" @close="openUploadModal" @upload="upload" type="import">
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
    </modal>
    <div v-if="confirmationModal">
        <Confirmation @close="toggleConfirmationModal" :title="title" :modaltype="modaltype" :selected="selectedCMO" />
    </div>
    <div v-show="$page.props.flash.success">
        <Notification :message="$page.props.flash.success" />
    </div>
    <div v-show="$page.props.flash.failed">
        <Notification :message="$page.props.flash.failed" />
    </div>
</template>

<script setup>
import {
    useForm,
    router
} from '@inertiajs/vue3';

const form = useForm({
    cmo_file: null,
});

function upload() {
    form.post('/admin/CMOs/create/import', form);
}

function edit(id) {
    router.get('/admin/CMOs/draft/' + id + '/edit');
}

function deleteCMO(id) {
    router.delete('/admin/CMOs/delete/' + id);
}

defineProps([
    'cmo_list',
    'listItems',
]);
</script>


<script>
import Layout from '../Shared/Layout.vue'
export default {
    data() {
        return {
            componentKey: 0,
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
    },

    layout: Layout,
}
</script>
