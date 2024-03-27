<template>

    <Head title="Program List" />
    <AdminPanel />
    <content-container pageTitle="Program List" :hasTopButton="canAdd" hasNavigation="true" hasSearch="true"
        hasFilters="true" :data_list="program_list">
        <template v-slot:navigation>
            <div>
                <div>
                    <button class="select-none text-blue-500 h-10 mr-8 border-b-2 font-bold border-blue-500">
                        Program
                    </button>
                    <Link :href="route('admin.discipline.list')">
                    <button class="select-none text-gray-500 hover:text-black">
                        Discipline
                    </button>
                    </Link>
                </div>
            </div>
        </template>
        <template v-slot:search>
            <div class="w-full flex justify-end relative">
                <input @keydown.enter="submit" v-model="query.search" type="search" id="content-search"
                    placeholder="Search"
                    class="w-full rounded-full bg-slate-100 h-10 border-none indent-3 text-base placeholder-gray-400 pr-11 mr-2" />
                <button @click="submit"
                    class="hover:bg-gray-300 active:text-blue-500 h-10 w-10 rounded-full absolute right-3">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </template>
        <template v-slot:top-button>
            <Link href="/admin/program/create">
            <button
                class="select-none bg-blue-500 hover:bg-blue-600 h-10 px-3 whitespace-nowrap rounded text-white text-sm"><i
                    class="fas fa-plus mr-2"></i>Add program</button>
            </Link>
        </template>
        <template v-slot:options>
            <div>
                <Link href="/admin/program">
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
                    <th class="p-3 pl-5">Program</th>
                    <th class="p-3">Major</th>
                    <th class="p-3">Discipline</th>
                    <th v-show="canEdit" class="p-3 text-right">
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
                        class="hover:bg-slate-300" :class="{'bg-slate-200': index % 2 == 0}">
                        <td class="p-3 pl-5">
                            {{ program.program }}
                        </td>
                        <td class="p-3">
                            {{ program.major }}
                        </td>
                        <td class="p-3">
                            {{ program.discipline?.discipline }}
                        </td>
                        <td class="p-3 text-right">
                            <button v-show="canEdit" @click="edit(program.id)"
                                class="mr-1 select-none h-10 w-10 rounded text-white bg-blue-500 hover:bg-blue-600 tooltipForActions"
                                data-tooltip="Edit">
                                <i class="fas fa-edit mr-0.5"></i>
                            </button>
                            <button v-show="canDelete"
                                @click="toggleConfirmationModal(program, 'deleteProgram', 'Delete Program')"
                                class="select-none h-10 w-10 rounded text-white bg-red-500 hover:bg-red-600 tooltipForActions"
                                data-tooltip="Delete">
                                <i class="fas fa-trash mr-0.5"></i>
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
        <Confirmation @close="closeModal" :title="title" :modaltype="modaltype" :selected="selectedProgram" />
    </div>

    <Notification :message="$page.props.flash.success" type="success" />
    <Notification :message="$page.props.flash.failed" type="failed" />
</template>

<script setup>
    import { useForm, router } from "@inertiajs/vue3";
    import { ref, reactive } from "vue";

    const props = defineProps(['program_list', 'canEdit', 'canAdd', 'canDelete', 'filters']);
    const id = ref(null);
    const deleteProcessing = ref(false);
    const selected = ref(null);

    const query = useForm({
        show: props.filters.show,
        search: props.filters.search,
    });

    function submit() {
        query.get("/admin/program", {
            preserveState: true,
            preserveScroll: true,
        });
    }

    function changeResultCount() {
        query.get("/admin/program", {
            preserveScroll: false,
            preserveState: false,
        });
    }

    function edit(id) {
        router.get('/admin/program/' + id + '/edit');
    }


</script>

<script>
    import Layout from "../Shared/Layout.vue";
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