<template>
    <Head title="Program Evaluation"/>
    <page-title title="Program Monitoring & Evaluation"/>
    <div class="mx-3 md:mx-8 bg-white border border-gray-400 p-3 rounded mt-8">
        <div><b>Role: </b>{{ role[0].role }}</div>
        <div><b>Discipline: </b>{{ role[0].discipline.discipline }}</div>
    </div>
    <content-container :hasSearch="true" :hasFilters="true" :data_list="evaluation_tool">
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
                <Link href="/hei/dean/evaluation">
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
                    <th class="text-base p-3 pl-5">Program/Discipline</th>
                    <th class="text-base p-3">Status</th>
                    <th class="text-base p-3">Progress</th>
                    <th class="text-base p-3 text-right">
                        <i class="fas fa-ellipsis-v"></i>
                    </th>
                </template>
                <template v-slot:table-body>
                    <tr v-if="evaluation_tool.data.length == 0">
                        <td colspan="4" class="py-10 text-center">
                            No evaluation tool found
                        </td>
                    </tr>
                    <tr v-else v-for="(tool, index) in evaluation_tool.data" :key="tool.id" :class="{'bg-slate-200': index % 2 == 0}">
                        <td class="text-base p-3 pl-5">{{ tool.program }}</td>
                        <td class="text-base p-3">{{ tool.status }}</td>
                        <td class="text-base p-3">{{ tool.progress }}%</td>
                        <td class="text-base p-3 text-right">
                            <button @click="view(tool.id)" class="text-base select-none h-10 w-10 bg-green-600 hover:bg-green-700 text-white rounded tooltipForActions" 
                            data-tooltip="View">
                            <i class="fas fa-eye"></i>
                            </button>
                        </td>
                    </tr>
                </template>
            </content-table>
        </template>
        <template v-slot:show-item>
            <div class="mr-2">Items per page</div>
            <select v-model="query.show" id="showResultCount" @change="changeResultCount"
                class="select-none rounded h-8 w-20 p-1">
                <option value="25">25</option>
                <option :value="50">50</option>
                <option :value="75">75</option>
                <option :value="100">100</option>
                <option :value="200">200</option>
            </select>
        </template>
    </content-container>
</template>

<script setup>
import { router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps([
    'evaluation_tool',
    'role',
    'filters',
]);


const query = useForm({
    show: props.filters.show,
    search: props.filters.search,
});

function submit() {
    query.get("/hei/dean/evaluation", {
        preserveState: true,
        preserveScroll: true,
    });
}

function changeResultCount() {
    query.get("/hei/dean/evaluation", {
        preserveScroll: false,
        preserveState: false,
    });
}

function view(id) {
    router.get('/hei/evaluation/' + id + '/view');
}
























</script>

<script>
import Layout from "../Shared/Layout.vue";

export default {
    layout: Layout,
}
</script>