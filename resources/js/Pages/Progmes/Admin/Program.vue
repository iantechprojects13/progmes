<template>

    <Head title="Program List" />
    <AdminPanel />
    <content-container pageTitle="Program List" hasResultCount="true" hasTopButton="true" hasNavigation="true"
        hasSearch="true" hasFilters="true" :data_list="program_list">
        <template v-slot:result-count>
            <div class="ml-5 text-base text-gray-500">( {{ count }} Result<span v-if="count > 1">s</span> )</div>
        </template>
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
            <div class="w-full flex justify-end">
                <input @keydown.enter="submit" v-model="query.search" type="search" id="content-search"
                    placeholder="Search a program"
                    class="rounded border-2 w-full border-gray-500 h-10 bg-white text-base placeholder-gray-400" />
                <button @click="submit"
                    class="hover:bg-gray-300 border-2 active:bg-blue-600 active:text-white h-10 w-12 border-gray-500 relative right-0.5 bg-white rounded-r">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </template>
        <template v-slot:options>
            <div class="mr-1">
                <Link href="/admin/program">
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
                                class="h-8 px-2 rounded bg-red-500 hover:bg-red-600 text-white">Delete</button>
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

    <Notification :message="$page.props.flash.success" />
    <Notification :message="$page.props.flash.failed" />
</template>

<script setup>
    import { useForm, router } from "@inertiajs/vue3";
    import { ref, reactive } from "vue";

    const props = defineProps(['program_list', 'count', 'canEdit', 'filters']);
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