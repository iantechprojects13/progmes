<template>

    <Head title="Higher Education Institution List" />
    <AdminPanel />
    <content-container pageTitle="Higher Education Institution List" page="hei" :hasTopButton="canAdd" :hasSearch="true"
        :hasFilters="true" :data_list="institution_list">
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
            <Link href="/admin/higher-education-institutions/create">
            <button class="bg-blue-500 hover:bg-blue-600 h-10 px-3 rounded text-white text-sm whitespace-nowrap">
                <i class="fas fa-plus mr-2"></i>Add HEI
            </button>
            </Link>
        </template>
        <template v-slot:options>
            <div class="mr-1">
                <Link href="/admin/higher-education-institutions">
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
                    <th class="p-3 pl-5">HEI Name</th>
                    <!-- <th class="p-3">Current Status</th> -->
                    <th v-show="canEdit" class="p-3 text-right">
                        <i class="fas fa-ellipsis-v"></i>
                    </th>
                </template>
                <template v-slot:table-body>
                    <tr v-if="institution_list.data.length == 0">
                        <td class="text-center py-10" colspan="3">
                            No HEI found
                        </td>
                    </tr>
                    <tr v-else v-for="(hei, index) in institution_list.data" :key="hei.id" class="hover:bg-slate-300"
                        :class="{'bg-slate-200': index % 2 == 0}">
                        <td class="p-3 pl-5">
                            {{ hei.name }}
                        </td>
                        <!-- <td class="p-3 text-xs">
                            <div v-if="hei.isActive" class="bg-green-600 text-white py-0.5 px-1 rounded w-fit">
                                Active
                            </div>
                            <div v-else class="bg-gray-700 text-white py-0.5 px-1 rounded w-fit">
                                Not active
                            </div>
                        </td> -->
                        <td class="p-3 text-right">
                            <button @click="view(hei.id)"
                                class="mr-1 select-none h-10 w-10 rounded text-white bg-green-600 hover:bg-green-700 tooltipForActions"
                                data-tooltip="View">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button v-show="canEdit" @click="edit(hei.id)"
                                class="mr-1 select-none h-10 w-10 rounded text-white bg-blue-500 hover:bg-blue-600 tooltipForActions"
                                data-tooltip="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button v-show="canDelete" @click="toggleConfirmationModal(hei, 'deleteHEI', 'Delete HEI')"
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
        <Confirmation @close="closeModal" :title="title" :modaltype="modaltype" :selected="selectedHEI" />
    </div>

    <Notification :message="$page.props.flash.success" type="success" />
    <Notification :message="$page.props.flash.failed" type="failed" />
</template>

<script setup>
    import { router, useForm } from '@inertiajs/vue3';
    import { ref } from 'vue';

    const props = defineProps(['institution_list', 'canEdit', 'canAdd', 'canDelete', 'filters']);

    const query = useForm({
        show: props.filters.show,
        search: props.filters.search,
    });

    function submit() {
        query.get("/admin/higher-education-institutions", {
            preserveState: true,
            preserveScroll: true,
        });
    }

    function changeResultCount() {
        query.get("/admin/higher-education-institutions", {
            preserveScroll: false,
            preserveState: false,
        });
    }

    function edit(id) {
        router.get('/admin/higher-education-institutions/' + id + '/edit');
    }

    function view(id) {
        router.get('/admin/higher-education-institutions/' + id + '/view');
    }


</script>

<script>
    import Layout from '../Shared/Layout.vue';
    export default {
        data() {
            return {
                confirmationModal: false,
                selectedHEI: '',
                modaltype: '',
                title: '',
            }
        },
        layout: Layout,

        methods: {
            toggleConfirmationModal(item, type, title) {
                this.confirmationModal = !this.confirmationModal;
                this.selectedHEI = item;
                this.modaltype = type;
                this.title = title;
            },

            closeModal() {
                this.confirmationModal = false;
            }
        }
    }
</script>