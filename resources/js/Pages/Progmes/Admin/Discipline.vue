<template>

    <Head title="Discipline List" />
    <AdminPanel />
    <content-container pageTitle="Discipline List" hasTopButton="true" hasNavigation="true" hasSearch="true"
        hasPageDescription="true" hasFilters="true" :data_list="discipline_list">
        <template v-slot:top-button>
            <Link :href="route('admin.discipline.create')">
            <button class="bg-blue-500 hover:bg-blue-600 h-10 px-2 rounded text-white mr-1">Add Discipline</button>
            </Link>
        </template>
        <template v-slot:page-description>
            <div class="ml-5 text-base text-gray-500">( {{ count }} Result<span v-if="count > 1">s</span> )</div>
        </template>
        <template v-slot:navigation>
            <div>
                <div>
                    <Link :href="route('admin.program.list')">
                    <button class="text-gray-500 hover:text-black mr-8">
                        Program List
                    </button>
                    </Link>
                    <button class="text-blue-500 h-10 border-b-2 font-bold border-blue-500">
                        Discipline List
                    </button>

                </div>
            </div>
        </template>
        <template v-slot:search>
            <div class="w-full flex justify-end">
                <input @keydown.enter="submit" v-model="query.search" type="search" id="content-search"
                    placeholder="Search a discipline"
                    class="rounded border-2 w-full border-gray-400 h-10 bg-white text-base placeholder-gray-400" />
                <button @click="submit"
                    class="hover:bg-gray-300 border-2 active:bg-blue-600 active:text-white h-10 w-12 border-gray-400 relative right-0.5 bg-white rounded-r">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </template>
        <template v-slot:options>
            <div class="mr-1">
                <Link href="/admin/program/discipline">
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
                    <th class="py-2">Discipline</th>
                    <th v-show="canEdit" class="p-2 text-right">
                        <i class="fas fa-ellipsis-v"></i>
                    </th>
                </template>
                <template v-slot:table-body>
                    <tr v-for="(discipline, index) in discipline_list.data" :key="discipline.id"
                        class="hover:bg-gray-100" :class="{'bg-slate-200': index % 2 == 0}">
                        <td class="p-2">
                            {{ discipline.discipline }}
                        </td>
                        <td v-show="canEdit" class="p-2 text-right">
                            <button class="h-8 px-2 rounded bg-red-500 hover:bg-red-600 text-white">Delete</button>
                        </td>
                    </tr>
                    <tr v-if="discipline_list.data.length == 0">
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

    const props = defineProps(['discipline_list', 'canEdit', 'filters', 'count']);

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


</script>

<script>
    import Layout from "../Shared/Layout.vue";
    export default {
        layout: Layout,
    };
</script>