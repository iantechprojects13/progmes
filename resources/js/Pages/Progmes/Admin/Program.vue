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
                        Program List
                    </button>
                    <Link :href="route('admin.discipline.list')">
                    <button class="text-gray-500 hover:text-black">
                        Discipline List
                    </button>
                    </Link>
                </div>
            </div>
        </template>
        <template v-slot:search>
            <div class="w-full flex justify-end">
                <input @keydown.enter="submit" v-model="query.search" type="search" id="content-search"
                    placeholder="Search a program"
                    class="rounded border-2 w-full border-gray-400 h-10 bg-white text-base placeholder-gray-400" />
                <button @click="submit"
                    class="hover:bg-gray-300 border-2 active:bg-blue-600 active:text-white h-10 w-12 border-gray-400 relative right-0.5 bg-white rounded-r">
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
                    <th class="py-2">Program</th>
                    <th class="py-2">Discipline</th>
                    <th v-show="canEdit" class="py-2 text-right">
                        <i class="fas fa-ellipsis-v"></i>
                    </th>
                </template>
                <template v-slot:table-body>
                    <tr v-for="(program, index) in program_list.data" :key="program.id" class="hover:bg-gray-100"
                        :class="{'bg-slate-200': index % 2 == 0}">
                        <td class="p-2">
                            {{ program.program }}
                        </td>
                        <td class="p-2">
                            {{ program.discipline.discipline }}
                        </td>
                        <td class="p-2 text-right">
                            <button class="h-8 px-2 rounded bg-red-500 hover:bg-red-600 text-white">Delete</button>
                        </td>
                    </tr>
                    <tr v-if="program_list.data.length == 0">
                        <td class="text-center py-10" colspan="3">
                            No programs found
                        </td>
                    </tr>
                </template>
            </content-table>
        </template>
    </content-container>
    <Notification :message="$page.props.flash.success" />
</template>

<script setup>
    import { useForm, router } from "@inertiajs/vue3";
    import { ref } from "vue";

    const props = defineProps(['program_list', 'canEdit', 'filters']);

    const query = useForm({
        search: props.filters.search,
        sort: props.filters.sort,
    });

    function submit() {
        if (query.search == "" && query.sort == "") {
            router.get("/admin/program/");
        } else {
            query.get("/admin/program/", {
                preserveState: true,
                preserveScroll: true,
            });
        }
    }


</script>

<script>
    import Layout from "../Shared/Layout.vue";
    export default {
        layout: Layout,
        data() {
            return {
                openCreateDropdown: false,
            };
        },
    };
</script>