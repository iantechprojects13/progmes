<template>
    <Head title="CHED Memorandum Order Create" />
    <AdminPanel />
    <content-container placeholder="Search CMO">
        <template v-slot:channel>
            <Link :href="route('admin.cmo.list')">
            <button class="text-gray-500 hover:text-gray-800 mr-5">List</button>
            </Link>
            <button class="text-blue-500 font-bold underline">Create</button>
        </template>
        <template v-slot:actions>
            <!-- <button class="hover:bg-gray-200 p-2 px-3 rounded border border-gray-400 mr-1 tooltipForActions"
                data-tooltip="Upload file" @click="openModal">
                <i class="fas fa-upload"></i>
            </button> -->
        </template>
        <template v-slot:content>
            <div class="px-3 py-5">
                <button class="hover:bg-gray-200 p-2 px-3 rounded border border-gray-400 mr-1" @click="openModal">
                    <i class="fas fa-upload mr-2"></i>Import file
                </button>
            </div>
        </template>
    </content-container>
    <modal v-if="modal" title="Upload CMO" @close="openModal" @upload="upload" type="upload">
        <form @submit.prevent="upload">
            <div class="text-center">
                <input ref="uploadfile" hidden type="file" @change="form.cmo_file = $event.target.files[0]" id="excel">
                <div v-if="form.cmo_file" class="text-black underline">
                    {{ form.cmo_file.name }} <button class="inline-block ml-4 py-1 px-2 border border-gray-400 rounded"
                        @click.prevent="$refs.uploadfile.click()"><i class="fas fa-pencil"></i></button>
                </div>
                <button v-if="!form.cmo_file" @click.prevent="$refs.uploadfile.click()"
                    class="border border-gray-400 p-2 rounded"><i class="fas fa-file mr-2"></i>Click
                    here
                    to select</button>
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
    form.post('/admin/CMOs/upload', form);
}

defineProps([
    'file',
]);

</script>

<script>
import Layout from '../Shared/Layout.vue'
export default {
    data() {
        return {
            modal: false,
        }
    },
    layout: Layout,
    methods: {
        openModal() {
            this.modal = !this.modal;
        }
    }
}
</script>

