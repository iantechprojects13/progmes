<template>
    <Head title="Program Application" />
    <page-title title="Program Application" />
    <content-container hasSearch="true" pageTitle="Program Application" page="application" :hasTopButton="true" :hasFilters="true" :data_list="application_list">
        <template v-slot:top-button>
            <Link :href="route('ched.gpr.view')"
                    class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 focus:outline-none transition duration-150 ease-in-out disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <span class="flex items-center">
                        <svg
                            class="w-5 h-5 mr-2"
                            viewBox="0 0 24 24"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <!-- Document Shape -->
                            <path
                                d="M7 3H14L19 8V21C19 21.5523 18.5523 22 18 22H6C5.44772 22 5 21.5523 5 21V5C5 3.89543 5.89543 3 7 3Z"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                            <!-- Folded Corner -->
                            <path
                                d="M14 3V8H19"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                        </svg>

                        View GPR
                    </span>
                </Link>
        </template>
        <template v-slot:search>
            <div class="relative">
                <div
                    class="absolute inset-y-0 left-3 flex items-center pointer-events-none"
                >
                    <svg
                        class="w-5 h-5 text-gray-400"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                        />
                    </svg>
                </div>

                <input
                    @keydown.enter="submit"
                    v-model="query.search"
                    type="search"
                    id="content-search"
                    placeholder="Search"
                    class="w-full py-2.5 pl-11 pr-4 bg-white border border-gray-200 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors duration-200"
                />
            </div>
        </template>
        <template v-slot:options>
            <div class="flex gap-2">
                <button
                    class="p-2.5 inline-flex items-center justify-center rounded-lg text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 transition-all duration-200 tooltipForActions"
                    data-tooltip="Filters"
                >
                    <svg
                        class="w-5 h-5"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"
                        />
                    </svg>
                    <span
                        class="absolute -top-8 left-1/2 -translate-x-1/2 px-2 py-1 bg-gray-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200"
                    >
                        Filters
                    </span>
                </button>

                <Link href="/ched/program-application">
                    <button
                        class="p-2.5 inline-flex items-center justify-center rounded-lg text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 transition-all duration-200 tooltipForActions"
                        data-tooltip="Refresh page"
                    >
                        <svg
                            class="w-5 h-5"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                            />
                        </svg>
                    </button>
                </Link>
            </div>
        </template>
        <template v-slot:main-content>
            <content-table>
                <template v-slot:table-head>
                    <th class="px-6 py-4 text-left text-base font-bold text-gray-800">
                        Program
                    </th>
                    <th class="px-6 py-4 text-left text-base font-bold text-gray-800">
                        Type of Application
                    </th>
                    <th class="px-6 py-4 text-left text-base font-bold text-gray-800">
                        Academic Year
                    </th>
                    <th class="px-6 py-4 text-left text-base font-bold text-gray-800">
                        Status
                    </th>
                    <th class="px-6 py-4 text-right w-24">
                        <span class="text-gray-800">
                            <i class="fas fa-ellipsis-v text-lg"></i>
                        </span>
                    </th>
                </template>
                <template v-slot:table-body>
                    <tr v-if="application_list.data.length == 0">
                        <no-search-result text="applications"/>
                    </tr>
                    <tr v-else v-for="(application, index) in application_list.data" :key="application.id"
                    class="transition-colors hover:bg-blue-200 border-b border-gray-200"
                        :class="{ 'bg-slate-200': index % 2 === 1 }"
                    >
                        <td class="px-6 py-2 text-base text-gray-900">
                            <div>
                                {{ application.program?.program }}
                                <span v-if="application.program?.major">
                                    - {{ application.program?.major }}
                                </span>
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ application.institution?.name }}
                            </div>
                        </td>
                        <td class="px-6 py-2 text-base text-gray-900">
                            {{ application.type || '-' }}
                        </td>
                        <td class="px-6 py-2 text-base text-gray-900">
                            {{ application.academicYear || '-' }}
                        </td>
                        <td class="px-6 py-2 text-base text-gray-900">
                            <status :text="application.status" />
                        </td>
                        <td class="px-6 py-2 whitespace-nowrap">
                            <Link :href="`/hei/program-application/${application.id}/edit`">
                                <button
                                class="inline-flex items-center justify-center rounded-full h-10 w-10 text-blue-700 hover:bg-blue-100 transition-colors tooltipForActions"
                                data-tooltip="Edit"
                            >
                                <i class="fas fa-edit text-lg"></i>
                            </button>
                            </Link>
                            <button @click="toggleDeleteModal(application)" class="inline-flex items-center justify-center rounded-full h-10 w-10 text-red-700 hover:bg-red-100 transition-colors tooltipForActions"
                            data-tooltip="Delete"
                        >
                            <i class="fas fa-trash text-lg"></i>
                            </button>
                        </td>
                    </tr>
                </template>
            </content-table>
        </template>
    </content-container>
</template>

<script setup>

import { router } from '@inertiajs/vue3';
import { reactive, ref } from 'vue';

const props = defineProps([
    'application_list',
    'filters',
])

const query = reactive({
    search: ref(props.filters.search),
});

function submit() {
    router.get('/ched/program-application', query);
}



</script>

<script>
    import Layout from '@/Shared/Layout.vue';

    export default {
        layout: Layout,
    }
</script>