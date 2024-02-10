<template>
    <Head title="CHED Memorandum Order List" />
    <AdminPanel />
    <content-container placeholder="Search CMO" :data_list="cmo_list">
        <template v-slot:actions>
            <div class="relative text-gray-600" @mouseleave="openCreateDropdown = false">
                <button class="px-3 border rounded h-10 text-white bg-blue-500 hover:bg-blue-600"
                    @click.prevent="openCreateDropdown = !openCreateDropdown">
                    <i class="fas fa-plus"></i>
                    <i class="fas fa-chevron-down ml-2"></i></button>
                <div v-show="openCreateDropdown"
                    class="flex flex-col items-start absolute top-10 right-0 shadow border border-gray-300 bg-white rounded px-3 py-3 z-30 w-48"
                    @mouseover.prevent="openCreateDropdown = true">
                    <button class="py-2 w-full px-2 text-left flex flex-row hover:bg-gray-200 hover:text-black rounded">
                        <div class="w-6">
                            <i class="fas fa-plus"></i>
                        </div>
                        <div>Create new</div>
                    </button>
                    <button class="flex flex-row py-2 w-full px-2 text-left hover:bg-gray-200 hover:text-black rounded"
                        @click="openUploadModal">
                        <div class="w-6">
                            <i class="fas fa-upload"></i>
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
                    <th class="scope-col p-3">Version</th>
                    <th class="scope-col p-3">Discipline</th>
                    <th class="scope-col p-3">Program</th>
                </template>
                <template v-slot:table-body>
                    <tr v-for="item in cmo_list.data" :key="item.id" class="border-b border-gray-400 text-gray-600">
                        <Link :href="route('admin.cmo.show', [item])" class="hover:underline rounded">
                        <th scope="row" class="py-2 px-3 pl-5">
                            <i class="fas fa-file text-2xl mr-2"></i>
                            No.{{ item.number }} Series of {{
                                item.series
                            }}
                        </th>
                        </Link>
                        <td class="py-2 px-3">{{ item.version }}</td>
                        <td class="py-2 px-3">{{ item.discipline }}</td>
                        <td class="py-2 px-3">{{ item.program }}</td>
                    </tr>
                    <tr v-show="cmo_list.data.length === 0">
                        <td colspan="4" class="h-24 text-center">No data</td>
                    </tr>
                </template>
            </content-table>
        </template>
    </content-container>
    <modal v-if="uploadModal" title="Import excel file" @close="openUploadModal" @upload="upload" type="import">
        <form @submit.prevent="upload">
            <div class="text-center">
                <input ref="uploadfile" hidden type="file" @change="form.cmo_file = $event.target.files[0]" id="excel">
                <div v-if="form.cmo_file" class="text-black underline">
                    {{ form.cmo_file.name }} <button
                        class="inline-block ml-4 py-1 px-2 border border-gray-400 rounded hover:bg-orange-400"
                        @click.prevent="$refs.uploadfile.click()"><i class="fas fa-pencil"></i></button>
                </div>
                <button v-if="!form.cmo_file" @click.prevent="$refs.uploadfile.click()"
                    class="hover:text-white border border-green-700 hover:bg-green-700 p-2 rounded"><i
                        class="fas fa-cloud-upload mr-2"></i>Select
                    file</button>
                <div v-if="$page.props.errors" class="text-sm text-red-500 mt-3">
                    {{ $page.props.errors.cmo_file }}
                </div>
            </div>
        </form>
    </modal>
</template>

<script setup>

import { useForm } from '@inertiajs/vue3';

const form = useForm({
    cmo_file: null,
});

function upload() {
    form.post('/admin/CMOs/create/import', form);
}


defineProps([
    'cmo_list',
]);
</script>

<script>
import Layout from '../Shared/Layout.vue'
export default {
    data() {
        return {
            openCreateDropdown: false,
            uploadModal: false,
        }
    },

    methods: {
        openUploadModal() {
            this.uploadModal = !this.uploadModal;
        },
    },
    layout: Layout,
}
</script>


