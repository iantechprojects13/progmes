<template>

    <Head title="Program List" />
    <AdminPanel />
    <content-container pageTitle="Program List" hasTopButton="true" hasNavigation="true" hasSearch="true"
        hasFilters="true" :data_list="program_list">
        <template v-slot:top-button>
            <Link href="/admin/program/create">
            <button class="bg-blue-500 hover:bg-blue-600 h-10 px-2 rounded text-white">Add program</button>
            </Link>
        </template>
        <template v-slot:navigation>
            <div>
                <div>
                    <button class="text-blue-500 h-10 mr-8 border-b-2 font-bold border-blue-500">
                        Program
                    </button>
                    <Link :href="route('admin.discipline.list')">
                    <button class="text-gray-500 hover:text-black">
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
        <template v-slot:options>
            <div class="mr-1">
                <Link href="/admin/program">
                <button
                    class="px-2 border-2 w-12 whitespace-nowrap rounded h-10 text-gray-600 hover:text-black border-gray-500 tooltipForActions"
                    data-tooltip="Refresh page">
                    <i class="fas fa-refresh"></i>
                </button>
                </Link>
            </div>
        </template>
        <template v-slot:main-content>
            <content-table>
                <template v-slot:table-head>
                    <th class="py-2 px-3">Program</th>
                    <th class="py-2 px-3">Major</th>
                    <th class="py-2 px-3">Discipline</th>
                    <th v-show="canEdit" class="py-2 px-3 text-right">
                        <i class="fas fa-ellipsis-v"></i>
                    </th>
                </template>
                <template v-slot:table-body>
                    <tr v-if="program_list.data.length == 0">
                        <td class="text-center py-10" colspan="4">
                            No program found
                        </td>
                    </tr>
                    <tr v-else v-for="(program, index) in program_list.data" :key="program.id" class="hover:bg-gray-100"
                        :class="{'bg-slate-200': index % 2 == 0}">
                        <td class="p-2 px-3">
                            {{ program.program }}
                        </td>
                        <td class="p-2 px-3">
                            {{ program.major }}
                        </td>
                        <td class="p-2 px-3">
                            {{ program.discipline?.discipline }}
                        </td>
                        <td class="p-2 px-3 text-right">
                            <button @click="deleteModal = true; selected = program;"
                                class="select-none h-10 w-10 rounded bg-white text-gray-700 hover:text-black active:text-red-500 border-2 border-gray-500 tooltipForActions"
                                data-tooltip="Delete">
                                <i class="fas fa-trash mr-0.5"></i>
                            </button>
                            <button @click="edit(program.id)"
                                class="ml-1 select-none h-10 w-10 rounded bg-white text-gray-700 hover:text-black active:text-blue-500 border-2 border-gray-500 tooltipForActions"
                                data-tooltip="Edit">
                                <i class="fas fa-edit mr-0.5"></i>
                            </button>
                        </td>
                    </tr>
                </template>
            </content-table>
        </template>
    </content-container>

    <DeleteModal title="Delete Program" @close="deleteModal = false" :showModal="deleteModal">
        <template v-slot:message>
            <div v-if="deleteProcessing" class="py-5 w-full text-center">
                Deleting
                <i class="fas fa-spinner animate-spin ml-2"></i>
            </div>
            <div v-else class="py-5">Are you sure you want to delete <b>{{ selected?.program }}</b>? This action
                can't be
                undone.</div>
        </template>
        <template v-slot:buttons>
            <button @click="deleteProgram" :disabled="deleteProcessing"
                class="h-10 w-16 rounded bg-red-500 hover:bg-red-600 text-white">Delete</button>
        </template>
    </DeleteModal>

    <Notification :message="$page.props.flash.success" type="success" />
    <Notification :message="$page.props.flash.failed" type="failed" />
</template>

<script setup>
    import { useForm, router } from "@inertiajs/vue3";
    import { ref, reactive } from "vue";

    const props = defineProps(['program_list', 'canEdit', 'filters']);
    const id = ref(null);
    const deleteProcessing = ref(false);
    const selected = ref(null);

    const query = useForm({
        search: props.filters.search,
    });

    function submit() {
        if (query.search == "") {
            router.get("/admin/program");
        } else {
            query.get("/admin/program", {
                preserveState: true,
                preserveScroll: true,
            });
        }
    }

    function edit(id) {
        router.get('/admin/program/' + id + '/edit');
    }

    function deleteProgram() {
        router.post('/admin/program/delete', { 'id': selected.value.id }, {
            onStart: () => {
                deleteProcessing.value = true;
            },
            onFinish: () => {
                deleteProcessing.value = false;
            },
            preserveState: false,
        });
    };


</script>

<script>
    import Layout from "../Shared/Layout.vue";
    export default {
        layout: Layout,
        data() {
            return {
                deleteModal: false,
            };
        },
    };
</script>