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
            <div class="w-full flex justify-end">
                <input @keydown.enter="submit" v-model="query.search" type="search" id="content-search"
                    placeholder="Search a Higher Education Institution"
                    class="rounded border-2 w-full border-gray-400 h-10 bg-white text-base placeholder-gray-400" />
                <button @click="submit"
                    class="hover:bg-gray-300 border-2 active:bg-blue-600 active:text-white h-10 w-12 border-gray-400 relative right-0.5 bg-white rounded-r">
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
                    <th class="py-2">HEI Name</th>
                    <th v-show="canEdit" class="py-2 text-right">
                        <i class="fas fa-ellipsis-v"></i>
                    </th>
                </template>
                <template v-slot:table-body>
                    <tr v-if="institution_list.data.length == 0">
                        <td class="text-center py-10 italic" colspan="3">
                            No HEI found
                        </td>
                    </tr>
                    <tr v-else v-for="(hei, index) in institution_list.data" :key="hei.id" class="hover:bg-gray-100"
                        :class="{'bg-slate-200': index % 2 == 0}">
                        <td class="p-2">
                            {{ hei.name }}
                        </td>
                        <td class="p-2 text-right">
                            <button @click="deleteModal = true; selected = hei;"
                                class="h-8 px-2 rounded bg-red-500 hover:bg-red-600 text-white">Delete</button>
                        </td>
                    </tr>

                </template>
            </content-table>
        </template>
    </content-container>
    <DeleteModal title="Delete HEI" @close="deleteModal = false" :showModal="deleteModal">
        <template v-slot:message>
            <div v-if="deleteProcessing" class="py-5 w-full text-center">
                Deleting
                <i class="fas fa-spinner animate-spin ml-2"></i>
            </div>
            <div v-else class="py-5">Are you sure you want to delete <b>{{ selected?.name }}</b>? This action
                can't be
                undone.</div>
        </template>
        <template v-slot:buttons>
            <button @click="deleteHEI" :disabled="deleteProcessing"
                class="h-10 w-16 rounded bg-red-500 hover:bg-red-600 text-white">Delete</button>
        </template>
    </DeleteModal>
    <Notification :message="$page.props.flash.success" />
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
                deleteModal: false,
            }
        },
        layout: Layout,

        methods: {
            // toggleModal(item, type, title) {
            //     this.openModal = !this.openModal;
            //     this.selected = item;
            //     this.modaltype = type;
            //     this.title = title;
            // },

            // closeModal() {
            //     this.openModal = false;
            // }
        }
    }
</script>