<template>
    <Head title="Program Application" />
    <page-title title="Program Application" />
    <content-container
        hasSearch="true"
        pageTitle="Program Application List"
        page="application"
        :hasTopButton="true"
        :hasFilters="true"
        :data_list="application_list"
    >
        <template v-slot:top-button>
            <Link
                :href="route('ched.gpr.view')"
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
            <search-box v-model="query.search" @submit="submit" />
        </template>
        <template v-slot:options>
            <options @filter="toggleFilterModal" href="/ched/program-application" />
        </template>
        <template v-slot:main-content>
            <content-table>
                <template v-slot:table-head>
                    <th
                        class="px-6 py-4 text-left text-base font-bold text-gray-800"
                    >
                        Program
                    </th>
                    <th
                        class="px-6 py-4 text-left text-base font-bold text-gray-800"
                    >
                        Type of Application
                    </th>
                    <th
                        class="px-6 py-4 text-left text-base font-bold text-gray-800"
                    >
                        Academic Year
                    </th>
                    <th
                        class="px-6 py-4 text-left text-base font-bold text-gray-800"
                    >
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
                        <no-search-result text="applications" />
                    </tr>
                    <tr
                        v-else
                        v-for="(application, index) in application_list.data"
                        :key="application.id"
                        class="hover:bg-blue-200 border-b border-gray-200"
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
                            {{ application.type || "-" }}
                        </td>
                        <td class="px-6 py-2 text-base text-gray-900">
                            {{ application.academicYear || "-" }}
                        </td>
                        <td class="px-6 py-2 text-base text-gray-900">
                            <status :text="application.status" />
                        </td>
                        <td class="px-6 py-2 whitespace-nowrap">
                            <button @click="evaluate(application.id)" :disabled="application.status == 'Approved'"
                                class="inline-flex items-center justify-center rounded-full h-10 w-10 text-blue-700 hover:bg-blue-100 tooltipForActions"
                                data-tooltip="Evaluate"
                            >
                                <i class="fas fa-edit text-lg"></i>
                            </button>
                            <button
                                @click="toggleDeleteModal(application)"
                                class="inline-flex items-center justify-center rounded-full h-10 w-10 text-red-700 hover:bg-red-100 tooltipForActions"
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
import { router } from "@inertiajs/vue3";
import { reactive, ref } from "vue";

const props = defineProps(["application_list", "filters"]);

const query = reactive({
    search: ref(props.filters.search),
});

function submit() {
    router.get("/ched/program-application", query);
}

function evaluate(id) {
    router.get(`/ched/program-application/${id}/evaluate`);
}
</script>

<script>
import Layout from "@/Shared/Layout.vue";

export default {
    layout: Layout,
};
</script>
