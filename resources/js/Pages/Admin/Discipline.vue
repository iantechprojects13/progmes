<template>

    <Head title="Discipline List" />
    <content-container :hasAdminPanel="true" pageTitle="Discipline List" page="discipline" :hasTopButton="canAdd" :hasNavigation="true" :hasSearch="true"
        :hasFilters="true" :data_list="discipline_list">
        <template v-slot:top-button>
            <Link :href="route('admin.discipline.create')">
            <button
                class="select-none whitespace-nowrap bg-blue-500 hover:bg-blue-600 h-10 px-3 rounded text-white">
                <i class="fas fa-plus mr-2"></i>Add Discipline</button>
            </Link>
        </template>
        <template v-slot:navigation>
            <Link :href="route('admin.program.list')">
            <button class="select-none h-12 w-28 hover:bg-gray-100 text-gray-700 hover:text-black">
                Program
            </button>
            </Link>
            <button class="select-none h-12 w-28 hover:bg-gray-100 text-blue-500 border-b-4 font-bold border-blue-500">
                Discipline
            </button>
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
                <Link href="/admin/program/discipline">
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
                    <th class="p-2 pl-5">Discipline</th>
                    <th class="p-2 pr-5 text-right">
                        <i class="fas fa-ellipsis-v"></i>
                    </th>
                </template>
                <template v-slot:table-body>
                    <tr v-if="discipline_list.data.length == 0">
                        <td class="text-center py-10" colspan="2">
                            No discipline found
                        </td>
                    </tr>
                    <tr v-else v-for="(discipline, index) in discipline_list.data" :key="discipline.id"
                        class="hover:bg-gray-200" :class="{'bg-gray-100': index % 2 == 1}">
                        <td class="p-2 pl-5">
                            {{ discipline.discipline }}
                        </td>
                        <td class="p-2 pr-5 text-right whitespace-nowrap">
                            <button v-show="canEdit" @click="edit(discipline.id)"
                                class="select-none h-8 w-8 text-center text-xl hover:bg-gray-300 rounded-full text-blue-500 hover:text-blue-600 tooltipForActions"
                                data-tooltip="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button v-show="canDelete"
                                @click="toggleConfirmationModal(discipline, 'deleteDiscipline', 'Delete Discipline')"
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

    <Confirmation :showModal="confirmationModal" @close="closeModal" :title="title" :modaltype="modaltype" :selected="selectedDiscipline" width="md" height="short" />
    

    
</template>

<script setup>
import { useForm, router } from "@inertiajs/vue3";
import { ref } from "vue";

const props = defineProps(['discipline_list', 'canEdit', 'canAdd', 'canDelete', 'filters']);

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
    query.get("/admin/program/discipline", {
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
    query.get("/admin/program/discipline", {
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


function edit(id) {
    router.get('/admin/program/discipline/' + id + '/edit');
}


</script>

<script>
    import Layout from "@/Shared/Layout.vue";
    export default {
        data() {
            return {
                confirmationModal: false,
                selectedDiscipline: '',
                modaltype: '',
                title: '',
            }
        },
        layout: Layout,
        methods: {
            toggleConfirmationModal(id, type, title) {
                this.confirmationModal = !this.confirmationModal;
                this.selectedDiscipline = id;
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