<template>

    <Head title="Discipline List" />
    <AdminPanel />
    <content-container pageTitle="Discipline List" page="discipline" :hasTopButton="canAdd" :hasNavigation="true" :hasSearch="true"
        :hasFilters="true" :data_list="discipline_list">
        <template v-slot:navigation>
            <div>
                <div>
                    <Link :href="route('admin.program.list')">
                    <button class="select-none text-gray-500 hover:text-black mr-8">
                        Program
                    </button>
                    </Link>
                    <button class="select-none text-blue-500 h-10 border-b-2 font-bold border-blue-500">
                        Discipline
                    </button>
                </div>
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
            <Link :href="route('admin.discipline.create')">
            <button
                class="select-none whitespace-nowrap bg-blue-500 hover:bg-blue-600 h-10 px-3 rounded text-white text-sm">
                <i class="fas fa-plus mr-2"></i>Add Discipline</button>
            </Link>
        </template>
        <template v-slot:options>
            <div>
                <Link href="/admin/program/discipline">
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
                    <th class="p-3 pl-5">Discipline</th>
                    <th v-show="canEdit" class="p-3 text-right">
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
                        class="hover:bg-slate-300" :class="{'bg-slate-200': index % 2 == 0}">
                        <td class="p-3 pl-5">
                            {{ discipline.discipline }}
                        </td>
                        <td v-show="canEdit" class="p-3 text-right">
                            <button @click="edit(discipline.id)"
                                class="mr-1 select-none h-10 w-10 rounded bg-blue-500 hover:bg-blue-600 text-white tooltipForActions"
                                data-tooltip="Edit">
                                <i class="fas fa-edit mr-0.5"></i>
                            </button>
                            <button v-show="canDelete"
                                @click="toggleConfirmationModal(discipline, 'deleteDiscipline', 'Delete Discipline')"
                                class="select-none h-10 w-10 rounded text-white bg-red-500 hover:bg-red-600 tooltipForActions"
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
        <Confirmation @close="closeModal" :title="title" :modaltype="modaltype" :selected="selectedDiscipline" />
    </div>

    <Notification :message="$page.props.flash.success" type="success" />
    <Notification :message="$page.props.flash.failed" type="failed" />
</template>

<script setup>
    import { useForm, router } from "@inertiajs/vue3";
    import { ref } from "vue";

    const props = defineProps(['discipline_list', 'canEdit', 'canAdd', 'canDelete', 'filters']);
    const selected = ref(null);
    const deleteProcessing = ref(false);

    const query = useForm({
        show: props.filters.show,
        search: props.filters.search,
    });

    function submit() {
        query.get("/admin/program/discipline", {
            preserveState: true,
            preserveScroll: true,
        });
    }

    function changeResultCount() {
        query.get("/admin/program/discipline", {
            preserveScroll: false,
            preserveState: false,
        });
    }

    function edit(id) {
        router.get('/admin/program/discipline/' + id + '/edit');
    }

    function deleteDiscipline() {
        router.post('/admin/discipline/delete', { 'id': selected.value.id }, {
            onStart: () => {
                deleteProcessing.value = true;
            },
            onFinish: () => {
                deleteProcessing.value = false;
            },
            preserveState: false,
        });
    }


</script>

<script>
    import Layout from "../Shared/Layout.vue";
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