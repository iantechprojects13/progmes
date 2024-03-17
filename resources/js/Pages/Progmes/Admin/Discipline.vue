<template>

    <Head title="Discipline List" />
    <AdminPanel />
    <content-container pageTitle="Discipline List" hasTopButton="true" hasNavigation="true" hasSearch="true"
        hasResultCount="true" hasFilters="true" :data_list="discipline_list">
        <template v-slot:top-button>
            <Link :href="route('admin.discipline.create')">
            <button class="select-none bg-blue-500 hover:bg-blue-600 h-10 px-2 rounded text-white mr-1">Add
                Discipline</button>
            </Link>
        </template>
        <template v-slot:result-count>
            <div class="ml-5 text-base text-gray-500">( {{ count }} Result<span v-if="count > 1">s</span> )</div>
        </template>
        <template v-slot:navigation>
            <div>
                <div>
                    <Link :href="route('admin.program.list')">
                    <button class="text-gray-500 hover:text-black mr-8">
                        Program
                    </button>
                    </Link>
                    <button class="text-blue-500 h-10 border-b-2 font-bold border-blue-500">
                        Discipline
                    </button>

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
        <template v-slot:options>
            <div class="mr-1">
                <Link href="/admin/program/discipline">
                <button
                    class="px-2 border-2 w-12 whitespace-nowrap rounded h-10 text-gray-700 hover:text-black border-gray-500">
                    <i class="fas fa-refresh"></i>
                </button>
                </Link>
            </div>
        </template>
        <template v-slot:main-content>
            <content-table>
                <template v-slot:table-head>
                    <th class="py-2">Discipline</th>
                    <th v-show="canEdit" class="p-2 text-right">
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
                        class="hover:bg-gray-100" :class="{'bg-slate-200': index % 2 == 0}">
                        <td class="p-3">
                            {{ discipline.discipline }}
                        </td>
                        <td v-show="canEdit" class="p-3 text-right">
                            <button @click="deleteModal = true; selected = discipline;"
                                class="select-none h-10 px-2 rounded bg-white text-gray-700 hover:text-black active:text-red-500 border-2 border-gray-500">
                                <i class="fas fa-trash mr-0.5"></i>Delete
                            </button>
                            <button @click="edit(discipline.id)"
                                class="ml-1 select-none h-10 px-2 rounded bg-white text-gray-700 hover:text-black active:text-blue-500 border-2 border-gray-500">
                                <i class="fas fa-edit mr-0.5"></i>Edit
                            </button>
                        </td>
                    </tr>

                </template>
            </content-table>
        </template>
    </content-container>
    <DeleteModal title="Delete Discipline" @close="deleteModal = false" :showModal="deleteModal">
        <template v-slot:message>
            <div v-if="deleteProcessing" class="py-5 w-full text-center">
                Deleting
                <i class="fas fa-spinner animate-spin ml-2"></i>
            </div>
            <div v-else class="py-5">Are you sure you want to delete <b>{{ selected?.discipline }}</b>? This action
                can't be
                undone.</div>
        </template>
        <template v-slot:buttons>
            <button @click="deleteDiscipline" :disabled="deleteProcessing"
                class="select-none h-10 w-16 rounded bg-red-500 hover:bg-red-600 text-white">Delete</button>
        </template>
    </DeleteModal>
    <Notification :message="$page.props.flash.success" type="success" />
    <Notification :message="$page.props.flash.failed" type="failed" />
</template>

<script setup>
    import { useForm, router } from "@inertiajs/vue3";
    import { ref } from "vue";

    const props = defineProps(['discipline_list', 'canEdit', 'filters', 'count']);
    const selected = ref(null);
    const deleteProcessing = ref(false);

    const query = useForm({
        search: props.filters.search,
    });

    function submit() {
        if (query.search == "") {
            router.get("/admin/program/discipline");
        } else {
            query.get("/admin/program/discipline", {
                preserveState: true,
                preserveScroll: true,
            });
        }
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
                deleteModal: false,
                message: '',
            }
        },
        layout: Layout,
    };
</script>