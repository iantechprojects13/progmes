<template>

    <Head title="Program List" />
    <content-container :hasAdminPanel="true" pageTitle="Program List" page="program" :hasTopButton="canAdd" :hasNavigation="true" :hasSearch="true"
        :hasFilters="true" :data_list="program_list">
        <template v-slot:top-button>
            <Link href="/admin/program/create">
            <button
                class="select-none bg-blue-500 hover:bg-blue-600 h-10 px-3 whitespace-nowrap rounded text-white"><i
                    class="fas fa-plus mr-2"></i>Add program</button>
            </Link>
        </template>
        <template v-slot:navigation>
            <button class="select-none h-12 w-28 hover:bg-gray-100 text-blue-500 border-b-4 relative font-bold border-blue-500">
                Program
            </button>
            <Link :href="route('admin.discipline.list')">
            <button class="select-none h-12 w-28 hover:bg-gray-100 text-gray-700 hover:text-black">
                Discipline
            </button>
            </Link>
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
                <Link href="/admin/program">
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
                    <th class="p-2 pl-5">Program</th>
                    <th class="p-2">Major</th>
                    <th class="p-2">Discipline</th>
                    <th class="p-2 pr-5 text-right">
                        <i class="fas fa-ellipsis-v"></i>
                    </th>
                </template>
                <template v-slot:table-body>
                    <tr v-if="program_list.data.length == 0">
                        <td class="text-center py-10" colspan="4">
                            No program found
                        </td>
                    </tr>
                    <tr v-else v-for="(program, index) in program_list.data" :key="program.id"
                        class="hover:bg-gray-200 border-b" :class="{'bg-gray-100': index % 2 == 1}">
                        <td class="p-2 pl-5">
                            {{ program.program }}
                        </td>
                        <td class="p-2">
                            {{ program.major }}
                        </td>
                        <td class="p-2">
                            {{ program.discipline?.discipline }}
                        </td>
                        <td class="p-2 pr-5 text-right whitespace-nowrap">
                            <button v-show="canEdit" @click="edit(program.id)"
                                class="select-none h-8 w-8 text-center text-xl hover:bg-gray-300 rounded-full text-blue-500 hover:text-blue-600 tooltipForActions"
                                data-tooltip="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button v-show="canDelete"
                                @click="toggleConfirmationModal(program, 'deleteProgram', 'Delete Program')"
                                class="select-none h-8 w-8 text-center text-xl hover:bg-gray-300 rounded-full text-red-500 hover:text-red-600 tooltipForActions"
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

    <Confirmation :showModal="confirmationModal" @close="closeModal" :title="title" :modaltype="modaltype" :selected="selectedProgram" width="md" height="short"/>

    
</template>

<script setup>
import { useForm, router } from "@inertiajs/vue3";
import { ref, reactive } from "vue";

const props = defineProps(['program_list', 'canEdit', 'canAdd', 'canDelete', 'filters']);

const showFilterModal = ref(false);
const processing = ref(false);

const query = useForm({
    show: props.filters.show != null ? props.filters.show : null,
    search: props.filters.search,
});

function toggleFilterModal() {
showFilterModal.value = !showFilterModal.value;
}

function submit() {
    query.get("/admin/program", {
        onStart: () => {
            processing.value = true;
        },
        onFinish: () => {
            processing.value = false;
        },
        preserveState: false,
    });
}

function filter() {
    query.get("/admin/program", {
        onStart: () => {
            processing.value = true;
        },
        onFinish: () => {
            processing.value = false;
            toggleFilterModal();
        },
        preserveState: true,
    });
}

function edit(id) {
    router.get('/admin/program/' + id + '/edit');
}


</script>

<script>
    import Layout from "@/Shared/Layout.vue";
    export default {
        layout: Layout,
        data() {
            return {
                confirmationModal: false,
                selectedProgram: '',
                modaltype: '',
                title: '',
            };
        },
        methods: {
            toggleConfirmationModal(id, type, title) {
                this.confirmationModal = !this.confirmationModal;
                this.selectedProgram = id;
                this.modaltype = type;
                this.title = title;
            },

            closeModal() {
                this.confirmationModal = false;
                this.uploadModal = false;
            }
        }
    };
</script>