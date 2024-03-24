<template>

    <Head title="Higher Education Institution List" />
    <AdminPanel />
    <content-container pageTitle="Higher Education Institution List" hasTopButton="true" hasSearch="true"
        hasFilters="true" :data_list="institution_list">
        <template v-slot:top-button>
            <Link href="/admin/higher-education-institutions/create">
            <button class="bg-blue-500 hover:bg-blue-600 h-10 px-2 rounded text-white">Add HEI</button>
            </Link>
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
        <template v-slot:options>
            <div class="mr-1">
                <Link href="/admin/higher-education-institutions">
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
                    <th class="p-3">HEI Name</th>
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
                    <tr v-else v-for="(hei, index) in institution_list.data" :key="hei.id" class="hover:bg-gray-100"
                        :class="{'bg-slate-200': index % 2 == 0}">
                        <td class="p-3">
                            {{ hei.name }}
                        </td>
                        <td class="p-3 text-right">
                            <button @click="edit(hei.id)"
                                class="mr-1 select-none h-10 w-10 rounded text-white bg-blue-500 hover:bg-blue-600 tooltipForActions"
                                data-tooltip="Edit">
                                <i class="fas fa-edit mr-0.5"></i>
                            </button>
                            <button @click="toggleConfirmationModal(hei, 'deleteHEI', 'Delete HEI')"
                                class="select-none h-10 w-10 rounded text-white bg-red-500 hover:bg-red-600 tooltipForActions"
                                data-tooltip="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                </template>
            </content-table>
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

    const props = defineProps(['institution_list', 'count', 'canEdit', 'filters']);
    const selected = ref(null);
    const deleteProcessing = ref(false);

    const query = useForm({
        search: props.filters.search,
    });

    function submit() {
        if (query.search == "") {
            router.get("/admin/higher-education-institutions");
        } else {
            query.get("/admin/higher-education-institutions", {
                preserveState: true,
                preserveScroll: true,
            });
        }
    }

    function edit(id) {
        router.get('/admin/higher-education-institutions/' + id + '/edit');
    }

    function deleteHEI() {
        router.post('/admin/higher-education-institutions/delete', { 'id': selected.value.id }, {
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